<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\Request;
use Vimeo\Laravel\Facades\Vimeo;

class GroupController extends Controller
{
    public function index( Request $request )
    {

        $mappingCheck = auth()->user()->role_id !== 1;

        $group =Group::select('groups.name','groups.description','groups.group_id','groups.creator')->when($mappingCheck, function($q){
            $q->join('group_users','group_users.group_id', '=', 'groups.group_id')
            ->where('group_users.user_id', auth()->user()->id )
            ->where( function($q){
                $q->whereDate('group_users.expiry_date','>=', date('Y-m-d'))
                ->orWhereNull('group_users.expiry_date');
            });
        });

        $groups = [
            'data' =>  $group->limit($request->per_page)->offset(($request->page-1)*$request->per_page )->get(),
            'page' => 1,
            'total' => $group->count(),
            'per_page' => $request->per_page
        ] ;
        return response()->json( [
            'success' => true,
            'groups' => $groups
        ] );
    }

    public function list()
    {
        $groups = [];$total = 0;

        $total = $this->fetchGroups($groups);
        if( $total > 100 ) { //since max limit is 100 for pagination
            $pages = ($total / 100 ) > 0 ?  ceil($total / 100 ) : 0 ;
            $i = 2;
            while( $i <= $pages )
            {
                $this->fetchGroups($videos, $i);
                $i++;
            }
        }

        return response()->json( [
            'success' => true,
            'groups' => $groups
        ] );

    }

    private function fetchGroups(  &$groups, $page = 1 )
    {
        $response = Vimeo::request('/me/groups',['per_page' => 100, 'page'=> $page ], 'GET');
        if( isset($response['body']) && isset($response['body']['data'])){
            foreach( $response['body']['data'] as $d ) {
                array_push($groups,['label' =>$d['name'] , 'id' =>explode('/',$d['uri'])[2] ]);
            }
        }
        return $response['body']['total'];
    }

    public function rules( $id = "" )
    {
        return [
            'name' => [
                'required',
                'unique:groups,name,'.$id
            ],
            'description' => 'required',
            'videos' => 'sometimes'
        ];
    }

    public function store( Request $request )
    {
        $request->validate( $this->rules() );

        $data = $request->all();


        $data['privacy'] = [
            'invite' => 'members',
            'join' => 'members',
            'comment' => 'members',
            'view' => 'members',
            'videos' => 'members'
        ];


        $response = Vimeo::request('/groups', $data, 'POST');
        $failure = [];
        if( isset($response['body']) && !isset($response['body']['error']) ) {
          
            $uri =  $response['body']['uri'];

            $groupId = explode('/',$uri)[2];

            $group = new Group();

            $group->name = $data['name'];

            $group->group_id = $groupId;

            $group->description = $data['description'];

            $group->creator = auth()->user()->id;

            $group->save();

            if( auth()->user()->role_id !== 1 ) {
                GroupUser::insert([
                    'user_id' => auth()->user()->id,
                    'group_id' => $groupId
                ]);
            } 

            $failure = $this->addVideosToGroup( $request->videos, $uri );

            return response()->json(['success' => 'true','data' => $response,'failure' => $failure,'message' => "Group ".$data['name']."created successfully", 'type' => 'success']);
        }
        return response()->json(['success' => 'false', 'data' => $response,'failure' => $failure,'message' => "something went wrong while creating group,Please try again", 'type' => 'success']);
    }

    private function addVideosToGroup( $videos, $uri )
    {
        $failure = [];
        if( count($videos))
        {

            foreach( $videos as $video )
            {
                $response = Vimeo::request("$uri/videos/$video",[], 'put');

                if( !isset($response['body']))
                {
                    $failure[] = $video;
                }
            }

        }
        return $failure;
    }


    public function update( Request $request,int $id )
    {
        $group = Group::where('group_id', $id )->first();

        $request->validate( $this->rules($group->id) );

       

        $group->name = $request->name;

        $group->description = $request->description;

        $group->save();

        $failure =  $this->addVideosToGroup( $request->videos, "/groups/$id");

        return response()->json(['success' => 'true','failure' => $failure,'message' => "Group updated successfully", 'type' => 'success']);
    }

    public function destroy( $uri )
    {
        $response = Vimeo::request("/groups/$uri",[], 'DELETE');

        Group::where('group_id', $uri)->delete();

        if( $response['status'] === 204 ) {
            return response()->json(['success' => 'true','data' => $response,'message' => "Group deleted successfully", 'type' => 'success']);
        } else {
            return response()->json(['success' => 'false', 'data' => $response,'message' => "something went wrong while deleting group,Please try again", 'type' => 'success']);
        }
    }

    public function videos( Request $request, $group )
    {
        $videos = [];

        $reqArr = ['per_page' => 100, 'page'=> 1 ];
        if( $request->view && $request->view != null ) {
            $reqArr=['per_page' => $request->per_page, 'page'=> $request->page,'query' => $request->keyword ];
            $response =Vimeo::request("/groups/$group/videos/",  $reqArr, 'GET');
            if( isset($response['body'])){
                $videos =$response['body'];
            }
        }
        else 
        {
            if( $request->per_page === 100 ) {
                $total = $this->fetchGroupVideos($group, $videos, $reqArr );
                if( $total > 100 ) { //since max limit is 100 for pagination
                    $pages = ($total / 100 ) > 0 ?  ceil($total / 100 ) : 0 ;
                    $i = 2;
                    while( $i <= $pages )
                    {
                        $reqArr['page'] = $i;
                        $this->fetchGroupVideos($group, $videos ,$reqArr);
                        $i++;
                    }
                }
            }
            else
            {
                $reqArr = ['per_page' => $request->per_page, 'page'=> $request->page ];
                $this->fetchGroupVideos($group, $videos , $reqArr);
            }
        }

        return response()->json( [
            'success' => true,
            'videos' => $videos,
            'creator' => Group::where('group_id', $group)->value('creator')
        ] );
    }

    private function fetchGroupVideos(  $group, &$videos,  $reqArr )
    {
        $response =Vimeo::request("/groups/$group/videos",  $reqArr, 'GET');
        if( isset($response['body']) && isset($response['body']['data'])){
            $videos = array_merge($videos, $reqArr['per_page'] !== 100 ? $response['body'] : $response['body']['data']  );
        }
        return $response['body']['total'];
    }

    public function removeVideo($group, $video )
    {
        $response = Vimeo::request("/groups/$group/videos/$video", [], 'DELETE');

        if( $response['status'] === 204 ) {
            return response()->json(['success' => 'true','data' => $response,'message' => "Video removed successfully", 'type' => 'success']);
        } else {
            return response()->json(['success' => 'false', 'data' => $response,'message' => "something went wrong while removing video,Please try again", 'type' => 'success']);
        }
    }
}
