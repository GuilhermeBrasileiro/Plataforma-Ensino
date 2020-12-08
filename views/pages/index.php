<?php
    if(isset($_POST['adicionar'])) {
        $titulo = $_POST['modulotitulo'];
        $semestre = $_POST['modulosemestre'];
        $imagem = $_POST['imagem'];

        $imgPath = pathinfo($imagem);
        $imgPath['filename'] = "modulo_img_".uniqid("");
        $imgRename = rename($imagem,"images/".$imgPath['filename'].".".$imgPath['extension']);
        $imagem = "images/".$imgPath['filename'].".".$imgPath['extension'];

        $sql = \MySql::conectar()->prepare('INSERT INTO modulo VALUES (null,?,?,?)');
        $sql->execute(array($imagem,$titulo,$semestre));
        header('location: index');
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
        <a class="navbar-brand" href="#">Plataforma de ensino</a>
        <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModalLong">
        <i class="fas fa-folder-plus"></i>
        </button>
    </nav>

    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">
                
            <?php
                $pegarModulos = \MySql::conectar()->prepare("SELECT * FROM modulo");
                $pegarModulos->execute();
                $pegarModulos = $pegarModulos->fetchAll();
                foreach ($pegarModulos as $key => $value){
            ?>
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow" style="height: 400px;">
                        <img class="card-img-top" alt="Imagem não encontrada" style="height: 225px; width: 100%; display: block;" src="<?php echo $value['img']; ?>" data-holder-rendered="true">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title text-center"><?php echo $value['nome']; ?></h4>
                            <div class="btn-group btn-group-sm w-100 mt-auto" role="group" aria-label="First group">
                                <a class='btn btn-dark' type="button" href="module?id=<?php echo $value['id']; ?>">Acessar módulo</a>
                                <button type="button" class="btn btn-dark"><small><?php echo $value['semestre']; ?>º semestre</small></button>
                                <button type="button" class="btn btn-sm btn-dark"><a class="text-white" href="scripts/moduleDelete.php?id=<?php echo $value['id']; ?>"><i class="fas fa-minus-square"></a></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Novo Módulo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form class="w-100" id="form" method="POST">
                                <div class="form-group">
                                    <label for="modulotitulo">Titulo</label>
                                    <input name="modulotitulo" type="text" class="form-control">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="modulosemestre">Semestre</label>
                                    <input name="modulosemestre" type="number" class="form-control">
                                </div>
                                <hr>
                                <div class='custom-file'>
                                    <label class='custom-file-label' for='imagem'>Escolher imagem</label>
                                    <input type='file' name='imagem' class='custom-file-input' id='imagem' required>
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
    </body>
</html>
