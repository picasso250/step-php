<?php

function _get($key, $default = '')
{
    return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
}
function _post($key, $default = '')
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

function render_with_layout($layout_tpl, $inner_tpl_list, $data = [])
{
    $data['_inner_tpl_list'] = $inner_tpl_list;
    extract($data);
    include $layout_tpl;
}
function sv($name,$value=null){
    static $lazy;
    static $pool;
    if ($value=== null){
        // get
        if(isset($pool[$name]))return $pool[$name];
        if(isset($lazy[$name]))return $pool[$name]=($lazy[$name])();
        return null;
    } else {
        // set
        if (is_callable($value)) $lazy[$name]=$value;
        else $pool[$name]=$value;
    }
}
function dot_env($root=""){
    if (defined("ROOT")&&$root==="") $root=ROOT;
    $file="$root/.env";
    if(!file_exists($file))die("no .env file");
    $vars=parse_ini_file($file);
    foreach($vars as $k=>$v){
        $_ENV[$k]=$v;
    }
}
    
          
