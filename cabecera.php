<?php 
session_start();
if(isset($_COOKIE['PUBLICAR'])) //para evitar publicar seguido, cuando se publica una cookie que desaparece al pasar 10 segundos
	$publicar=false;
else
	$publicar=true;

require_once("lib/conexion.php");
require("lib/varios.php");
require_once("clases/Usuario.php");
require_once("clases/Videojuego.php");
require_once("clases/Ventas.php");

define("COMENTxPAG", 8);
define("ESPERA", 10); //TIEMPO A ESPERAR ANTES DE PUBLICAR OTRA VEZ
$msg="";
if (isset($_POST['publicar'])){
	setcookie("PUBLICAR","NO",time()+ESPERA);
}
if(isset($_POST['enviar_login'])){
	$usr=new Usuario("","",$_POST['email'],$_POST['pass'],"");
	$res=$usr->buscarUsuarioPass();
	if($res==NULL)
		$msg= "LOGIN INCORRECTO";
	else{
		$_SESSION['email']=$res['email'];
		$_SESSION['nombre']=$res['nombre'];
		$_SESSION['id_usr']=$res['id_usr'];
		$_SESSION['tipo']=$res['tipo'];
	}
}

if(isset($_SESSION['email']))
	$email=$_SESSION['email']; //si no está establecida la variable $email, no hay sesión activa
if(isset($_SESSION['nombre']))
	$nombre=$_SESSION['nombre'];
else
	$nombre="";
if(isset($_SESSION['id_usr']))
	$id_usr=$_SESSION['id_usr'];
else
	$id_usr="";
if(isset($_SESSION['tipo']))
	$tipo_usr=$_SESSION['tipo'];
else
	$tipo_usr="";

if(isset($_GET['a'])){
	$accion=$_GET['a'];
	if($accion=="logout"){
		unset($_SESSION['email']);
		unset($_SESSION['nombre']);
		unset($_SESSION['id_usr']);
		unset($_SESSION['tipo']);
		unset($email);
		$nombre="";
		$id_usr="";
		$tipo_usr="";
	}
}
?>
<!DOCTYPE html>
<html lang="en">



    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
   
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
 
     <!-- Site Metas -->
    <title>Cyber Game</title>  
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
	<!-- Fuentes -->
	<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Odibee+Sans&display=swap" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Play&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&display=swap" rel="stylesheet">



	
    <!-- Site Icons -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Add jQuery library -->
	
		<!-- Add fancyBox -->
	<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="css/style.css">
     <link rel="stylesheet" href="style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Custom CSS -->
   
	<!-- Scrips -->
    <script src="js/customscrips.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>







</head>
<?php
	$p="home";
	if(isset($_GET['P'])){
		$p=$_GET['P'];
	}
?>

<body>





    
	<div class="top-bar">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<div class="left-top">
						<h1 id="titulop">CYBERGAME</h1>
					</div>


				</div>





				<div class="col-md-6 col-sm-6">
					<div class="right-top">
						<div class="social-box">
						
						<?php 

						if(!isset($email)){ //comprueba si no estas logueado, y sino lo estas te muestra la opcion de loguin
						?>
							<span class="fa fa-user" ></span>
						<div id="usuario"><a <?php if($p=="login") echo 'class="active"' ?> href="<?php echo $_SERVER['PHP_SELF'] ?>?P=login">Login</a> </div>

						<?php }else{ //si estas logueado muestra el icono y tu nombre de usuario?>
					
						<span class="fa fa-user" ></span>
						<div id="usuario"><?php if(isset($email)) echo "$nombre"?> </div>

						<?php }  ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <header class="header header_style_01">
        <nav class="megamenu navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    
					
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a <?php if($p=="home") echo 'class="active"' ?> href="<?php echo $_SERVER['PHP_SELF'] ?>?P=home">Inicio</a></li>
				
						<?php if(isset($email) and $tipo_usr=="adm"){ ?>
                        <li><a <?php if($p=="alta") echo 'class="active"' ?> href="<?php echo $_SERVER['PHP_SELF'] ?>?P=alta">alta usuario</a></li>
                        <li><a <?php if($p=="modif") echo 'class="active"' ?> href="<?php echo $_SERVER['PHP_SELF'] ?>?P=modif">modifica usuario</a></li>
                        <li><a <?php if($p=="borra") echo 'class="active"' ?> href="<?php echo $_SERVER['PHP_SELF'] ?>?P=borra">baja usuario</a></li>
                          <li><a <?php if($p=="añad") echo 'class="active"' ?> href="<?php echo $_SERVER['PHP_SELF'] ?>?P=añad">Añadir Juego</a></li>
                        
						<?php } ?>

								<?php if(!isset($email)){ ?>
							<li><a <?php if($p=="login") echo 'class="active"' ?> href="<?php echo $_SERVER['PHP_SELF'] ?>?P=login">Login</a></li>
						<?php }else{ ?>
							<li><a <?php if($p=="misPed") echo 'class="active"' ?> href="<?php echo $_SERVER['PHP_SELF'] ?>?P=misPed">Mis Pedidios</a></li>
							<li><a href="<?php echo $_SERVER['PHP_SELF'] ?>?a=logout">Salir</a></li>
							
						<?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
	