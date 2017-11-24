<?php
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );

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

$sql = "select * from CatTramos where Plaza in ('".$_SESSION['S_Plaza']."')";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }  while ( odbc_fetch_row($rs) ) { 
    $_SESSION['S_Base'] = odbc_result($rs, 'BASE');	 
}//While 

if ($_SESSION['S_Privilegios'] == 'ADMINISTRADOR' || $_SESSION['S_Privilegios'] == 'COORDINADOR'){
	$_SESSION['S_Base'] = "EN01','OC01','PA01','TE01','TO01','ZI01','JA01','USA01','LE01";
}

$x=0;
$filas = array();
$sql = "SELECT *  FROM CatMaquinaria where CvBase IN ('".$_SESSION['S_Base']."')";
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
        $(document).ready(function () {          
            var conceptos = <?php echo json_encode($datos); ?>;
	

		    //// prepare the data
			 var source =
            {
                dataType: "json",
                dataFields: [
                    
                    { name: 'CvBase', type: 'char' },
                    { name: 'NoEco', type: 'char' },
					{ name: 'Descrip', type: 'varchar' },
                    { name: 'CvTipMaq', type: 'char' }                
                ],
                hierarchy:
                {
                    keyDataField: { name: 'MaqId' },
                    parentDataField: { name: 'MaqId' }
                },
                id: 'MaqId',
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
                  { text: 'CvBase', dataField: 'CvBase', width: 130 },
                  { text: 'NoEco', dataField: 'NoEco', width: 180 },
                  { text: 'Descripción', dataField: 'Descrip', width: 480 },
                  { text: 'CvTipMaq', dataField: 'CvTipMaq', width: 150 }
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

</head>
<body>
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
<!--   <li><a href='#'><span>Contratos</span></a>   
     <ul id="contratos">
         <li><a href='#'><span>OPEX</span></a>
         <li><a href='#'><span>CAPEX</span></a>                     
     </ul>
   </li>     
   <li><a href='#'><span>Presupuestos</span></a>   
     <ul id="presupuestos">
         <li><a href='#'><span>OPEX</span></a>
         <li><a href='#'><span>CAPEX</span></a>                     
     </ul>
   </li>   
 <li class='last' id="ayuda"><a href='ayuda.php'><span>Ayuda</span></a></li>
 
 <li class='last' id="asistencia"><a href='Asistencia.php' target="_blank"><span>Asistencia</span></a>
     <ul>
         <li id="entrada"><a href='EntradaManual.php'><span>Entrada Manual</span></a>                   
     </ul>
 </li>  -->
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
         <li><a href='almacen_contable.php'><span>Contable</span></a>
         <li><a href='almacen_fisico.php'><span>Fisico</span></a>                     
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
<div align="center">
<img src="images/maquinaria.png" border="0" />
<h1 style="font-family:fantasy;">Catálogo de Maquinaria</h1>
</div>
<br>
<br>
<center>
<div id="treeGrid"></div>
 </center>


</body>
</html>