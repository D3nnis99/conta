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
    <title>MyPhone</title>
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
        $id_empleado = null;
        $nombres = null;
        $apellidos = null;
        $id_cargo = null;
        $salario_basico = null;
        $tipo_salario = null;
        $isss = null;
        $nup = null;
        $nit = null;
        $dui = null;
    }
    else
    {
        $id_empleado = $_GET['id'];
        $delete = $_GET['delete'];
        $sql = "SELECT * FROM empleados WHERE id_empleado = ?";
        $params = array($id_empleado);
        $data = Database::getRow($sql, $params);
        $nombres = $data['nombres'];
        $apellidos = $data['apellidos'];
        $id_cargo = $data['id_cargo'];
        $salario_basico = $data['salario_basico'];
        $tipo_salario = $data['tipo_salario'];
        $isss = $data['isss'];
        $nup = $data['nup'];
        $nit = $data['nit'];
        $dui = $data['dui'];
    }
    
    if(!empty($_POST))
    {
        $_POST = Validator::validateForm($_POST);
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $id_cargo = $_POST['nombre_cargo'];
        $salario_basico = $_POST['salario_basico'];
        $tipo_salario = $_POST['tipo_salario'];
        $isss = $_POST['isss'];
        $nup = $_POST['nup'];
        $nit = $_POST['nit'];
        $dui = $_POST['dui'];
        try 
        {
            if($id_empleado == null)
            {
                $sql = "INSERT INTO `empleados`(`nombres`, `apellidos`, `id_cargo`, `salario_basico`, `tipo_salario`, `isss`, `nup`, `nit`, `dui`) VALUES (?,?,?,?,?,?,?,?,?)";
                $params = array($nombres, $apellidos,$id_cargo, $salario_basico,$tipo_salario, $isss,$nup,$nit, $dui);
                Database::executeRow($sql, $params);
                Page::showMessage(1, "Operación satisfactoria: Registro ingresado.", "empleados.php");
            }
            else if($id_empleado != null)
            {
                if($delete == 0)
                {
                    $sql = "UPDATE `empleados` SET `nombres`=?,`apellidos`=?,`id_cargo`=?,`salario_basico`=?,`tipo_salario`=?,`isss`=?,`nup`=?,`nit`=?,`dui`=? WHERE `id_empleado`=?";
                    $params = array($nombres, $apellidos,$id_cargo, $salario_basico,$tipo_salario, $isss,$nup,$nit, $dui, $id_empleado);
                    Database::executeRow($sql, $params);
                    Page::showMessage(1, "Operación satisfactoria: Registro modificado.", "empleados.php");
                }
                else if($delete == 1)
                {
                    $sql = "DELETE FROM `empleados` WHERE `id_empleado` = ?";
                    $params = array($id_empleado);
                    Database::executeRow($sql, $params);
                    Page::showMessage(1, "Operación satisfactoria: Registro eliminado.", "empleados.php");
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

                <div class="sec-titulo bottom_1em">Cargos</div>

                <form method='post' enctype='multipart/form-data'>
                        <div class="row">      
                            <div class="input-field col l6 m6 s6">
                                <i class="material-icons prefix">perm_identity</i>
                                <input id='nombre_cargo' placeholder='Ejemplo: Director de seguridad' type='text' maxlength="40" name='nombre_cargo' value='<?php print($nombre_cargo); ?>' class='validate' required/>
                                <label for='nombre_cargo'>Nombre de cargo</label>
                            </div>
                            <div class="input-field col l6 m6 s12">
                                <i class='material-icons prefix'>new_releases</i>
                                <?php
                                $sql = "SELECT id_cargo, nombre_cargo FROM cargos";
                                Page::setCombo("Cargos", "nombre_cargo", $id_cargo, $sql);
                                ?>
                            </div>
                            <div class='center-align'>
                             <?php  
                                    if($id_cargo == null)
                                    {
                                        print("
                                        <button type='submit' class='btn waves-effect blue'>
                                            <i class='material-icons'>
                                            save
                                            </i>
                                        </button>
                                        ");
                                    }
                                    else if($id_cargo != null)
                                    {
                                        if($delete == 1)
                                        {
                                            print("
                                            <button type='submit' class='btn waves-effect red'>
                                                <i class='material-icons'>
                                                remove_circle
                                                </i>
                                            </button>
                                            <a href='cargos.php' class='btn waves-effect grey'><i class='material-icons'>cancel</i></a>
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
                                            <a href='cargos.php' class='btn waves-effect grey'><i class='material-icons'>cancel</i></a>
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
            $sql = "SELECT * FROM cargos ORDER BY nombre_cargo";
            $params = null;
            $data = Database::getRows($sql, $params);
                foreach($data as $row)
                {
                    print("
					<li class='collection-item avatar'>
                        <img src='img/avatars_usuarios/default.jpg' alt='' class='circle circle_img_user'>
                        <div class='cont-list-empleados'>
                            <strong class='title'>" . $row['nombre_cargo'] ."</strong>
                            <p>
                            <span class=''>Descripción: " . $row['descripcion'] . "</span><br>
                            </p>
                            <a href='cargos.php?id=".$row['id_cargo']."&delete=0' class='blue-text'><i class='material-icons'>edit</i></a>
                            <a href='cargos.php?id=".$row['id_cargo']."&delete=1' class='red-text'><i class='material-icons'>delete</i></a>
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
<?php
    include('inc/footer.php');
?>

<!--Llamando a los Script de Java-->
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="lib/materialize/materialize.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>