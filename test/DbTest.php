<?php

class DbTest extends PHPUnit_Extensions_Database_TestCase
{

    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            self::$pdo->exec("CREATE TABLE IF NOT EXISTS `t` (
	`id` INT(11) NULL DEFAULT NULL,
	`content` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`user` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`created` DATETIME NULL DEFAULT NULL
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
");
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(dirname(__FILE__).'/_files/t-seed.xml');
    }

    public function testCanFetch()
    {
        $ds = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
        $ds->addTable('t');
        sv::db(function() {
                return new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
        });
        $a = db::fetch("select *from t where id=1");
        $this->assertEquals("joe", $a['user']);
    }
}