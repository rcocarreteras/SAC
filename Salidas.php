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
	
$sql = "SELECT DISTINCT(ID_SALIDA), SUM(CANTIDAD) AS CANTIDAD, SUM(IMPORTE) AS IMPORTE, FECHA, ESTATUS FROM Salidas WHERE BASE in ('".$buscarbase."') and fecha ='".$fecha_filtro."' GROUP BY ID_SALIDA, FECHA, ESTATUS";
 //echo $sql;
  	$rs = odbc_exec( $conn, $sql );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
  
}else{
	
	$sql = "SELECT DISTINCT(ID_SALIDA), SUM(CANTIDAD) AS CANTIDAD, SUM(IMPORTE) AS IMPORTE, FECHA, ESTATUS FROM Salidas WHERE BASE IN ('".$_SESSION['S_Base']."') AND FECHA='".$fecha_filtro."' GROUP BY ID_SALIDA, FECHA, ESTATUS";
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
       
	/*body, html {
		width: 100%;
		height: 100%;
		overflow: hidden;
		   
	}*/
	#contenedor {
		width: 100%;
		height: 100%;
		overflow: hidden;		
		box-sizing: border-box;
		padding: 0px;
		margin: 0;		
		font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;
		float: left;
	}
	#grid{		
		float: left;
		margin: 0;
		padding: 5px;
		width: 35%;	
		height: 75%;			
	}
	#detalle{		
		float: left;
		margin: 0;				
		width: 52%;
		height: 75%;
		border: 2px dashed #49A2FF;
	 	overflow-y: scroll;				
	}

	#filtro{
		float: left;
		margin: 0;
		padding: 10px;
		width: 35%;
		height: 8%;			
	}	
	#botones{
		float: left;
		margin: 0;	
		padding: 10px;			
		width: 52%;
		height: 8%;
	}

	//CLASES 
	.boton{
		float: left;		
	}
	

/*@media screen and (min-width: 800px) and (max-width: 1024px) {*/
	@media screen and (min-width: 800px) and (max-width: 1366px) {
		body{
			/*background: black;*/
		}
		span{			
			font-size: 80%;
			font-weight:bold; 
		}				
		.jqx-widget { 			
			font-weight:bold;
		}
		
    	/*.jqx-grid-cell{//Celdas del Grid
			font-size:8pt;
			
		}
		.jqx-grid-column-header {//Encabezados
	    	font-size:20px;
	    	font-weight:bold;
		}*/
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
                    { name: 'ID_SALIDA', type: 'varchar' },
                    { name: 'CANTIDAD', type: 'int' },
                    { name: 'IMPORTE', type: 'varchar' },
                    { name: 'ESTATUS', type: 'varchar' },
                    { name: 'FECHA', type: 'varchar' }   
                ],
                hierarchy:
                {
                    keyDataField: { name: 'ID_SALIDA' },
                    parentDataField: { name: 'FECHA' }
                },
                id: 'ID_SALIDA',
                localData: data
            };
            // var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties) {    
            // 	//alert(columnfield);
            // 	switch	(columnfield){
            // 		case 'IMPORTE':     
            // 			var importe = value.priceFormat({clearPrefix: true});        			           		
            // 			return '<span>$' + importe + '</span>'            			
            // 			break;

            // 		defaul:
            // 		return '<span>' + value + '</span>'
            // 	}                 
            // }      
		  
		    var dataAdapter = new $.jqx.dataAdapter(source);
            // create Tree Grid            
            $("#treeGrid").jqxTreeGrid(
            {
                width: '100%',
				height: '100%',
                source: dataAdapter,
                filterable: true,
                filterMode: 'simple',
                ready: function()
                {
                    $("#treeGrid").jqxTreeGrid('expandRow', '5');
                },
                columns: [
                  { text: 'Num Salida', dataField: 'ID_SALIDA', width: '30%'},
                  { text: 'Articulos', dataField: 'CANTIDAD', cellsformat: 'f2', cellsAlign: 'right', width: '20%' },
                  { text: 'Importe', dataField: 'IMPORTE', cellsformat: 'c2', cellsAlign: 'right', width: '30%'},
                  { text: 'Estatus', dataField: 'ESTATUS', width: '20%'}
                  //{ text: 'Fecha', dataField: 'FECHA', width: '10%'}
                ]
            });
            $('#treeGrid').on('rowSelect', function (event) {           
            	var args = event.args;
      			var row = args.row;
      			var folio =  row.ID_SALIDA;
      			
      			$("#detalle").empty();
      			$.post("consultaAvanceDiario.php", { Salida: folio }, function(data){
      				//alert(data);
					$("#detalle").append(data);
			    });  
			  	
			});		
			
			var mostrar = '<?php echo $mostrar; ?>';
			
			if (mostrar == 'Todas'){
				$("#Entrada").prop("disabled",true);
			}else{
				$("#Entrada").prop("disabled",false);
			}
  
           
			//GUARDAR
		function Guardar(){			
			if(cont == 0){	
				alert("No hay articulos seleccionados");//$("#jqxNotification4").jqxNotification("open");	
			}else{					
				//SE SACA EL ULTIMO FOLIO, SE LE SUMA 1 Y SE GUARDA CON ESE NÚMERO
				$.post("consultaAvanceDiario.php", { FolioSalida: '' }, function(data){
					alert(data);
					var folio = data;	
					$("#fol_desc").val(folio);
					for (var i = 0; i < cont; i++) {
						//GUARDAMOS LOS ARTICULOS SELECCIONADOS	
						$.post("consultaAvanceDiario.php", { GuardaSalida: '', Folio1: folio, concepto: arrayconcep[i], cantidad: '1', importe: arrayprecio[i]}, function(data2){	
						alert(data2);
						});
						if (i == cont -1){
							alert('Registro Guardado');
							//$("#notificationContent3").html('Registro Guardado <br> Folio: '+data);
							//$("#jqxNotification3").jqxNotification("open");
							for (var j = 0; j <= cont; j++) {
								$("#art" + j  ).remove();
								$("#descripcion" + j  ).remove();
								$("#cantidad" + j  ).remove();
								$("#importe" + j  ).remove();
							}
							//INICIALIZAMOS VARIABLES GLOBALES
							total=0;					
							cont=0;
							arrayconcep=[];
							arrayprecio=[];
							$("#total").text('$'+ total + '.00');	
						}	
					}	   	
				});	
			}//Else	
		}
		$("#generaSalida").click(function(){
				$("#Modal").modal('show');							
		})
		$("#Guardar").click(function(){
				Guardar();	
		});


			
});//document.ready
function Prueba(){
	var cont = $("#contador").val();
	var folio = $("#folio_sal").val();
	
	var mostrar = '<?php echo $mostrar; ?>';
	//alert(mostrar);
	
	for (var i = 1; i <= cont; i++) {
		
		//GUARDAMOS LOS REGISTROS
		var unidad = $("#unidad" + i).val();
		var descripcion = $("#desc" + i).val();
		var precio = $("#precio" + i).val();
		var entrega = $("#entrega" + i).val();
		var cantidad = $("#cantidad" + i).val();
		var importe = $("#importe" + i).val();
		var fecha = $("#fecha" + i).val();
		
		//alert(fecha);
							
		$.post("consultaAvanceDiario.php", { GuardaEntrada: '', Unidad: unidad, Desc: descripcion, Precio: precio, Entrega: entrega, Folio2: folio, Base: mostrar, Cantidad: cantidad, Importe: importe, Fecha: fecha  }, function(data){
			//alert(data);
		});
	}
	location.reload();	
	
}

</script>    
</head>
<body>

<div id=="contenedor">
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
    <center><strong>Fecha: <?php echo $fecha_filtro; ?>&rlm;&rlm;Base: <?php echo $mostrar; ?></strong></center>
    </div>
    
    
    <div id="botones" align="right">
    <button type='button' class='btn btn-primary' id='Entrada' onClick='Prueba()'>Guardar</button>
    </div>
	<br>

	<div id="grid">		
		<div id="treeGrid"></div>
	</div>
    
	<div id="detalle">
		<table width='770' style='font-family:comic sans'>
			<tr bgcolor='#CBCBCB'>
				<td width='289' align='center' height='20'><input type='hidden' name='folio_sal' id='folio_sal' value='".$valor."'><strong>Descripcion</strong></td>
				<td width='52'><strong>Unidad</strong></td><td width='113' align='center'><strong>P.U.</strong></td>
				<td width='71'><strong>Cantidad</strong></td>
				<td width='133' align='center'><strong>Importe</strong></td>
				<td width='84'><strong>Entrega</strong></td>
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
<form id="formulario5" action="Salidas.php" method="post" enctype="multipart/form-data" >      
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