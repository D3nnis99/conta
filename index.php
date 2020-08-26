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
    $id = null;
    $nombre_empleado = null;
    $salario_basico = null;
    $comision = 0.00;
    $vacaciones = 0;
    $hora_extra_diurna = null;
    $hora_extra_nocturna = null;

    if(!empty($_POST))
    {
        $_POST = Validator::validateForm($_POST);
        $nombre_empleado = $_POST['nombre_empleado'];
        $salario_basico = $_POST['salario_basico'];
        $comision = $_POST['comision'];
        $vacaciones = $_POST['vacaciones'];
        $hora_extra_diurna = $_POST['hora_extra_diurna'];
        $hora_extra_nocturna = $_POST['hora_extra_nocturna'];
        $salario_hora = ($salario_basico/30)/8;
        $subtotal = null;
        $isss = null;
        $afp = null;
        $renta = null;
        $horas_extras = null;
        $total_retenciones = null;
        $sueldo_liquido = null;
        echo "NOICA".$nombre_empleado;
        echo "sb".$salario_basico;
        echo "c".$comision;
        echo "v".$vacaciones;
        echo "hed".$hora_extra_diurna;
        echo "hen".$hora_extra_nocturna;
        echo "sh".$salario_hora;
        try 
        {
            if($nombre_empleado != "" && $salario_basico != "")
            {
                if($hora_extra_diurna != "")
                {
                    $hora_extra_diurna = ($salario_hora*2)*$hora_extra_diurna;
                }
                if($hora_extra_nocturna != "")
                {
                    $hora_extra_nocturna = (($salario_hora*2)*1.25)*$hora_extra_nocturna;
                }
                if($vacaciones == 1)
                {
                    $vacaciones = ($salario_basico/2)*(0.3);
                }
                $horas_extras = $hora_extra_nocturna + $hora_extra_diurna;
                $subtotal = ($salario_basico+$comision  + $horas_extras+$vacaciones);
                $isss = 30;
                if($subtotal < 1000)
                {
                    $isss = $subtotal*0.03;
                }
                $afp = $subtotal*0.0625;
                $spr = $subtotal - ($isss + $afp);
                if($subtotal <= 472.00)
                {
                    $renta = 0;
                }
                else if($subtotal >= 472.01 && $subtotal <= 895.24)
                {
                    $renta = (($spr-472.00)*0.10)+17.67;
                }
                else if($subtotal >= 895.25 && $subtotal <= 2038.10)
                {
                    $renta = (($spr-895.24)*0.20)+60;
                }
                else if($subtotal >= 2038.11)
                {
                    $renta = (($spr-2038.10)*0.30)+288.57;
                }
                $total_retenciones = $isss+$afp+$renta;
                $salario_liquido = $subtotal - ($isss+$afp+$renta);
                $sql = "INSERT INTO planillas(nombre_empleado, salario_basico, comision, horas_extras, vacaciones, subtotal, isss, afp, renta, total_retenciones, salario_liquido) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $params = array($nombre_empleado, $salario_basico, $comision, $horas_extras, $vacaciones, $subtotal, $isss, $afp, $renta, $total_retenciones, $salario_liquido);
                Database::executeRow($sql, $params);
                Page::showMessage(1, "Operación satisfactoria", "index.php");
            }
            else
            {
                throw new Exception("ERROR: Salario u nombre completo no digitado");
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

                <div class="sec-titulo bottom_1em">Planillas</div>

                <form method='post' enctype='multipart/form-data'>
                        <div class="row">      
                            <div class="input-field col l12 m12 s12">
                                <i class="material-icons prefix">perm_identity</i>
                                <input id='nombre_empleado' placeholder='Ejemplo: Juan Peréz' type='text' name='nombre_empleado' class='validate' value='<?php print($nombre_empleado); ?>' required/>
                                <label for='nombre_empleado'>Nombre Completo:</label>
                            </div>
                            <div class="input-field col l6 m6 s12">
                                <i class="material-icons prefix">payment</i>
                                <input id='salario_basico' placeholder='Ejemplo: 300.00' type='number' name='salario_basico' class='validate' max='3999.99' min='0.01' step='0.01' value='<?php print($salario_basico); ?>' required/>
                                <label for='salario_basico'>Salario Básico ($):</label>
                            </div>
                            <div class="input-field col l6 m6 s12">
                                <i class="material-icons prefix">work</i>
                                <input id='comision' type='number' name='comision' placeholder='Ejemplo: 0.00' class='validate' max='1999.99' min='0.00' step='0.01' value='<?php print($comision); ?>'/>
                                <label for="comision">Comisión ($):</label>
                            </div>
                            <div class="input-field col l4 m12 s12">
                                <i class="material-icons prefix">av_timer</i>
                                <input id='hora_extra_diurna' placeholder='Ejemplo: 3' type='number' name='hora_extra_diurna' class='validate' max='40' min='0' step='1' value='<?php print($hora_extra_diurna); ?>'/>
                                <label for='hora_extra_diurna'>Horas Extras Diurnas:</label>
                            </div>
                            <div class="input-field col l4 m12 s12">
                                <i class="material-icons prefix">av_timer</i>
                                <input id='hora_extra_nocturna' type='number' placeholder='Ejemplo: 3' name='hora_extra_nocturna' class='validate' max='40' min='0' step='1' value='<?php print($hora_extra_nocturna); ?>'/>
                                <label for='hora_extra_nocturna'>Horas Extras Nocturnas:</label>
                            </div>
                            <div class="input-field col l4 m12 s12">
                                <span>Vacaciones:</span>
                                <input id='no' type='radio' name='vacaciones' class='with-gap' value='0' <?php print(($vacaciones == 0)?"checked":""); ?>/>
                                <label for='no'><i class='material-icons left'>not_interested</i></label>
                                <input id='si' type='radio' name='vacaciones' class='with-gap' value='1' <?php print(($vacaciones == 1)?"checked":""); ?>/>
                                <label for='si'><i class='material-icons left'>done</i></label>
                            </div>
                        </div>
                        <div class='row center-align'>
                            <button type='submit' class='btn waves-effect blue'><i class='material-icons'>save</i></button>
                        </div>
                        <br>
                </form><br>
            </div>
            <div class="box-productos">
 			<ul class="collection">            
            <?php
            //Ciclo para imprimir los registros obtenidos
            $sql = "SELECT * FROM planillas ORDER BY nombre_empleado";
            $params = null;
            $data = Database::getRows($sql, $params);
                foreach($data as $row)
                {
                        print("
									<li class='collection-item avatar'>
										<img src='img/avatars_usuarios/default.jpg' alt='' class='circle circle_img_user'>
										<div class='cont-list-empleados'>
											<strong class='title'>" . $row['nombre_empleado'] ."</strong>
											<p>Salario básico. $". $row['salario_basico'] ."<br>
											<span class=''>Comision. $" . $row['comision'] . "</span><br>
											<span class=''>Horas extras. $" . $row['horas_extras'] . "</span><br>
                                            <span class=''>Vacaciones. $" . $row['vacaciones'] . "</span><br>
                                            <span class=''>ISSS. $" . $row['isss'] . "</span><br>
                                            <span class=''>AFP. $" . $row['afp'] . "</span><br>
                                            <span class=''>Renta. $" . $row['renta'] . "</span><br>
                                            <span class=''>Total de retenciones. $" . $row['total_retenciones'] . "</span><br>
                                            <span class=''>Salario líquido. $" . $row['salario_liquido'] . "</span><br>
											</p>
										</div>
                                        </li>   
										");
                                        
										/*	if($row['estado'] == 1)
											{
												print(" data-tooltip='Activo'><i class='material-icons lista-icon'>visibility</i>");
											}
											else
											{
												print(" data-tooltip='Inactivo'><i class='material-icons lista-icon'>visibility_off</i>");
											}
										print("</a>
										<a class='tooltipped' data-position='top' data-tooltip='Editar' href='agregar.php?id=" . $row['id_usuario_a'] . "'><i class='material-icons lista-icon'>edit </i></a> 
										<a class='tooltipped' data-position='top' data-tooltip='Eliminar' href='delete.php?id=" . $row['id_usuario_a'] . "'><i class='material-icons lista-icon'>delete</i></a> </span>
                                        */
									    
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