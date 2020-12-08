<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
    $idModulo = $_GET['id'];
    $moduloData = \MySql::conectar()->prepare('SELECT * FROM modulo WHERE id = ?');
    $moduloData->execute(array($idModulo));
    $moduloData = $moduloData->fetchAll();
    if(isset($_POST['adicionar'])) {
        $i = 0;
        $titulo = 1;
        $paragrafo = 1;
        $imagem = 1;
        $legenda = 1;
        $lista = 0;
        $lousa = 1;
        $referencia = 1;
        foreach ($_POST as $value){
            $i++;
            if($value == $_POST['titulotopico']){
                $tituloTopico = $_POST['titulotopico'];
                $sql = \MySql::conectar()->prepare('INSERT INTO topico VALUES (null,?,?)');
                $sql->execute(array($tituloTopico,$idModulo));
                $value = "<div class='d-none'>$value</div>";
            }
            if($value == $_POST['tituloprincipal']){
                $value = "<h2 class='card-title'>$value</h2>";
            }
            if($value == $_POST['descricaoprincipal']){
                $value = "<p class='card-text blockquote'>$value</p>";
            }
            if($value == $_POST['legendaprincipal']){
                $value = "<p class='card-footer bg-white blockquote-footer'>$value</p>";
            }
            if($value == $_POST['titulo'.$titulo]){
                $titulo++;
                $value = "<hr><h1>$value</h1><br>";
            }
            if($value == $_POST['paragrafo'.$paragrafo]){
                $paragrafo++;
                $value = "<p>$value</p>";
            }
            if($value == $_POST["imagem".$imagem]){
                $img = $_POST['imagem'.$imagem];
                $imgPath = pathinfo($img);
                $imgPath['filename'] = "conteudo_img_".uniqid("");
                $imgRename = rename($img,"images/".$imgPath['filename'].".".$imgPath['extension']);
                $imagemPath = "images/".$imgPath['filename'].".".$imgPath['extension'];
                $value = "<figure class='figure w-100'><img src='$imagemPath' class='figure-img img-fluid rounded w-100'><figcaption class='figure-caption text-center'>Legenda: ".$_POST['descricaoimagem'.$imagem]."</figcaption></figure>";
                $imagem++;
            }
            if($value == $_POST['descricaoimagem'.$legenda]){
                $legenda++;
                $value = "<div class='d-none'>$value</div>";
            }
            if($value == $_POST['innerlista'.$lista]){
                $value = "<li class='text-center'>".$_POST['innerlista'.$lista]."</li>";
                $lista++;
            }
            if($value == $_POST['lousa'.$lousa]){
                $value = "<hr><textarea disabled class='form-control bg-light font-italic' style='resize: none; height:500px;'>".$_POST['lousa'.$lousa]."</textarea><br>";
                $lousa++;
            }
            if($value == $_POST['innerreferencia'.$referencia]){
                $value = "<li><a href=".$_POST['innerreferencia'.$referencia]." class='text-info' target='_blank'>".$_POST['innertext'.$referencia]."</a></li>";
            }
            if($value == $_POST['innertext'.$referencia]){
                $referencia++;
                $value = "<div class='d-none'>$value</div>";
            }
            if($value == $_POST['adicionar']){
                $value = "<div class='d-none'>$value</div>";
            }
            ${"data".$i} = $value;
            $topico = $_POST['titulotopico'];
            $pegarTopico = \MySql::conectar()->prepare("SELECT * FROM topico WHERE nome = ?");
            $pegarTopico->execute(array($topico));
            $pegarTopico = $pegarTopico->fetchAll();
            if($_POST['titulotopico'] == $pegarTopico[0]['nome']){
                $id = $pegarTopico[0]['id'];
                $sql = \MySql::conectar()->prepare('INSERT INTO conteudo VALUES (null,?,?,?)');
                $sql->execute(array(${"data".$i},$id,$i));
            }
        }
        header("location: module?id=$idModulo");
        die();
    }
?>
<!DOCTYPE html>
<html lang="<?php echo LANG; ?>">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo TITLE; ?></title>
        <link href="<?php echo ROUTE_CSS; ?>" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between">
        <a class="navbar-brand" href="index">Plataforma de ensino</a>
        <p class="navbar-brand mb-0 h1"><?php echo $moduloData[0]['nome']; ?> | <small><?php echo $moduloData[0]['semestre']."º semestre"; ?></small></p>
        <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModalLong">
            <i class="far fa-plus-square"></i>
        </button>
    </nav>

    <div class="container-fluid pt-3">
        <div class="row">
            <div class="list-group col-3">
            <?php
                $pegarTopico = \MySql::conectar()->prepare("SELECT * FROM topico WHERE idModulo = ?");
                $pegarTopico->execute(array($idModulo));
                $pegarTopico = $pegarTopico->fetchAll();
                foreach($pegarTopico as $key => $value){
            ?>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm border border-right-0"><a href="scripts/topicDelete.php?idtopico=<?php echo $value['id']; ?>&id=<?php echo $idModulo ?>"><i class="fas fa-minus-square"></a></i></button>
                    <button type="button" class="list-group-item list-group-item-action border-left-0" onClick="topic('<?php echo $value['id']; ?>')"><?php echo $value['nome']; ?></button>
                </div>
            <?php
            }
            ?>
            </div>
        <div class="col-9" id="topic"></div>
    </div>

    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Novo Tópico | <small>Não coloque a mesma informação em mais de um campo</small></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form class="w-100" id="form" method="POST">
                            <div style="overflow-y: scroll; overflow-x: hidden; height: 425px; padding: 10px;">
                                <div class="form-group">
                                    <label for="titulotopico">Título do Tópico</label>
                                    <input name="titulotopico" type="text" class="form-control">
                                </div>
                                <hr>
                                    <div class="form-group">
                                        <label for="tituloprincipal">Título Principal</label>
                                        <input name="tituloprincipal" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="descricaoprincipal">Descrição Principal</label>
                                        <textarea name="descricaoprincipal" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="legendaprincipal">Legenda Principal</label>
                                        <input name="legendaprincipal" type="text" class="form-control">
                                    </div>
                            </div>
                                <hr>
                                <div class="row d-flex justify-content-around" name='buttons'>
                                    <button type="button" class="btn btn-secondary" id="btnAddTitulo">Título <i class="fas fa-heading"></i></button>
                                    <button type="button" class="btn btn-secondary" id="btnAddParagrafo">Parágrafo <i class="fas fa-paragraph"></i></button>
                                    <button type="button" class="btn btn-secondary" id="btnAddImagem">Imagem <i class="fas fa-images"></i></button>
                                    <button type="button" class="btn btn-secondary" id="btnAddLista">Lista <i class="fas fa-list"></i></button>
                                    <button type="button" class="btn btn-secondary" id="btnAddLousa">Lousa <i class="fas fa-chalkboard"></i></button>
                                    <button type="button" class="btn btn-secondary" id="btnAddReferencia">Referência <i class="fas  fa-asterisk"></i></button>
                                </div>
                                <hr>
                            <input type="submit" name="adicionar" value="Adicionar" class="btn btn-primary w-100">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo ROUTE_JS; ?>"></script>
        <script>
            var a = 0; b = 0; c = 0; d = 0; e = 0; f = 0; g = 0;
            $('#btnAddTitulo').click(function() {
                a++;
                var titulo = $("<hr><div class='form-group'><label for='titulo'>Título</label><input name='titulo"+a+"' type='text' class='form-control'></div>");
                $('.form-group').last().append(titulo);
                });
            $('#btnAddParagrafo').click(function() {
                b++;
                var paragrafo = $("<hr><div class='form-group'><label for='paragrafo'>Parágrafo</label><textarea name='paragrafo"+b+"' class='form-control'></textarea></div>");
                $('.form-group').last().append(paragrafo);
                });
            $('#btnAddImagem').click(function() {
                c++;
                var imagem = $("<hr><div class='custom-file'><input type='file' name='imagem"+c+"' class='custom-file-input' id='imagem"+c+"' required><label class='custom-file-label' for='imagem"+c+"'>Escolher imagem</label></div><div><label for='descricaoimagem"+c+"'>Descrição da imagem</label><input type='text' name='descricaoimagem"+c+"' class='form-control'></div>");
                $('.form-group').last().append(imagem);
                });
            $('#btnAddLista').click(function() {
                d++;
                var lista = $("<hr><div class='form-group'><label for='lista"+d+"' class='w-100'><button type='button' class='btn btn-secondary w-100' id='btnAddListaInner'>Lista <i class='fas fa-plus-circle'></i></button></label><ul name='lista"+d+"' id='lista"+d+"'></ul></div>");
                $('.form-group').last().append(lista);
                });
            $('#form').on('click','#btnAddListaInner',function() {
                var lista = $("<li><input type='text' class='form-control' name='innerlista"+e+++"' id='lista"+d+"'></li>");
                $('#lista'+d).append(lista);
                });
            $('#btnAddLousa').click(function() {
                f++;
                var lousa = $("<hr><label for='lousa"+f+"'>Lousa</label><textarea class='form-control bg-light font-italic' style='resize: none; height:250px;' name='lousa"+f+"'></textarea>");
                $('.form-group').last().append(lousa);
                });
            $('#btnAddReferencia').one("click",function() {
                var referencia = $("<hr><div class='form-group'><label for='link' class='w-100'><button type='button' class='btn btn-secondary w-100' id='btnAddLinkInner'>Referência <i class='fas fa-plus-circle'></i></button></label><ul name='link' id='link'></ul></div>");
                $('.form-group').last().append(referencia);
                });
            $('#form').on('click','#btnAddLinkInner',function() {
                g++;
                var link = $("<li><input type='text' class='form-control' name='innerreferencia"+g+"' placeholder='Coloque o link'><br><input type='text' class='form-control' name='innertext"+g+"' placeholder='Coloque o texto'></li><br>");
                $('#link').append(link);
                });
        </script>
        <script type="text/javascript">
            function topic(id) {
            $('#topic').load('scripts/topicSelect.php?id=' + id);
            }
        </script>
    </body>
</html>