<?php 
require_once('Connections/sac2.php'); 
//print_r($_SESSION);
//-------------------------------VALIDAMOS QUE ESTE LOGEADO-----------------------------
if (!isset($_SESSION)) {
  session_start();
}
$login_ok = $_SESSION['login_ok'];
//RESTRINGIMOS EL ACCESO A USUARIOS NO IDENTIFICADOS
if ($login_ok == "identificado"){
 
}else{
echo "No Identificado";	 
	//session_unset();  // remove all session variables
	session_destroy();  // destroy the session 
	header("Location: index.php");
}

if (isset($_POST['cerrar_sesion'])) {
	session_destroy();  // destroy the session 
	header("Location: index.php");
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
<title>SAC</title>
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/tab.css" rel="stylesheet" />
<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!--menu nuevo-->
   <link rel="stylesheet" href="css/styl.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>
   <!--fin-->
</head>
<body>
<div id='cssmenu'>
<ul>
   <li><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Catálogos</span></a>
      <ul>
         <li><a href='#'><span>General</span></a>
         <li><a href='#'><span>Firmas</span></a>
         <li><a href='#'><span>Usuarios</span></a>
         <li class='has-sub'><a href='#'><span>Materiales</span></a>
            <ul>
               <li><a href='#'><span>Productos</span></a></li>
               <li class='last'><a href='#'><span>Test</span></a></li>
            </ul>
         </li>
      </ul>
   </li>
   <li><a href='#'><span>Almacen</span></a>   
     <ul>
         <li><a href='#'><span>1</span></a>
         <li><a href='#'><span>2</span></a>                     
     </ul>
   </li>  
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul>
         <li><a href='progsemanal.php'><span>Prog. Semanal</span></a>
         <li><a href='#'><span>Avance Diario</span></a>                     
     </ul>
   </li>
   <li><a href='#'><span>Contratos</span></a>   
     <ul>
         <li><a href='#'><span>1</span></a>
         <li><a href='#'><span>2</span></a>                     
     </ul>
   </li>  
   <li><a href='#'><span>Maquinaria</span></a>   
     <ul>
         <li><a href='#'><span>1</span></a>
         <li><a href='#'><span>2</span></a>                     
     </ul>
   </li>
   <li><a href='#'><span>Presupuestos</span></a>   
     <ul>
         <li><a href='#'><span>1</span></a>
         <li><a href='#'><span>2</span></a>                     
     </ul>
   </li>
   <li><a href="#myModal" data-toggle="modal" data-target="#myModal" title="Registro"><span>Modal</span></a></li>
   <li class='last'><a href='#'><span>Ayuda</span></a></li>
   <table width="392" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7; background:none;" id="cssmenu">
     <tr>
       <tr>
       <td height="35" align="right">Bienvenid@:</td>
       <td width="155" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="103" valign="bottom" class="btn btn-info">
       <button  class="btn btn-info" border ="0">Cerrar Sesion</button>
       </td>
     </tr>

   </table>
</ul>
</div> 
  <!-- Modal -->
</p>
<table width="1318" height="773" border="0">
  <tr>
    <td align="center" valign="middle">
    <!--tabla-->
<div style="width:1050px; height:718px; overflow:auto;" align="center">

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="95%" border="0" align="center" cellspacing="1">
  <tr>
    <td align="left" valign="middle" bgcolor="#FFFFFF" scope="col"><span class="areas"> Gracias por consultar nuestro manual, lea cuidadosamente las instrucciones de operación
para utilizar el sistema correctamente, y mantenga este manual apropiadamente para
futuras referencias. </span><br />
      <hr />
     
     <embed src="ayuda/ManualCoprem10.pf" width=837 height=588>
     
      <br /></td>
  </tr>
</table>

</div>
<!--Fin-->
    </td>
  </tr>
</table>
<!--Modal-->
</body>
</html>