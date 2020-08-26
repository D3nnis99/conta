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
        $id_empleado = null;
        $nombres = null;
        $apellidos = null;
        $id_cargo = null;
        $salario_basico = null;
        $id_tipo_salario = null;
        $fecha_inicio_laboral = null;
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
        $id_tipo_salario = $data['id_tipo_salario'];
        $fecha_inicio_laboral = $data['fecha_inicio_laboral'];
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
        $id_tipo_salario = $_POST['nombre_tipo_salario'];
        $datetime = new DateTime();
        $fecha_actual = $datetime->format('Y-m-d');
        $fecha_inicio_laboral = $_POST['fecha_inicio_laboral'];
        $isss = $_POST['isss'];
        $nup = $_POST['nup'];
        $nit = $_POST['nit'];
        $dui = $_POST['dui'];
        echo"".$fecha_inicio_laboral;
        try 
        {
            if($id_empleado == null)
            {
                $sql = "INSERT INTO `empleados`(`nombres`, `apellidos`, `id_cargo`, `salario_basico`, `id_tipo_salario`, `fecha_inicio_laboral`, `isss`, `nup`, `nit`, `dui`) VALUES (?,?,?,?,?,?,?,?,?,?)";
                $params = array($nombres, $apellidos,$id_cargo, $salario_basico,$id_tipo_salario, $fecha_inicio_laboral, $isss,$nup,$nit, $dui);
                Database::executeRow($sql, $params);
                Page::showMessage(1, "Operación satisfactoria: Registro ingresado.", "empleados.php");
            }
            else if($id_empleado != null)
            {
                if($delete == 0)
                {
                    $sql = "UPDATE `empleados` SET `nombres`=?,`apellidos`=?,`id_cargo`=?,`salario_basico`=?,`id_tipo_salario`=?, `fecha_inicio_laboral`=?,`isss`=?,`nup`=?,`nit`=?,`dui`=? WHERE `id_empleado`=?";
                    $params = array($nombres, $apellidos,$id_cargo, $salario_basico,$id_tipo_salario,$fecha_inicio_laboral, $isss,$nup,$nit, $dui, $id_empleado);
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

                <div class="sec-titulo bottom_1em">Empleados</div>

                <form method='post' enctype='multipart/form-data'>
                        <div class="row">      
                            <div class="input-field col l6 m6 s12">
                                <i class="material-icons prefix">perm_identity</i>
                                <input id='nombres' placeholder='Ejemplo: Juan ' type='text' name='nombres' class='validate' value='<?php print($nombres); ?>' required/>
                                <label for='nombres'>Nombres</label>
                            </div>
                            <div class="input-field col l6 m6 s12">
                                <i class="material-icons prefix">payment</i>
                                <input id='apellidos' placeholder='Ejemplo: Perez' type='text' name='apellidos' class='validate' value='<?php print($apellidos); ?>' required/>
                                <label for='apellidos'>Apellidos</label>
                            </div>
                            <div class="input-field col l4 m4 s12">
                                <i class='material-icons prefix'>new_releases</i>
                                <?php
                                $sql = "SELECT id_cargo, nombre_cargo FROM cargos";
                                Page::setCombo("Cargos", "nombre_cargo", $id_cargo, $sql);
                                ?>
                            </div>
                            <div class="input-field col l4 m4 s12">
                                <i class="material-icons prefix">payment</i>
                                <input id='salario_basico' placeholder='Ejemplo: $100' type='number' name='salario_basico' step='0.1' class='validate' value='<?php print($salario_basico); ?>' required/>
                                <label for='salario_basico'>Salario básico</label>
                            </div>
                            <div class="input-field col l4 m4 s12">
                                <i class='material-icons prefix'>new_releases</i>
                                <?php
                                $sql = "SELECT id_tipo_salario, nombre_tipo_salario FROM tipos_salarios";
                                Page::setCombo("Tipos de Salario", "nombre_tipo_salario", $id_tipo_salario, $sql);
                                ?>
                            </div>
                            <div class="input-field col l3 m3 s12">
                                <i class="material-icons prefix">dialpad</i>
                                <input id='isss' type='text' placeholder='Ejemplo: 106730079' name='isss' maxlength="9" class='validate' value='<?php print($isss); ?>' required/>
                                <label for='isss'>ISSS</label>
                            </div>
                            <div class="input-field col l3 m3 s12">
                                <i class="material-icons prefix">dialpad</i>
                                <input id='nit' type='text' placeholder='Ejemplo: 3386343019' name='nit' maxlength="10" class='validate' value='<?php print($nit); ?>' required/>
                                <label for='nit'>NIT</label>
                            </div>
                            <div class="input-field col l3 m3 s12">
                                <i class="material-icons prefix">dialpad</i>
                                <input id='nup' type='text' placeholder='Ejemplo: 01336178' name='nup' maxlength="8" class='validate' value='<?php print($nup); ?>' required/>
                                <label for='nup'>NUP</label>
                            </div>
                            <div class="input-field col l3 m3 s12">
                                <i class="material-icons prefix">dialpad</i>
                                <input id='dui' type='text' placeholder='Ejemplo: 013361758' name='dui' maxlength="9" class='validate' value='<?php print($dui); ?>' required/>
                                <label for='dui'>DUI</label>
                            </div>
                            <div class='col s12 m12'>
                                <label>Fecha de Inicio Laboral</label>              
                                <input id='fecha_inicio_laboral' type='date' name='fecha_inicio_laboral' max="2038-12-31" min="1998-01-01" class='validate' value='<?php print($fecha_inicio_laboral); ?>' required/>    
                            </div> 
                        </div>
                        <div class='row center-align'>
                            <?php  
                                    if($id_empleado == null)
                                    {
                                        print("
                                        <button type='submit' class='btn waves-effect blue'>
                                            <i class='material-icons'>
                                            save
                                            </i>
                                        </button>
                                        ");
                                    }
                                    else if($id_empleado != null)
                                    {
                                        if($delete == 1)
                                        {
                                            print("
                                            <button type='submit' class='btn waves-effect red'>
                                                <i class='material-icons'>
                                                remove_circle
                                                </i>
                                            </button>
                                            <a href='empleados.php' class='btn waves-effect grey'><i class='material-icons'>cancel</i></a>
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
                                            <a href='empleados.php' class='btn waves-effect grey'><i class='material-icons'>cancel</i></a>
                                            ");
                                        }
                                    }
                                    ?>
                        </div>
                        <br>
                </form><br>
            </div>
            
            <div class="box-productos">
 			<ul class="collection">            
            <?php
            //Ciclo para imprimir los registros obtenidos
            $sql = "SELECT * FROM empleados, cargos, tipos_salarios WHERE empleados.id_cargo = cargos.id_cargo AND tipos_salarios.id_tipo_salario = empleados.id_tipo_salario ORDER BY nombres";
            $params = null;
            $data = Database::getRows($sql, $params);
                foreach($data as $row)
                {
                    print("
                    <li class='collection-item avatar'>
						<img src='img/avatars_usuarios/default.jpg' alt='' class='circle circle_img_user'>
						<div class='cont-list-empleados'>
							<strong class='title'>" . $row['nombres'] ."</strong>
                            <strong class='title'>" . $row['apellidos'] ."</strong>
                            <strong class='title'>- " . $row['nombre_cargo'] ."</strong>
							<p>Salario básico. $". $row['salario_basico'] ."<br>
                            <span class=''>Tipo de Salario. " . $row['nombre_tipo_salario'] . "</span><br>
                            <span class=''>Fecha de Inicio Laboral: " . $row['fecha_inicio_laboral'] . "</span><br>
                            <span class=''>ISSS: " . $row['isss'] . "</span><br>
                            <span class=''>NUP: " . $row['nup'] . "</span><br>
                            <span class=''>NIT: " . $row['nit'] . "</span><br>
                            <span class=''>DUI: " . $row['dui'] . "</span><br>
							</p>
                            <a class='tooltipped' data-position='top' data-tooltip='Editar' href='empleados.php?id=" . $row['id_empleado'] . "&delete=0'><i class='material-icons lista-icon'>edit </i></a> 
						    <a class='tooltipped' data-position='top' data-tooltip='Eliminar' href='empleados.php?id=" . $row['id_empleado'] . "&delete=1'><i class='material-icons lista-icon'>delete</i></a></span>
                            <a class='tooltipped' data-position='top' data-tooltip='Planilla' href='planillas.php?id_empleado=" . $row['id_empleado'] . "'><i class='material-icons lista-icon'>receipt</i></a></span>
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