<?php

    require 'Libs/Slim/Slim.php';
    require 'Libs/PhpActiverecord/ActiveRecord.php';
    require 'Libs/helpers.php';

    require_once 'Controllers/BaseController.php';
    require_once 'Controllers/HomeController.php';
    require_once 'Controllers/DevicesController.php';

    require_once 'Models/ModelConfig.php';
    require_once 'Models/Gadget.php';
    require_once 'Models/GadgetMaker.php';
    require_once 'Models/Network.php';
    require_once 'Models/Size.php';
    require_once 'Models/GadgetViewModel.php';
    require_once 'Models/Color.php';
    require_once 'Models/BaseLinePrice.php';

    \Slim\Slim::registerAutoloader();

    $app = new \Slim\Slim(array(
        'templates.path' => './Views'
    ));
    ModelConfig::setup('root','','localhost','sendy');
    \Controllers\HomeController::newInstance($app);
    \Controllers\DevicesController::newInstance($app, '/devices');
    $app->run();