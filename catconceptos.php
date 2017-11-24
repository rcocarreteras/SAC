<?php
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );

$x=0;
$filas = array();
$sql = "SELECT * FROM CatConcepto";
 //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$datos =  $filas; 
//echo json_encode($datos); 

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

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>SAC</title>    
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />  
        
    <link rel='stylesheet' href='http://s.codepen.io/assets/reset/reset.css'>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="css/styl.css">
    <link rel="shortcut icon" href="images/favicon.ICO" /> 
   	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
	<script type="text/javascript" src="jqwidgets/jqxdatatable.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtreegrid.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript">
	function logoutck() {
    var r = confirm("¿Deseas Cerrar Sesión?");
    if (r) {
       window.location.href = 'index.php' 
	   
    }
}

        $(document).ready(function () {          
            var conceptos = <?php echo json_encode($datos); ?>;
	
		    //// prepare the data
			 var source =
            {
                dataType: "json",
                dataFields: [
                    { name: 'SubCta', type: 'number' },
                    { name: 'CvPu', type: 'string' },
                    { name: 'CvCpt', type: 'number' },
                    { name: 'DesCpt', type: 'string' },
                    { name: 'Unid', type: 'string' },
                    { name: 'PunCpt', type: 'string' },
                    { name: 'CvSct', type: 'string' }                    
                ],
                hierarchy:
                {
                    keyDataField: { name: 'CvPu' },
                    parentDataField: { name: 'SubCta' }
                },
                id: 'CvPu',
                localData: conceptos
            };
      
		  
		    var dataAdapter = new $.jqx.dataAdapter(source);
            // create Tree Grid
            $("#treeGrid").jqxTreeGrid(
            {
                width: 965,
				height: 450,
                source: dataAdapter,
                filterable: true,
                filterMode: 'simple',
                ready: function()
                {
                    $("#treeGrid").jqxTreeGrid('expandRow', '5');
                },
                columns: [
                  { text: 'SubCta', dataField: 'SubCta', width: 70 },
                  { text: 'CvPu', dataField: 'CvPu', width: 60 },
                  { text: 'CvCpt', dataField: 'CvCpt', width: 50 },
                  { text: 'Descripcion', dataField: 'DesCpt', width: 520 },
                  { text: 'Unidad', dataField: 'Unid', width: 80 },
                  { text: 'PunCpt', dataField: 'PunCpt', width: 80 },
                  { text: 'CvSct', dataField: 'CvSct', width: 80 }
                 
                ]
            });
		//CONFIGURACION DE PERMISOS	
		var Privilegios = '<?php echo $_SESSION['S_Privilegios']; ?>';		
		
		 switch (Privilegios) {
			 case 'SOBRESTANTE':
				$('#asistencia').click(function () {return false;});
				$('#presupuestos').click(function () {return false;});	
				$('#contratos').click(function () {return false;});	
				$('#almacen').click(function () {return false;});	
				$('#entrada').click(function () {return false;});	
        	 break; 
		 }//Swicth			
			
        });
    </script>







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
		width: 100%;
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
<div id='cssmenu'>
<ul>
   <li class='active has-sub'><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul id="catalogos">
         <li><a href='catconceptos.php'><span>Conceptos</span></a>
         <li><a href='catempleados.php'><span>Empleados</span></a>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a>
         <li><a href='catins.php'><span>Insumos</span></a>         
       <!-- <li><a href='catgeneral.php'><span>General</span></a>     -->           
         </li>
      </ul>
   </li>
  <li><a href='#'><span>Almacen</span></a>   
     <ul id="almacen">
         <li><a href='almacen_contable.php'><span>Contable</span></a>
         <li><a href='almacen_fisico.php'><span>Fisico</span></a>                    
     </ul>
   </li>  
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul id="avance">
        <!--<li><a href='progsemanal.php'><span>Prog. Semanal</span></a>-->
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a> 			
			<?php }else{?> 
        <li><a href='AvanceDiario2.php'><span>Avance Diario</span></a>
        <?php } ?>
       <!--  <li><a href='#'><span>RUD</span></a>
         	<ul>
            	<li><a href='Maquinaria.php'><span>Maquinaria</span></a>
        		<li><a href='Insumos.php'><span>Insumos</span></a>    
              </ul>
          </li>           -->               
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
</div>
<?php }else{ ?>
<div id='cssmenu'>
<ul>
   <li class='active has-sub'><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Cat&aacute;logos</span></a>
      <ul id="catalogos">
         <li><a href='catconceptos.php'><span>Conceptos</span></a>
         <li><a href='catempleados.php'><span>Empleados</span></a>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a>
         <li><a href='catins.php'><span>Insumos</span></a>         
        <!-- <li><a href='catgeneral.php'><span>General</span></a> -->
         </li>
      </ul>
   </li>
   <li><a href='#'><span>Almacen</span></a>   
     <ul>
         <li><a href='#'><span>Contable</span></a>
         <li><a href='#'><span>Fisico</span></a>                     
     </ul>
   </li>
   <li><a href='#'><span>Avance de Obra</span></a>   
     <ul>
        <li><a href='progsemanal.php'><span>Prog. Semanal</span></a>
        <?php 
		 if($_SESSION['S_Privilegios'] == 'SOBRESTANTE' || $_SESSION['S_Privilegios'] == 'SUPERVISOR'){
			?><li><a href='AvanceDiario.php'><span>Avance Diario</span></a> 			
			<?php }else{?> 
        <li><a href='AvanceDiario2.php'><span>Avance Diario</span></a>
        <?php } ?>                   
     </ul>
   </li>
   <li><a href='#'><span>Contratos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a>
         <li><a href='#'><span>CAPEX</span></a>                     
     </ul>
   </li>     
   <li><a href='#'><span>Presupuestos</span></a>   
     <ul>
         <li><a href='#'><span>OPEX</span></a>
         <li><a href='#'><span>CAPEX</span></a>                     
     </ul>
   </li>  
   <li class='last' id="asistencia"><a href='Asistencia.php' target="_blank"><span>Asistencia</span></a>
     <ul>
         <li><a href='EntradaManual.php'><span>Entrada Manual</span></a>                   
     </ul>
 </li>
    <li><a href='Maquinaria.php'><span>Maquinaria</span></a></li>
    <li class='last'><a href='Insumos.php'><span>Insumos</span></a></li>
 
  
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="100" valign="bottom" class="btn btn-info">
       <form id="salir" action="AvanceDiario2.php" method="post" enctype="multipart/form-data" >             
          <button type="submit" class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion" > Cerrar Sesion</button> 
       </form> 
       </td>
     </tr>
   </table>
</ul>
</div>
<?php } ?>
<div class="main">
<div align="center">
<img src="images/catologo.png" width="50%" border="0" />
<h1 style="font-family:fantasy;">Catálogo de Conceptos</h1>
</div>
<br>
<br>
<center>
<div id="treeGrid"></div>
</center>
</div>
</div>
</body>
</html>