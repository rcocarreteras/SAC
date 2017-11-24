<?php
require_once('Connections/Sac2.php');
require_once('fechas.php');
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
//print_r($_POST);

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
    header("Location: ../index.php");
} 

$valida = false;
$periodo = date("Y").date("m");
$x=0;
$fila = "";
//$periodo = "201610";

$dx = date('N', strtotime(date("Y-m"."-01")));
$dias = array('L','M','I','J','V','S','D');

//------------------------------------------CAMBIO DE PRIVILEGIOS------------------------------------------------------
if (isset($_REQUEST['cambiar_privilegios'])) { 
  //$perfil = $_POST["perfil"]; 
  $plaza_perfil = $_POST["plaza_perfil"]; 
  
  $_SESSION['S_Plaza'] = $plaza_perfil;
  $buscarplaza = $plaza_perfil;
 
}

//-------------------------------------- FILTRO -----------------------------------------------------------
if (isset($_REQUEST['Filtrar'])) {
  $periodo = $_POST['periodoFiltro'];

  $año = substr($periodo, 0, 4); 
  $mes = substr($periodo, 4, 2); 
  
  $dx = date('N', strtotime($año."-".$mes."-01"));
  $dias = array('L','M','I','J','V','S','D');
}

//VALIDAMOS SI EXISTE UN ROL CARGADO EN ESTE PERIODO
$sql = "SELECT * FROM GeneradorSubdren";
$rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   
while ( odbc_fetch_row($rs) ) {
  $id = odbc_result($rs, 'id');  
  $mes = odbc_result($rs, 'MES');
  $anio = odbc_result($rs, 'ANIO');
  $tipo = odbc_result($rs, 'TIPO');
  $tramo = odbc_result($rs, 'TRAMO');
  $cuerpo = odbc_result($rs, 'CUERPO');
  $km_ini = odbc_result($rs, 'KM_INI');
  $km_fin = odbc_result($rs, 'KM_FIN');
  $longitud = odbc_result($rs, 'LONG');
  $acum = odbc_result($rs, 'ACUMULADO');
  $x1 = odbc_result($rs, 'X1');
  $y1 = odbc_result($rs, 'Y1');
  $y2 = odbc_result($rs, 'Y2');
  $x2 = odbc_result($rs, 'X2');
  
  $fila.="{\"ID\":\"".$id."\",\"MES\":\"".$mes."\",\"ANIO\":\"".$anio."\",\"TIPO\":\"".$tipo."\",\"TRAMO\":\"".$tramo."\",\"CUERPO\":\"".$cuerpo."\",\"KM_INI\":\"".$km_ini."\",\"KM_FIN\":\"".$km_fin."\",\"LONG\":\"".$longitud."\",\"ACUMULADO\":\"".$acum."\",\"Y1\":\"".$y1."\",\"X1\":\"".$x1."\",\"Y2\":\"".$y2."\",\"X2\":\"".$x2."\"},"; 
  $x++;  
 }


$datos = substr_replace("[".$fila,"]",-1);
//echo $datos;


//-------------------------------------- GUARDAR EVALUACION DIARIA -----------------------------------------------------------
if (isset($_REQUEST['Guardar'])) {  
  $mes = $_POST['mes']; 
  $anio = $_POST['anio'];
  $cuerpo = $_POST['cuerpo'];
  $km_ini = $_POST['km_ini'];
  $km_fin = $_POST['km_fin'];
  $tramo = $_POST['tramo'];
  $longitud = $_POST['longitud'];
  $acumulado = $_POST['acumulado'];
  $y1 = $_POST['y1'];
  $x1 = $_POST['x1'];
  $y2 = $_POST['y2'];
  $x2 = $_POST['x2'];

  $sql = "INSERT INTO GeneradorSubdren VALUES('".$mes."','".$anio."','".$tipo."','".$tramo."','".$cuerpo."','".$km_ini."','".$km_fin."','".$longitud."','".$acumulado."','".$y1."','".$x1."','".$y2."','".$x2."')";
  //echo $sql;  
  $rs = odbc_exec( $conn, $sql );
    //$notificacion='Guardado';
  if ( !$rs ) { 
    exit( "Error en la consulta" );
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>Generador de Bacheo</title>
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="font-awesome/css/font-awesome.css">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap-dialog.min.css" type="text/css" />
    <script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>

    <!-- JQwidgets -->
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />      
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxtooltip.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>    
    <script type="text/javascript" src="jqwidgets/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.columnsresize.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmaskedinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxnumberinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxradiobutton.js"></script>    
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxnavbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.edit.js"></script>

    <style type="text/css">
		/*FUENTES*/
		@font-face {
			font-family: 'ubuntu_titlingbold';
    		src: url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.eot');
    		src: url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.eot?#iefix') format('embedded-opentype'),
         	url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.woff') format('woff'),
         	url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.ttf') format('truetype'),
         	url('Fuentes/ubuntu/UbuntuTitling-Bold-webfont.svg#ubuntu_titlingbold') format('svg');    		
			font-weight: normal;
			font-style: normal;
		}
		@font-face {
			font-family: 'commandocommando';
		    src: url('Fuentes/commando/commando-webfont.eot');
		    src: url('Fuentes/commando/commando-webfont.eot?#iefix') format('embedded-opentype'), 
		    url('Fuentes/commando/commando-webfont.woff') format('woff'), 
		    url('Fuentes/commando/commando-webfont.ttf') format('truetype'), 
		    url('Fuentes/commando/commando-webfont.svg#commandocommando') format('svg');
			font-weight: normal;
			font-style: normal;
		}
		header{
			margin:0;			
			background: #2c2c2c;
			height:65px; 
			width:100%;
			border-bottom: 3px solid #0057B3;
		}
		header img{			
			float: left;
		}
		header span{			
			float: left;
			padding-top: 20px;
			width: 150px;
			height: 65px;
			background: #2c2c2c;
			border-bottom: 3px solid #0057B3;

			font-family: 'ubuntu_titlingbold';
			font-size: 20px;
			text-align: center;
			color: white;
		}
		/*IDENTIFICADORES*/
		
		#contenido{
			width: 100%;
			height: auto;

		}
		#encabezadoFijo{
			margin:0;
			padding-top: 20px;			
			padding-left: 20px;
			background:white;			
			font-family: 'commandocommando';
			font-size: 18px;			
			text-align:left;
			height:60px;
			width:100%;
			color:#000;	
			z-index: 1;			
		}
		
		/*CLASES*/
		.izquierda{
			float: left;
			margin-left: 60px;
			color:#000;
		}
		.coordenadas{
			font-family: ubuntu_titlingbold;
			font-size:20px;
		}
		.derecha{
			float: right;
			margin-right: 60px;
			color:#000;
		}
		.fixed{
			position:fixed;
			border-bottom: 2px solid #0057B3; 
			top:0			
		}
		.titulo{			
			font-family: ubuntu_titlingbold;
			font-size: 16px;
			background-color:#C0DCF3;	
		}
		.tabla{			
			font-family: ubuntu_titlingbold;
			font-size: 12px;
		}
		
		/*EFECTOS*/
		header span:hover {
			text-decoration: none;
			background: #49A2FF;
			color:black;
		}
		tbody tr:nth-child(even){
			background-color: #f2f2f2;
		}
		tbody tr:hover{
			background-color:#acd0e9;
		}

	</style>
    <script type="text/javascript">        
        $(document).ready(function () {		
			
            //$("#toolTip1").jqxTooltip({ content: 'Filtrar dia anterior', position: 'left', name: 'movieTooltip'});
            
            //CREAMOS LOS TABS
            $('#jqxTabs').jqxTabs({ width: '99%', height: '90%', position: 'top'});           
            $('#jqxTabs').jqxTabs({ selectionTracker: true });
            $('#jqxTabs').jqxTabs({ animationType: 'fade' });

            //MOSTRAMOS EL ROL DE TURNOS JI ********************************************************************************
            var data =  <?php echo $datos; ?>;                  
            var source =
            {
                datatype: "array",
                updaterow: function (rowid, rowdata, commit, cell, value) {
					//var longitud = $(this).cellvalue;
                    // synchronize with the server - send update command
                    // call commit with parameter true if the synchronization with the server is successful 
                    // and with parameter false if the synchronization failed.
                    //var test = $(this).val();

                    alert(value);
                    commit(true);
                },
                datafields: [
                    { name: 'ID', type: 'number' },   
                    { name: 'MES', type: 'number' },
                    { name: 'ANIO', type: 'number' }, 	
                    { name: 'TIPO', type: 'string' },
                    { name: 'TRAMO', type: 'string' }, 
                    { name: 'CUERPO', type: 'string' }, 
                    { name: 'KM_INI', type: 'string' },
                    { name: 'KM_FIN', type: 'string' },
                    { name: 'LONG', type: 'number' },
                    { name: 'ACUMULADO', type: 'number' }, 
                    { name: 'Y1', type: 'number' },
                    { name: 'X1', type: 'number' },
                    { name: 'Y2', type: 'number' },
                    { name: 'X2', type: 'number' }
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#gridBacheo").jqxGrid({
                width: '100%',
                height: '90%',
                source: dataAdapter,
                selectionmode: 'singlecell',
                editable: true,
                sortable: true,                 
                editmode: 'click',
                keyboardnavigation: true,
                columns: [
                 // { text: '#Emp', dataField: 'EMPLEADO', width: '5%' },
                  { text: 'Numero', dataField: 'ID', width: '5%' },
                  { text: 'Mes', dataField: 'MES', width: '5%', cellsalign: 'right' },
                  { text: 'A&ntilde;o', dataField: 'ANIO', width: '5%' },
                  { text: 'Tipo', dataField: 'TIPO', width: '5%' },
                  { text: 'Autopista', dataField: 'TRAMO', width: '17%' },
                  { text: 'Cuerpo', dataField: 'CUERPO', width: '5%', cellsalign: 'center' },
                  { text: 'Del Km', dataField: 'KM_INI', width: '6%' },
                  { text: 'Al Km', dataField: 'KM_FIN', width: '6%' },
                  { text: 'Logitud', dataField: 'LONG', width: '7%', cellsalign: 'right' },
                  { text: 'Acumulado', dataField: 'ACUMULADO', width: '7%', cellsalign: 'right' },
                  { text: 'Y (N-S) Inicial', dataField: 'Y1', width: '8%', cellsalign: 'right' },
                  { text: 'X (E-O) Inicial', dataField: 'X1', width: '8%', cellsalign: 'right' },
                  { text: 'Y (N-S) Final', dataField: 'Y2', width: '8%', cellsalign: 'right' },
                  { text: 'X (E-O) Final', dataField: 'X2', width: '8%', cellsalign: 'right' }
                ]
            });            
         
            //BLOQUEAMOS LOS CAMPOS NO EDITABLES
              //$("#rolTurno").jqxGrid('setcolumnproperty', 'EMPLEADO', 'editable', false);  
              //$("#rolTurno").jqxGrid('setcolumnproperty', 'NOMBRE', 'editable', false);            
            //var bloqueo = '<?php echo $valida; ?>'; 
            
            //if (bloqueo == 1) {              
              /*$("#gridBacheo").jqxGrid('setcolumnproperty', 'ENSAYE', 'editable', false);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'NUM_BACHE', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'KM_INI', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'KM_FIN', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'CUERPO', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'CARRIL', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'TRAMO', 'editable', false);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'LONGITUD', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'ANCHO', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'ESPESOR', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'M3_FRESADO', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'ACUMULADO_FRESADO', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'M3_CARPETA', 'editable', true);  
              $("#gridBacheo").jqxGrid('setcolumnproperty', 'ACUMULADO_CARPETA', 'editable', true); */ 

           // }     
			
			$("#toolTip").click(function(){
				$("#descargar").modal('show'); 
            });       

            $("#toolTip2").click(function(){
              $("#agregar").modal('show'); 
            });   

            /* var flotante = 0;
            $("#MenuPopover").click(function(){
              switch(flotante) {
                case 0:
                  $('#flotante').show();              
                  $('#flotante').animate({
                    marginRight: "2%"
                  });
                  flotante = 1;
                  break;
                case 1:
                  $('#flotante').animate({
                    marginRight: "-50%"
                  });
                  setTimeout(function(){
                    $('#flotante').hide();                  
                  },500);
                  flotante=0;
                  break;                
              }               
            });  */    

        });//document.ready
    </script>
</head>
<body>
    <header>
		<!--<img src="images/HEADBIOMETRICO.png"  width="153" height="46">-->
		<a href="index.php"><img class="derecha" src="images/cerrarsesion.png"></a>
		<a href="Almacen.php"><span>Insumos</span></a>
		<a href="Salidas.php"><span>Salida Insumos</span></a>
		<a href="AlmacenMaq.php"><span>Maquinaria</span></a>
		<a href="SalidasMaq.php"><span>Entrada Maq</span></a>
		<a href="AvanceDiarioPlus.php"><span>Avance Diario</span></a>
		<a href="Contratos.php"><span>Contratos</span></a>
		<a href="Comparativo.php"><span>Comparativa</span></a>         
    </header> 
<br>
    <div id="contenido">
        <center>
        <div id='jqxWidget'>
            <div id='jqxTabs'>
                <ul>
                    <li style="margin-left: 30px;">Generador de Subdren <?php echo date("Y"); ?></li>
                                      
                </ul>
                <br>
                <i class="fa fa-plus-circle fa-3x" aria-hidden="true" id="toolTip2" style="cursor:pointer"></i>&nbsp;&nbsp;&nbsp;
                <i class="fa fa-cloud-download fa-3x" aria-hidden="true" id="toolTip" style="cursor:pointer"></i>
                <div id="gridBacheo"></div>

            </div>
        </div>
        </center>
        <div style="margin-top: 5px;" id="editmodes"> </div>
	</div>

<!--CONFIGURACION DE LOS MODALES-->
<!-- CAMBIO DE FECHA-->
<form id="formulario1" action="GeneradorSubdren.php" method="post" enctype="multipart/form-data" >
  <!-- Modal -->
  <div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong><span style="font-family:Verdana; font-size:18px; color:#004782"></span></strong>
      </div>
      <div class="modal-body">
        <!--CUERPO-->  
      <div class="row">
      	<div class="col-lg-2">
        	Mes:
        </div>  
      	<div class="col-lg-3">
        	<input type="number" name="mes" id="mes" class="form-control" maxlength="2">
        </div> 
      	<div class="col-lg-2">
        	A&ntilde;o:
        </div>  
      	<div class="col-lg-3">
        	<input type="number" name="anio" id="anio" class="form-control" maxlength="4">
        </div>  
       </div><br>
       <div class="row">
      	<div class="col-lg-2">
        	Tipo:
        </div>
      	<div class="col-lg-3">
        	<input type="text" name="tipo" id="tipo" class="form-control">
        </div>   
        <div class="col-md-2">
            Cuerpo:
        </div>
         <div class="col-md-3">
            <select  class="form-control" name="cuerpo" id="cuerpo">
            	<option value="A">A</option>
                <option value="B">B</option>
            </select>
        </div> 
      </div>
      <br>
      <div class="row">            
        <div class="col-md-2">
            Del km:
        </div>
         <div class="col-md-3">
            <input type="number" class="form-control" name="km_ini" id="km_ini" step="any">
        </div>       
        <div class="col-md-2">
            Al km:
        </div>
         <div class="col-md-3">
            <input type="number" class="form-control" name="km_fin" id="km_fin" step="any">
        </div>      
      </div><br>
      <div class="row">    
        <div class="col-md-2">
            Autopista:
        </div>
         <div class="col-md-8">
            <select class="form-control" id="tramo" name="tramo">
                	<option value="">Seleccione una opci&oacute;n</option>
                    <?php 	
				      $i=1;
 				     //$selected="";	   
 				     $sql = "SELECT DISTINCT(TRAMO) from CatTramos order by TRAMO";
				      echo $sql;
				      $rs = odbc_exec( $conn, $sql );
					      if ( !$rs ) { 
  						     exit( "Error en la consulta SQL" ); 
 					     }while ( odbc_fetch_row($rs) ) { 
       						$tramo = odbc_result($rs, 'TRAMO');	  	
      					 echo "<option id='".$tramo."'>".$tramo."</option>";		   	     
      					 $i++;
      					}//While  
     				?>
              </select>
        </div>   
      </div>
      <br>
      <div class="row">       
        <div class="col-md-2">
            Logitud:
        </div>
         <div class="col-md-3">
            <input type="number" class="form-control" name="longitud" id="longitud" step="any">
        </div>       
        <div class="col-md-2">
            Acumulado:
        </div>
         <div class="col-md-3">
            <input type="number" class="form-control" name="acumulado" id="acumulado" step="any">
        </div>  
      </div>
      <hr>
      <center><span class="coordenadas">Coordenadas Iniciales</span></center>
      <br>
      <div class="row">        
        <div class="col-md-1" align="center"></div>
        <div class="col-md-2" align="center">
            Y (N-S):
        </div>
         <div class="col-md-3">
            <input type="number" class="form-control" name="y1" id="y1" step="any">
        </div>       
        <div class="col-md-2" align="center">
            X (E-O):
        </div>
         <div class="col-md-3">
            <input type="number" class="form-control" name="x1" id="x1" step="any">
        </div>
      </div>
      <center><span class="coordenadas">Coordenadas Finales</span></center>
      <br>
      <div class="row">        
        <div class="col-md-1" align="center"></div>
        <div class="col-md-2" align="center">
            Y (N-S):
        </div>
         <div class="col-md-3">
            <input type="number" class="form-control" name="y2" id="y2" step="any">
        </div>       
        <div class="col-md-2" align="center">
            X (E-O):
         </div>
        <div class="col-md-3">
            <input type="number" class="form-control" name="x2" id="x2" step="any">
        </div>
      </div>
          <!--FIN--> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Cerrar</button> 
        <button type="submit" class="btn btn-primary" id="Guardar" name="Guardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
</form>

<!-- FILTRO -->
<form id="formulario5" action="Excel_Subdren.php" method="post" enctype="multipart/form-data" >      
<div class="modal fade descargar" id="descargar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center>  <img src="images/filtro_header.png" height="40"> EXPORTAR INFORMACI&Oacute;N <img src="images/filtro_header1.png" height="40">  </center></h4>
      </div>
      <div class="modal-body">
      <!--CUERPO-->
     <div class="panel-group" >
    <div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					A&ntilde;o
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
                <div class="row">
                    <div class="col-lg-2">A&ntilde;o:</div>
                    <div class="col-lg-3">
                    	<select class="form-control" id="anioExcel" name="anioExcel">
                        	<option value="<?php echo $anioAntes; ?>"><?php echo $anioAntes; ?></option>
                        	<option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
                        	<option value="<?php echo $anioDes; ?>"><?php echo $anioDes; ?></option>
                        </select>
                    </div>
                </div>
     		 </div>
    	</div>
  	</div>
  </div>
  <br>
  <div class="panel-group" >
    <div class="panel panel-default">
    				<div class="panel-heading" role="tab" id="headingOne">
      					<h4 class="panel-title">
         					Tramo
      					</h4>
	    			</div>
    			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      		<div class="panel-body">
                <div class="row">
                    <div class="col-lg-2">Tramo:</div>
                    <div class="col-lg-8">
                    	<select class="form-control" id="tramoExcel" name="tramoExcel">
                            <?php 	
                              $i=1;
                             //$selected="";	   
                             $sql = "SELECT DISTINCT(TRAMO) from CatTramos order by TRAMO";
                              echo $sql;
                              $rs = odbc_exec( $conn, $sql );
                                  if ( !$rs ) { 
                                     exit( "Error en la consulta SQL" ); 
                                 }while ( odbc_fetch_row($rs) ) { 
                                    $tramo = odbc_result($rs, 'TRAMO');	  	
                                 echo "<option id='".$tramo."'>".$tramo."</option>";		   	     
                                 $i++;
                                }//While  
                            ?>
                      </select>
                    </div>
                </div>
     		 </div>
    	</div>
  	</div>
  </div>
	<!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>        
        <button type="submit" class="btn btn-success" name="exportar_subdren" id="exportar_subdren">Exportar</button>
      </div>
    </div>
  </div>
  </div>
</div>
</form> 
<!--Fin Modal-->


</body>
</html>