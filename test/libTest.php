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
        $this->assertEquals(42, _get('a'));
    }
}