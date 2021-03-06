<?php
declare(strict_types=1);

namespace test\labo86\hapi;

use labo86\hapi\ResponseJson;
use PHPUnit\Framework\TestCase;

class ResponseJsonTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSend()
    {
        $originalData = ['a' => 1, 'b' => 2];
        $response = new ResponseJson($originalData);
        $response->addHeader("Expires: 0");
        ob_start();
        $response->send();
        $json_data = ob_get_clean();
        $recoveredData = json_decode($json_data, true);
        $this->assertEquals(200, $response->getHttpResponseCode());
        $this->assertEquals('application/json;charset=utf-8', $response->getMimeType());
        $this->assertEquals(["Expires: 0"], $response->getHeaderList());
        $this->assertEquals($originalData, $recoveredData);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendCookie()
    {
        $originalData = ['a' => 1, 'b' => 2];
        $response = new ResponseJson($originalData);
        $response->setCookie('session_id', 'wachulin');
        ob_start();
        $response->send();
        $json_data = ob_get_clean();
        $recoveredData = json_decode($json_data, true);
        $this->assertEquals($originalData, $recoveredData);

        $this->assertEquals(['value' => 'wachulin', 'options' => []],$response->getCookieMap()['session_id']);
    }
}
