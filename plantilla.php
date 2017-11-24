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

//CARGAMOS LOS ACCESOS EN VARIABLES DE SESION
$i=0;
$sql = "SELECT DISTINCT (TRAMO), BASE, PLAZA, SUBTRAMO FROM Accesos WHERE Usuario_ID='".$_SESSION['S_UsuarioID']."'";
//echo $sql;
$rs = odbc_exec( $conn, $sql );
if ( !$rs ) { 
	exit( "Error en la consulta SQL" ); 
}     
while ( odbc_fetch_row($rs) ) {
	if ($i == 0){
		$_SESSION['S_Tramo'] = odbc_result($rs, 'TRAMO'); 
		$_SESSION['S_Base'] = odbc_result($rs, 'BASE');
		$_SESSION['S_Plaza'] = odbc_result($rs, 'PLAZA');
		$_SESSION['S_Subtramo'] = odbc_result($rs, 'SUBTRAMO');
	}else{
		$_SESSION['S_Tramo'] .= "','". odbc_result($rs, 'TRAMO');
		$_SESSION['S_Base'] .= "','". odbc_result($rs, 'BASE');
		$_SESSION['S_Plaza'] .= "','". odbc_result($rs, 'PLAZA');
		$_SESSION['S_Subtramo'] .= "','". odbc_result($rs, 'SUBTRAMO');	
	}
	$i++;
}//While 

//echo $_SESSION['S_Plaza'];	 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>PLANTILLA</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />    
	<link rel="stylesheet" href="css/menu1.css"><!--Necesario para Menu 1-->
	<link rel="stylesheet" href="css/styl.css"><!--Necesario para Menu 2-->
	<link rel="stylesheet" href="css/menuLateral.css"> <!--Necesario para Menu 3-->	
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
	<script type="text/javascript" src="scripts/menu1.js"></script><!--Necesario para Menu 1-->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>		
	<script type="text/javascript" src="js/jquery.bpopup-0.11.0.min.js"></script>
    <!--CSS AJUSTE PANTALLA-->
	<style>		
       
	body, html {
		width: 100%;
		height: 100%;
		overflow: hidden;
		margin: 0;    
	}
	.contenedor {		/*width: 100%;
		height: 100%;*/
		width: 100%;
		height: 100%;
		overflow: hidden;	
		box-sizing: border-box;		
		/*max-width: 1000px;*/
		position:relative;
	}
	.menusuperior {
		border: 2px solid #269DFF;
		border-left-width: 0px;
		border-right-width: 0px;
		border-top-width: 0px;
		width: 100%;
		height: 3%;
		position: relative;
		box-sizing: border-box;
		background: black;
	}
	
	.submenu_superior
{
		position: relative;		
		border-width: 2px;
		border: 1px solid black;		
		background: black;
	    width: 150px;
		height: 80px;		
		box-sizing: border-box;		
		font-family: arial;			
		color: white;
		font-weight: bold;
		text-align: center;		
		float: left;
		width: 15%;
		height: 100%;
	}
	
	.submenu_superior_sel {
		position: relative;		
		border-width: 2px;
		border: 0px solid black;		
		background: #49A2FF;
	    width: 150px;
		height: 50%;		
		box-sizing: border-box;		
		font-family: arial;			
		color: white;
		font-weight: bold;
		text-align: center;		
		float: left;
		width: 15%;
		height: 100%;
	}	

	.menulateral {				
		width: 15%;
		height: 100%;
		float: left;
		background: black;				
		overflow: hidden;
		font-size:18px;
		box-sizing: border-box;		
	}
	
	.submenu_lateral_encabezado {		
		font-family: arial;
		font-size:10px;
		color: white;
		padding: 10px;
		text-align: center;	
		border: 3px solid #269DFF;
		border-bottom-width: 2px;
		border-left-width: 0px;
		border-right-width: 0px;
		border-top-width: 0px;	
	}
	.submenu_lateral {		
		font-family: arial;
		font-size: 15px;
		color: white;
		padding: 10px;	
		border: 3px dashed #269DFF;
		border-bottom-width: 2px;
		border-left-width: 0px;
		border-right-width: 0px;
		border-top-width: 0px;	
	}
	.submenu_lateral_encabezado img {
		width: 48%;
		height: 5%;
		alignment-adjust:central;
		
	}
	.main {
		margin-left: 5px;
		margin-right: 15px;
		position: relative;		
		padding: 5px;
		width: 80%;
		height: 100%;
		float: left;		
		background: white;		
		overflow: hidden;
		box-sizing: border-box;
		overflow:hidden;
		
	
	.buscar {
		margin-top: 15px;
		margin-bottom: 15px;
		font-family: impact;
		border-radius: 5px;
		border: 2px;
		border-color:  #269DFF;;
		width: 50%;
		height: 30px;
		box-sizing: border-box;
		padding: 5px;
		background: #fff;
		margin-left: 5px;
	}
	
	.encabezado{
		width: 48%;
		height: 5%;
		alignment-adjust:central;
		
	}
		
</style>
    
</head>
<body>

<div class="contenedor">
        <div class="encabezado"> <!--Logo prinicipal de la página-->
		<img src="images/Head_SAC.png" width="300" height="70">
		</div>
		<div class="menusuperior">
      
			<div class="submenu_superior_sel">				
					Menu 1							
			</div>
			<div class="submenu_superior">				
					Menu 2							
			</div>
			<div class="submenu_superior">				
					Menu 3							
			</div>
			<div class="submenu_superior">				
					Menu 4							
			</div>					
		</div>

		<div class="menulateral">			
			<!--<div class="submenu_lateral_encabezado">  Logo prinicipal del menu lateral
                <img src="imagenes/CABEZA_SIP.png"/>
			</div>-->
			<div class="submenu_lateral">
				Menu 
			</div>
			<div class="submenu_lateral">
				Actualizar Bit&aacute;cora
			</div>
			<div class="submenu_lateral">
				Detalle de Avance
			</div>
            <div class="submenu_lateral">
				Auditores Online
			</div>
            <div class="submenu_lateral">
			<A href="Bitacora.php">	BITACORA </A>
			</div>
			
		</div>

  <div class="main">
         
            
            </div>
	</div>
  
  

	
</body>
</html>