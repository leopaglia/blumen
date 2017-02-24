<?php

namespace App\Http\Controllers;
use App\Repositories\ServiceRepository;
use Laravel\Lumen\Routing\Controller;

/**
 * Class StatusController
 * @package App\Http\Controllers
 */
class StatusController extends Controller
{
    public function __construct(ServiceRepository $s)
    {
        $this->s = $s;
    }

    public function getStatus()
    {
        return $this->s->findAll();
//        return ["status" => "OK"];
    }
}