<!DOCTYPE html>
<?php
session_start();
if(session_name() == "contabilidad")
{
    session_write_close(); //cerramos y guardamos la primera sesion
    session_name("contabilidad");
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
}
?>
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
    $fecha_filtro = null;
    $id_articulo_filtro = null;
    $doc = null;
    $metodo = null;
    $id_articulo_entrada = null;
    $id_articulo_salida = null;
    $fecha_entrada = null;
    $cantidad_entrada = null;
    $precio_unidad_entrada = null;
    $fecha_salida = null;
    $cantidad_salida = null;
    $costo_total = null;
    if(isset($_GET['metodo'])) 
    {
        $metodo = $_GET['metodo'];
    }
    $sql = "SELECT MAX(fecha) AS fecha FROM inventarios WHERE metodo = ?";
    $params = array($metodo);
    $data = Database::getRow($sql, $params);
    $uf = strtotime($data['fecha']);
    if(isset($_POST['btn_filtro']))
    {
        if(!empty($_POST['fecha_filtro']))
        {
           
            if(!empty($_POST['nombre_articulo_filtro']))
            {
                $fecha_filtro = $_POST['fecha_filtro'];
                $_SESSION['fecha_filtro'] = $fecha_filtro;
                $id_articulo_filtro = $_POST['nombre_articulo_filtro'];
                $_SESSION['id_articulo_filtro'] = $id_articulo_filtro;
            }
            else
            {
                Page::showMessage(2, "Seleccione un artículo", null);
            }
        }
        else
        {
            Page::showMessage(2, "Ingrese una fecha para filtrar", null);
        }
    }
    if(isset($_POST['btn_entrada']))
    {
        $fecha_entrada = $_POST['fecha_entrada'];
        $id_articulo_entrada = $_POST['nombre_articulo_entrada'];
        $cantidad_entrada = $_POST['cantidad_entrada'];
        $precio_unidad_entrada = $_POST['precio_unidad_entrada'];
        $doc = null;
        $mensaje = null;
        if(isset($_SESSION['fecha_filtro']))
        {
            $fecha_filtro = $_SESSION['fecha_filtro'];
        }
        try 
        {
            if($fecha_filtro != null)
            {
                $fe = strtotime($fecha_entrada);
                if($fecha_entrada != null)
                {
                    if($cantidad_entrada != null && $cantidad_entrada > 0)
                    {
                        if($id_articulo_entrada != null)
                        {
                            if($precio_unidad_entrada != null && $precio_unidad_entrada> 0)
                            {
                                if($fe >= $uf)
                                {
                                    if($metodo != 'cp')
                                    {
                                        $sql = "SELECT * FROM inventarios WHERE id_articulo = ? AND metodo = ? AND doc = ? AND fecha LIKE ?";
                                        $params = array($id_articulo_entrada, $metodo, 'ii', ''.$fecha_filtro.'%');
                                        $data = Database::getRow($sql, $params);
                                        if($data != null)
                                        {
                                            $doc = 'c';
                                            $mensaje = "Compra de inventario ingresado.";
                                        }
                                        else
                                        {
                                            $doc = 'ii';
                                            $mensaje = "Inventario inicial del mes ingresado.";
                                        }
                                        $precio_unidad_entrada = round($precio_unidad_entrada, 2);
                                        $costo_total = $cantidad_entrada * $precio_unidad_entrada;
                                        $costo_total = round($costo_total, 2);
                                        $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES (?,?,?,?,?,?,?)";
                                        $params = array($metodo, $fecha_entrada, $doc, $id_articulo_entrada, $cantidad_entrada, $precio_unidad_entrada, $costo_total);
                                        Database::executeRow($sql, $params);
                                        Page::showMessage(1, $mensaje, "inventarios.php?metodo=".$metodo);
                                    }
                                    else if($metodo == 'cp')
                                    {
                                        $sql = "SELECT * FROM inventarios WHERE id_articulo = ? AND metodo = ? AND doc = ? AND fecha LIKE ?";
                                        $params = array($id_articulo_entrada, $metodo, 'ii', ''.$fecha_filtro.'%');
                                        $data = Database::getRow($sql, $params);
                                        if($data != null)
                                        {
                                            $sql = "SELECT * FROM inventarios WHERE id_inventario = (SELECT MIN(id_inventario) FROM inventarios WHERE metodo = ? AND estado = ? AND doc <> ? AND fecha LIKE ?)";
                                            $params = array($metodo, 1, 'v', ''.$fecha_filtro.'%');
                                            $data = Database::getRow($sql, $params);
                                            $sql = "UPDATE `inventarios` SET estado = ? WHERE id_inventario = ?";
                                            $params = array(0, $data['id_inventario']);
                                            Database::executeRow($sql, $params);
                                            $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`, estado) VALUES (?,?,?,?,?,?,?,?)";
                                            $params = array($metodo, $fecha_entrada, 'c', $id_articulo_entrada, $cantidad_entrada, round($precio_unidad_entrada,2), round($cantidad_entrada*$precio_unidad_entrada,2), 0);
                                            Database::executeRow($sql, $params);
                                            $nueva_cantidad = $data['cantidad']+$cantidad_entrada;
                                            $nuevo_costo_total = round($data['costo_total']+($precio_unidad_entrada*$cantidad_entrada), 2);
                                            $nuevo_precio_unidad = round($nuevo_costo_total/$nueva_cantidad, 2);
                                            $precio_unidad_entrada = round($precio_unidad_entrada, 2);
                                            $costo_total = $cantidad_entrada * $precio_unidad_entrada;
                                            $costo_total = round($costo_total, 2);
                                            $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES (?,?,?,?,?,?,?)";
                                            $params = array($metodo, $fecha_entrada, 'r', $id_articulo_entrada, $nueva_cantidad, $nuevo_precio_unidad, $nuevo_costo_total);
                                            Database::executeRow($sql, $params);
                                            Page::showMessage(1, "Compra de inventario ingresado.", "inventarios.php?metodo=".$metodo);
                                        }
                                        else
                                        {
                                            $precio_unidad_entrada = round($precio_unidad_entrada, 2);
                                            $costo_total = $cantidad_entrada * $precio_unidad_entrada;
                                            $costo_total = round($costo_total, 2);
                                            $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES (?,?,?,?,?,?,?)";
                                            $params = array($metodo, $fecha_entrada, 'ii', $id_articulo_entrada, $cantidad_entrada, $precio_unidad_entrada, $costo_total);
                                            Database::executeRow($sql, $params);
                                            Page::showMessage(1, "Inventario inicial del mes ingresado.", "inventarios.php?metodo=".$metodo);
                                        }
                                    }
                                }
                                else
                                {
                                    throw new Exception("Ingrese una fecha válida");
                                } 
                            }
                            else
                            {
                                throw new Exception("Ingrese un precio válido");
                            }
                        }
                        else
                        {
                            throw new Exception("Seleccione un artículo");
                        }
                    }
                    else
                    {
                        throw new Exception("Ingrese una cantidad válida");
                    }
                }
                else
                {
                    throw new Exception("Ingrese una fecha");
                }
            }
            else
            {
                throw new Exception("Primero filtre por mes el inventario a trabajar");
            }
        }
        catch (Exception $error)
        {
            Page::showMessage(2, $error->getMessage(), null);
        }
    }
    if(isset($_POST['btn_salida']))
    {
        $fecha_salida = $_POST['fecha_salida'];
        $id_articulo_salida = $_POST['nombre_articulo_salida'];
        $cantidad_salida = $_POST['cantidad_salida'];
        $cantidad_temp = $cantidad_salida;
        $doc = 'v';
        if(isset($_SESSION['fecha_filtro']))
        {
            $fecha_filtro = $_SESSION['fecha_filtro'];
        }
        try 
        {
            if($fecha_filtro != null)
            {
                $fs = strtotime($fecha_salida);
                if($fecha_salida != null)
                {
                    if($cantidad_salida != null && $cantidad_salida > 0)
                    {
                        if($id_articulo_salida != null)
                        {
                            if($fs >= $uf)
                            {
                                if($metodo != 'cp')
                                {
                                    $sql_p = "";
                                    if($metodo == 'peps')
                                    {
                                        $sql_p = "SELECT * FROM inventarios WHERE id_inventario = (SELECT MIN(id_inventario) FROM inventarios WHERE fecha = (SELECT MIN(fecha) FROM inventarios WHERE id_articulo = ? AND metodo = ? AND estado = ? AND doc <> ? AND fecha LIKE ?))";
                                    }
                                    if($metodo == 'ueps')
                                    {
                                        $sql_p = "SELECT * FROM inventarios WHERE id_inventario = (SELECT MAX(id_inventario) FROM inventarios WHERE fecha = (SELECT MAX(fecha) FROM inventarios WHERE id_articulo = ? AND metodo = ? AND estado = ? AND doc <> ? AND fecha LIKE ?))";
                                    }
                                    while($cantidad_temp > 0)
                                    {
                                        $params_p = array($id_articulo_salida, $metodo, 1, 'v', $fecha_filtro."%");
                                        $data = Database::getRow($sql_p, $params_p);
                                        if($cantidad_temp < $data['cantidad'])
                                        {
                                            $data['cantidad'] = $data['cantidad'] - $cantidad_temp;
                                            $sql = "UPDATE `inventarios` SET estado = ? WHERE id_inventario = ?";
                                            $params = array(0, $data['id_inventario']);
                                            Database::executeRow($sql, $params);
                                            $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES (?,?,?,?,?,?,?)";
                                            $params = array($metodo, $fecha_salida, 'v', $id_articulo_salida, $cantidad_temp, $data['precio_unidad'], round($cantidad_temp*$data['precio_unidad'], 2));
                                            Database::executeRow($sql, $params);
                                            $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES (?,?,?,?,?,?,?)";
                                            $params = array($metodo, $data['fecha'], 'r', $data['id_articulo'], $data['cantidad'], $data['precio_unidad'], round($data['cantidad']*$data['precio_unidad'], 2));
                                            Database::executeRow($sql, $params);
                                            $sql_r = "SELECT * FROM inventarios WHERE id_articulo = ? AND metodo = ? AND estado = ? AND doc <> ?";
                                            $params_r = array($id_articulo_salida, $metodo, 1, 'v');
                                            $data_r = Database::getRows($sql_r, $params_r); //orgasm denial chastity belt nhentai.net javfor.me javlibrary.com tanix.com javhub.com
                                            if($data_r != null)
                                            {
                                                foreach($data_r as $row_r)
                                                {
                                                    $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES (?,?,?,?,?,?,?)";
                                                    $params = array($metodo, $row_r['fecha'], 'r', $row_r['id_articulo'], $row_r['cantidad'], $row_r['precio_unidad'], $row_r['costo_total']);
                                                    Database::executeRow($sql_r, $params_r);
                                                }
                                            }
                                            $cantidad_temp = 0;
                                        }
                                        else if($cantidad_temp >= $data['cantidad'])
                                        {
                                            $sql = "UPDATE `inventarios` SET estado = ? WHERE id_inventario = ?";
                                            $params = array(0, $data['id_inventario']);
                                            Database::executeRow($sql, $params);
                                            $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES (?,?,?,?,?,?,?)";
                                            $params = array($metodo, $fecha_salida, 'v', $id_articulo_salida, $data['cantidad'], $data['precio_unidad'], $data['costo_total']);
                                            Database::executeRow($sql, $params);
                                            $cantidad_temp = $cantidad_temp - $data['cantidad'];
                                        }
                                    }
                                    if($cantidad_temp == 0)
                                    {
                                        Page::showMessage(1, "Venta realizada con éxito", "inventarios.php?metodo=".$metodo);
                                    }
                                }
                                else if($metodo == 'cp')
                                {
                                    $sql_p = "SELECT * FROM inventarios WHERE id_inventario = (SELECT MIN(id_inventario) FROM inventarios WHERE id_articulo = ? AND metodo = ? AND estado = ? AND doc <> ? AND doc <> ? AND fecha LIKE ?)";
                                    $params_p = array($id_articulo_salida, 'cp', 1, 'v', 'c', $fecha_filtro."%");
                                    $data = Database::getRow($sql_p, $params_p);
                                    echo"AIUDA: ".$cantidad_salida;
                                    echo"AIUDAx2: ".$data['cantidad'];
                                    if($cantidad_salida <= $data['cantidad'])
                                    {
                                        $sql = "UPDATE `inventarios` SET estado = ? WHERE id_inventario = ?";
                                        $params = array(0, $data['id_inventario']);
                                        Database::executeRow($sql, $params);
                                        $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES (?,?,?,?,?,?,?)";
                                        $params = array($metodo, $fecha_salida, 'v', $id_articulo_salida, $cantidad_salida, $data['precio_unidad'], round($data['precio_unidad']*$cantidad_salida,2));
                                        Database::executeRow($sql, $params);
                                        $data['cantidad'] = $data['cantidad']-$cantidad_salida;
                                        if($data['cantidad'] > 0)
                                        {
                                            $data['costo_total'] = round($data['precio_unidad']*$data['cantidad'], 2);
                                            $sql = "INSERT INTO `inventarios`(`metodo`, `fecha`, `doc`, `id_articulo`, `cantidad`, `precio_unidad`, `costo_total`) VALUES (?,?,?,?,?,?,?)";
                                            $params = array($metodo, $data['fecha'], 'r', $id_articulo_salida, $data['cantidad'], $data['precio_unidad'], $data['costo_total']);
                                            Database::executeRow($sql, $params);
                                        }
                                        Page::showMessage(1, "Venta de inventario realizado con éxito.", "inventarios.php?metodo=".$metodo);
                                    }
                                    else
                                    {
                                        throw new Exception("No hay suficientes articulos para realizar la compra.");
                                    }
                                }
                            }
                            else
                            {
                                throw new Exception("Ingrese una fecha válida");
                            } 
                        }
                        else
                        {
                            throw new Exception("Seleccione un artículo");
                        }
                    }
                    else
                    {
                        throw new Exception("Ingrese una cantidad válida");
                    }
                }
                else
                {
                    throw new Exception("Ingrese una fecha");
                }
            }
            else
            {
                throw new Exception("Primero filtre por mes el inventario a trabajar");
            }
        }
        catch (Exception $error)
        {
            Page::showMessage(2, $error->getMessage(), null);
        }
    }
    ?>
    <form method='post' enctype='multipart/form-data' novalidate>
    <div class="principal">
        <div class="row">
                <div class="dashboard-div">
                    <div class="sec-titulo bottom_1em">Inventario por metodo <?php echo"".$metodo ?></div>
                        <div>
                            <ul class='tabs center-align '>
                                <li class='tab'><a href='#div_entrada'>Entrada</a></li>
                                <li class='tab'><a href='#div_salida'>Salida</a></li>
                            </ul>
                        </div>
                        <br>
                        <div class="row" id='div_entrada'>
                                <div class="input-field col l3 m3 s6">
                                    <i class="material-icons prefix">date_range</i>
                                    <input id='fecha_entrada' placeholder='Ejemplo: 2017-08-01' name='fecha_entrada' type="text" class="datepicker validate" value='<?php print($fecha_entrada); ?>' >
                                    <label for='fecha_entrada'>Fecha: </label>
                                </div>
                                <div class="input-field col l4 m4 s12">
                                    <i class='material-icons prefix'>filter_none</i>
                                    <?php
                                    $sql = "SELECT id_articulo, nombre_articulo FROM articulos";
                                    Page::setCombo("Artículos", "nombre_articulo_entrada", $id_articulo_entrada, $sql);
                                    ?>
                                </div>
                                <div class="input-field col l2 m2 s12">
                                    <i class="material-icons prefix">format_list_numbered</i>
                                    <input id='cantidad_entrada' placeholder='Ejemplo: 3' type='number' name='cantidad_entrada' class='validate' max='100000' min='0' step='1' value='<?php print($cantidad_entrada); ?>'/>
                                    <label for='cantidad_entrada'>Cantidad:</label>
                                </div>     
                                <div class="input-field col l3 m3 s12">
                                    <i class="material-icons prefix">attach_money</i>
                                    <input id='precio_unidad_entrada' type='number' name='precio_unidad_entrada' placeholder='Ejemplo: 5.00' class='validate' max='1999.99' min='0.00' step='0.01' value='<?php print($precio_unidad_entrada); ?>'/>
                                    <label for="precio_unidad_entrada">Precio unitario:</label>
                                </div>
                                <div class='center-align col s12 m12 l12'>
                                    <button id='btn_entrada' type='submit' name='btn_entrada' class='btn waves-effect'><i class="material-icons prefix">check_circle</i></button>                                      
                                </div>
                        </div>
                        <div class="row" id='div_salida'>
                                <div class="input-field col l3 m3 s6">
                                    <i class="material-icons prefix">date_range</i>
                                    <input id='fecha_salida' placeholder='Ejemplo: 2017-08-01' name='fecha_salida' type="text" class="datepicker validate" value='<?php print($fecha_salida); ?>' >
                                    <label for='fecha_salida'>Fecha: </label>
                                </div>
                                <div class="input-field col l4 m4 s12">
                                    <i class='material-icons prefix'>filter_none</i>
                                    <?php
                                    $sql = "SELECT id_articulo, nombre_articulo FROM articulos";
                                    Page::setCombo("Artículos", "nombre_articulo_salida", $id_articulo_salida, $sql);
                                    ?>
                                </div>
                                <div class="input-field col l2 m2 s12">
                                    <i class="material-icons prefix">format_list_numbered</i>
                                    <input id='cantidad_salida' placeholder='Ejemplo: 3' type='number' name='cantidad_salida' class='validate' max='100000' min='0' step='1' value='<?php print($cantidad_salida); ?>'/>
                                    <label for='cantidad_salida'>Cantidad:</label>
                                </div>
                                <div class='center-align col s12 m3 l3'>
                                    <button id='btn_salida' type='submit' name='btn_salida' class='btn waves-effect'><i class="material-icons prefix">check_circle</i></button>                                      
                                </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row card-content" id="div_filtrar">
                        <div class="input-field col l5 m5 s12">
                            <i class='material-icons prefix'>filter_none</i>
                            <?php
                            $sql = "SELECT id_articulo, nombre_articulo FROM articulos";
                            Page::setCombo("Artículos", "nombre_articulo_filtro", $id_articulo_filtro, $sql);
                            ?>
                        </div>
                        <div class="input-field col m4 s12 ">
                            <i class="material-icons prefix">date_range</i>
                            <input id='fecha_filtro' placeholder='Ejemplo: 2017-08' type="date" name='fecha_filtro' class="datepicker" value='<?php print($fecha_filtro); ?>' required>
                            <label for='fecha_filtro'>Filtar por Fecha de registro</label>
                        </div>
                        <div class='col s12 m3 l3 center-align'>
                            <button id='btn_filtro' type='submit' name='btn_filtro' class="btn waves-effect waves-light"><i class='material-icons prefix'>search</i></button>                                      
                        </div>
                        <?php
                            if(isset($_SESSION['fecha_filtro']))
                            {
                                $fecha_filtro = $_SESSION['fecha_filtro'];
                            }
                            if(isset($_SESSION['id_articulo_filtro']))
                            {
                                $id_articulo_filtro = $_SESSION['id_articulo_filtro'];
                            }
                            $mes = strftime("%B",strtotime($fecha_filtro.'-01'));
                            $año = strftime("%Y",strtotime($fecha_filtro.'-01'));
                            if($fecha_filtro != null)
                            {
                                print("
                                <div class='col s12 m12 l12 center-align'>
                                    <div class='card-panel purple lighten-2'>
                                        <span class='black-text text-darken-2'>
                                            <i class='material-icons left'>check_circle</i>
                                            Mostrando registros de ".$mes." de ".$año."");
                                            if($id_articulo_filtro != null)
                                            {
                                                $sql = "SELECT nombre_articulo FROM articulos WHERE id_articulo = ?";
                                                $params = array($id_articulo_filtro);
                                                $data = Database::getRow($sql, $params);
                                                echo' del artículo "'.$data['nombre_articulo'].'"';
                                            }
                                        print("</span>
                                    </div>
                                </div>
                                ");
                            }
                        ?>
                    </div>
                    <div class="card-tabs">
                        <ul class='tabs center-align '>
                            <li class='tab'><a href='#div_entradas' class='active'>Compras</a></li>
                            <li class='tab'><a href='#div_salidas'>Ventas</a></li>
                            <?php
                                if(isset($_SESSION['id_articulo_filtro']))
                                {
                                    $id_articulo_filtro = $_SESSION['id_articulo_filtro'];
                                }
                                if($id_articulo_filtro != null)
                                {
                                    echo"<li class='tab'><a href='#div_resumen'>Resumen</a></li>";
                                }
                            ?>
                        </ul>
                    </div>
                    <br>
                    <div class="card-content grey lighten-4">
                        <div class="box-productos" id='div_entradas'>
                            <ul class="collection">            
                            <?php
                                if(isset($_SESSION['id_articulo_filtro']))
                                {
                                    $id_articulo_filtro = $_SESSION['id_articulo_filtro'];
                                }
                                if(isset($_SESSION['fecha_filtro']))
                                {
                                    $fecha_filtro = $_SESSION['fecha_filtro'];
                                }
                                if($fecha_filtro != null)
                                {
                                    if($id_articulo_filtro != null)
                                    {
                                        $sql = "SELECT * FROM inventarios, articulos WHERE inventarios.id_articulo = articulos.id_articulo AND metodo = ? AND doc = ? AND fecha LIKE ? AND inventarios.id_articulo = ?";
                                        $params = array($metodo, 'c', ''.$fecha_filtro.'%', $id_articulo_filtro);
                                    }
                                    else
                                    {
                                        $sql = "SELECT * FROM inventarios, articulos WHERE inventarios.id_articulo = articulos.id_articulo AND metodo = ? AND doc = ? AND fecha LIKE ?";
                                        $params = array($metodo, 'c', ''.$fecha_filtro.'%');
                                    }
                                    $data = Database::getRows($sql, $params);
                                    foreach($data as $row)
                                    {
                                        print("
                                        <li class='collection-item avatar'>
                                            <i class='material-icons circle'>shopping_cart</i>
                                            <span class='title'>Entrada: ".$row['id_inventario']."</span>
                                            <div class='row'>
                                                <div class='col s12 m5 l5'>
                                                    <strong class='title'>Articulo: </strong>".$row['nombre_articulo']."<br>
                                                    <strong >Fecha de Registro: </strong>".$row['fecha']."
                                                </div>
                                                <div class='col s12 m3 l3'>
                                                    <strong >Cantidad: </strong>".$row['cantidad']."<br>
                                                    <strong >Costo Unitario: $</strong>".$row['precio_unidad']."
                                                </div>
                                                <div class='col s12 m4 l4'>
                                                    <strong >Costo Total: $</strong>".$row['costo_total']."
                                                </div>
                                            </div>
                                        </li>   
                                        ");    
                                    }
                                }
                                else
                                {
                                    print("
                                    <div class='card-panel purple lighten-2'>
                                        <span class='black-text text-darken-2'>
                                            <i class='material-icons left'>warning</i>
                                            Seleccione un mes para poder mostrar los registros
                                        </span>
                                    </div>
                                    ");
                                }
                            ?>
                            </ul>
                        </div>
                        <div class="box-productos" id='div_salidas'>
                            <ul class="collection">
                            <?php
                                if(isset($_SESSION['id_articulo_filtro']))
                                {
                                    $id_articulo_filtro = $_SESSION['id_articulo_filtro'];
                                }
                                if(isset($_SESSION['fecha_filtro']))
                                {
                                    $fecha_filtro = $_SESSION['fecha_filtro'];
                                }
                                if($fecha_filtro != null)
                                {
                                    if($id_articulo_filtro != null)
                                    {
                                        $sql = "SELECT * FROM inventarios, articulos WHERE inventarios.id_articulo = articulos.id_articulo AND metodo = ? AND doc = ? AND fecha LIKE ? AND inventarios.id_articulo = ?";
                                        $params = array($metodo, 'v', ''.$fecha_filtro.'%', $id_articulo_filtro);
                                    }
                                    else
                                    {
                                        $sql = "SELECT * FROM inventarios, articulos WHERE inventarios.id_articulo = articulos.id_articulo AND metodo = ? AND doc = ? AND fecha LIKE ?";
                                        $params = array($metodo, 'v', ''.$fecha_filtro.'%');
                                    }
                                    $data = Database::getRows($sql, $params);
                                    foreach($data as $row)
                                    {
                                        print("
                                        <li class='collection-item avatar'>
                                            <i class='material-icons circle'>shopping_cart</i>
                                            <span class='title'>Entrada: ".$row['id_inventario']."</span>
                                            <div class='row'>
                                                <div class='col s12 m5 l5'>
                                                    <strong class='title'>Articulo: </strong>".$row['nombre_articulo']."<br>
                                                    <strong >Fecha de Registro: </strong>".$row['fecha']."
                                                </div>
                                                <div class='col s12 m3 l3'>
                                                    <strong >Cantidad: </strong>".$row['cantidad']."<br>
                                                    <strong >Costo Unitario: $</strong>".$row['precio_unidad']."
                                                </div>
                                                <div class='col s12 m4 l4'>
                                                    <strong >Costo Total: $</strong>".$row['costo_total']."
                                                </div>
                                            </div>
                                        </li>   
                                        ");    
                                    }
                                }
                                else
                                {
                                    print("
                                    <div class='card-panel purple lighten-2'>
                                        <span class='black-text text-darken-2'>
                                            <i class='material-icons left'>warning</i>
                                            Seleccione un mes para poder mostrar los registros
                                        </span>
                                    </div>
                                    ");
                                }
                            ?>
                            </ul>
                        </div>
                                   
                            <?php
                                if(isset($_SESSION['id_articulo_filtro']))
                                {
                                    $id_articulo_filtro = $_SESSION['id_articulo_filtro'];
                                }
                                if(isset($_SESSION['fecha_filtro']))
                                {
                                    $fecha_filtro = $_SESSION['fecha_filtro'];
                                }
                                if($fecha_filtro != null)
                                {
                                    if($id_articulo_filtro != null)
                                    {
                                        $sql_inventario_inicial = "SELECT * FROM inventarios, articulos WHERE inventarios.id_articulo = articulos.id_articulo AND id_inventario = (SELECT MIN(id_inventario) FROM inventarios WHERE fecha=(SELECT MIN(fecha) FROM inventarios WHERE metodo = ? AND doc = ? AND fecha LIKE ? AND id_articulo = ?) AND metodo = ? AND doc = ? AND fecha LIKE ? AND id_articulo = ?)";
                                        $params_inventario_inicial = array($metodo, 'ii', ''.$fecha_filtro.'%', $id_articulo_filtro, $metodo, 'ii', ''.$fecha_filtro.'%', $id_articulo_filtro);
                                        $data_inventario_inicial = Database::getRow($sql_inventario_inicial, $params_inventario_inicial);
                                        $sql_inventario_final = "SELECT * FROM inventarios, articulos WHERE inventarios.id_articulo = articulos.id_articulo AND id_inventario = (SELECT MAX(id_inventario) FROM inventarios WHERE metodo = ? AND doc = ? AND fecha LIKE ? AND id_articulo = ?)";
                                        $params_inventario_final = array($metodo, 'r', ''.$fecha_filtro.'%', $id_articulo_filtro);
                                        $data_inventario_final = Database::getRow($sql_inventario_final, $params_inventario_final);
                                        print("
                                        <div class='box-productos' id='div_resumen'>
                                            <ul class='collection'>
                                                    <div class='row'>
                                                        <div class='col s12 m6 l6'>
                                                            <li class='collection-item avatar'>
                                                                <i class='material-icons circle'>shopping_cart</i>
                                                                <span class='title'>INVENTARIO INICIAL </span><br>
                                                                <span class='title'>Cantidad: </span>".$data_inventario_inicial['cantidad']."<br>
                                                                <span class='title'>Precio de Unidad: $</span>".$data_inventario_inicial['precio_unidad']."<br>
                                                                <span class='title'>Costo Total: $</span>".$data_inventario_inicial['costo_total']."
                                                            </li> 
                                                        </div>
                                                        <div class='col s12 m6 l6'>
                                                            <li class='collection-item avatar'>
                                                                <i class='material-icons circle'>shopping_cart</i>
                                                                <span class='title'>INVENTARIO FINAL </span><br>
                                                                <span class='title'>Cantidad: </span>".$data_inventario_final['cantidad']."<br>
                                                                <span class='title'>Precio de Unidad: $</span>".$data_inventario_final['precio_unidad']."<br>
                                                                <span class='title'>Costo Total: $</span>".$data_inventario_final['costo_total']."
                                                            </li> 
                                                        </div>
                                                    </div>
                                                
                                            </ul>
                                        </div>
                                        "); 
                                    }
                                }
                            ?>
                    </div>
                </div>
        </div>
    </div>
    </form>
</div>


<!--Llamando a los Script de Java-->
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="lib/materialize/materialize.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>

</body>
</html>