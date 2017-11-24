<?php
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );

$x=0;
$filas = array();
$sql = "SELECT * FROM Inventario where TIPO_INVENTARIO='fisico'";
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

//require_once('Connections/conexion.php'); 
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
                    { name: 'INVENTARIO_ID', type: 'int' },
                    { name: 'EMPRESA', type: 'varchar' },
                    { name: 'BASE', type: 'varchar' },
                    { name: 'ALMACEN', type: 'varchar' },
                    { name: 'ARTICULO', type: 'varchar' },
                    { name: 'DESCRIPCION', type: 'varchar' },
                    { name: 'UOM', type: 'varchar' },
					{ name: 'PU', type: 'varchar' },
                    { name: 'EXISTENCIA', type: 'varchar' },
                    { name: 'OBSERVACIONES', type: 'varchar' }                   
                ],
                hierarchy:
                {
                    keyDataField: { name: 'INVENTARIO_ID' },
                    parentDataField: { name: 'EMPRESA' }
                },
                id: 'INVENTARIO_ID',
                localData: conceptos
            };
      
		  
		    var dataAdapter = new $.jqx.dataAdapter(source);
            // create Tree Grid
            $("#treeGrid").jqxTreeGrid(
            {
                width: 1120,
				height: 450,
                source: dataAdapter,
                filterable: true,
                filterMode: 'simple',
                ready: function()
                {
                    $("#treeGrid").jqxTreeGrid('expandRow', '5');
                },
                columns: [
                  { text: 'ID', dataField: 'INVENTARIO_ID', width: 50 },
                  { text: 'Empresa', dataField: 'EMPRESA', width: 70 },
                  { text: 'Base', dataField: 'BASE', width: 65 },
                  { text: 'Articulo', dataField: 'ALMACEN', width: 82 },
                  { text: 'Descripcion', dataField: 'ARTICULO', width: 390 },
                  { text: 'UOM', dataField: 'DESCRIPCION', width: 90 },
				  { text: 'PU', dataField: 'UOM', width: 65 },
                  { text: 'Existencia', dataField: 'PU', width: 92 },
                  { text: 'Observaciones', dataField: 'OBSERVACIONES', width: 200 }
                 
                ]
            });
        });
    </script>

</head>
<body>
<div id='cssmenu'>
<ul>
   <li class='active has-sub'><a href='Inicio.php'><span>Inicio</span></a></li>
   <li><a href='#'><span>Catálogos</span></a>
      <ul>
         <li><a href='catconceptos.php'><span>Conceptos</span></a>
         <li><a href='catempleados.php'><span>Empleados</span></a>
         <li><a href='catmaquinaria.php'><span>Maquinaria</span></a>
         <li><a href='catins.php'><span>Insumos</span></a>         
         <!-- <li><a href='catgeneral.php'><span>General</span></a>     -->           
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
         <li><a href='avancediario.php'><span>Avance Diario</span></a>
         <!--  <li><a href='#'><span>RUD</span></a> 
         	<ul>
            	<li><a href='Maquinaria.php'><span>Maquinaria</span></a>
        		<li><a href='Insumos.php'><span>Insumos</span></a>    
              </ul>
          </li>   -->                      
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
 
  
     <table width="500" border="0" align="right" style="'Oxygen Mono', Tahoma, Arial, sans-serif; font-size:15px; color: #58D3F7">
     <tr>
       <td width="150" height="30" align="right">Bienvenid@ :</td>
       <td width="200" align="center"><?php echo $_SESSION['S_Usuario']; ?></td>
       <td width="100" valign="bottom" class="btn btn-info">        
       <!--- <button  class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion">Cerrar Sesion</button> --->
         
       <button  class="btn btn-info" border ="0" id="cerrar_sesion" name="cerrar_sesion" onclick='logoutck();' value='LOGOUT'> Cerrar Sesion</button> 
       </td>
     </tr>
   </table>
</ul>
</div>
<div align="center">
<img src="images/fisico.png" width="1049" border="0" />
<h1 style="font-family:fantasy;">Almac&eacute;n F&iacute;sico </h1>
</div>
<br>
<br>
<center>
<div id="treeGrid"></div>
 </center>


</body>
</html>