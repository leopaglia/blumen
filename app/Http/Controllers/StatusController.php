<?php

namespace App\Http\Controllers;

/**
 * Class StatusController
 * @package App\Http\Controllers
 */
class StatusController
{
    public function getStatus()
    {
        return ["status" => "OK"];
    }
}