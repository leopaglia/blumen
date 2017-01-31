<?php

require_once __DIR__.'/../../vendor/autoload.php';

class DeviceServiceTest extends TestCase
{
    /**
     * @test
     */
    function simpleTest() {
        $this->mock('App\Repositories\DeviceRepository')->shouldReceive('findAll')->andReturn(collect([]));
        $response = $this->call('GET', '/device/');
        $this->assertEquals('[]', $response);
    }
    
}