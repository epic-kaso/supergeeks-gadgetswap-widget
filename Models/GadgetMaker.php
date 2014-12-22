<?php
use ActiveRecord\Model;

class GadgetMaker extends Model{


    //Conditions
    //1. scratched_condition
    //2. bad_condition
    //3. normal_condition


    // can have many gadgets
    static $has_many = array(
        array('gadgets')
    );

    // must have a name
    static $validates_presence_of = array(
        array('name')
    );
}