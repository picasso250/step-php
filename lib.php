<?php

function render_with_layout($layout_tpl, $inner_tpl_list, $data)
{
    $data['_inner_tpl_list'] = $inner_tpl_list;
    extract($data);
    include $layout_tpl;
}