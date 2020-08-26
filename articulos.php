<!DOCTYPE html>
<head>
    <meta charset="utf-8/">
    <!--Importando los iconos desde la carpeta css-->
    <link rel="shortcut icon" href="img/logos/logi.ico">
    <link type='text/css' rel='stylesheet' href='css/sweetalert2.min.css'/>                
    <script type='text/javascript' src='js/sweetalert2.min.js'></script>
    <!--Para que la pagina se optimize al movil-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="lib/materialize/materialize.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/icons.css">
    <title>PcPoint</title>
</head>

<?php include('inc/admin/menu.php') ?>   

<body>
  
<!--Formulario de Login-->
<div class="container">   
    <?php
    require("lib/validator.php");
    require("lib/database.php");
    require("lib/page.php");
    if(empty($_GET['id'])) 
    {
        $id_articulo = null;
        $nombre_articulo = null;
        $descripcion = null;
    }
    else
    {
        $id_articulo = $_GET['id'];
        $delete = $_GET['delete'];
        $sql = "SELECT * FROM articulos WHERE id_articulo = ?";
        $params = array($id_articulo);
        $data = Database::getRow($sql, $params);
        $nombre_articulo = $data['nombre_articulo'];
        $descripcion = $data['descripcion'];
    }
    
    if(!empty($_POST))
    {
        $_POST = Validator::validateForm($_POST);
        $nombre_articulo = $_POST['nombre_articulo'];
        $descripcion = $_POST['descripcion'];
        try 
        {
            if($id_articulo == null)
            {
                $sql = "INSERT INTO articulos(nombre_articulo, descripcion) VALUES (?,?)";
                $params = array($nombre_articulo, $descripcion);
                Database::executeRow($sql, $params);
                Page::showMessage(1, "Operación satisfactoria: Registro ingresado.", "articulos.php");
            }
            else if($id_articulo != null)
            {
                if($delete == 0)
                {
                    $sql = "UPDATE articulos SET nombre_articulo=?,descripcion=? WHERE id_articulo=?";
                    $params = array($nombre_articulo, $descripcion, $id_articulo);
                    Database::executeRow($sql, $params);
                    Page::showMessage(1, "Operación satisfactoria: Registro modificado.", "articulos.php");
                }
                else if($delete == 1)
                {
                    $sql = "DELETE FROM `articulos` WHERE `id_articulo` = ?";
                    $params = array($id_articulo);
                    Database::executeRow($sql, $params);
                    Page::showMessage(1, "Operación satisfactoria: Registro eliminado.", "articulos.php");
                }
            }
        }
        catch (Exception $error)
        {
            Page::showMessage(2, $error->getMessage(), null);
        }
    }
    ?>

    <div class="principal">
        <div class="row">
             <div class="dashboard-div">

                <div class="sec-titulo bottom_1em">Artículos</div>

                <form method='post' enctype='multipart/form-data'>
                        <div class="row">      
                            <div class="input-field col l6 m6 s6">
                                <i class="material-icons prefix">filter_none</i>
                                <input id='nombre_articulo' placeholder='Ejemplo: Shampoo de bebé' type='text' maxlength="40" name='nombre_articulo' value='<?php print($nombre_articulo); ?>' class='validate' required/>
                                <label for='nombre_articulo'>Nombre de artículo</label>
                            </div>
                            <div class='center-align'>
                             <?php  
                                    if($id_articulo == null)
                                    {
                                        print("
                                        <button type='submit' class='btn waves-effect blue'>
                                            <i class='material-icons'>
                                            save
                                            </i>
                                        </button>
                                        ");
                                    }
                                    else if($id_articulo != null)
                                    {
                                        if($delete == 1)
                                        {
                                            print("
                                            <button type='submit' class='btn waves-effect red'>
                                                <i class='material-icons'>
                                                remove_circle
                                                </i>
                                            </button>
                                            <a href='articulos.php' class='btn waves-effect grey'><i class='material-icons'>cancel</i></a>
                                            ");
                                        }
                                        else if($delete == 0)
                                        {
                                            print("
                                            <button type='submit' class='btn waves-effect green'>
                                                <i class='material-icons'>
                                                edit
                                                </i>
                                            </button>
                                            <a href='articulos.php' class='btn waves-effect grey'><i class='material-icons'>cancel</i></a>
                                            ");
                                        }
                                    }
                                    ?>
                            </div>
                            <div class="input-field col l12 m12 s12">
                                <i class="material-icons prefix">mode_edit</i>
                                <textarea id='descripcion' type='text' maxlength="300" name='descripcion' class='materialize-textarea validate' required><?php print($descripcion); ?></textarea>
                                <label for='descripcion'>Descripción</label>
                            </div>
                        </div>
                        <br>
                </form><br>
            </div>
            <div class="box-productos">
 			<ul class="collection">            
            <?php
            //Ciclo para imprimir los registros obtenidos
            $sql = "SELECT * FROM articulos ORDER BY nombre_articulo";
            $params = null;
            $data = Database::getRows($sql, $params);
                foreach($data as $row)
                {
                    print("
					<li class='collection-item avatar'>
                        <img src='img/avatars_usuarios/default.jpg' alt='' class='circle circle_img_user'>
                        <div class='cont-list-empleados'>
                            <strong class='title'>" . $row['nombre_articulo'] ."</strong>
                            <p>
                            <span class=''>Descripción: " . $row['descripcion'] . "</span><br>
                            </p>
                            <a class='tooltipped' data-position='top' data-tooltip='Editar' href='articulos.php?id=" . $row['id_articulo'] . "&delete=0'><i class='material-icons lista-icon'>edit </i></a> 
						    <a class='tooltipped' data-position='top' data-tooltip='Eliminar' href='articulos.php?id=" . $row['id_articulo'] . "&delete=1'><i class='material-icons lista-icon'>delete</i></a> </span>
                        </div>
                    </li>   
					");    
                }
            ?>
            </ul>
            </div>
            </br>
        </div>
    </div>
</div>


<!--Llamando a los Script de Java-->
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="lib/materialize/materialize.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>