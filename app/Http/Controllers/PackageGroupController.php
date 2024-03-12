<?php

namespace App\Http\Controllers;

use App\Models\PackageGroup;
use App\Models\PackageGroupMapping;
use App\Models\PackageGroupUser;
use App\Models\User;
use Illuminate\Http\Request;

class PackageGroupController extends Controller
{
    public function index( Request $request )
    {
        return response()->json([
            'groups' =>  PackageGroup::limit($request->per_page)->offset(($request->page-1)*$request->per_page )->get(),
            'page' => $request->page,
            'total' => PackageGroup::count(),
            'per_page' => $request->per_page
        ]);
    }

    public function partners()
    {
        $partners = User::where('role_id',2)
        ->whereNotIn('id',PackageGroupUser::where('group_id', request('group_id'))->pluck('user_id')->toArray())
        ->select('name as label','id')->get();

        return response()->json(['success' => 'true', 'partners' => $partners]);
    }
    
    public function store( Request $request )
    {
        $request->validate([
            'name' => ['required','unique:package_groups,name'],
            'description' => 'required',
            'packages.*' => 'required'
        ]);

        $group = new PackageGroup();
        $group->name = $request->name;
        $group->description = $request->description;
        $group->creator = auth()->user()->id;
        $group->save();

        $this->addPackagesToGroup( $request->packages, $group);

        return response()->json(['success' => true,'message' => 'Program Group created succssfully','type' => 'success']);
    }

    private function addPackagesToGroup( $packages,$group )
    {
        foreach( $packages as $package)
        {
            PackageGroupMapping::updateOrCreate(
                [
                    'package_id' => $package,
                    'group_id' => $group->id,
                ],
                [
                    'package_id' => $package,
                    'group_id' => $group->id,
                ]   
            );
        }
    }

    public function show( $id )
    {
        $group = PackageGroup::find($id);
        if( $group){
            return response()->json(['success'=>true, 'group' => $group,'type' => 'success']);
        } 
        return response()->json(['success' => false,'message' => 'Program group  not found in storage','type' => 'danger']);
    }

    public function update( Request $request , int $id )
    {
        $request->validate(
            [
                'name' => ['required','unique:package_groups,name,'.$id],
                'description' => 'required',
                'packages.*' => 'required'
            ]
        );

        $group = PackageGroup::find($id);
        if( $group) 
        {
            $group->name = $request->name;
            $group->description = $request->description;
            $group->update();
    
            $this->addPackagesToGroup( $request->packages, $group);

            return response()->json(['success' => true, 'message' => 'Program group updated succesfully','type' => 'success']);
        }
        return response()->json(['success' => false,'message' => 'Program group not found in storage','type' => 'danger']);
    }

    public function destroy(int $id)
    {
        $group = PackageGroup::find($id);
        if( $group) 
        {
            PackageGroupMapping::where('group_id', $id)->delete();

            PackageGroupUser::where('group_id', $id)->delete();
            
            $group->delete();
    
            return response()->json(['success' => true, 'message' => 'Program group deleted succesfully','type' => 'success']);
        }
        return response()->json(['success' => false,'message' => 'Program group not found in storage','type' => 'danger']);
    }


    public function removeMapping(int $id)
    {
        $mapping = PackageGroupMapping::find($id);
        if( $mapping )
        {
            $mapping->delete();
            
            return response()->json(['success' => true, 'message' => 'Program group mapping deleted succesfully','type' => 'success']);
        }
        return response()->json(['success' => false,'message' => 'Program group mapping not found in storage','type' => 'danger']);
        
    }
}
