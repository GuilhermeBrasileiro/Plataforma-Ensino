<?php

use controllers\adminController;

    define('HOST','localhost');
    define('DATABASE','personal_ava');
    define('USER','root');
    define('PASSWORD','1234');

    define('ROUTE_CSS','styles/styles.css');
    define('ROUTE_JS','scripts/scripts.js');
    define('ROUTE_ASSETS','assets/demo/');
    define('LANG','pt-BR');
    define('TITLE','Plataforma de Ensino');
    
    require 'vendor/autoload.php';

    $autoload = function($class){
        include($class.'.php');
    };

    spl_autoload_register($autoload);

    $adminController = new \controllers\adminController();
    
    Router::get('/index',function() use ($adminController){
        $adminController->index();
    });

    Router::get('/module',function() use ($adminController){
        if(isset($_GET['id'])){
            if($adminController->existeModule()){
                $adminController->module();
            }else{
                header("location: index");
            }
        }else{
            header("location: index");
        }
    });
?>