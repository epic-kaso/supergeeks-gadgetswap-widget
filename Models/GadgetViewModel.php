<?php

class GadgetViewModel {

    public function setBaseLinePrices($prices = []){
        $this->baseLinePrices = $prices;
    }

    public function getBaseLinePrices(){
        return $this->baseLinePrices;
    }
    public function setNetwork(Network $network){
        $this->network = $network;
    }

    public function getNetwork(){
        return $this->network;
    }
    public function setGadgetMaker(GadgetMaker $gadgetMaker){
        $this->gadgetMaker = $gadgetMaker;
    }

    public function getGadgetMaker(){
        return $this->gadgetMaker;
    }

    public function setModel($model){
        $this->model = $model;
    }

    public function getModel(){
        return $this->model;
    }

    public function setSizes($value){
        if(is_array($value)){
            $this->sizes = $value;
        }
        if(is_string($value)){
            $this->sizes = $this->parseAndFetchArray($value);
        }
    }

    public function getSizes(){
        return $this->sizes;
    }

    public function setColors($value){
        if(is_array($value)){
            $this->colors = $value;
        }
        if(is_string($value)){
            $this->colors = $this->parseAndFetchArray($value);
        }
    }

    public function getColors(){
        return $this->colors;
    }

    protected function getKey(){
        return $this->model . time();
    }

    private function parseAndFetchArray($value,$seperator = ',')
    {
        return explode($seperator,$value);
    }
}