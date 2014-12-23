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
