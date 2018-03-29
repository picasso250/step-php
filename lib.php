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