<?php 
//print_r($_POST);
require_once('../Connections/sac2.php');
//require_once('../PHPMailer/class.phpmailer.php');
//include("../PHPMailer/class.smtp.php"); 
//$Bandera = false; 
//$notificacion = "";
//$password = "";

error_reporting(0);

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_POST['entrar'])) {  
  $loginUserName=$_POST['UserUsername'];
  $password=base64_encode($_POST['UserPassword']);
   // Se define la consulta que va a ejecutarse
  $sql = "SELECT * FROM Usuarios WHERE usuario='".$loginUserName."' AND PASSWORD='".$password."' AND PRIVILEGIOS IN ('ADMINISTRADOR','COORDINADOR')";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
  exit( "Error en la consulta SQL" ); 
  }  
   // Se muestran los resultados
 while ( odbc_fetch_row($rs) ) {
  $id_usuario=odbc_result($rs, 'USUARIO_ID'); 
  $nombre=odbc_result($rs, 'NOMBRE');    
  $Privilegios  = odbc_result($rs, 'Privilegios'); 
  $Bandera = true; 
  
  $_SESSION['S_UsuaioId'] = $id_usuario; 
  $_SESSION['S_Nombre'] = $nombre; 
  $_SESSION['S_Privilegios'] = $Privilegios;
  $_SESSION['login_ok'] = "identificado";     	
 }//While
 
  //echo $Bandera;
  if ($Bandera == TRUE){
	  header("Location: inicio.php"); 
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
<link rel="shortcut icon" href="../images/favicon.ico" />
<title>Bienvenido</title>

<!--STYLESHEETS-->
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.min.css" rel="stylesheet" />
<!--<link href="css/style4.css" rel="stylesheet" type="text/css" />
<link href="css/ventana.css" rel="stylesheet" />-->
<link href="../css/styleindex2.css" rel="stylesheet" type="text/css" />
<!--SCRIPTS-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<!--Slider-in icons-->

<!-- PNotify -->
<link href="../src/pnotify.core.css" rel="stylesheet" type="text/css" />
<link href="../src/pnotify.brighttheme.css" rel="stylesheet" type="text/css" />
<link href="../src/pnotify.buttons.css" rel="stylesheet" type="text/css" />
<link href="../src/pnotify.history.css" rel="stylesheet" type="text/css" />
<link href="../src/pnotify.picon.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../src/pnotify.confirm.js"></script>
<script type="text/javascript" src="../src/pnotify.core.js"></script>
<script type="text/javascript" src="../src/pnotify.buttons.js"></script>
<script type="text/javascript" src="../src/pnotify.nonblock.js"></script>
<script type="text/javascript" src="../src/pnotify.desktop.js"></script>
<script type="text/javascript" src="../src/pnotify.history.js"></script>
<script type="text/javascript" src="../src/pnotify.callbacks.js"></script>
<script type="text/javascript" src="../src/pnotify.reference.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	
	//CONFIGURACION DE LAS NOTIDICACIONES		
		var Tipo = '<?php echo $notificacion;  ?>';		
		PNotify.prototype.options.styling = "bootstrap3";
		
		 switch (Tipo) {
			 case 'success':
			 	new PNotify({
					title: "Exito",
					text: "Registro guardado.",
					delay: 2000,
					animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
					nonblock: {
						nonblock: false
					},
					type: "success"
				});					
        		break;
				case 'errorEntrada':
			 	new PNotify({
					title: "Error",
					text: "No se ha completado el registro. ",
					delay: 2000,
					animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
					nonblock: {
						nonblock: false
					},
					type: "error"
				});					
        		break;
				case 'successCorreo':
			 	new PNotify({
					title: "Exito",
					text: "Mensaje enviado.",
					delay: 2000,
					animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
					nonblock: {
						nonblock: false
					},
					type: "success"
				});					
        		break;
				case 'errorCorreo':
			 	new PNotify({
					title: "Error",
					text: "El correo introducido no es valido. ",
					delay: 2000,
					animation: 'fade', //'show', 'slide', animation: { effect_in: 'show', effect_out: 'slide' }				
					nonblock: {
						nonblock: false
					},
					type: "error"
				});					
        		break;
		 }//Swicth
	
	//$("#nombre").jqxInput({placeHolder: "Introduce el folio", height: 25, width: 150, minLength: 1,  source: borrar });
	
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
	
	
	$("#correcto1").hide();	
	$("#correcto2").hide();
	$("#incorrecto2").hide();
	$("#incorrecto1").hide();	
	$("#password2").attr("disabled", "disabled");	
	$("#registrar").attr("disabled", "disabled");
	$("#acepto").attr("disabled", "disabled");
	
	$("#password1").blur(function(){		
		var p1 = document.getElementById("password1").value;
		var longitud = p1.length;
		if(longitud > 0){
			$("#correcto1").show();		
		    $('#password2').removeAttr("disabled", "disabled");			
		}else{
			$("#correcto1").hide();
			$("#correcto2").hide();
			$("#incorrecto2").hide();
			document.getElementById("password2").value="";
			$("#password2").attr("disabled", "disabled");
		}
	});	
	$("#password2").keyup(function(){
		
		var p1 = document.getElementById("password1").value;
		var p2 = document.getElementById("password2").value;		
		var longitud = p2.length;		
		var cadena = p1.substring(0,longitud);
		
		if (p2==cadena && longitud > 0){			
			$("#correcto2").show();
			$("#incorrecto2").hide();
			$('#acepto').removeAttr("disabled", "disabled");    			
		}else{
			$("#incorrecto2").show();
			$("#correcto2").hide();	
			$("#acepto").attr("disabled", "disabled");
			$("#registrar").attr("disabled", "disabled");		
		}	
	});
	$("#acepto").click(function(){
		if( $('#acepto').prop('checked') ) {
			$('#registrar').removeAttr("disabled", "disabled");    		
		}else{
			$("#registrar").attr("disabled", "disabled");	
		}
		
	});
	
	
});
</script>
</head>
<body id="page">
<ul class="cb-slideshow">
</ul>
<!--WRAPPER--> 
<script type="text/javascript" src="../js/modernizr.custom.86080.js"></script>
<div id="wrapper"> 
	
	<!--SLIDE-IN ICONS-->
  <div class="user-icon"></div>
  <div class="pass-icon"></div>
	<!--END SLIDE-IN ICONS--> 
	
	<!--LOGIN FORM-->
  <form id="formulario" name="login-form" class="login-form" action="index.php" method="post" enctype="multipart/form-data" align="center">
		
		<!--HEADER-->
		<div class="header"> 
			<!--TITLE-->
			<h1>
				<center>
					<p><img src="../images/SAC.png" width="191" height="194" alt="" /></p>
				</center>
				<!--END DESCRIPTION--> </h1>
		</div>
		<!--END HEADER--> 
		
		<!--CONTENT-->
		<div class="content"> 
			<!--USERNAME--> 
			<!--FIN USUARIO--> 
			<!--PASSWORD-->
		  <input type="text" class="input username" id="UserUsername" name="UserUsername" onfocus="this.value=''" value="Usuario" />
			<input name="UserPassword" id="UserPassword" type="password" class="input password" value="Password" onfocus="this.value=''" />
			<!--FIN CONTRASEÑA--> 
		</div>
		<!--END CONTENT--> 
		<!--FOOTER-->
		<div class="footer"> 
			<!--LOGIN BUTTON-->
			<button type="submit" class="btn btn-primary" style="width:180px" color="white" name="entrar" id="entrar">Iniciar Sesión</button>
			<br />
		
				<!--END LOGIN BUTTON--> 
		</div>
		<!--END FOOTER-->
	</form>
	<!--END LOGIN FORM--><!--END WRAPPER
<!--GRADIENT--> 
<!--END GRADIENT--> 
</div>
</body>
</html>
