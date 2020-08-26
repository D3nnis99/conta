<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kaisha</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" href="/img/favicon.png" sizes="64x64" type="image/png">
    <link rel="stylesheet" href="/lib/materialize/materialize.min.css">
    <link rel="stylesheet" href="/css/estilo.css">
    <link rel="stylesheet" href="/css/icons.css">
</head>

<header>

  <div class="navbar-fixed"> 
      <nav class="purple lighten-2">
        <div class="nav-wrapper">
        <a href="#" data-activates="mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>
        <a href="index.php" class="brand-logo"><span class="logo-kaisha transition_s5"></span></a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="articulos.php">Articulos</a></li>
            <li><a href="#">Impresiones</a></li>
            <li><a href="login.php" class="waves-effect waves-light btn purple lighten-3 btn-normal">Iniciar sesión</a></li>
            <li><a href="registro.php" class="waves-effect waves-light btn palette_pink btn-normal">Registrarse</a></li>
        </div>
     </nav>
    </div>
    <ul class="side-nav" id="mobile-menu">
        <li class="nv_title"><span>Menú</span></li>
        <li class="nv_normal"><a href="articulos.php">Articulos</a></li>
        <li class="nv_normal"><a href="#">Impresiones</a></li>
        <li class="purple lighten-3 nav_specialbtn nv_purple"><a href="login.php">Iniciar sesión</a></li>
        <li class="palette_pink nav_specialbtn nv_pink"><a href="registro.php">Registrarse</a></li>
    </ul>
</header>

<body>
