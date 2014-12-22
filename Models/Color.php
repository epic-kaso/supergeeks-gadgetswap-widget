<?php

use ActiveRecord\Model;

class Color extends Model{

    static $belongs_to = array(
        array('gadget'),
    );
}