<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleUser;
use App\utils\Helpers;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $helpers = new Helpers();
        $roles = Role::where('deleted_at', '=', null)->get();
        $totalRows = $roles->count();
        return response()->json([
            'roles' => $roles,
            'totalRows' => $totalRows,
        ]);
    }

    /**
     * display role and permission information
     * for editing role
     * @param int $id
     */
    public function edit(int $id)
    {
        $role = Role::find($id);
        $permissions = [];

        $modules = config('modules');
        $role->permissions->pluck('name', 'id')
            ->map(function ($permission, $index) use (&$permissions, $modules) {
                foreach ($modules as $k => $module) {
                    $permissions[$k] = $permissions[$k] ?? [];
                    if (strpos($permission, strtolower($module)) !== false) {
                        array_push($permissions[$k], $index);
                    }
                }
            });

        return response()->json(
            [
                'success' => true,
                'data' => [
                    'name' => $role->name,
                    'description' => $role->description,
                    'color' => $role->color,
                    'permissions' => $permissions,
                ]
            ]
        );
    }


    private function getPermissions($permissions)
    {
        $data = [];
        foreach ($permissions as $k => $permission) {
            if (is_array($permission)) {
                foreach ($permission as $per) {
                    array_push($data, $per);
                }
            }
        }
        return $data;
    }

    private function rules($id = "")
    {
        return [
            'name' => [
                'required',
                'unique:roles,name,' . $id
            ],
            'description' => 'required',
            'permissions' => 'required'
        ];
    }


    public function store(Request $request, Role $role)
    {
        $request->validate($this->rules());
        DB::beginTransaction();
        try {
            $role->name = $request->name;
            $role->label = ucfirst($request->name);
            $role->description = $request->description;
            $role->color = $request->color;
            $role->status = 1;
            $role->save();

            $role->permissions()->sync($this->getPermissions($request->permissions));

            DB::commit();

            return response()->json(['success' => true, 'message' => "Role {$role->name} created successfully"]);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
        }
    }


    public function update(Request $request, int $id)
    {
        $role = Role::find($id);
        if ($role) {
            $request->validate($this->rules($id));
            DB::beginTransaction();
            try {
                $role->name = $request->name;
                $role->label =  ucfirst($request->name);
                $role->description = $request->description;
                $role->color = $request->color;
                $role->update();

                $role->permissions()->sync($this->getPermissions($request->permissions));

                DB::commit();
                return response()->json(['success' => true, 'message' => "Role {$role->name} updated successfully"]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['success' => false, 'message' => "Something went wrong please try after some time"]);
            }
        }
    }


    public function list(Request $request)
    {
        if ($request->isconsultant) {
            return response()->json(['success' => true, 'roles' => Role::where('status', 1)->where('is_consultant', '1')->select('label', 'id', 'color')->get()]);
        }
        if ($request->allusers) {
            return response()->json(['success' => true, 'roles' => Role::where('status', 1)->whereNotIn('id', [1, 2, 3])->select('label', 'id', 'color')->get()]);
        }
        if (!empty($request->description)) {
            return response()->json(['success' => true, 'roles' => Role::where('status', 1)->whereNotIn('id', [1, 2, 3])->whereNotIn('name', ['coordinator', 'healthcoach'])->where('description', $request->description)->select('label', 'id', 'color')->get()]);
        }
        return response()->json(['success' => true, 'roles' => Role::where('status', 1)->whereNotIn('id', [1, 2, 3])->whereNotIn('name', ['coordinator', 'healthcoach'])->select('label', 'id', 'color')->get()]);
    }

    public function adminRoles()
    {
        $roles = Role::where('status', 1)->where('id', '>', 3)->select('name as label', 'id')->whereNotIn('name', ['coordinator', 'healthcoach'])->get();
        return response()->json(['success' => true, 'roles' => $roles]);
    }

    public function roleUserList(Request $request)
    {
        return response()->json(['success' => true, 'users' => Role::find($request->role_id)->users]);
    }

    public function destroy(int $id)
    {
        $role = Role::find($id);
        if ($role) {
            if ($role->users->count()) {
                return response()->json(['success' => false, 'message' => 'sorry can not delete role , role has associated users']);
            }
            DB::table('permission_role')->where('role_id', $role->id)->delete();
            $role->delete();

            return response()->json(['success' => true, 'message' => 'role deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'role not found in storage']);
    }
}
