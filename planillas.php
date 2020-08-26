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
    $id_empleado = null;
    require("lib/validator.php");
    require("lib/database.php");
    require("lib/page.php");
    if(empty($_GET['id'])) 
    {
        $id_planilla = null;
        if(isset($_GET['id_empleado']))
        {
            $id_empleado = $_GET['id_empleado'];  
        }
        $comision = 0;
        $horas_extras = 0;
        $vacaciones = 0;
        $subtotal = 0;
        $isss = 0;
        $afp = 0;
        $renta = 0;
        $total_retenciones = 0;
        $salario_liquido = 0;
        $indemnizacion = 0;
        $aguinaldo = 0;
        $rbvacaciones = 0;
        $rbindemnizacion = 0;
        $dias_ausentes = 0;
        $hora_extra_diurna = 0;
        $hora_extra_nocturna = 0;
        $dias_ausentes = 0;
    }
    else
    {
        $id_planilla = $_GET['id'];
        $delete = $_GET['delete'];
        $sql = "SELECT * FROM planillas WHERE id_planilla = ?";
        $params = array($id_planilla);
        $data = Database::getRow($sql, $params);
        $id_empleado = $data['id_empleado'];
        $comision = $data['comision'];
        $horas_extras = $data['horas_extras'];
        $vacaciones = $data['vacaciones'];
        $aguinaldo = $data['aguinaldo'];
        $indemnizacion = $data['indemnizacion'];
        $subtotal = $data['subtotal'];
        $isss = $data['isss'];
        $afp = $data['afp'];
        $renta = $data['renta'];
        $total_retenciones = $data['total_retenciones'];
        $salario_liquido = $data['salario_liquido'];
        $hora_extra_diurna = $data['hora_extra_diurna'];
        $hora_extra_nocturna = $data['hora_extra_nocturna'];
        $dias_ausentes = $data['dias_ausentes'];
        $fecha_planilla_c = $data['fecha_planilla'];
        $rbvacaciones = 0;
        $rbindemnizacion = 0;
        if($vacaciones != 0)
        {
            $rbvacaciones = 1;
        }
    }
    
    if(!empty($_POST))
    {
        $_POST = Validator::validateForm($_POST);
        $sql = "SELECT * FROM empleados WHERE id_empleado = ?";
        $params = array($id_empleado);
        $data = Database::getRow($sql, $params);
        $id_tipo_salario = $data['id_tipo_salario'];
        $salario_basico = $data['salario_basico'];
        $fecha_inicio_laboral = $data['fecha_inicio_laboral'];
        $fecha_actual = new DateTime("now");
        $fecha_planilla = $fecha_actual->format('Y-m-d');
        $fil = new DateTime($fecha_inicio_laboral);
        $diferencia_fechas = date_diff($fil, $fecha_actual);
        $diferencia_fechas_dias = $diferencia_fechas->days;
        $diferencia_fechas_meses = $diferencia_fechas->m;
        $diferencia_fechas_años = $diferencia_fechas->y;
        $fa = $fecha_actual->createFromFormat('d/m/Y', date('d/m/Y'));
        $fi = date("d/m/Y", mktime(0, 0, 0, 12, 12, 2017));
        $ff = date("d/m/Y", mktime(0, 0, 0, 12, 20, 2017));
        $fap = date("d/m/Y", mktime(0, 0, 0, 12, 12, 2017));
        $ffp = date("d/m/Y", mktime(0, 0, 0, 12, 20, 2017));
        $comision = $_POST['comision'];
        $rbvacaciones = $_POST['rbvacaciones'];
        $rbindemnizacion = $_POST['rbindemnizacion'];
        $dias_ausentes = $_POST['dias_ausentes'];
        $hora_extra_diurna = $_POST['hora_extra_diurna'];
        $hora_extra_nocturna = $_POST['hora_extra_nocturna'];
        $subtotal = 0;
        $isss = 0;
        $afp = 0;
        $renta = 0;
        $horas_extras = 0;
        $total_retenciones = 0;
        $sueldo_liquido = 0;
        $indemnizacion = 0;
        $aguinaldo = 0;
        $hed = 0;
        $hen = 0;
        //SALARIO MENSUAL
        if($id_tipo_salario == 1)
        {
            $salario_dia = $salario_basico/30;
            $salario_hora = $salario_dia/8;
            if($rbvacaciones == 1)
            {
                $vacaciones = ($salario_dia*15)*(0.3);
            }
            if($rbindemnizacion == 1)
            {
                $indemnizacion = $salario_basico*($diferencia_fechas_dias/365);
            }
            else if($rbindemnizacion == 2)
            {
                $meses = $diferencia_fechas_meses%12;
                $años = ($diferencia_fechas_meses-$meses)/12;
                echo"MESES: ".$meses;
                echo"AÑOS: ".$años;
                if($salario_dia*15 > 300)
                {
                    $indemnizacion = 600*$años;
                }
                else
                {
                    $indemnizacion = ($salario_dia*15)*$años;
                }
            }
            if($fa >= $fi && $fa <= $ff)
            {
                if($diferencia_fechas_dias > 365)
                {
                    if($diferencia_fechas_dias >= 365 && $diferencia_fechas_dias < 1095)//años trabajados de 1 a 3 años
                    {
                        $aguinaldo = $salario_dia*15;
                    }
                    else if($diferencia_fechas_años >= 1095 && $diferencia_fechas_años < 3650)//años trabajados de 3 a 10 años
                    {
                        $aguinaldo = $salario_dia*19;
                    }
                    else if($diferencia_fechas_años >= 3650)//mas de 10 años
                    {
                        $aguinaldo = $salario_dia*21;
                    }
                }
                else
                {
                    $aguinaldo = ($salario_dia*15)*($diferencia_fechas_dias/365);
                }
            }
            if($dias_ausentes == null || $dias_ausentes == "")
            {
                $dias_ausentes = 0;
            }
            $salario_basico = (30-$dias_ausentes)*$salario_dia;
            if($hora_extra_diurna != "" || $hora_extra_diurna > 0)
            {
                $hed = ($salario_hora*2)*$hora_extra_diurna;
            }
            if($hora_extra_nocturna != "" || $hora_extra_nocturna > 0)
            {
                $hen = (($salario_hora*2)*1.25)*$hora_extra_nocturna;
            }
            $horas_extras = $hed + $hen;
            $subtotal = ($salario_basico + $comision + $horas_extras + $vacaciones);
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
            $total_retenciones = $isss + $afp + $renta;
        }
        //SALARIO QUINCENAL
        else if($id_tipo_salario == 2)
        {
            $salario_dia = $salario_basico/15;
            $salario_hora = $salario_dia/8;
            if($rbvacaciones == 1)
            {
                $vacaciones = ($salario_dia*15)*(0.3);
            }
            if($rbindemnizacion == 1)
            {
                if($diferencia_fechas_meses >= 12)
                {
                    $indemnizacion = $salario_basico*($diferencia_fechas_dias/365);
                }
            }
            else if($rbindemnizacion == 2)
            {
                $meses = $diferencia_fechas_meses%12;
                $años = ($diferencia_fechas_meses-$meses)/12;
                if($salario_dia*15 > 300)
                {
                    $indemnizacion = 600*$años;
                }
                else
                {
                    $indemnizacion = ($salario_dia*15)*$años;
                }
            }
            if($fa >= $fi && $fa <= $ff)
            {
                if($diferencia_fechas_dias > 365)
                {
                    if($diferencia_fechas_dias >= 365 && $diferencia_fechas_dias < 1095)//años trabajados de 1 a 3 años
                    {
                        $aguinaldo = $salario_dia*15;
                    }
                    else if($diferencia_fechas_años >= 1095 && $diferencia_fechas_años < 3650)//años trabajados de 3 a 10 años
                    {
                        $aguinaldo = $salario_dia*19;
                    }
                    else if($diferencia_fechas_años >= 3650)//mas de 10 años
                    {
                        $aguinaldo = $salario_dia*21;
                    }
                }
                else
                {
                    $aguinaldo = ($salario_dia*15)*($diferencia_fechas_dias/365);
                }
            }
            if($dias_ausentes == null || $dias_ausentes == "")
            {
                $dias_ausentes = 0;
            }
            $salario_basico = (15-$dias_ausentes)*$salario_dia;
            if($hora_extra_diurna != "" || $hora_extra_diurna > 0)
            {
                $hed = ($salario_hora*2)*$hora_extra_diurna;
            }
            if($hora_extra_nocturna != "" || $hora_extra_nocturna > 0)
            {
                $hen = (($salario_hora*2)*1.25)*$hora_extra_nocturna;
            }
            $horas_extras = $hed + $hen;
            $subtotal = ($salario_basico + $comision  + $horas_extras + $vacaciones);
            $isss = 30;
            if($subtotal < 1000)
            {
                $isss = $subtotal*0.03;
            }
            $afp = $subtotal*0.0625;
            $spr = $subtotal - ($isss + $afp);
            if($subtotal <= 236.00)
            {
                $renta = 0;
            }
            else if($subtotal >= 236.01 && $subtotal <= 447.62)
            {
                $renta = (($spr-236.00)*0.10)+8.83;
            }
            else if($subtotal >= 447.63 && $subtotal <= 1019.05)
            {
                $renta = (($spr-447.62)*0.20)+30;
            }
            else if($subtotal >= 1019.06)
            {
                $renta = (($spr-1019.05)*0.30)+144.28;
            }
            $total_retenciones = $isss + $afp + $renta;
        }
        //SALARIO SEMANAL
        else if($id_tipo_salario == 3)
        {
            $salario_dia = $salario_basico/7;
            $salario_hora = $salario_dia/8;
            if($rbvacaciones == 1)
            {
                $vacaciones = ($salario_dia*15)*(0.3);
            }
            if($rbindemnizacion == 1)
            {
                if($diferencia_fechas_dias >= 365)
                {
                    $indemnizacion = $salario_basico*($diferencia_fechas_dias/365);
                }
            }
            else if($rbindemnizacion == 2)
            {
                $meses = $diferencia_fechas_meses%12;
                $años = ($diferencia_fechas_meses-$meses)/12;
                if($salario_dia*15 > 300)
                {
                    $indemnizacion = 600*$años;
                }
                else
                {
                    $indemnizacion = ($salario_dia*15)*$años;
                }
            }
            if($fa >= $fi && $fa <= $ff)
            {
                if($diferencia_fechas_dias > 365)
                {
                    if($diferencia_fechas_dias >= 365 && $diferencia_fechas_dias < 1095)//años trabajados de 1 a 3 años
                    {
                        $aguinaldo = $salario_dia*15;
                    }
                    else if($diferencia_fechas_años >= 1095 && $diferencia_fechas_años < 3650)//años trabajados de 3 a 10 años
                    {
                        $aguinaldo = $salario_dia*19;
                    }
                    else if($diferencia_fechas_años >= 3650)//mas de 10 años
                    {
                        $aguinaldo = $salario_dia*21;
                    }
                }
                else
                {
                    $aguinaldo = ($salario_dia*15)*($diferencia_fechas_dias/365);
                }
            }
            if($dias_ausentes == null || $dias_ausentes == "")
            {
                $dias_ausentes = 0;
            }
            $salario_basico = (7-$dias_ausentes)*$salario_dia;
            if($hora_extra_diurna != "" || $hora_extra_diurna > 0)
            {
                $hed = ($salario_hora*2)*$hora_extra_diurna;
            }
            if($hora_extra_nocturna != "" || $hora_extra_nocturna > 0)
            {
                $hen = (($salario_hora*2)*1.25)*$hora_extra_nocturna;
            }
            $horas_extras = $hed + $hen;
            $subtotal = ($salario_basico + $comision + $horas_extras + $vacaciones);
            $isss = 30;
            if($subtotal < 1000)
            {
                $isss = $subtotal*0.03;
            }
            $afp = $subtotal*0.0625;
            $spr = $subtotal - ($isss + $afp);
            if($subtotal <= 236.00)
            {
                $renta = 0;
            }
            else if($subtotal >= 236.01 && $subtotal <= 447.62)
            {
                $renta = (($spr-236.00)*0.10)+8.83;
            }
            else if($subtotal >= 447.63 && $subtotal <= 1019.05)
            {
                $renta = (($spr-447.62)*0.20)+30;
            }
            else if($subtotal >= 1019.06)
            {
                $renta = (($spr-1019.05)*0.30)+144.28;
            }
            $total_retenciones = $isss + $afp + $renta;
        }
        try 
        {
            $total_retenciones = $isss + $afp + $renta;
            $salario_liquido = ($subtotal - $total_retenciones);  
            $comision = round($comision, 2);
            $horas_extras = round($horas_extras, 2);
            $vacaciones = round($vacaciones, 2);
            $aguinaldo = round($aguinaldo, 2);
            $indemnizacion = round($indemnizacion, 2);
            $subtotal = round($subtotal, 2);
            $isss = round($isss, 2);
            $afp = round($afp, 2);
            $renta = round($renta, 2);
            $total_retenciones = round($total_retenciones, 2);
            $salario_liquido = round($salario_liquido, 2);
            if($id_planilla == null)
            {
                echo"ID_empleado: ".$id_empleado;
                
                echo"s_dia: ".round($salario_dia, 2);
                echo"FECHAS-DIAS: ".$diferencia_fechas_dias;
                echo"horas_extras: ".$horas_extras;
                echo"vacaciones: ".$vacaciones;
                echo"aguinaldo: ".$aguinaldo;
                echo"rbindemnizacion: ".$rbindemnizacion;
                echo"indemnizacion: ".$indemnizacion;
                echo"subtotal: ".$subtotal;
                echo"isss: ".$isss;
                echo"afp: ".$afp;
                echo"renta: ".$renta;
                echo"total_retenciones: ".$total_retenciones;
                echo"salario_liquido: ".$salario_liquido;
                $sql = "INSERT INTO `planillas`(`id_empleado`, `fecha_planilla`, `dias_ausentes`, `comision`, `hora_extra_diurna`, `hora_extra_nocturna`, `horas_extras`, `vacaciones`, `aguinaldo`, `indemnizacion`, `subtotal`, `isss`, `afp`, `renta`, `total_retenciones`, `salario_liquido`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $params = array($id_empleado, $fecha_planilla, $dias_ausentes, $comision, $hora_extra_diurna, $hora_extra_nocturna, $horas_extras, $vacaciones, $aguinaldo, $indemnizacion, $subtotal, $isss,$afp,$renta, $total_retenciones, $salario_liquido);
                Database::executeRow($sql, $params);
                Page::showMessage(1, "Operación satisfactoria: Registro ingresado.", "planillas.php");
            }
            else if($id_empleado != null)
            {
                if($delete == 0)
                {
                    $sql = "UPDATE `planillas` SET `fecha_planilla` = ?, `dias_ausentes` = ?, `comision` = ?, `hora_extra_diurna` = ?, `hora_extra_nocturna` = ?, `horas_extras` = ?, `vacaciones` = ?, `aguinaldo` = ?, `indemnizacion` = ?, `subtotal` = ?, `isss` = ?, `afp` = ?, `renta` = ?, `total_retenciones` = ?, `salario_liquido` = ? WHERE `id_planilla`=?";
                    $params = array($fecha_planilla, $dias_ausentes, $comision, $hora_extra_diurna, $hora_extra_nocturna, $horas_extras, $vacaciones, $aguinaldo, $indemnizacion, $subtotal, $isss,$afp,$renta, $total_retenciones, $salario_liquido, $id_planilla);
                    Database::executeRow($sql, $params);
                    Page::showMessage(1, "Operación satisfactoria: Registro modificado.", "planillas.php");
                }
                else if($delete == 1)
                {
                    $sql = "DELETE FROM `planillas` WHERE `id_planilla` = ?";
                    $params = array($id_planilla);
                    Database::executeRow($sql, $params);
                    Page::showMessage(1, "Operación satisfactoria: Registro eliminado.", "planillas.php");
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
                <div class="sec-titulo bottom_1em">Planillas</div>     
            <?php
            if(!empty($_GET['id_empleado'])) 
            {
                //Ciclo para imprimir los registros obtenidos
            $sql = "SELECT * FROM empleados, cargos, tipos_salarios WHERE empleados.id_cargo = cargos.id_cargo AND tipos_salarios.id_tipo_salario = empleados.id_tipo_salario AND empleados.id_empleado = ?";
            $params = array($id_empleado);
            $data = Database::getRows($sql, $params);
                foreach($data as $row)
                {
                    print("
                    <div class='box-productos'>
 			        <ul class='collection'>    
                    <li class='collection-item avatar'>
						<img src='img/avatars_usuarios/default.jpg' alt='' class='circle circle_img_user'>
						<div class='cont-list-empleados'>
							<strong class='title'>" . $row['nombres'] ."</strong>
                            <strong class='title'>" . $row['apellidos'] ."</strong>
                            <strong class='title'>- " . $row['nombre_cargo'] ."</strong>
							<p>Salario básico. $". $row['salario_basico'] ."<br>
                            <span class=''>Tipo de Salario. " . $row['nombre_tipo_salario'] . "</span><br>
                            <span class=''>Fecha de Inicio Laboral. " . $row['fecha_inicio_laboral'] . "</span><br>
							</p>
						</div>
                        </li> 
                        </ul>
                    </div>
						");    
                }
            }
            ?>
                <form method='post' enctype='multipart/form-data'>
                        <div class="row">      
                            <div class="input-field col l3 m3 s12">
                                <i class="material-icons prefix">work</i>
                                <input id='comision' type='number' name='comision' placeholder='Ejemplo: 0.00' class='validate' max='1999.99' min='0.00' step='0.01' value='<?php print($comision); ?>'/>
                                <label for="comision">Comisión ($):</label>
                            </div>
                            <div class="input-field col l3 m3 s12">
                                <i class="material-icons prefix">av_timer</i>
                                <input id='hora_extra_diurna' placeholder='Ejemplo: 3' type='number' name='hora_extra_diurna' class='validate' max='40' min='0' step='1' value='<?php print($hora_extra_diurna); ?>'/>
                                <label for='hora_extra_diurna'>Horas Extras Diurnas:</label>
                            </div>
                            <div class="input-field col l3 m3 s12">
                                <i class="material-icons prefix">av_timer</i>
                                <input id='hora_extra_nocturna' type='number' placeholder='Ejemplo: 3' name='hora_extra_nocturna' class='validate' max='40' min='0' step='1' value='<?php print($hora_extra_nocturna); ?>'/>
                                <label for='hora_extra_nocturna'>Horas Extras Nocturnas:</label>
                            </div>
                            <div class="input-field col l3 m3 s12">
                                <i class="material-icons prefix">av_timer</i>
                                <input id='dias_ausentes' type='number' placeholder='Ejemplo: 5' name='dias_ausentes' class='validate' max='15' min='0' step='1' value='<?php print($dias_ausentes); ?>'/>
                                <label for='dias_ausentes'>Días Ausentes:</label>
                            </div>
                            <div class="input-field col l4 m4 s12">
                                <span>Vacaciones:</span>
                                <input id='no_rbvacaciones' type='radio' name='rbvacaciones' class='with-gap' value='0' <?php print(($rbvacaciones == 0)?"checked":""); ?>/>
                                <label for='no_rbvacaciones'><i class='material-icons left'>not_interested</i></label>
                                <input id='si_rbvacaciones' type='radio' name='rbvacaciones' class='with-gap' value='1' <?php print(($rbvacaciones == 1)?"checked":""); ?>/>
                                <label for='si_rbvacaciones'><i class='material-icons left'>done</i></label>
                            </div>
                            <div class="input-field col l8 m8 s12">
                                <span>Indemnizacion:</span>
                                <input id='no_rbindemnizacion' type='radio' name='rbindemnizacion' class='with-gap' value='0' <?php print(($rbindemnizacion == 0)?"checked":""); ?>/>
                                <label for='no_rbindemnizacion'><i class='material-icons left'>not_interested</i></label>
                                <input id='re_rbindemnizacion' type='radio' name='rbindemnizacion' class='with-gap' value='1' <?php print(($rbindemnizacion == 1)?"checked":""); ?>/>
                                <label for='re_rbindemnizacion'><i class='material-icons left'>done</i>Despido</label>
                                <input id='de_rbindemnizacion' type='radio' name='rbindemnizacion' class='with-gap' value='2' <?php print(($rbindemnizacion == 2)?"checked":""); ?>/>
                                <label for='de_rbindemnizacion'><i class='material-icons left'>done</i>Renuncia</label>
                            </div>
                            
                        </div>
                        <div class='row center-align'>
                            <?php  
                                    if($id_empleado == null)
                                    {
                                        print("
                                        <a href='empleados.php' class='btn waves-effect grey'><i class='material-icons'>perm_identity</i></a>
                                        ");
                                    }
                                    else
                                    {
                                        if($id_planilla == null)
                                        {
                                            print("
                                            <button type='submit' class='btn waves-effect blue'>
                                                <i class='material-icons'>
                                                save
                                                </i>
                                            </button>
                                            ");
                                        }
                                        else if($id_planilla != null)
                                        {
                                            if($delete == 1)
                                            {
                                                print("
                                                <button type='submit' class='btn waves-effect red'>
                                                    <i class='material-icons'>
                                                    remove_circle
                                                    </i>
                                                </button>
                                                <a href='planillas.php' class='btn waves-effect grey'><i class='material-icons'>cancel</i></a>
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
                                                <a href='planillas.php' class='btn waves-effect grey'><i class='material-icons'>cancel</i></a>
                                                ");
                                            }
                                        }
                                    }
                                    ?>
                        </div>
                </form>
                </div>
            </div>
            <div class="box-productos">
 			<ul class="collection">            
            <?php
            //Ciclo para imprimir los registros obtenidos
            $sql = "SELECT id_planilla, planillas.id_empleado as id_empleado, nombres, apellidos, nombre_cargo, salario_basico, nombre_tipo_salario, comision, horas_extras, vacaciones, aguinaldo, indemnizacion, subtotal, planillas.isss as isss, afp, renta, total_retenciones, salario_liquido, fecha_planilla FROM planillas, empleados, cargos, tipos_salarios WHERE planillas.id_empleado = empleados.id_empleado AND tipos_salarios.id_tipo_salario = empleados.id_tipo_salario AND cargos.id_cargo = empleados.id_cargo ORDER BY fecha_planilla";
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
                            <span class=''>Tipo de Salario: " . $row['nombre_tipo_salario'] . "</span><br>
                            <span class=''>Comision: $" . $row['comision'] . "</span><br>
                            <span class=''>Horas Extras: $" . $row['horas_extras'] . "</span><br>
                            <span class=''>Vacaciones: $" . $row['vacaciones'] . "</span><br>
                            <span class=''>Aguinaldo: $" . $row['aguinaldo'] . "</span><br>
                            <span class=''>Indemnizacion: $" . $row['indemnizacion'] . "</span><br>
                            <span class=''>Subtotal: $" . $row['subtotal'] . "</span><br>
                            <span class=''>ISSS: $" . $row['isss'] . "</span><br>
                            <span class=''>AFP: $" . $row['afp'] . "</span><br>
                            <span class=''>Renta: $" . $row['renta'] . "</span><br>
                            <span class=''>Total de Retenciones: $" . $row['total_retenciones'] . "</span><br>
                            <span class=''>Salario Liquido: $" . $row['salario_liquido'] . "</span><br>
                            <span class=''>Fecha de Planilla: " . $row['fecha_planilla'] . "</span><br>
							</p>
                            <a class='tooltipped' data-position='top' data-tooltip='Editar' href='planillas.php?id=" . $row['id_planilla'] . "&delete=0&id_empleado=".$row['id_empleado']."'><i class='material-icons lista-icon'>edit </i></a> 
						    <a class='tooltipped' data-position='top' data-tooltip='Eliminar' href='planillas.php?id=" . $row['id_planilla'] . "&delete=1'><i class='material-icons lista-icon'>delete</i></a> </span>
						</div>
                        </li>   
						");    
                }
            ?>
            </ul>
            </div>
            </br>
                        <div style="overflow: auto">
            <table class='striped'>
                <thead>
                    <tr>
                        <th>Nombre de Empleado</th>
                        <th>Nombre de Cargo</th>
                        <th>Salario Basico</th>
                        <th>Tipo de Salario</th>
                        <th>Comision</th>
                        <th>Horas Extras</th>
                        <th>Vacaciones</th>
                        <th>Aguinaldo</th>
                        <th>Indemnizacion</th>
                        <th>Subtotal</th>
                        <th>ISSS</th>
                        <th>AFP</th>
                        <th>Renta</th>
                        <th>Total de Retenciones</th>
                        <th>Salario Liquido</th>
                        <th>Fecha de Planilla</th>
                    </tr>
                </thead>
                <tbody>

                <?php
            //Ciclo para imprimir los registros obtenidos

                foreach($data as $row)
                {
                    print("
                        <tr>
                            <td>".$row['nombres']." ".$row['apellidos']."</td>
                            <td>".$row['nombre_cargo']."</td>
                            <td>".$row['salario_basico']."</td>
                            <td>".$row['nombre_tipo_salario']."</td>
                            <td>".$row['comision']."</td>
                            <td>".$row['horas_extras']."</td>
                            <td>".$row['vacaciones']."</td>
                            <td>".$row['aguinaldo']."</td>
                            <td>".$row['indemnizacion']."</td>
                            <td>".$row['subtotal']."</td>
                            <td>".$row['isss']."</td>
                            <td>".$row['afp']."</td>
                            <td>".$row['renta']."</td>
                            <td>".$row['total_retenciones']."</td>
                            <td>".$row['salario_liquido']."</td>
                            <td>".$row['fecha_planilla']."</td>
                        </tr>
                    ");
                }
                print("
                    </tbody>
                </table>
                </div>
                <br>
                ");
            ?>
            
        </div>
    </div>
</div>


<!--Llamando a los Script de Java-->
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="lib/materialize/materialize.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>