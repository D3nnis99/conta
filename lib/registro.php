 <?php
   
    require("../lib/database.php");
    require("../lib/validator.php");
    $_POST = Validator::validateForm($_POST);
  	$nombres = $_POST['nombres'];
  	$apellidos = $_POST['apellidos'];
    $email = $_POST['correo'];
    $clave1 = $_POST['clave1'];
    $clave_confirmar = $_POST['clave_confirmar'];
    $telefono = $_POST['telefono'];
    $empresa = $_POST['empresa'];
    $fecha = $_POST['fecha'];
    $usuario = $_POST['usuario'];
    
    try 
    {

        if(isset($_POST['terminos']))
        {
      	if($nombres != "" && $apellidos != "" && $usuario!= ""  )
        {
            if($telefono != "")
            {
                if($email != "")
                {
                    if($clave1 != "" || $clave_confirmar != "")
                    {
                        if($clave1 == $clave_confirmar)
                        {
                            $clave = password_hash($clave1, PASSWORD_DEFAULT);
                            $sql = "INSERT INTO usuarios_c(nombres, apellidos,correo, clave, telefono,nombre_usuario, fecha_nacimiento, nombre_empresa,estado) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $params = array($nombres, $apellidos, $email, $clave, $telefono, $usuario, $fecha, $empresa, 1);
                            database::executeRow($sql, $params);
                            echo "<script>$(function(){alertify.alert('Se registro su Usuario');})</script>";
                        }
                        else
                        {
                            alertify.alert('Error', 'Las contraseñas no coinciden', function(){ alertify.fail('Incorrecto'); });
                        }
                    }
                    else
                    {
                         alertify.alert('Error', 'Ingrese las contraseñas', function(){ alertify.fail('Incorrecto'); });
                    }
                }
                else
                {
                     alertify.alert('Error', 'Ingrese un email', function(){ alertify.fail('Incorrecto'); });
                }
            }
            else
            {
                 alertify.alert('Error', 'Ingrese su telefono', function(){ alertify.fail('Incorrecto'); });
            }
        }
        else
        {
             alertify.alert('Error', 'Ingrese su nombre completo', function(){ alertify.fail('Incorrecto'); });
        }
        }
        else
        {
           print ("<script>window.location='/public/login.php'</script>;");       
        }
    }
    catch (Exception $error)
    {
        alertify.alert('Error', ''. $error, function(){ alertify.fail('Incorrecto'); });
    }


?>
