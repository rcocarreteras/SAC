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
  $_SESSION['S_Lugar']  = odbc_result($rs, 'PLAZA');    
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

//CARGAMOS LOS ACCESOS EN VARIABLES DE SESION
$i=0;
$sql = "SELECT DISTINCT (BASE) FROM Accesos WHERE Usuario_ID='".$_SESSION['S_UsuarioID']."'";
//echo $sql;
$rs = odbc_exec( $conn, $sql );
if ( !$rs ) { 
	exit( "Error en la consulta SQL" ); 
}     
while ( odbc_fetch_row($rs) ) {
	if ($i == 0){ 
		$_SESSION['S_Lugar'] = odbc_result($rs, 'BASE');
	}else{
		$_SESSION['S_Lugar'] .= ",". odbc_result($rs, 'BASE');
	}
	$i++;
}//While 

//echo $_SESSION['S_Plaza'];	 
?>

<!DOCTYPE html>
<html lang="en"><head>
    <title id='Description'>Entrada Manual</title>
        
    <link rel='stylesheet' href='http://s.codepen.io/assets/reset/reset.css'>
    <link href="css/bootstrap.min.css" rel="stylesheet" />  
    <link rel="shortcut icon" href="images/favicon.ICO" /> 
    <link rel="stylesheet" href="css/styl.css">
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script> 
    <script type="text/javascript" src="scripts/demos.js"></script>    
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="script.js"></script>    
	
	  
   	<style>		
       
	body, html {
		width: 100%;
		height: 100%;
		overflow: hidden;
		margin: 0;
		background: url(images/bg_ini.jpg); 
		   background-size: cover;
		   background-position:center;
    	   -moz-background-size: cover;
           -webkit-background-size: cover;
	       -o-background-size: cover;    
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
<?php if ($_SESSION['S_Privilegios'] == 'SOBRESTANTE'){ ?>
<div class="menusuperior">
<ul>
   <li class='active has-sub'><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul>
         <li><a href='catconceptos.php'><span>Conceptos</span></a></li>
         <li><a href='catempleados.php'><span>Empleados</span></a></li>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a></li>
         <li><a href='catins.php'><span>Insumos</span></a></li>
      </ul>
   </li>
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul>
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a> </li>			
			<?php }else{?> 
        <li><a href='AvanceDiario1.php'><span>Avance Diario</span></a></li>
        <?php } ?>              
     </ul>
   </li>
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="100" valign="bottom" class="btn btn-info">  
       <form id="salir" action="avanceDiario.php" method="post" enctype="multipart/form-data" >      
       <button  class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion">Cerrar Sesion</button> 
       </form>   
       </td>
     </tr>
   </table>
</ul>

<?php }else{ ?>

<div id='cssmenu'>
<ul>
   <li class='active has-sub'><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul>
         <li><a href='catconceptos.php'><span>Conceptos</span></a></li>
         <li><a href='catempleados.php'><span>Empleados</span></a></li>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a></li>
         <li><a href='catins.php'><span>Insumos</span></a></li>
      </ul>
   </li>
   <li><a href='almacen.php'><span>Almacen</span></a>   
     <ul>
         <li><a href='almacen_contable.php'><span>Contable</span></a></li>
         <li><a href='almacen_fisico.php'><span>Fisico</span></a></li>
     </ul>
   </li>
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul>
        <li><a href='progsemanal.php'><span>Prog. Semanal</span></a></li>
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE' || $_SESSION['S_Privilegios'] == 'SUPERVISOR' ||  $_SESSION['S_Privilegios'] == 'JEFE DE TRAMO'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a></li>			
			<?php }else{?> 
        <li><a href='AvanceDiario1.php'><span>Avance Diario</span></a></li>
        <?php } ?>                   
     </ul>
   </li>
   <li><a href='#'><span>Contratos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a></li>
         <li><a href='#'><span>CAPEX</span></a></li>                     
     </ul>
   </li>     
   <li><a href='#'><span>Presupuestos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a>
         	<ul>
            	<a href='Prorrateo.php'><span>PRORRATEOS</span></a>
            </ul> 
          </li>  
         <li><a href='#'><span>CAPEX</span></a></li>                     
     </ul>
   </li>  
   <li class='last' id="asistencia"><a href='Asistencia.php' target="_blank"><span>Asistencia</span></a>
     <ul>
         <li><a href='EntradaManual.php'><span>Entrada Manual</span></a> </li>                  
     </ul>
 </li>
    <li><a href='Maquinaria.php'><span>Maquinaria</span></a></li>
    <li class='last'><a href='Insumos.php'><span>Insumos</span></a></li>
 
  
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="100" valign="bottom" class="btn btn-info">
       <form id="salir" action="AvanceDiario1.php" method="post" enctype="multipart/form-data" >             
          <button type="submit" class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion" > Cerrar Sesion</button> 
       </form> 
       </td>
     </tr>
   </table>
</ul>

<?php } ?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal</h4>
      </div>
      <div class="modal-body">
        <!--CUERPO-->
        <!--FIN-->
      </div>
      <div class="modal-footer">       
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>        
        <button type="submit" class="btn btn-primary" name="guardaTomas" id="guardaTomas">Guardar</button>
      </div>
    </div>
  </div>
</div></div></div>

<!--Modal-->
</div></div>
</body>
</html>
