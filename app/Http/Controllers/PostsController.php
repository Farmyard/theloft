<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Category;
use App\Posts;
use App\User;
use EndaEditor;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function create()
    {
        return view('posts.save',['id'=>0]);
    }

    public function edit($id)
    {
        return view('posts.save',['id'=>$id]);
    }

    public function store(Request $request)
    {
        if($request->input('id')){
            $aJson['success']=false;
            $aJson['msg']="非法请求";
            return response()->json($aJson);
        }
        
        if(empty($request->input('title'))){
            $aJson['success']=false;
            $aJson['msg']="文章标题不能为空";
            return response()->json($aJson);
        }else{
            $hData=Posts::withTrashed()->where('title',$request->input('title'))->first();
            if(!empty($hData)){
                $aJson['success']=false;
                $aJson['msg']="文章标题已经存在";
                return response()->json($aJson);
            }
        }
        if(empty($request->input('category_id'))){
            $aJson['success']=false;
            $aJson['msg']="请选择分类";
            return response()->json($aJson);
        }
        $posts = new Posts;

        $posts->category_id=$request->category_id;
        $posts->title = $request->title;
        $posts->content = $request->content;
        $posts->user_id = Auth::id();

        if($posts->save()){
            $aJson['success']=true;
            $aJson['msg']="新增成功";
        }else{
            $aJson['success']=false;
            $aJson['msg']="新增失败";
        }
        return response()->json($aJson);
    }

    public function update(Request $request)
    {
        $posts = Posts::withTrashed()->find($request->input('id'));
        if(empty($request->input('title'))){
            $aJson['success']=false;
            $aJson['msg']="文章标题不能为空";
            return response()->json($aJson);
        }else{
            if($posts->title!=$request->input('title')){
                $hData=Posts::withTrashed()->where('title',$request->input('title'))->first();
                if(!empty($hData)){
                    $aJson['success']=false;
                    $aJson['msg']="文章标题已经存在";
                    return response()->json($aJson);
                }
            }
        }
        if(empty($request->input('category_id'))){
            $aJson['success']=false;
            $aJson['msg']="请选择分类";
            return response()->json($aJson);
        }
        $posts->category_id=$request->category_id;
        $posts->title = $request->title;
        $posts->content = $request->content;
        $posts->user_id = Auth::id();

        if($posts->save()){
            $aJson['success']=true;
            $aJson['msg']="修改成功";
        }else{
            $aJson['success']=false;
            $aJson['msg']="修改失败";
        }
        return response()->json($aJson);
    }

    public function destroy(Request $request)
    {
        $rs=false;
        if($request->input('type')=='soft'){
            $rs=Posts::destroy($request->input('id'));
        }elseif($request->input('type')=='force'){
            $rs=Posts::withTrashed()->where('id',$request->input('id'))->forceDelete();
        }
        if($rs){
            $aJson['success']=true;
            $aJson['msg']="删除成功";
        }else{
            $aJson['success']=false;
            $aJson['msg']="删除失败";
        }
        return response()->json($aJson);
    }

    public function show(Request $request)
    {
        $posts=new Posts();
        $hData=$posts->withTrashed()->where('id',$request->input('id'))->first();
        $aData=array();
        if(!empty($hData)){
            $aData['id']=$hData->id;
            $aData['category_id']=$hData->category_id;
            $aData['title']=$hData->title;
            $aData['category']=breadcrumb($hData->category_id);
            $aData['content']=$hData->content;
            $aData['user']=User::where('id',$hData->user_id)->first()->name;
            $aData['time']=$hData->updated_at->format('Y年m月d日');
        }
        $aJson['data']=$aData;
        $aJson['success']=(!empty($aData))?true:false;
        return response()->json($aJson);
    }

    public function restore(Request $request)
    {
        if(Posts::withTrashed()->where('id', $request->input('id'))->restore()){
            $aJson['success']=true;
            $aJson['msg']="恢复成功";
        }else{
            $aJson['success']=false;
            $aJson['msg']="恢复失败";
        }
        return response()->json($aJson);
    }

    public function upload()
    {
        $path = 'uploads/image';
        $data = EndaEditor::uploadImgFile($path);
        return json_encode($data); 
    }
}