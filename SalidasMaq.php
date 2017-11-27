<?php 
require_once('Connections/sac2.php'); 
//require_once('Connections/biometrico.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
//-------------------------------VALIDAMOS QUE ESTE LOGEADO-----------------------------
if (!isset($_SESSION)) {
  session_start();
}

// print_r($_POST);
// print_r($_GET);

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

$buscarbase = $_SESSION['S_Base'];
$fecha_filtro = date("Y-m-d");

	if ($buscarbase == "EN01','EN01','USA01','USA01','OC01','OC01','PA01','PA01','ZI01','ZI01','JA01','TE01','TO01','TO01"){
		$mostrar="Todas";
	}else{
		$mostrar = $_SESSION['S_Lugar'];
	}

//---------------------------------------------------------FILTRO-----------------------------------	
if (isset($_REQUEST['filtrar'])) {

	$fecha_filtro = $_POST['fecha_filtro'];
	$buscarbase = $_POST['buscarbase'];
	
	if ($buscarbase == "EN01','EN01','USA01','USA01','OC01','OC01','PA01','PA01','ZI01','ZI01','JA01','TE01','TO01','TO01"){
		$mostrar="Todas";
	}else{
		$mostrar = $buscarbase;
	}
	
$sql = "SELECT DISTINCT(ID_SALIDAMAQ), SUM(DATEPART(HOUR,HR_INI)) AS HR_INI, FECHA, ESTATUS, BASE FROM SalidasMaq WHERE BASE in ('".$buscarbase."') and fecha ='".$fecha_filtro."' GROUP BY ID_SALIDAMAQ, FECHA, ESTATUS, BASE";
 //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
  
}else{
	
	$sql = "SELECT DISTINCT(ID_SALIDAMAQ), SUM(DATEPART(HOUR,HR_INI)) AS HR_INI, BASE, FECHA, ESTATUS FROM SalidasMaq WHERE BASE IN ('".$_SESSION['S_Base']."') AND FECHA='".$fecha_filtro."' GROUP BY ID_SALIDAMAQ, FECHA, ESTATUS, BASE";
 	//echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
}
/****************************************************GRID************************************************/
$x=0;
$filas = array();
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
<html lang="en">
<head>
	<meta charset="UTF-8">	
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title id='Description'>Salidas</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />    
	<link rel="stylesheet" href="CssLocal/Menus.css"><!--Necesario para Menu 1-->   
	<link rel="stylesheet" href="CssLocal/Menu1.css"><!--Necesario para Menu 1-->
	
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtooltip.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
	
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
    <!--CSS AJUSTE PANTALLA-->
	<style>		
       
	body, html {
		width: 100%;
		height: 100%;
		/*overflow: hidden;*/
		   
	}
	.contenedor {
		width: 100%;
		height: 100%;
		/*overflow: hidden;*/		
		box-sizing: border-box;
		padding: 0px;
		margin: 0 auto;		
		font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;
		float: left;
	}
	
	.encabezado{
		width: 48%;
		height: 5%;
		alignment-adjust:central;
		
	}

	.titulo{
		padding: 5px;
		border:#000000;
		background: #d8d8d8;
	}
	.titulo1 {
		font-family: arial;
		font-size: 10px;
		padding: 10px;	
		border: 3px solid #FF8C00;
		border-bottom-width: 3px;
		border-left-width: 0px;
		border-right-width: 0px;
		border-top-width: 0px;
		width: 55%;
		height: 50px;
		float: left;
		box-sizing: border-box;	
	}
	.titulo2 {
		font-family: arial;
		font-size: 10px;
		text-align: center;		
		padding: 10px;	
		border: 3px solid #FF8C00;
		border-bottom-width: 3px;
		border-left-width: 0px;
		border-right-width: 0px;
		border-top-width: 0px;
		width: 15%;
		height: 50px;
		float: left;
		box-sizing: border-box;	
	}

	.titulo3 {
		font-family: arial;
		font-size: 10px;
		text-align: right;
		padding: 10px;	
		border: 3px solid #FF8C00;
		border-bottom-width: 3px;
		border-left-width: 0px;
		border-right-width: 0px;
		border-top-width: 0px;
		width: 15%;		
		height: 50px;
		float: left;
		box-sizing: border-box;	
	}
	
	#buscar {		
		margin: 0 auto;
		padding: 5px;
		font-size: 1em;
		width: 500px;
		height: 50px;	
	}

	#main {
		margin-left: 5px;
		margin-right: 5px;		
		padding: 5px;
		width: 49%;
		height: 100%;
		float: left;			
		box-sizing: border-box;		
		/*overflow: hidden;*/			
	}	

	#almacenSac{
		margin-right: 2%;
		float: left;
		padding-top: 5px;
		width: 35%;
		height: 70%;
		border: 2px dashed #49A2FF;
		border-radius: 10px;
		box-sizing: border-box;
		overflow-y: scroll;
		/*overflow-x: hidden;*/
	}

	#art{
		padding: 5px;
		background: #d8d8d8;
		width: 15%;
		float: left;			
	}

	#descripcion{
		padding: 5px;
		background: #d8d8d8;				
		width: 55%;
		float: left;		
	}
	#cantidad{
		padding: 5px;
		background: #d8d8d8;				
		width: 15%;
		float: left;
		text-align: center;
	}
	#importe{
		padding: 5px;
		background: #d8d8d8;				
		width: 15%;
		float: left;
		text-align: center;
	}			
	.total {				
		border-radius: 10px;
		margin-top: 10px;
		margin-left: 5px;
		margin-right: 15px;
		position: relative;				
		background: black;
		font-family: arial;
		font-size: 23px;
		font-weight: bold;
		color: white;
		padding-top: 30px;
		padding-left: 15px;
		padding-right: 15px;				
		width: 35%;
		height: 10%;
		float: left;					
		/*overflow: hidden;*/
		box-sizing: border-box;
		cursor:pointer;
	}		
	.total1 {				
		border-radius: 10px;
		margin-top: 5px;
		margin-left: 5px;
		margin-right: 15px;
		position: relative;				
		background: black;
		font-family: arial;
		font-size: 23px;
		font-weight: bold;
		color: white;
		padding-top: 15px;
		padding-left: 15px;
		padding-right: 15px;				
		width: 35%;
		height: 5%;
		float: left;					
		/*overflow: hidden;*/
		box-sizing: border-box;
		cursor:not-allowed;
	}
	.money2 {
		position: relative;
		float: right;		
		text-align: right;
		font-family: arial;
		font-size: 23px;
		font-weight: bold;		
	}
	input.inputstyle{
		font-family: Arial; 
		font-size: 3pt; 
		background-color: #00FF00;
	}
</style>
<script type="text/javascript">
$(document).ready(function () {
			var cont=0;
			var total=0;           
            var arrayconcep=[];
            var arrayprecio=[];

			
			//EFECTOS EN EL MENU LATERAL
            $("#sub1").mouseenter(function(e){
				$("#sub1").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});  
			});
			$("#sub1").mouseleave(function(e){
				$("#sub1").css({"background": "#2c2c2c"});			  
			});
			$("#sub2").mouseenter(function(e){
				$("#sub2").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub2").mouseleave(function(e){
				$("#sub2").css({"background": "#2c2c2c"});			  
			});
			$("#sub3").mouseenter(function(e){
				$("#sub3").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub3").mouseleave(function(e){
				$("#sub3").css({"background": "#2c2c2c"});			  
			});
			$("#sub4").mouseenter(function(e){
				$("#sub4").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub4").mouseleave(function(e){
				$("#sub4").css({"background": "#2c2c2c"});			  
			});
			$("#sub5").mouseenter(function(e){
				$("#sub5").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub5").mouseleave(function(e){
				$("#sub5").css({"background": "#2c2c2c"});			  
			});
			$("#sub6").mouseenter(function(e){
				$("#sub6").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub6").mouseleave(function(e){
				$("#sub6").css({"background": "#2c2c2c"});			  
			});
			$("#sub7").mouseenter(function(e){
				$("#sub7").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub7").mouseleave(function(e){
				$("#sub7").css({"background": "#2c2c2c"});			  
			});
			$("#sub8").mouseenter(function(e){
				$("#sub8").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub8").mouseleave(function(e){
				$("#sub8").css({"background": "#2c2c2c"});			  
			});
			$("#sub9").mouseenter(function(e){
				$("#sub9").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub9").mouseleave(function(e){
				$("#sub9").css({"background": "#2c2c2c"});			  
			});
			$("#sub10").mouseenter(function(e){
				$("#sub10").css({"background": "#49A2FF", "border":"1px dotted #0057b3"});			  
			});
			$("#sub10").mouseleave(function(e){
				$("#sub10").css({"background": "#2c2c2c"});			  
			});
			
				
			//GRID DE SALIDAS
			var data =  <?php echo json_encode($datos); ?>;				    
		    var source =
            {
                dataType: "json",
                dataFields: [
                    { name: 'ID_SALIDAMAQ', type: 'varchar' },
                    { name: 'HR_INI', type: 'int' },
                    { name: 'ESTATUS', type: 'varchar' },
                    { name: 'BASE', type: 'varchar' },
                    { name: 'FECHA', type: 'varchar' }   
                ],
                hierarchy:
                {
                    keyDataField: { name: 'ID_SALIDAMAQ' },
                    parentDataField: { name: 'FECHA' }
                },
                id: 'ID_SALIDAMAQ',
                localData: data
            };   
		  
		    var dataAdapter = new $.jqx.dataAdapter(source);
            // create Tree Grid            
            $("#treeGrid").jqxTreeGrid(
            {
                width: '45%',
				height: '80%',
                source: dataAdapter,
                filterable: true,
                filterMode: 'simple',
                ready: function()
                {
                    $("#treeGrid").jqxTreeGrid('expandRow', '5');
                },
                columns: [
                  { text: 'Num Salida', dataField: 'ID_SALIDAMAQ', width: '35%'},
                  { text: 'Base', dataField: 'BASE', width: '35%' },
                  { text: 'Estatus', dataField: 'ESTATUS', width: '30%'}
                ]
            });
            $('#treeGrid').on('rowSelect', function (event) {           
            	var args = event.args;
      			var row = args.row;
      			var folio =  row.ID_SALIDAMAQ;
      			
      			$("#detalle").empty();
      			$.post("consultaAvanceDiario.php", { SalidaMaq: folio }, function(data){
      				//alert(data);
					var token = data.split();
					var variable = token[0].split('*');	
					
					if (variable[1]=="ENTRADA"){
						$("#Entrada_Maq").prop("disabled",true);
					}else{
						$("#Entrada_Maq").prop("disabled",false);
					}
					$("#detalle").append(variable[0]);
					
					
					var mostrar = '<?php echo $mostrar; ?>';
			
					if (mostrar == 'Todas'){
						$("#Entrada_Maq").prop("disabled",true);
					}else{
						$("#Entrada_Maq").prop("disabled",false);
					}
			    });  
			  	
			});		
			
			var mostrar = '<?php echo $mostrar; ?>';
			
			if (mostrar == 'Todas'){
				$("#Entrada_Maq").prop("disabled",true);
			}else{
				$("#Entrada_Maq").prop("disabled",false);
			}
			
});//document.ready
function Prueba(){
	var cont = $("#contador").val();
	var folio = $("#folio_sal").val();
	
	var mostrar = '<?php echo $mostrar; ?>';
	//alert(mostrar);
	
	for (var i = 1; i <= cont; i++) {
		
		//GUARDAMOS LOS REGISTROS
		var noeco = $("#noeco" + i).val();
		var descripcion = $("#desc" + i).val();
		var hrini = $("#hrini" + i).val();
		var total = $("#total" + i).val();
		var fecha = $("#fecha" + i).val();
		//alert(total);
							
		$.post("consultaAvanceDiario.php", { GuardaEntradaMaq: '', Desc: descripcion, Noeco: noeco, Hrini: hrini, Folio2: folio, Base: mostrar, Total: total, Fecha: fecha }, function(data){
			//alert(data);
		});
	}
	location.reload();	
	
}

</script>    
</head>
<body>


	<header>
		<a href="index.php"><img class="derecha" src="images/cerrarsesion.png"></a>
		<a href="Almacen.php"><span>Insumos</span></a>
		<a href="Salidas.php"><span>Salida Insumos</span></a>
		<a href="AlmacenMaq.php"><span>Maquinaria</span></a>
		<a href="SalidasMaq.php"><span>Entrada Maq</span></a>
		<a href="AvanceDiarioPlus.php"><span>Avance Diario</span></a>
            <?php 
			if($_SESSION['S_Privilegios'] == 'ADMINISTRADOR' || $_SESSION['S_Privilegios'] == 'COORDINADOR'){
			 ?>
		<a href="Contratos.php"><span>Contratos</span></a>
		<a href="Comparativo.php"><span>Comparativa</span></a>
			<?php } ?>  
		<a href="Prorrateo.php"><span>Carga Costos MO</span></a>
		<a href="ProrrateoAct.php"><span>Prorrateo MO</span></a>
		<a href="ProrrateoMaq.php"><span>Carga Costos Maq</span></a>
		<a href="ProrrateoMaquinaria.php"><span>Prorrateo Maq</span></a> 
	</header>
<div id=="contenedor">

	<div class="menulateral">
        <div class="submenu_lateral_encabezado">
        	<span class="glyphicon glyphicon-wrench"></span> &rlm; HERRAMIENTAS
        </div>
		<a href="#" data-toggle="modal" data-target=".filtro" onClick="Limpiar()"><div class="submenu_lateral" id="sub1">
			<span class="glyphicon glyphicon-filter"></span> &rlm; Filtro 
		</div></a>
		<!--<div class="submenu_lateral" id="sub2">
			<span class="glyphicon glyphicon-user"></span> &rlm; Herramienta 2 
		</div>-->       
	</div>


	<div id="filtro">
    <center><strong>Fecha: <?php echo $fecha_filtro; ?>&rlm;&rlm;           Base: <?php echo $mostrar; ?></strong></center>
    </div>
    
    
    <div id="botones" align="right">
    <button type='button' class='btn btn-primary' id="Entrada_Maq" name="Entrada_Maq" onClick='Prueba()'>Guardar</button>
    </div>
	<br>

	<div id="grid">		
		<div id="treeGrid"></div>
	</div>
    
	<div id="detalle">
        <table width='701' style='font-family:comic sans'>
            <tr bgcolor='#CBCBCB'>
                <td width="120"><strong>No. Econ&oacute;mico</strong></td>
                <td width='278' align='center' height='20'><strong>Descripcion</strong></td>
                <td width='164' align='center'><strong>Hora Salida</strong></td>
                <td width='119' align='center'><strong>Hora Entrada</strong></td>
            </tr>
        </table>
	</div>
    
    <br>
<!-- Genera Salidas -->   
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Genarar Salidas de Almacen</h4>
      </div>
      <div class="modal-body">
        <center>&iquest;Deseas guardar una salida de almacen?</center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="Guardar">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!--DESCARGAR-->
<form id="formulario5" action="Excel_AlmacenSalidas.php" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="descargar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Salidas de Almacen</h4>
      </div>
      <div class="modal-body">
	<input type="hidden" name="fol_desc" id="fol_desc">
        <center>&iquest;Deseas descargar una salida de almacen?</center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="Descargar">Descargar</button>
      </div>
    </div>
  </div>
</div>
</form>

<!-- FILTRO -->
<form id="formulario5" action="SalidasMaq.php" method="post" enctype="multipart/form-data" >      
<div class="modal fade filtro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center>  <img src="images/filtro_header.png" height="40"> FILTRO DE INFORMACI&Oacute;N <img src="images/filtro_header1.png" height="40">  </center></h4>
      </div>
      <div class="modal-body">
      <!--CUERPO-->
     <div class="panel-group" >
    <div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Fecha
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
<table width="547" border="0">
  <tr>
    <td width="60">Seleccionar: </td>
    <td width="251"><input type="date" name="fecha_filtro" id="fecha_filtro" value="<?php echo date("Y-m-d"); ?>" class="form-control" style="width:200px" ></td>
  </tr>
</table>       
     		 </div>
    	</div>
  	</div>
  </div>
  
  <div class="panel-group" >
    <div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Base
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
<table width="547" border="0">
  <tr>
    <td width="60">Seleccionar: </td>
    <td width="251">
    	<select class="form-control" name="buscarbase" id="buscarbase" style="width:200px" >
        	<?php
			if ($_SESSION['S_Privilegios'] == 'ADMINISTRADOR'){
			?>	
			<option value="EN01">EN01</option>
			<option value="JA01">JA01</option>
			<option value="OC01">OC01</option>
			<option value="PA01">PA01</option>
			<option value="TE01">TE01</option>
			<option value="TO01">TO01</option>
			<option value="USA01">USA01</option>
			<option value="ZI01">ZI01</option>
			<option value="EN01','EN01','USA01','USA01','OC01','OC01','PA01','PA01','ZI01','ZI01','JA01','TE01','TO01','TO01">Todas</option>				
			<?php	
			}else{
			  $i=1;
			  $selected="";
			  $sql = "SELECT DISTINCT Base FROM Accesos WHERE Usuario_ID='".$_SESSION['S_UsuarioID']."'";
			  echo $sql;
			  $rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) { 
			   exit( "Error en la consulta SQL" ); 
			  }     
			  while ( odbc_fetch_row($rs) ) { 
				$plaza = odbc_result($rs, 'Base');
			   echo "<option id='".$i."'".$selected.">".$plaza."</option>";
			   $i++;
			  }//While 	
			}
	   		?>
        </select>
    </td>
  </tr>
</table>       
     		 </div>
    	</div>
  	</div>
  </div>
	<!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>        
        <button type="submit" class="btn btn-primary" name="filtrar" id="filtrar">Aceptar</button>
      </div>
    </div>
  </div>
  </div>
</div>
</form> 
<!--Fin Modal-->

</body>
</html>