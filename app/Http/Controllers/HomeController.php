<?php

namespace App\Http\Controllers;

use App\Posts;
use App\User;
use EndaEditor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index', ['topic' => 0]);
    }

    public function lists(Request $request)
    {
        $pager = [
            'page' => $request->input('page') ? $request->input('page') : 1,
            'size' => 20,
        ];
        //缓存分页id
        if($request->input('category_id')==0){
            $prefix = (Auth::check()) ? 'CPostsPager_' . Auth::id() : 'CPostsPager';
        }else{
            $prefix = (Auth::check()) ? 'CPostsPager_' .$request->input('category_id').'_'. Auth::id() : 'CPostsPager';
        }
        $aId = CPager($pager, $prefix, function () use ($request) {
            $posts = new Posts;
            if (Auth::check()) {
                $posts = $posts->withTrashed();
                $posts = $posts->where('user_id', Auth::id());
            }
            if (!empty($request->input('category_id'))) {
                $posts = $posts->where('category_id', $request->input('category_id'));
            }

            $offset = ($request->input('page') - 1) * 20;
            return $posts->offset($offset)->limit(20)->orderBy('updated_at', 'desc')->get();
        });

        $aData = array();
        foreach ($aId as $id) {
            //缓存记录
            $v = (object) CRecord("CPosts_" . $id, function () use ($id) {
                $posts = new Posts();
                if (Auth::check()) {
                    $posts = $posts->withTrashed();
                }
                return $posts->where('id', $id)->first()->toArray();
            });
            //格式化字段
            $time = date('Y-m-d', strtotime($v->updated_at));
            if (!array_key_exists($time, $aData)) {
                $aData[$time] = array();
            }
            $aItem = array(
                'id' => $v->id,
                'title' => $v->title,
                'url' => $v->url,
                'updated_at' => $v->updated_at,
                'destroy' => (Auth::check()) ? 1 : 0,
                'edit' => (Auth::check()) ? 1 : 0,
                'restore' => !empty($v->deleted_at) ? 1 : 0,
            );
            array_push($aData[$time], $aItem);
        }

        $aJson['page'] = $pager['page'];
        $aJson['data'] = $aData;
        $aJson['success'] = (!empty($aData)) ? true : false;
        return response()->json($aJson);
    }

    public function topic(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            $aJson['data'] = Auth::check() ? CNavbar() : array();
            $aJson['success'] = !empty($aJson['data']) ? true : false;
            return response()->json($aJson);
        } else {
            return view('home.index', ['topic' => $id]);
        }
    }

    public function posts(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {

            $id = $request->input('id');
            $hData = (object) CRecord('CPosts_' . $id, function () use ($id) {
                $posts = new Posts();
                if (Auth::check()) {
                    $posts = $posts->withTrashed();
                }
                return $posts->where('id', $request->input('id'))->first();
            });

            $aData = array();
            if (!empty($hData)) {
                $aData['id'] = $hData->id;
                $aData['user_id'] = $hData->user_id;
                $aData['title'] = $hData->title;
                $aData['category'] = breadcrumb($hData->category_id);
                $aData['content'] = EndaEditor::MarkDecode($hData->content);
                $aData['user'] = User::where('id', $hData->user_id)->first()->name;
                $aData['time'] = $hData->updated_at;
            }
            $aJson['data'] = $aData;
            $aJson['success'] = (!empty($aData)) ? true : false;
            return response()->json($aJson);
        } else {
            return view('home.posts', ['id' => $id]);
        }
    }
}
