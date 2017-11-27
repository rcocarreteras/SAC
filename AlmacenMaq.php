<?php 
require_once('Connections/sac2.php'); 
//require_once('Connections/biometrico.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
//-------------------------------VALIDAMOS QUE ESTE LOGEADO-----------------------------
if (!isset($_SESSION)) {
  session_start();
}

//print_r($_POST);
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
if ($_SESSION['S_Base'] == "EN01','EN01','USA01','USA01','OC01','OC01','PA01','PA01','ZI01','ZI01','JA01','TE01','TO01','TO01"){
		$mostrar="Todas";
}else{
	$mostrar = $_SESSION['S_Lugar'];
}
$salida_dia = '';
$base_salida = '';
//---------------------------------------------------------FILTRO-----------------------------------
$buscarbase = $_SESSION['S_Base'];
 	
if (isset($_REQUEST['filtrar'])) {

	$buscarbase = $_POST['buscarbase'];
	
	
if ($buscarbase == "EN01','EN01','USA01','USA01','OC01','OC01','PA01','PA01','ZI01','ZI01','JA01','TE01','TO01','TO01"){
		$mostrar="Todas";
	}else{
		$mostrar = $buscarbase;
	}
  
  	$sql1 = "select * from CatMaquinaria where CvBase in ('".$buscarbase."') order by Descrip";
	$sql2 = "select * from SalidasMaq where Base in ('".$buscarbase."') and FECHA = '".date("Y-m-d")."'";
}else{
	
	$sql1 = "select * from CatMaquinaria where CvBase in ('".$_SESSION['S_Base']."') order by Descrip";
	$sql2 = "select * from SalidasMaq where Base in ('".$_SESSION['S_Base']."') and FECHA = '".date("Y-m-d")."'";	
}
/***********************************BUSCAR SALIDAS DEL DÍA**********************************************/

if ($mostrar != 'Todas'){
    //echo $sql2;
  	$rs = odbc_exec( $conn, $sql2 );
   	if ( !$rs ) { 
  	    exit( "Error en la consulta SQL" ); 
  	} 	
	while ( odbc_fetch_row($rs) ) {  
		$salida_dia = odbc_result($rs, 'ID_SALIDAMAQ');
		$base_salida = odbc_result($rs, 'BASE');
	}
}else{
	$base_salida = 'Todas';
}

/*************************************************GRID*************************************************/
$x=0;
$filas = array();
  //echo $sql1;
  $rs = odbc_exec( $conn, $sql1 );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {  
	 $filas[$x] = array_map('utf8_encode',$row);    
	 $x++;    
 }//While
$datos =  $filas; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title id='Description'>Almacén 2</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />    
	<link rel="stylesheet" href="CssLocal/Menus.css"><!--Necesario para Menu 1--> 
	<link rel="stylesheet" href="CssLocal/Menu1.css"><!--Necesario para Menu 1-->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtooltip.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
	<script type="text/javascript" src="scripts/menu1.js"></script><!--Necesario para Menu 1-->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>		
	<script type="text/javascript" src="js/jquery.bpopup-0.11.0.min.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdatatable.js"></script>
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
            var arraymaq=[];
			
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
			
			//CREAMOS EL TAB			
            $('#tabsWidget').jqxTabs({ width: '100%', height: '80%', position: 'top'});
            $('#tabsWidget').jqxTabs('focus');
			
			
			var mostrar = '<?php echo $mostrar; ?>';
			
			//Bloquear si no se ha filtrado a una sola plaza
			if (mostrar == 'Todas'){
				$("#generaSalida").hide();
				$("#buscarArt").attr("disabled",true);
				$("#buscarArt").attr("placeholder","Filtra una base");
			}else{
				$("#generaSalida").show();
				$("#buscarArt").attr("disabled",false);
			}
		
		
			//GRID	
			var data =  <?php echo json_encode($datos); ?>;				    
		    var source =
            {
                datafields: [
					{ name: 'CvTipMaq', type: 'nchar' },
					{ name: 'NoEco', type: 'nchar' },
					{ name: 'Descrip', type: 'nvarchar' },
					{ name: 'CvBase', type: 'nvarchar' }													
                ],
                localdata: data 
            };
			//var bandera = false;
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#almacen").jqxGrid(
            {
                width: '100%',
				height: '90%',
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'No Economico', dataField: 'NoEco', width: '25%' },
				  { text: 'Descripcion', dataField: 'Descrip', width: '45%' },
				  { text: 'Tipo', dataField: 'CvTipMaq', cellsAlign: 'center', width: '10%' },
                ]
            });
            $("#buscarArt").keypress(function(e){
            	if(e.which == 13){
            		var num = $("#buscarArt").val();
					//alert(articulo);
				
					$("#buscarArt").val('');		
					var base = '<?php echo $mostrar; ?>';			
					$.post("consultaAvanceDiario.php", { noeco: num, Base: base }, function(data){
						var consecutivo = $("#consecutivo").val();
						if (data == "Error"){
							alert("Ya existe una Salida de esta maquinaria");
						}else{
							
							if (data == ""){
								alert('Vehiculo/Maquinaria no encontrada');
									
							}else{			
							//alert(cont);
								arraymaq[cont] = data;
								
								cont ++;	
								consecutivo++;						
								$("#almacenSac").append("<div class='titulo2' id='art"+ cont + "'>"+consecutivo+"</div><div class='titulo1'><input type='hidden' name='descripcion1"+ cont +"' id='descripcion1"+ cont +"' value='"+data+"'>"+data+"</div>");
								$("#consecutivo").val(consecutivo);
							}
						}
			     	});
            	}//Si presiona Enter
            });
				//GUARDAR
		function Guardar(){				
			if(cont == 0){	
				alert("No hay maquinria seleccionada");//$("#jqxNotification4").jqxNotification("open");	
			}else{					
				//SE SACA EL ULTIMO FOLIO, SE LE SUMA 1 Y SE GUARDA CON ESE NÚMERO
				var base = '<?php echo $mostrar; ?>';
				$.post("consultaAvanceDiario.php", { FolioSalidaMaq: '', Base: base }, function(data){
					//alert(cont);
					var folio = data;	
					
					//SE GUARDA LA INFORMACIÓN DEL ALMACEN
					for (var i = 0; i < cont; i++) {
						//alert(arrayconcep[i]);
						$.post("consultaAvanceDiario.php", { GuardaSalidaMaq: '', FolioSa: folio, Base: base, Maquinaria: arraymaq[i] }, function(data2){
						//alert(data2);	

						});
						
						//SE LIMPIAN LOS CAMPOS
						if (i == cont -1){
							alert('Registro Guardado');
							//$("#notificationContent3").html('Registro Guardado <br> Folio: '+data);
							//$("#jqxNotification3").jqxNotification("open");
							for (var j = 0; j <= cont; j++) {
								$("#art" + j  ).remove();
								$("#descripcion" + j  ).remove();
							}
							$("#almacenSac").empty();
							//INICIALIZAMOS VARIABLES GLOBALES				
							cont=0;
							arraymaq=[];
							location.reload();
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
$(window).load(function() {
	/********************************VERIFICAR SI HAY SALIDAS DEL DÍA*******************************************/
	var base = '<?php echo $base_salida; ?>';	
	if(base == 'Todas'){
		$.post("consultaAvanceDiario.php", { BuscarTodas: '' }, function(data){		
				var token = data.split();
				var variable = token[0].split('*');				
				$("#almacenSac").html(variable[0]);
		});
	}else
		//alert(base);
		$.post("consultaAvanceDiario.php", { BuscarSalida3: '', Base: base }, function(data){	
				//alert(data);
				var token = data.split();
				var variable = token[0].split('*');
				$("#almacenSac").html(variable[0]);
				$("#consecutivo").val(variable[1]);
		});
	
});//$(window).load	
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

<div class="contenedor">
	<div class="menulateral">
        <div class="submenu_lateral_encabezado">
        	<span class="glyphicon glyphicon-wrench"></span> &rlm; HERRAMIENTAS
        </div>
		<a href="#" data-toggle="modal" data-target=".filtro" onClick="Limpiar()"><div class="submenu_lateral" id="sub1">
			<span class="glyphicon glyphicon-filter"></span> &rlm; Filtro 
		</div></a>
           <!-- <a href="#" data-toggle="modal" data-target=".guardar_almacen" onClick="Limpiar()"><div class="submenu_lateral" id="sub2">
                <span class="glyphicon glyphicon-plus"></span> &rlm; Agregar Almac&eacute;n 
            </div></a>-->
 </form>          
	</div>

	<div id="buscar">
		<center><input type="text" id="buscarArt" value="" size="10"class="form-control" placeholder="Buscar" autofocus/></center>	
	</div>	

	<div id="main">	      
        <div id='jqxWidget'>
          <div id="tabsWidget">
            <ul style="margin-left: 30px;">
            	<li>Almacen</li>                        
            </ul>         
            <div>
            	<br>
                <div class="row">
                	<div class="col-lg-12" align="center"><strong>Base: <span><?php echo $mostrar; ?></span></strong></div>
                </div>
            	<br>
                <div id="almacen"></div>              
            </div>
          </div>
        </div>


	</div>	<input type='hidden' id='subtotal' value='0'><input type='hidden' id='consecutivo' value='0'>
	<div id="almacenSac">
		<div id="art">
			<span class="titulo">#</span>			
		</div>
		<div id="descripcion">
			<span class="titulo">Descripcion</span>
		</div>
	</div>
	<div class="total" id="generaSalida">					
		<center>Guardar maquinaria</center>
	</div>
	
</div>

<!-- Genera Salidas -->   
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Genarar Salidas de Maquinaria</h4>
      </div>
      <div class="modal-body">
        <center>&iquest;Deseas guardar una salida de maquinaria?</center>
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
<form id="formulario5" action="AlmacenMaq.php" method="post" enctype="multipart/form-data" >      
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