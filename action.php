<?php

function action_index()
{
    echo "A Step Away From PHP.";
}
function action_hello($name)
{
    echo "hello, $name";
}
function action_html_file()
{
    $debugbar = $GLOBALS['debugbar'];
    $debugbar["messages"]->addMessage("hello world!");
    include ROOT_VIEW.'/a.php';
}
function action_db()
{
    $v = ORM::for_table('user')->find_one();
    echo "user_id=$v[id]";
}
function action_error_example()
{
    echo $a;
}
function action_full()
{
    $u = ORM::for_table('user')->find_one();
    render_with_layout(ROOT_VIEW.'/layout.php', ['content' => ROOT_VIEW.'/full.php'], compact('u'));
}