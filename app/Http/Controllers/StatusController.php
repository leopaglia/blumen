<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;

/**
 * Class StatusController
 * @package App\Http\Controllers
 */
class StatusController extends Controller
{
    /**
     * @return array
     */
    public function getStatus()
    {
        return ["status" => "OK"];
    }
}