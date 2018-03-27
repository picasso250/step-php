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
    include ROOT.'/view/a.html';
}
function action_db()
{
    $v = ORM::for_table('user')->find_one();
    echo "user_id=$v[id]";
}