<?php

namespace Controllers;

/**
 * Created by PhpStorm.
 * User: kaso
 * Date: 12/20/2014
 * Time: 9:07 AM
 */
class HomeController extends BaseController {

    function initialize() {
        $app = $this->getApp();
        $app->get($this->baseRoute, array($this, 'getIndex'));
    }

    function getIndex() {
        $app = $this->getApp();
        $app->render('index.php');
    }

}
