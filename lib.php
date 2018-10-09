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

/**
 * Model can save
 *
 * @category Class
 * @package  GLOBAL
 * @author   xiaochi <wxiaochi@qq.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://coding.net/u/picasso250/p/10x-programer/git
 */
class Model implements ArrayAccess
{
    protected $data_ = [];
    protected $dirty_ = [];

    /**
     * Array offsetExists
     *
     * @param string $offset name
     *
     * @return bool
     */
    public function offsetExists( $offset )
    {
        return isset($this->data_[$offset]);
    }
    /**
     * Array offsetGet
     *
     * @param string $offset name
     *
     * @return mixed
     */
    public function offsetGet( $offset )
    {
        return $this->data_[$offset];
    }
    /**
     * Array offsetSet
     *
     * @param string $offset name
     * @param string $value  value
     *
     * @return null
     */
    public function offsetSet( $offset , $value )
    {
        $this->data_[$offset] = $value;
        $this->dirty_[$offset] = 1;
    }

    /**
     * Array offsetSet
     * 
     * @param string $offset name
     * 
     * @SuppressWarnings("unused")
     * 
     * @return null
     */
    public function offsetUnset( $offset )
    {
    }

    /**
     * From array
     *
     * @param array $data name
     *
     * @return null
     */
    public function __construct($data =[])
    {
        $this->data_ = $data;
    }

    /**
     * Table name
     *
     * @return string
     */
    static function table()
    {
        return strtolower(get_called_class());
    }

    /**
     * Primary Key
     *
     * @return string
     */
    static function pkey()
    {
        return 'id';
    }

    /**
     * Primary Key
     *
     * @param string $id id
     * 
     * @return static
     */
    static function find($id)
    {
        $t = static::table();
        $pkey = static::pkey();
        $sql = "SELECT * from `$t` where `$pkey`=? limit 1";
        $a = db::fetch($sql, [$id]);
        return $a ? new static($a) : null;
    }

    /**
     * Save(update or insert)
     *
     * @return static
     */
    function save()
    {
        $t = static::table();
        $pkey = static::pkey();
        if (!isset($this[$pkey])) {
            $a = $b = $v = [];
            foreach ($this->dirty_ as $key => $_) {
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
            foreach ($this->dirty_ as $key => $_) {
                $a[] = "`$key`=?";
                $v[] = $this[$key];
            }
            $a_ = implode(',', $a);
            $v[] = $this['id'];
            db::execute("UPDATE `$t` set $a_ where `$pkey`=?", $v);
        }
        $this->dirty_ = [];
    }

}