<?php
function navbar()
{
    $category=new \App\Category();
    $pData=$category->where('pid',0)->get();
    $aData=array();
    foreach($pData as $val){
        $aItem=array(
            'id'=>$val->id,
            'name'=>$val->name,
            'children'=>array()
        );
        $cData=$category->where('pid',$val->id)->get();
        foreach($cData as $v){
            array_push($aItem['children'],array(
                'id'=>$v->id,
                'name'=>$v->name
            ));
        }
        array_push($aData,$aItem);
    }
    return $aData;
}

function breadcrumb($category_id,$name='')
{
    $category=new \App\Category();
    $hData=$category->where('id',$category_id)->first();
    if(!empty($hData)){
        $name=($hData->pid!=0)?breadcrumb($hData->pid)." / ".$hData->name:$hData->name;
    }
    return $name;
}