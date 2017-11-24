<?php 
//print_r($_POST);
require_once('Connections/Sac2.php');
$Bandera = false; 

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_POST['entrar'])) {
	  
  $loginUserName=$_POST['Username'];
  $password=base64_encode($_POST['UserPassword']);
   // Se define la consulta que va a ejecutarse
  $sql = "SELECT * FROM Usuarios WHERE Usuario='".$loginUserName."' AND Password='".$password."'";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
  exit( "Error en la consulta SQL" ); 
  }  
   // Se muestran los resultados
 while ( odbc_fetch_row($rs) ) { 
  $UsuarioID  = odbc_result($rs, 'Usuario_Id'); 
  $Nombre = odbc_result($rs, 'Nombre');
  $Usuario  = odbc_result($rs, 'Usuario'); 
  $Password  = odbc_result($rs, 'Password');    
  $Tramo  = odbc_result($rs, 'Tramo');
    
  $Bandera = true;
  
  $_SESSION['S_Usuario'] = $Usuario; 
  /* $_SESSION['S_UsuarioID'] = $UsuarioID;
  $_SESSION['S_Password'] = $Password;
  $_SESSION['S_Rfc'] = $rfc; 
  $_SESSION['S_Correo'] = $correo; 
  $_SESSION['S_Constancia'] = $constancia;
  $_SESSION['S_Privilegios'] = $Privilegios;	     
  $_SESSION['S_Nombre'] = $Nombre; */
  $_SESSION['login_ok'] = "identificado";    
  
 }//While
  //echo $Bandera;
  if ($Bandera == TRUE)	 	
  	header("Location: Inicio.php"); 
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
   <link rel="shortcut icon" href="images/favicon.ico" /> 
<title>Bienvenido SAC</title>

<!--STYLESHEETS-->

<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
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
<table width="419" height="952" border="0" align="center">
  <tr> <td width="413" height="139">
  </td></tr><tr>
    <td height="434"><center><img src="images/sac.png" width="390" height="400" alt="sac" /></center></td>
  </tr>
  <tr>
    <td>
    <form id="formulario" name="login-form" class="login-form" action="index.php" method="post" enctype="multipart/form-data">
   <center> 
     <table width="248" border="0" align="center">
       <tr>
         <td width="36" height="43"><center><img src="images/user-icon.png" width="25" height="27" alt="user" /></center></td></
         ><td width="222"><input name="Username" type="text" class="input username" id="Username" onfocus="this.value=''" value="Usuario" style="width:200px"/></td>
         </tr>
       <tr>
         <td height="39" ><center><img src="images/pass-icon.png" width="25" height="25" alt="pass"/></center></td>
         <td><input name="UserPassword" id="UserPassword" type="password" class="input password" value="Password" onfocus="this.value=''" style="width:200px"/></td>
         </tr>
       <tr>
         <td colspan="2"> <button type="submit" class="btn btn-success" style="width:235px" name="entrar" id="entrar">Entrar    </button>
         </td>
         </tr>
     </table> 
    </form>
  <tr>
    <td> 
    </td>
  </tr>
  <tr> <td height="215"><p>&nbsp;</p>
    <p><center> <font color="#FFFFFF"> RCO Carreteras, Ing. Erick López <p> Ext. 8078 </p> </font></p>
  </td></tr>
  <tr>
    <td height="18"></td>
  </tr>
</table>
</body>
</html>