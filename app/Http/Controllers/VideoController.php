<?php

namespace App\Http\Controllers;

use App\Models\GroupUser;
use App\Models\UserVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vimeo\Laravel\Facades\Vimeo;

class VideoController extends Controller
{
    public function index(Request $request)
    {

        $total = 0;
        $uris = $this->getUris($request, $total);
        $allvidoes = Vimeo::request('/me/videos', ['per_page' => 50, 'page' => $request->page, 'query' => ""], 'GET');
        $allvidoes_title = array_column($allvidoes['body']['data'], 'name');
        // sleep(8);
        if (!$uris) {
            $response = Vimeo::request('/me/videos', ['per_page' => $request->per_page, 'page' => $request->page, 'query' => $request->keyword ?? ""], 'GET');
        } else if ($uris !== "No Data") {
            $response = Vimeo::request(
                '/videos',
                [
                    'page' => $request->page,
                    'per_page' => $request->per_page,
                    'uris' => $uris,
                    'query' => $request->keyword ?? ""
                ],
                'GET'
            );
            $response['body']['total'] = $total;
        } else {
            $response['body'] = null;
        }

        return response()->json([
            'success' => true,
            'videos' => $response['body'],
            'lists' => $allvidoes_title
        ]);
    }

    protected function getUris($request, &$total, $flag = false)
    {
        $uris = null;
        $user = Auth::user();
        $client = false;
        if (isset($request->user_id) && $request->user_id != null) {
            $client = true;
            $userId = $request->user_id;
        } else {
            $userId =  $user->id;
        }
        if (in_array($user->role_id, [2, 3]) || $client) {
            $ids = UserVideo::where('user_id', $userId)->whereDate('expiry_date', '>=', date('Y-m-d'))->pluck('video_id')->toArray();
            $groups = GroupUser::where('user_id', $userId)->whereDate('expiry_date', '>=', date('Y-m-d'))->pluck('group_id')->toArray();

            foreach ($groups as $g) {
                $ids1 = $this->videos($request, $g);
                $ids = array_merge($ids, $ids1);
            }
            $total = count($ids);
            if ($total) {
                if (!$flag) {
                    $videos = array_slice($ids, ($request->page - 1) * $request->per_page, $request->page * $request->per_page);
                } else {
                    $videos = $ids;
                }

                array_walk($videos, function (&$val) {
                    $val = sprintf("/videos/%s", $val);
                });

                $uris = implode(",", $videos);
            } else {
                $uris = "No Data";
            }
        }
        return $uris;
    }

    public function list(Request $request)
    {
        $videos = [];
        $total = 0;

        $uris = $this->getUris($request, $total, true);

        $uri = "/videos";
        if (!$uris || $uris === "No Data") {
            $reqArr = ['per_page' => 100, 'page' => 1];
            $uri = "/me/videos";
        } else {
            $reqArr['uris'] = $uris;
        }

        if (isset($reqArr['uris'])) {
            $this->fetchVideos($videos, $reqArr, $uri);
        } else {
            $total = $this->fetchVideos($videos, $reqArr, $uri);
            if ($total > 100) { //since max limit is 100 for pagination
                $pages = ($total / 10) > 0 ?  ceil($total / 100) : 0;
                $i = 2;
                while ($i <= $pages) {
                    $reqArr['page'] = $i;
                    $this->fetchVideos($videos,  $reqArr, $uri);
                    $i++;
                }
            }
        }



        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    private function fetchVideos(&$videos,  $reqArr, $uri)
    {
        $response = Vimeo::request($uri, $reqArr, 'GET');
        if (isset($response['body']) && isset($response['body']['data'])) {
            foreach ($response['body']['data'] as $d) {
                array_push($videos, ['label' => $d['name'], 'id' => explode('/', $d['uri'])[2]]);
            }
        }
        return $response['body']['total'];
    }

    public function videos(Request $request, $group)
    {
        $videos = [];

        $reqArr = ['per_page' => 100, 'page' => 1];

        if ($request->per_page === 100) {
            $total = $this->fetchGroupVideos($group, $videos, $reqArr);
            if ($total > 100) { //since max limit is 100 for pagination
                $pages = ($total / 100) > 0 ?  ceil($total / 100) : 0;
                $i = 2;
                while ($i <= $pages) {
                    $reqArr['page'] = $i;
                    $this->fetchGroupVideos($group, $videos, $reqArr);
                    $i++;
                }
            }
        } else {
            $this->fetchGroupVideos($group, $videos, $reqArr);
        }

        return $videos;
    }

    private function fetchGroupVideos($group, &$videos,  $reqArr)
    {
        $response = Vimeo::request("/groups/$group/videos",  $reqArr, 'GET');
        if (isset($response['body']) && isset($response['body']['data'])) {
            foreach ($response['body']['data'] as $d) {
                array_push($videos, explode('/', $d['uri'])[2]);
            }
        }
        return $response['body']['total'];
    }
}
