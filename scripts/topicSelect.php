<?php
error_reporting (E_ALL ^ E_NOTICE);
include('../index.php');
include('../MySql.php');
$idtopico = $_GET['id'];
$pegarConteudo = \MySql::conectar()->prepare("SELECT * FROM conteudo WHERE idtopico = ?");
$pegarConteudo->execute(array($idtopico));
$pegarConteudo = $pegarConteudo->fetchAll();
foreach($pegarConteudo as $key => $value){
    if($value['ordem'] <= '4'){
        echo "<div class='card-body bg-white text-center'>".$value['slug']."</div>"; 
    }
}
if($pegarConteudo[4]['ordem'] == '5' and $pegarConteudo[4]['slug'] != "<div class='d-none'>Adicionar</div>"){
    echo "<hr><div class='jumbotron'>";
    foreach($pegarConteudo as $key => $value){
        if($value['ordem'] >= '5' and $value['slug'] != "<div class='d-none'>Adicionar</div>"){
            echo $value['slug']; 
        }
    }
    echo "</div>";
}
?>