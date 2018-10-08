<?php

function action_index()
{
    include ROOT_VIEW.'/welcome.php';
}
function action_hello($name)
{
    echo "hello, $name";
}
function action_db()
{
    $v = ORM::for_table('user')->find_one();
    echo "user_id=$v[id]";
}

/**
 * @SuppressWarnings(PHPMD)
 */
function action_error_example()
{
    echo $a;
}
function action_full()
{
    $u = ORM::for_table('user')->find_one();
    Res::$layout_tpl=ROOT_VIEW.'/layout.php';
    Res::renderWithLayout(['content' => ROOT_VIEW.'/full.php'], compact('u'));
}