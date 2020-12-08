<?php

    namespace controllers;

    class adminController{
        public function index(){
            \views\mainView::render('index');
        }

        public function module(){
            \views\mainView::render('module');
        }

        public function existeModule(){
            $id = $_GET['id'];
            $verifica = \MySql::conectar()->prepare("SELECT * FROM modulo WHERE id = ?");
            $verifica->execute(array($id));
            if($verifica->rowCount() == 1)
                return true;
            else
                return false;
        }
    }

?>