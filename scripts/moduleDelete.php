<?php
error_reporting (E_ALL ^ E_NOTICE);
include('../index.php');
include('../MySql.php');

$id = $_GET['id'];

$idtopico = \MySql::conectar()->prepare("SELECT * FROM topico WHERE idmodulo = ?");
$idtopico->execute(array($id));
$idtopico = $idtopico->fetchAll();

foreach($idtopico as $key => $value){
    $sqlConteudo = \MySql::conectar()->prepare("DELETE FROM conteudo WHERE idtopico = ?");
    $sqlConteudo->execute(array($value['id']));
}

$sqlTopico = \MySql::conectar()->prepare("DELETE FROM topico WHERE idmodulo = ?");
$sqlTopico->execute(array($id));

$sqlModulo = \MySql::conectar()->prepare("DELETE FROM modulo WHERE id = ?");
$sqlModulo->execute(array($id));

header("location: ../index");

die();
?>