<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Category;
use App\Posts;
use App\User;
use EndaEditor;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index',['topic'=>0]);
    }

    public function lists(Request $request)
    {
        $posts = new Posts;
        
        if(Auth::check()){
            $posts=$posts->withTrashed();
        }
        if(!empty($request->input('category_id'))){
            $posts=$posts->where('category_id',$request->input('category_id'));
        }

        $aJson['page']=$request->input('page');
        $aJson['total']=ceil($posts->count()/20);

        $offset=($request->input('page')-1)*20;
        $hData=$posts->offset($offset)->limit(20)->orderBy('updated_at', 'desc')->get();
        $aData=array();
        foreach($hData as $v){
            $time=date('Y-m-d',strtotime($v->updated_at));
            if(!array_key_exists($time,$aData)){
                $aData[$time]=array();
            }
            $aItem=array(
                'id'=>$v->id,
                'title'=>$v->title,
                'updated_at'=>$v->updated_at->format('Y-m-d'),
                'destroy'=>(Auth::check())?1:0,
                'edit'=>(Auth::check())?1:0,
                'restore'=>!empty($v->deleted_at)?1:0,
            );
            array_push($aData[$time],$aItem);
        }
        
        $aJson['data']=$aData;
        $aJson['success']=(!empty($aData))?true:false;
        return response()->json($aJson);
    }

    public function topic(Request $request,$id=null)
    {
        if($request->isMethod('post')){
            $aJson['data']=navbar();
            $aJson['success']=(!empty(navbar()))?true:false;
            return response()->json($aJson);
        }else{
            return view('home.index',['topic'=>$id]);
        }
    }

    public function posts(Request $request,$id=null)
    {
        if($request->isMethod('post')){
            $posts=new Posts();

            if(Auth::check()){
                $posts=$posts->withTrashed();
            }
        
            $hData=$posts->where('id',$request->input('id'))->first();
            $aData=array();
            if(!empty($hData)){
                $aData['id']=$hData->id;
                $aData['title']=$hData->title;
                $aData['category']=breadcrumb($hData->category_id);
                $aData['content']=EndaEditor::MarkDecode($hData->content);
                $aData['user']=User::where('id',$hData->user_id)->first()->name;
                $aData['time']=$hData->updated_at->format('Y年m月d日');
            }
            $aJson['data']=$aData;
            $aJson['success']=(!empty($aData))?true:false;
            return response()->json($aJson);
        }else{
            return view('home.posts',['id'=>$id]);
        }
    }
}
