<?php
include('Mobile_Detect.php');
$detect = new Mobile_Detect();
if ( $detect->isAndroidtablet() || $detect->isIpad() || $detect->isBlackberrytablet() || $detect->isAndroid() || $detect->isIphone() || $detect->isMobile()) {
    header('Location: ../sac/indexmobile.php');
}
//print_r($_POST);
require_once('Connections/Sac2.php');
$Bandera = false; 
session_start ();
session_destroy();  // destroy the session 
if (isset($_POST['entrar'])) {	  
  $loginUserName=$_POST['Username'];
  $password=base64_encode($_POST['UserPassword']);  
  $sql = "SELECT * FROM Usuarios WHERE Usuario='".$loginUserName."' AND Password='".$password."'";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
  exit( "Error en la consulta SQL" ); 
  }  
   // Se muestran los resultados
 while ( odbc_fetch_row($rs) ) { 
  session_start (); 
  $_SESSION['S_UsuarioID']  = odbc_result($rs, 'USUARIO_ID'); 
  $_SESSION['S_Nombre'] = odbc_result($rs, 'Nombre');
  $_SESSION['S_Usuario']  = odbc_result($rs, 'Usuario');    
  $_SESSION['S_Privilegios']  = odbc_result($rs, 'Privilegios');  
  $_SESSION['login_ok'] = "identificado";  
  $Bandera = true;
 }//While  
  //echo $Bandera;
  if ($Bandera == TRUE){
	  header("Location: Almacen.php"); 
  }
  else {
	//echo $error;
	echo"<script>alert('Datos Incorrectos')</script>";
   }//Bandera 
} 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

<!--META-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link rel="shortcut icon" href="../RCOApps/images/rcoapssico.ico" />
<title>Bienvenido</title>

<!--STYLESHEETS-->
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/style4.css" rel="stylesheet" type="text/css" />
<link href="css/styleindex.css" rel="stylesheet" type="text/css" />
<!--<link href="css/ventana.css" rel="stylesheet" />-->

<!--SCRIPTS-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<!--Slider-in icons-->
<script type="text/javascript">
$(document).ready(function() {
	$(".username").focus(function() {
		$(".user-icon").css("left","-48px");
	});
	$(".username").blur(function() {
		$(".user-icon").css("left","0px");
	});
	
	$(".password").focus(function() {
		$(".pass-icon").css("left","-48px");
	});
	$(".password").blur(function() {
		$(".pass-icon").css("left","0px");
	});
});
</script>

</head>
<body>
<body id="page">
        <ul class="cb-slideshow">
            <li><span></span><div><h3>BIENVENIDO</h3></div></li>
            <li><span></span><div><h3>- SISTEMA DE ADIMINISTRACIÓN DE CONSERVACIÓN -</h3></div></li>
            <li><span></span><div><h3><img src="images/logos.png"/></h3></div></li>
            <li><span></span><div><h3> </h3></div></li>
            <li><span></span><div><h3> </h3></div></li>
            <li><span></span><div><h3> </h3></div></li>
         
</ul>
<!--WRAPPER-->
<script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
<div id="wrapper" align="center">

	<!--SLIDE-IN ICONS-->
    <div class="user-icon"></div>
    <div class="pass-icon"></div>
    <!--END SLIDE-IN ICONS-->

<!--LOGIN FORM-->
<form id="formulario" name="login-form" class="login-form" action="index.php" method="post" enctype="multipart/form-data" align="center">

	<!--HEADER-->
    <div class="header">
    <!--TITLE-->
    <h1> <center> 
      <img src="images/sac.png" width="200" height="200" alt="sac" /><img src="images/sistema_conservacion.png" width="177" height="48" /></center>
      <!--END DESCRIPTION-->    </h1>
    </div>
    <!--END HEADER-->
	
	<!--CONTENT-->
    <div class="content">
	<!--USERNAME--><!--FIN USUARIO-->
    <!--PASSWORD-->
    <input name="Username" type="text" class="input username" id="Username" onfocus="this.value=''" value="Usuario"/>
    <input name="UserPassword" id="UserPassword" type="password" class="input password" value="Password" onfocus="this.value=''" /><!--FIN CONTRASEÑA-->
    </div>
    <!--END CONTENT-->
    <!--FOOTER-->
    <div class="footer">
    <!--LOGIN BUTTON--><button type="submit" class="btn btn-primary" style="width:235px" name="entrar" id="entrar">Entrar</button><!--END LOGIN BUTTON-->
    <!--REGISTER BUTTON--<input type="submit" name="submit" value="Registrar" class="register" /><!--END REGISTER BUTTON-->
    </div>

    <!--END FOOTER-->

</form>
<!--END LOGIN FORM-->
</div>
<!--END WRAPPER
<div id="modal3" class="modalmask">
		<div class="modalbox resize">
			<a href="#close" title="Close" class="close">X</a>
			<h2>Datos Incorrectos</h2>
			<a href=javascript:window.history.back()>Retornar e ingresar datos correctos.</a><br>";
		</div>
	</div>-->
<!--GRADIENT--><!--END GRADIENT-->

</body>
</html>
