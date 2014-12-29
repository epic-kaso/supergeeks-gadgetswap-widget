<?php

namespace Controllers;
use Gadget;
use GadgetMaker;
use Network;

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
        $app->post($this->baseRoute . 'add-swap-detail', array($this, 'postSwapDetails'));
    }

    function getIndex() {
        $app = $this->getApp();
        $ms = GadgetMaker::all();
        $networks = Network::all();

        if (empty($model)) {
            $ds = Gadget::all();
        } else {
            $ds = Gadget::find('all', array('conditions' => array('gadget_maker_id=?', $model)));
        }

        $data = array(
            'models' => array(),
            'devices' => array(),
            'networks' => array()
        );

        foreach($networks as $network){
            $t = json_decode($network->to_json());
            $t->slug =  strtolower(url_title($t->name));
            $data['networks'][] = $t;
        }

        foreach ($ms as $d) {
            $t = json_decode($d->to_json());
            $t->image_url = strtolower($t->name) . '.png';
            $t->slug = url_title($t->name);
            $data['models'][] = $t;
        }
        foreach ($ds as $d) {
            $t = json_decode($d->to_json());
            $t->colors = $this->fetch_array($d->colors);
            $t->sizes = $this->fetch_array($d->sizes);
            $t->base_line_prices = $this->fetch_array($d->base_line_prices);
            $t->slug = url_title($t->model);
            $data['devices'][] = $t;
        }
        $app->render('index.php',array('objects' => $data));
    }

    function postSwapDetails(){
        /*
         * {
         * "device":{
             *  "make":"Apple",
             *  "current_make":{"id":1},
             *  "model":{"id":15,"model":"Iphone 4s"},
             *  "size":{"id":27,"gadget_id":15,"name":"","value":"16gb","baseline_price":0,"slug":"16gb"},
             *  "baseLinePrice":"15000",
             *  "network":{"id":1,"name":"Airtel Ng","description":"Get Extra 1 gigabyte of data when you swap your phone"},
             *  "condition":"Like-New","condition_value":100
         *  },
         * "user":{
         *      "email":"lordkaso@gmail.com"
         *  }
         * }
         */
        $app = $this->getApp();
        //$details = $app->request->params();
        $json_input_data = json_decode(file_get_contents('php://input'),TRUE);

        $user = new \User();
        $user->email = $json_input_data['user']['email'];
        $user->phone = $json_input_data['user']['phone'];
        $user->device_make = $json_input_data['device']['make'];
        $user->device_model = $json_input_data['device']['model']['id'];
        $user->device_size = $json_input_data['device']['size']['id'];
        $user->device_network = $json_input_data['device']['network']['id'];
        $user->device_condition = $json_input_data['device']['condition'];
        $user->swap_location = $json_input_data['device']['swap_location'];
        $user->device_reward = $json_input_data['device']['reward'];

        $user->save();

        $app->response->header('content-type','application/json');
        $app->response->write($user->to_json());
    }
    private function fetch_array($colors) {
        $r = array();
        foreach ($colors as $color) {
            $v = json_decode($color->to_json());
            if(isset($color->value))
                $v->slug = url_title($color->value);
            elseif(isset($color->name))
                $v->slug = url_title($color->name);
            $r[] = $v;
        }
        return $r;
    }
}
