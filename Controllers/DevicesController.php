<?php

/**
 * Created by PhpStorm.
 * User: kaso
 * Date: 12/20/2014
 * Time: 9:35 AM
 */

namespace Controllers;

use Gadget;
use GadgetMaker;
use Network;

class DevicesController extends BaseController {

    function initialize() {
        $app = $this->getApp();
        $app->get($this->baseRoute, array($this, 'getIndex'))
                ->name('DevicesController@getIndex');
        $app->post($this->baseRoute . '/add-maker', array($this, 'postAddMake'))
                ->name('DevicesController@postAddMake');
        $app->post($this->baseRoute . '/add-network', array($this, 'postAddNetwork'))
            ->name('DevicesController@postAddNetwork');
        $app->post($this->baseRoute . '/add-device', array($this, 'postAddModel'))
                ->name('DevicesController@postAddModel');
        $app->delete($this->baseRoute . '/delete-device/:id', array($this, 'deleteGadget'))
                ->name('DevicesController@deleteGadget');
    }

    function getIndex() {
        $app = $this->getApp();
        $model = $app->request->get('model', null);
        $ms = GadgetMaker::all();
        $networks = Network::all();

        if (empty($model)) {
            $ds = Gadget::all();
        } else {
            $ds = Gadget::find('all', array('conditions' => array('gadget_maker_id=?', $model)));
        }


        if ($app->request->isAjax()) {
            $data = array(
                'models' => array(),
                'devices' => array()
            );

            foreach ($ms as $d) {
                $t = json_decode($d->to_json());
                $t->image_url = strtolower($t->name) . '.png';
                $data['models'][] = $t;
            }
            foreach ($ds as $d) {
                $t = json_decode($d->to_json());
                $t->colors = $this->fetch_array($d->colors);
                $t->sizes = $this->fetch_array($d->sizes);
                $data['devices'][] = $t;
            }

            $app->response->header('content-type', 'application/json');
            $app->response->write(json_encode($data));
        } else {
            $data = array(
                'models' => $ms,
                'devices' => $ds,
                'networks' => $networks
            );
            $app->render('Cms/dashboard.php', $data);
        }
    }

    function postAddMake() {
        $app = $this->getApp();
        $data = $app->request->params();

        if(!isset($data['name']))
        {
            $app->redirect($app->request->getUrl());
            exit;
        }

        $make = GadgetMaker::create($data);

        if(isset($data['normal_condition'])){
            $make->normal_condition = $data['normal_condition'];
        }

        if(isset($data['scratched_condition'])){
            $make->scratched_condition = $data['scratched_condition'];
        }

        if(isset($data['bad_condition'])){
            $make->bad_condition = $data['bad_condition'];
        }

        $make->save();

        $app->redirect($app->urlFor('DevicesController@getIndex'));
    }

    function postAddNetwork() {
        $app = $this->getApp();
        $data = $app->request->params();

        if(!isset($data['name']) || !isset($data['description']))
        {
            $app->redirect($app->request->getUrl());
            exit;
        }

        $make = Network::create($data);

        $app->redirect($app->urlFor('DevicesController@getIndex'));
    }

    function postAddModel() {
        $app = $this->getApp();
        $data = $app->request->params();

        if(
            !isset($data['model']) ||
            !isset($data['gadget_maker_id']) ||
            !isset($data['sizes']) ||
            !isset($data['baselines'])
        ){
            $app->redirect($app->request->getUrl());
            exit;
        }

        $make = Gadget::create(array(
                    'model' => $data['model'],
                    'gadget_maker_id' => $data['gadget_maker_id']
        ));

        $sizes = explode(',', $data['sizes']);
        foreach ($sizes as $size) {
            $make->create_sizes(array('value' => trim($size)));
        }

        $baselines = \BaseLinePrice::extractBaseLinePrices($data['baselines']);
        foreach ($baselines as $key => $value) {
            $make->create_base_line_prices(array('size' => trim($key), 'value' => trim($value)));
        }

        $make->save();

        $app->redirect($app->urlFor('DevicesController@getIndex'));
    }

    function getAllDevices() {

    }

    function deleteGadget($id) {
        $app = $this->getApp();

        $g = Gadget::find($id);
        $g->destroyEveryData();

        if ($app->request->isAjax()) {
            $app->response->header('content-type', 'application/json');
            $app->response->write(json_encode(
                            array(
                                'status' => 'success',
                                'url' => $app->urlFor('DevicesController@getIndex')
                            )
            ));
        } else {
            $app->redirect($app->urlFor('DevicesController@getIndex'));
        }
    }

    private function fetch_array($colors) {
        $r = array();
        foreach ($colors as $color) {
            $r[] = json_decode($color->to_json());
        }
        return $r;
    }

}
