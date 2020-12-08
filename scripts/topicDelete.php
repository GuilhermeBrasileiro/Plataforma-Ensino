<?php
error_reporting (E_ALL ^ E_NOTICE);
include('../index.php');
include('../MySql.php');
$idmodule = $_GET['id'];
$idtopico = $_GET['idtopico'];
$sqlTopico = \MySql::conectar()->prepare("DELETE FROM topico WHERE id = ?");
$sqlTopico->execute(array($idtopico));
$sqlConteudo = \MySql::conectar()->prepare("DELETE FROM conteudo WHERE idtopico = ?");
$sqlConteudo->execute(array($idtopico));
header("location: ../module?id=$idmodule");
die();
?>