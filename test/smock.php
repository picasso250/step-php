<?php
/**
 * Created by PhpStorm.
 * User: xiao
 * Date: 2018/9/21
 * Time: 下午5:37
 *
 * @category File
 * @package  GLOBAL
 * @author   xiaochi <wxiaochi@qq.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://coding.net/u/picasso250/p/10x-programer/git
 */

define('ROOT_URL', 'http://localhost:8000');

// 首页
list($ch, $ret) = Http_Get_must("/");
$info = curl_getinfo($ch);
if ($info['http_code'] != 200) {
    throw new Exception("http code should be 200, $info[http_code] found", $info['code']);
}

// 参数
list($ch, $ret) = Http_Get_must("/hello/xc");
$info = curl_getinfo($ch);
if ($info['http_code'] != 200) {
    throw new Exception("http code should be 200, $info[http_code] found", $info['code']);
}
if (!preg_match('/xc$/', $ret)) {
    throw new Exception("name should be xc");
}

// 错误页面
list($ch, $ret) = Http_Get_must("/error/example");
$info = curl_getinfo($ch);
if ($info['http_code'] != 500) {
    throw new Exception("http code should be 500, $info[http_code] found", $info['code']);
}

// ==== ALL OK =====
echo "OK.\n";

/**
 * Do a GET and return json
 *
 * @param string $url url
 *
 * @return array
 */
function Http_Get_must($url)
{
    echo "GET\t",ROOT_URL.$url,"\n";
    $ch = curl_init(ROOT_URL.$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    $ret = curl_exec($ch);
    $errno = curl_errno($ch);
    if ($errno != 0) {
        throw new \Exception(curl_error($ch), curl_errno($ch));
    }
    return [$ch, $ret];
}

/**
 * Do a GET and ensure no curl error
 *
 * @param string $url url
 *
 * @return string
 */
function Http_get($url)
{
    list($ch, $ret) = Http_Get_must($url);
    $info = curl_getinfo($ch);
    if ($info['http_code'] != 200) {
        throw new \Exception("http code not 200", $info['http_code']);
    }
    return substr($ret, $info['header_size']);
}

/**
 * Do a post and ensure no curl error
 *
 * @param string $url  url
 * @param string $data data
 *
 * @return array
 */
function Http_Post_must($url, $data)
{
    $ch = curl_init(ROOT_URL.$url);
    echo "POST\t",ROOT_URL.$url,"\n";
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36");
    $ret = curl_exec($ch);
    $errno = curl_errno($ch);
    if ($errno != 0) {
        throw new \Exception(curl_error($ch), curl_errno($ch));
    }
    return [$ch, $ret];
}

/**
 * Do a post and return json
 *
 * @param string $url  url
 * @param string $data data
 *
 * @return mixed
 */
function Http_post($url, $data)
{
    list($ch, $ret) = Http_Post_must($url, $data);
    $info = curl_getinfo($ch);
    if ($info['http_code'] != 200) {
        throw new \Exception("http code not 200", $info['http_code']);
    }
    $body = substr($ret, $info['header_size']);
    if (empty($body)) {
        throw new \Exception("no body", 1);
    }
    $j = json_decode($body);
    if (json_last_error()) {
        throw new \Exception(json_last_error_msg(), json_last_error());
    }
    return $j;
}
