<?php
use ActiveRecord\Model;

class Gadget extends Model{

    static $has_many = array(
        array('sizes'),
        array('colors'),
        array('base_line_prices','class_name'=>'BaseLinePrice')
    );

    static $belongs_to = array(
        array('gadget_maker','class_name'=>'GadgetMaker')
    );

    public function destroyEveryData(){
        $this->deleteRelationships(array('colors','sizes','base_line_prices'));
        $this->delete();

    }
    private function deleteRelationships($relationship){
        if(is_array($relationship)){
            foreach($relationship as $r){
                $relations = $this->{$r};
                foreach($relations as $b){
                    $b->delete();
                }
            }
        }else{
            $relations = $this->{$relationship};
            foreach($relations as $b){
                $b->delete();
            }
        }
    }
}