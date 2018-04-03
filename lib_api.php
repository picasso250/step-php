<?php

function _api_action_index()
{
    $action = $_GET['action'];
    $func = "api_action_$action";
    if (!function_exists($func)) {
        echo "function do not exists";
        die();
    }
    $func();
}
function _api_action_admin()
{

}
function _api_action_playground()
{
    $resources = _api_get_config_parsed();
    $field_table = _api_get_config_field_table();
    // print_r($field_table);exit;
    include ROOT_VIEW.'/api/play_ground.php';
}
function _api_get_config_parsed()
{
    static $config_parsed;
    if ($config_parsed) return $config_parsed;
    return $config_parsed = _api_parse_config();
}
function _api_parse_config()
{
    $ret = [];
    $resource_names = explode(",", _api_get_conf_by_key("core", "resources"));
    foreach ($resource_names as $resource_name) {
        $ret[$resource_name] = ['name' => $resource_name];
        $ret[$resource_name]['table_name'] = _api_get_config_by_key_default([$resource_name, 'table_name'], $resource_name);

        // method
        $ret[$resource_name]['methods'] = [];
        foreach (_api_get_conf_by_key($resource_name) as $key => $value) {
            if (strpos($key, '-') === 0 && $value) {
                $ret[$resource_name]['methods'][substr($key, 1)] = _api_parse_method($resource_name, substr($key, 1));
            }
        }
    }
    return $ret;
}

function _api_get_conf_by_key()
{
    $config_api = $GLOBALS['config_api'];
    foreach (func_get_args() as $key) {
        if (isset($config_api[$key])) {
            $config_api = $config_api[$key];
        } else {
            die("API 配置中 不存在 ".implode(".", func_get_args()));
        }
    }
    return $config_api;
}

function _api_get_config_by_key_default($key_path, $default = null)
{
    $config_api = $GLOBALS['config_api'];
    foreach ($key_path as $key) {
        if (isset($config_api[$key])) {
            $config_api = $config_api[$key];
        } else {
            return $default;
        }
    }
    return $config_api;
}
function _api_parse_method($resource, $method)
{
    $key = "$resource-$method";
    $config = _api_get_conf_by_key($key);
    $ret = [];
    $ret['name'] = _api_get_conf_by_key($key, 'name');
    $ret['uri'] = '/api.php?'.http_build_query(['resource'=>$resource, 'method'=>$method]);
    $input = _api_get_config_by_key_default([$key, 'input']);
    $ret['input'] = $input ? explode(',', $input) : [];
    $output = _api_get_config_by_key_default([$key, 'output']);
    $ret['output'] = $output ? explode(',', $output) : [];
    $ret['function'] = $func = 'api_data_'._api_get_conf_by_key($key, 'function');
    if (!function_exists($func))
        die("no function $func for $key");
    return $ret;
}
function _api_parse_field($value, $env = [])
{
    if (!$value) die("配置出错,不能为空");
    if ($value[0] == '=') {
        // stole from other
        $s = substr($value, 1);
        $a = explode('.', $s);
        if (count($a)!== 2) die("不能解析 $s, 期待 a.b");
        return $env[$a[0]][$a[1]];
    }
    $pos = strpos($value, ',');
    $type = substr($value, 0, $pos);
    $description = substr($value, $pos+1);
    return compact("type", 'description');
}

function _api_get_config_field_table()
{
    static $table;
    if ($table) return $table;
    else return $table = _api_parse_config_field_table();
}
function _api_parse_config_field_table()
{
    $ret = [];
    $config_api = $GLOBALS['config_api'];
    foreach ($config_api as $key => $value) {
        $ret[$key] = [];
        foreach ($value as $k => $v) {
            if (strpos($k, '.') === 0) {
                if (!$v) die("$key$k 配置不能为空");
                $ret[$key][substr($k, 1)] = _api_parse_field($v, $ret);
            }
        }
    }
    return $ret;
}