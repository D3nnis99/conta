<!-- Se incluye el header de la pagina -->
<?php include($_SERVER['DOCUMENT_ROOT'] . '/inc/header_form.php') ?>
<?php
require("../lib/database.php");
require("../lib/validator.php");


if(!empty($_POST))
{
	$_POST = validator::validateForm($_POST);
  	$usuario = $_POST['usuario'];
  	$clave = $_POST['clave'];
  	try
    {
      	if($usuario != "" && $clave != "")
  		{
  			$sql = "SELECT * FROM usuarios_a WHERE nombre_usuario = ?";
		    $params = array($usuario);
		    $data = Database::getRow($sql, $params);
		    if($data != null)
		    {
		    	$hash = $data['clave'];
		    	if(password_verify($clave, $hash)) 
		    	{
                    
			      	header("location: index.php");
				}
				else 
				{
					throw new Exception("La clave ingresada es incorrecta");
				}
		    }
		    else
		    {
		    	throw new Exception("El alias ingresado no existe");
		    }
	  	}
	  	else
	  	{
	    	throw new Exception("Debe ingresar un alias y una clave");
	  	}
    }
    catch (Exception $error)
    {  
         "<script>$(function(){alertify.alert('Alert Title', 'Alert Message!', function(){ alertify.success('Registro completo'); });;</script>";
    }
}
?>



<div class="principal">
    <div class="row">
        <div class="col m4 s12 offset-m4">
            <div class="cont_login">
                <div class="st_subtitle pad_bottom"> Iniciar sesión </div>
                <div class="input-field">
                    <i class="material-icons prefix">account_circle</i>
                    <input id="icon_prefix" type="text" class="validate">
                    <label for="icon_prefix">Usuario</label>
                </div>
                <div class="input-field">
                    <i class="material-icons prefix">vpn_key</i>
                    <input id="icon_prefix" type="password" class="validate">
                    <label for="icon_prefix">Clave</label>
                </div>
                <div class="mrg-btn">
                    <a class="waves-effect waves-light btn purple lighten-3 btn-complete">Iniciar sesión</a>
                    <hr class="line_lg" />
                    <a href="#" class="">¿Has olvidado tu contraseña?</a>
                </div>
            </div>

        </div>
    </div>
</div>
