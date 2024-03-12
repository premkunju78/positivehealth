<?php

namespace App\Http\Controllers;

use App\Models\ClientGroup;
use App\Models\ClientGroupMapping;
use App\Models\ClientGroupUser;
use App\Models\User;
use Illuminate\Http\Request;

class ClientGroupController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'groups' =>  ClientGroup::limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get(),
            'page' => $request->page,
            'total' => ClientGroup::count(),
            'per_page' => $request->per_page
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:client_groups,name'],
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $group = new ClientGroup();
        $group->name = $request->name;
        $group->description = $request->description;
        $group->start_date = date('Y-m-d', strtotime($request->start_date));
        $group->end_date = date('Y-m-d', strtotime($request->end_date));
        $group->save();

        return response()->json(['success' => true, 'message' => 'Client Group created succssfully', 'type' => 'success']);
    }

    public function clientassignment(Request $request)
    {
        if (!empty($request->selected)) {
            try {
                foreach ($request->selected as $client) {
                    ClientGroupMapping::updateOrCreate(
                        [
                            'client_id' => $client,
                            'group_id' => $request->group_id,
                        ],
                        [
                            'client_id' => $client,
                            'group_id' => $request->group_id,
                        ]
                    );
                }
                return response()->json(['success' => true, 'message' => "Client added to group successfully!"]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            return response()->json(['success' => false, 'message' => "Select atleast 1 client"]);
        }
    }

    public function show($id)
    {
        $group = ClientGroup::find($id);
        if ($group) {
            return response()->json(['success' => true, 'group' => $group, 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Client group  not found in storage', 'type' => 'danger']);
    }

    public function update(Request $request, int $id)
    {
        $request->validate(
            [
                'name' => ['required', 'unique:client_groups,name,' . $id],
                'description' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]
        );

        $group = ClientGroup::find($id);
        if ($group) {
            $group->name = $request->name;
            $group->description = $request->description;
            $group->update();

            return response()->json(['success' => true, 'message' => 'Client group updated succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Client group not found in storage', 'type' => 'danger']);
    }

    public function destroy(int $id)
    {
        $group = ClientGroup::find($id);
        if ($group) {
            ClientGroupMapping::where('group_id', $id)->delete();
            $group->delete();
            return response()->json(['success' => true, 'message' => 'Client group deleted succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Client group not found in storage', 'type' => 'danger']);
    }


    public function removeMapping(int $id)
    {
        $mapping = ClientGroupMapping::find($id);
        if ($mapping) {
            $mapping->delete();

            return response()->json(['success' => true, 'message' => 'Client group mapping deleted succesfully', 'type' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Client group mapping not found in storage', 'type' => 'danger']);
    }

    public function list()
    {
        $groups = ClientGroup::select('client_groups.id', 'client_groups.name as label')->get();
        return response()->json(['success' => true, 'groups' => $groups]);
    }

    public function assignedusers(Request $request){
        $mapping = ClientGroupMapping::where('group_id',$request->group_id)->join('users','users.id','client_group_mappings.client_id')->select('users.name as user_name','client_group_mappings.id as mapping_id')->get();
        return response()->json(['success' => true, 'users' => $mapping]);
    }
    
}
