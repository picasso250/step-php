<?php
/**
 * Created by PhpStorm.
 * User: niaogebiji
 * Date: 2018/9/24
 * Time: 下午5:16
 */

use PHPUnit\Framework\TestCase;

final class libTest extends TestCase
{
    public function testCanGet()
    {
        $_GET['a'] = 42;
        $this->assertEquals(42, Req::get('a'));
    }
    public function testCanGetService()
    {
        Sv::a(42);
        $this->assertEquals(42, Sv::a());
    }
    public function testCanLoadEnv()
    {
        $temp_dir=sys_get_temp_dir();
        $dir = $temp_dir."/a".mt_rand();
        if (!is_dir($dir)) mkdir($dir);
        $file = "$dir/.env";
        file_put_contents($file, "a=42");
        dotEnv($dir);
        $this->assertEquals("42", $_ENV['a']);
        unlink($file);
        rmdir($dir);
    }
}