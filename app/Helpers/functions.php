<?php
function CNavbar()
{
    $redis = new \Illuminate\Support\Facades\Redis;
    $key = 'CNavbar_' . (Auth::id());
    if (!$redis::exists($key)) {
        $category = new \App\Category();
        if (Auth::check()) {
            $category = $category->where('user_id', Auth::id());
        }
        $pData = $category->where('pid', 0)->get();
        $aData = array();
        foreach ($pData as $val) {
            $aItem = array(
                'id' => $val->id,
                'name' => $val->name,
                'children' => array(),
            );
            $category = new \App\Category();
            $cData = $category->where('pid', $val->id)->get();
            foreach ($cData as $v) {
                array_push($aItem['children'], array(
                    'id' => $v->id,
                    'name' => $v->name,
                ));
            }
            array_push($aData, $aItem);
        }
        $redis::set($key, json_encode($aData));
        $redis::expire($key, 1800);
    }
    return json_decode($redis::get($key), true);
}

function breadcrumb($category_id, $name = '')
{
    $category = new \App\Category();
    $hData = $category->where('id', $category_id)->first();
    if (!empty($hData)) {
        $name = ($hData->pid != 0) ? breadcrumb($hData->pid) . " / " . $hData->name : $hData->name;
    }
    return $name;
}

function CPager($pager, $prefix, $callback, $expire = 1800)
{
    $redis = new \Illuminate\Support\Facades\Redis;
    $key = $prefix . "_" . $pager['size'] . "_" . $pager['page'];
    if (!$redis::exists($key)) {
        $hData = $callback();
        foreach ($hData as $v) {
            $redis::zadd($key, strtotime($v->updated_at), $v->id);
        }
        $redis::expire($key, 1800);

        $redis::zadd($prefix, 20, $pager['page']);
        $redis::expire($prefix, 1800);
    }
    return $redis::zrevrange($key, 0, -1);
}

function CRecord($key, $callback, $expire = 1800)
{
    $redis = new \Illuminate\Support\Facades\Redis;
    if (!$redis::exists($key)) {
        $data = $callback();
        $redis::hmset($key, $data);
        $redis::expire($key, $expire);
    }

    return $redis::hgetall($key);
}

function rmPagerCache($prefix)
{
    $redis = new \Illuminate\Support\Facades\Redis;
    $pager = $redis::zrevrange($prefix, 0, -1);
    foreach ($pager as $page) {
        $key = $prefix . "_20_" . $page;
        $posts = $redis::zrevrange($key, 0, -1);
        $redis::del($key);
    }
    $redis::del($prefix);
}

function rmNavbarCache()
{
    $key='CNavbar_' . (Auth::id());
    $redis = new \Illuminate\Support\Facades\Redis;
    $redis::del($key);
}
