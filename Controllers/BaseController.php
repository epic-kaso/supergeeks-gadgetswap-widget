<?php
/**
 * Created by PhpStorm.
 * User: kaso
 * Date: 12/20/2014
 * Time: 9:07 AM
 */

namespace Controllers;


use Slim\Slim;

class BaseController {

    protected $slimApp;
    protected $baseRoute;

    function __construct(Slim $slimApp,$baseRoute = '/')
    {
        $this->slimApp = $slimApp;
        $this->baseRoute = $baseRoute;
        $this->initialize();
    }

    public function getApp(){
        return $this->slimApp;
    }

    public function initialize()
    {

    }

    public static function newInstance($app,$route = '/')
    {
        return new static($app,$route);
    }
}