<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kaisha - Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="/lib/materialize/materialize.min.css">
    <link rel="stylesheet" href="/css/estilo.css">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/icons.css">
</head>

<!-- Se incluye menu de navegacion -->
<?php include($_SERVER['DOCUMENT_ROOT'] . '/inc/admin/menu.php') ?>   

<div class="principal">
    <div class="row">
        <div class="col l7 m6 s12">
            <div class="dashboard-div">
                que ondas q pex
            </div>
        </div>
            
        <div class="col l5 m6 s12">
            <div class="dashboard-div">

                <div class="sec-titulo">Última actividad</div>

                <div class="collection">
                    <a href="#!" class="collection-item"><span class="badge">12/04/2017</span>Erick Arévalo agrego el producto "Taza de letona"</a>
                    <a href="#!" class="collection-item"><span class="badge">12/04/2017</span>Erick Arévalo elimino el producto "Su recuerdo"</a>
                    <a href="#!" class="collection-item"><span class="badge">12/04/2017</span>Erick Arévalo agrego el producto "Lapicera shida"</a>
                    <a href="#!" class="collection-item"><span class="badge">12/04/2017</span>Erick Arévalo agrego el producto "Tapiceria shida"</a>
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="dashboard-div">

                <div class="sec-titulo bottom_1em">Última actividad</div>
                 
                <div class="bar-opciones">
                    <ul class="bar-botones">
                        <li><a href="#" class="tooltipped" data-tooltip="Ver en lista"><i class="material-icons">view_list</i></a></li>
                        <li><a class="activo tooltipped" data-tooltip="Ver con miniaturas" href="#"><i class="material-icons">view_module</i></a></li>
                    </ul>
                </div>

                <div class="box-productos">
                    <!-- Producto -->
                    <div class="card productos-admin">
                        <div class="card-image">
                            <a href="#PROD">
                            <img src="/img/articulos/001.png">
                            <span class="card-title card_title_cont card_title_mini">Bolsas de tela multicolor </span>
                            </a>
                        </div>
                            <div class="halfway-top">
                                <a class="btn-floating waves-effect waves-light half-admin" onclick=""><i class="material-icons">mode_edit</i></a>
                            </div>
                    </div>
                    <!-- Producto -->
                    <div class="card productos-admin">
                        <div class="card-image">
                            <a href="#PROD">
                            <img src="/img/articulos/002.png">
                            <span class="card-title card_title_cont card_title_mini">Bolsas de tela multicolor </span>
                            </a>
                        </div>
                            <div class="halfway-top">
                                <a class="btn-floating waves-effect waves-light half-admin" onclick=""><i class="material-icons">mode_edit</i></a>
                            </div>
                    </div>
                    <!-- Producto -->
                    <div class="card productos-admin">
                        <div class="card-image">
                            <a href="#PROD">
                            <img src="/img/articulos/003.png">
                            <span class="card-title card_title_cont card_title_mini">Bolsas de tela multicolor </span>
                            </a>
                        </div>
                            <div class="halfway-top">
                                <a class="btn-floating waves-effect waves-light half-admin" onclick=""><i class="material-icons">mode_edit</i></a>
                            </div>
                    </div>
                    <!-- Producto -->
                    <div class="card productos-admin">
                        <div class="card-image">
                            <a href="#PROD">
                            <img src="/img/articulos/004.png">
                            <span class="card-title card_title_cont card_title_mini">Bolsas de tela multicolor </span>
                            </a>
                        </div>
                            <div class="halfway-top">
                                <a class="btn-floating waves-effect waves-light half-admin" onclick=""><i class="material-icons">mode_edit</i></a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Se incluye pie de pagina -->
<?php include($_SERVER['DOCUMENT_ROOT'] . '/inc/admin/footer.php') ?>
