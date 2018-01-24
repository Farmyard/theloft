<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Category;
use App\Posts;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $aJson['data']=$this->recursive(0,1);
        $aJson['success']=!empty($aJson['data'])?true:false;
        return response()->json($aJson);
    }

    private function recursive($pid,$level)
    {
        $category = new Category;
        $hData=$category->where('pid',$pid)->get();
        $_level=$level+1;
        $aData=array();
        foreach($hData as $v){
            $aItem=array(
                'id'=>$v->id,
                'pid'=>$v->pid,
                'name'=>$v->name,
                'level'=>$level,
            );
            $aItem['children']=(!empty($category->where('pid',$v->id)->get()))?$this->recursive($v->id,$_level):array();
            array_push($aData,$aItem);
        }
        return $aData;
    }

    public function store(Request $request)
    {
        if(empty($request->input('name'))){
            $aJson['success']=false;
            $aJson['msg']="分类名称不能为空";
            return response()->json($aJson);
        }else{
            $hData=Category::where('name',$request->input('name'))->first();
            if(!empty($hData)){
                $aJson['success']=false;
                $aJson['msg']="分类名称已经存在";
                return response()->json($aJson);
            }
        }
        $category = new Category;

        $category->pid=$request->pid;
        $category->name = $request->name;

        if($category->save()){
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
        if(empty($request->input('pid'))){
            $aJson['success']=false;
            $aJson['msg']="请选择分类名称";
            return response()->json($aJson);
        }
        if(empty($request->input('name'))){
            $aJson['success']=false;
            $aJson['msg']="分类名称不能为空";
            return response()->json($aJson);
        }else{
            $hData=Category::where('name',$request->input('name'))->first();
            if(!empty($hData)){
                $aJson['success']=false;
                $aJson['msg']="分类名称已经存在";
                return response()->json($aJson);
            }
        }
        $category = Category::find($request->pid);
        $category->name = $request->name;

        if($category->save()){
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
        if(empty($request->input('id'))||!is_numeric($request->input('id'))){
            $aJson['success']=false;
            $aJson['msg']="参数异常，无法删除";
            return response()->json($aJson);
        }
        //获取子类
        $category=Category::where('pid',$request->input('id'))->get();
        $cId=array($request->input('id'));
        foreach($category as $v){
            array_push($cId,$v->id);
        }
        $posts=Posts::whereIn('category_id',$cId)->get();
        $pId=array();
        foreach($posts as $v){
            array_push($pId,$v->id);
        }
        if(Category::destroy($cId)){
            Posts::destroy($pId);
            $aJson['success']=true;
            $aJson['msg']="删除成功";
        }else{
            $aJson['success']=false;
            $aJson['msg']="删除失败";
        }
        return response()->json($aJson);
    }

    public function topic(Request $request)
    {
        $aJson['data']=navbar();
        $aJson['success']=(!empty(navbar()))?true:false;
        return response()->json($aJson);
    }
}