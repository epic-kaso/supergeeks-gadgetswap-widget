<?php

use ActiveRecord\Config;

class ModelConfig {

    public static function setup($username = 'root',$password = '',$host = 'localhost',$dbname = 'helloworld')
    {
        Config::initialize(
            function($cfg) use ($username,$password,$host,$dbname)
        {
            $connectionLink = !empty($password) ?
                "mysql://$username:$password@$host/$dbname" :
                "mysql://$username@$host/$dbname";

            $cfg->set_model_directory(dirname(__FILE__));
            $cfg->set_connections(
                array('development' => $connectionLink)
            );

            // you can change the default connection with the below
            //$cfg->set_default_connection('production');
        });
    }
}