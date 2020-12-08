<?php

    namespace views;

    class mainView{
        public static function render($file,$info = null){
            include('pages/'.$file.'.php');
        }
    }
?>