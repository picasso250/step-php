<?php
/**
 * Magic class!
 * phpmd.phar lib.php text codesize,design,unusedcode
 * phpcs lib.php -n
 *
 * @category Class_And_Function
 * @package  GLOBAL
 * @author   xiaochi <wxiaochi@qq.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://coding.net/u/picasso250/p/10x-programer/git
 */

/**
 * Service, container, IoC
 *
 * @category Class
 * @package  GLOBAL
 * @author   xiaochi <wxiaochi@qq.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://coding.net/u/picasso250/p/10x-programer/git
 * @method   static Pdo db(callable $c=null) get or set db
 */
class Sv
{

    static $lazy;
    static $pool;

    /**
     * Get or set service
     *
     * @param string $name name of service
     * @param array  $args value or function
     *
     * @return null
     */
    static function __callStatic($name, $args)
    {
        $value = isset($args[0]) ? $args[0] : null;
        if ($value=== null) {
            // get
            if (isset(self::$pool[$name]))return self::$pool[$name];
            if (isset(self::$lazy[$name])) {
                $f = self::$lazy[$name];
                return self::$pool[$name]=$f();
            }
            return null;
        } else {
            // set
            if (is_callable($value)) self::$lazy[$name]=$value;
            else self::$pool[$name]=$value;
        }
    }
}

/**
 * Load env to $_ENV
 *
 * @param string $root dir
 *
 * @category Function
 * @package  GLOBAL
 * @author   xiaochi <wxiaochi@qq.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://coding.net/u/picasso250/p/10x-programer/git
 *
 * @return bool
 */
function dotEnv($root=__DIR__)
{
    if (defined("ROOT")&&$root==="") $root=ROOT;
    $file="$root/.env";
    if (!file_exists($file)) return false;
    $vars=parse_ini_file($file);
    foreach ($vars as $k=>$v) {
        $_ENV[$k]=$v;
    }
    return true;
}

/**
 * Database
 *
 * @category Class
 * @package  GLOBAL
 * @author   xiaochi <wxiaochi@qq.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://coding.net/u/picasso250/p/10x-programer/git
 */
class Db
{
    /**
     * Execute a sql
     *
     * @param string $sql  sql
     * @param array  $vars binding vars
     *
     * @return mixed
     */
    static function execute($sql, $vars=[])
    {
        $db = Sv::db();
        $stmt=$db->prepare($sql);
        $stmt->execute($vars);
        return $stmt;
    }

    /**
     * Execute a sql and fetch rows
     *
     * @param string $sql  sql
     * @param array  $vars binding vars
     *
     * @return mixed
     */
    static function fetchAll($sql, $vars=[])
    {
        $stmt = self::execute($sql, $vars);
        $a = $stmt->fetchAll();
        return $a ? $a : [];
    }

    /**
     * Execute a sql and fetch one
     *
     * @param string $sql  sql
     * @param array  $vars binding vars
     *
     * @return mixed
     */
    static function fetch($sql, $vars=[])
    {
        $stmt = self::execute($sql, $vars);
        $a = $stmt->fetch();
        return $a;
    }
}

/**
 * Response
 *
 * @category Class
 * @package  GLOBAL
 * @author   xiaochi <wxiaochi@qq.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://coding.net/u/picasso250/p/10x-programer/git
 */
class Res
{
    static $layout_tpl;

    /**
     * Render php template with layout
     *
     * @param array $inner_tpl_table table of template
     * @param array $data            vars
     *
     * @return null
     */
    static function renderWithLayout($inner_tpl_table, $data = [])
    {
        $data['_inner_tpl_table'] = $inner_tpl_table;
        extract($data);
        include self::$layout_tpl;
    }
}

/**
 * Request
 *
 * @category Class
 * @package  GLOBAL
 * @author   xiaochi <wxiaochi@qq.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://coding.net/u/picasso250/p/10x-programer/git
 */
class Req
{
    /**
     * Get from $_GET
     *
     * @param string $key     name
     * @param string $default value
     *
     * @return string
     */
    static function get($key, $default = '')
    {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }
    /**
     * Get from $_POST
     *
     * @param string $key     name
     * @param string $default value
     *
     * @return string
     */
    static function post($key, $default = '')
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }
}

class Model implements ArrayAccess {
    protected $_data = [];
    protected $_dirty = [];
    public function offsetExists ( $offset ) {
        return isset($this->_data[$offset]);
    }
    public function offsetGet ( $offset ) {
        return $this->_data[$offset];
    }
    public function offsetSet ( $offset , $value ) {
        $this->_data[$offset] = $value;
        $this->_dirty[$offset] = 1;
    }
    public function offsetUnset ( $offset ) {

    }
    public function __construct ($data ) {
        $this->_data = $data;
    }
    static function table() {
        return strtolower(get_called_class());
    }
    static function pkey() {
        return 'id';
    }
    static function find($id) {
        $t = static::table();
        $pkey = static::pkey();
        $sql = "SELECT * from `$t` where `$pkey`=? limit 1";
        $a = db::fetch($sql, [$id]);
        return $a ? new static($a) : null;
    }
    function save() {
        $t = static::table();
        $pkey = static::pkey();
        if (!isset($this[$pkey])) {
            $a = $b = $v = [];
            foreach ($this->_dirty as $key => $_) {
                $a[] = "`$key`";
                $b[] = "?";
                $v[] = $this[$key];
            }
            $a_ = implode(',', $a);
            $b_ = implode(',', $b);
            db::execute("INSERT into `$t` ($a_) values($b_)", $v);
            $db = sv::db();
            $this[$pkey] = $db->lastInsertId();
        } else {
            $a = $b = $v = [];
            foreach ($this->_dirty as $key => $_) {
                $a[] = "`$key`=?";
                $v[] = $this[$key];
            }
            $a_ = implode(',', $a);
            $v[] = $this['id'];
            db::execute("UPDATE `$t` set $a_ where `$pkey`=?", $v);
        }
        $this->_dirty = [];
    }

}