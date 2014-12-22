<?php
/**
 * Created by PhpStorm.
 * User: kaso
 * Date: 12/20/2014
 * Time: 1:39 PM
 */
use ActiveRecord\Model;

class Size extends Model{
    static $belongs_to = array(
        array('gadget'),
    );
}