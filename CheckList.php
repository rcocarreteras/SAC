<?php 
require_once('Connections/sac2.php');
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
    header("Location: index.php");
}
$fecha = date('Y-m-j');
$plaza = "";
/***************************************************FILTRO*************************************************/    
if (isset($_REQUEST['filtrar'])) {
	
    $fecha = $_POST["fechaFiltro"];
    //$fecha2 = $_POST["fecha2Filtro"];
    $plaza = $_POST["plazaFiltro"];
	
	if($plaza != "TODAS"){
		$sql = "SELECT Usuarios.USUARIO_ID, Usuarios.NOMBRE, Usuarios.USUARIO, Usuarios.PRIVILEGIOS, Accesos.PLAZA FROM Usuarios INNER JOIN Accesos ON Usuarios.USUARIO_ID = Accesos.USUARIO_ID WHERE PLAZA='".$plaza."' AND Usuarios.USUARIO_ID NOT IN ('257','789','1552') GROUP BY Usuarios.USUARIO_ID, Usuarios.NOMBRE, Usuarios.USUARIO, Usuarios.PRIVILEGIOS, Accesos.PLAZA ORDER BY Accesos.PLAZA, Usuarios.NOMBRE";
	}else{
		$sql = "SELECT Usuarios.USUARIO_ID, Usuarios.NOMBRE, Usuarios.USUARIO, Usuarios.PRIVILEGIOS, Accesos.PLAZA FROM Usuarios INNER JOIN Accesos ON Usuarios.USUARIO_ID = Accesos.USUARIO_ID WHERE Usuarios.USUARIO_ID NOT IN ('257')  GROUP BY Usuarios.USUARIO_ID, Usuarios.NOMBRE, Usuarios.USUARIO, Usuarios.PRIVILEGIOS, Accesos.PLAZA ORDER BY Accesos.PLAZA, Usuarios.NOMBRE";
	}
// AND Accesos.PLAZA = 'TONALA' AND Accesos.PLAZA = 'TONALA'
}else{
    $sql = "SELECT Usuarios.USUARIO_ID, Usuarios.NOMBRE, Usuarios.USUARIO, Usuarios.PRIVILEGIOS, Accesos.PLAZA FROM Usuarios INNER JOIN Accesos ON Usuarios.USUARIO_ID = Accesos.USUARIO_ID WHERE Usuarios.USUARIO_ID NOT IN ('257') GROUP BY Usuarios.USUARIO_ID, Usuarios.NOMBRE, Usuarios.USUARIO, Usuarios.PRIVILEGIOS, Accesos.PLAZA ORDER BY Accesos.PLAZA, Usuarios.NOMBRE";
}

/*************************************************VALIDAR CADA ACTIVIDAD*************************************************/
$fila = "";
$ad = 0;
$ins = 0;
$maq = 0;

$fecha1 = strtotime ( '+0 day' , strtotime ( $fecha ) ) ;
$fecha1 = date ( 'Y-m-j' , $fecha1 );
//$fecha2 = $fecha1;
  
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while (odbc_fetch_row($rs)) {
   $usuario = odbc_result($rs, 'USUARIO');
   $nombre = odbc_result($rs, 'NOMBRE');
   $plaza = odbc_result($rs, 'PLAZA');

   $sql2 = "SELECT ISNULL(COUNT(*),0) AS TOTAL FROM AvanceDiario WHERE FECHA = '".$fecha1."' and ALTA='".$usuario."' ";
   //echo $sql2;
   $rs2 = odbc_exec( $conn2, $sql2 );
    if ( !$rs2 ) { 
        exit( "Error en la consulta SQL" ); 
    }   //OBTENEMOS TODA LA FILA 
    while (odbc_fetch_row($rs2)) {
		$total = odbc_result($rs2, 'TOTAL');
		if($total != 0){
			$ad = 1;
		}
    }

   $sql2 = "SELECT ISNULL(COUNT(*),0) AS TOTAL FROM Salidas WHERE FECHA = '".$fecha1."' and ALTA='".$usuario."' ";
   $rs2 = odbc_exec( $conn2, $sql2 );
    if ( !$rs2 ) { 
        exit( "Error en la consulta SQL" ); 
    }   //OBTENEMOS TODA LA FILA 
    while (odbc_fetch_row($rs2)) {
		$total = odbc_result($rs2, 'TOTAL');
		if($total != 0){
			$ins = 1;
		}
    }

   $sql2 = "SELECT ISNULL(COUNT(*),0) AS TOTAL FROM SalidasMaq WHERE FECHA = '".$fecha1."' and ALTA='".$usuario."' ";
   //echo $sql2;
   $rs2 = odbc_exec( $conn2, $sql2 );
    if ( !$rs2 ) { 
        exit( "Error en la consulta SQL" ); 
    }   //OBTENEMOS TODA LA FILA 
    while (odbc_fetch_row($rs2)) {
		$total = odbc_result($rs2, 'TOTAL');
		if($total != 0){
			$maq = 1;
		}
    }

   $fila .= "{'USUARIO_ID':'".$usuario."','NOMBRE':'".$nombre."','PLAZA':'".$plaza."','AD':'".$ad."','SA':'".$ins."','MAQ':'".$maq."'},";    
   $ad = 0; 
   $ins = 0; 
   $maq = 0;
   
   //echo $fila;
 }//While
 
 //$fila .= "{'USUARIO_ID':'".$usuario."','NOMBRE':'".$nombre."','PLAZA':'".$plaza."','AD':'".$ad."','SA':'".$ins."','MAQ':'".$maq."'},";

 $datos = substr_replace("[".$fila,"]",-1);
 //echo json_encode($datos); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>CheckList.</title>
    <meta name="description" content="jQuery Grid Cells editing. jQWidgets Grid supports multiple built-in cell editors like checkbox, dropdownlist, combobox, etc." />
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.edit.js"></script>  
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcalendar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxnumberinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdatetimeinput.js"></script>
    <script type="text/javascript" src="jqwidgets/globalization/globalize.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="jqwidgets/generatedata.js"></script>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap-dialog.min.css" type="text/css" />
    <script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
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
			width: 140px;
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
		#resumen{
			width: 50%;
			height: 400px;
		}
		#grafica{
			width: 50%;
			height: 400px;
		}
		#encabezadoFijo{
			margin:0;
			padding-top: 20px;			
			padding-left: 20px;
			background:white;			
			font-family: 'commandocommando';
			font-size: 18px;
			color:white; 
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
		.derecha{
			float: right;
			margin-right: 10px;
			color:#000;
		}
		.derecha2{
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
			/*float: right;
			margin-right: 60px;*/
			font-family: ubuntu_titlingbold;
			font-size: 20px;
			/*background-color:#C0DCF3;*/
		}
		.tabla{
			font-family: ubuntu_titlingbold;
			font-size: 14px;
			color:#104A7A;
		}
		.tabla2{			
			font-family: ubuntu_titlingbold;
			font-size: 13px;
		}
		.base{			
			font-family: ubuntu_titlingbold;
			font-size: 15px;
			color:#00507C;
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
            // prepare the data
            var data = generatedata(200);
            var data = <?php echo json_encode($datos); ?>;  
            var data = <?php echo $datos; ?>;  
            var source =
            {
                localdata: data,
                datatype: "array",
                updaterow: function (rowid, rowdata, commit) {
                    // synchronize with the server - send update command
                    // call commit with parameter true if the synchronization with the server is successful 
                    // and with parameter false if the synchronization failder.
                    commit(true);
                },
                datafields:
                [
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'PLAZA', type: 'string' },                   
                    { name: 'AD', type: 'bool' },                 
                    { name: 'MAQ', type: 'bool' },
                    { name: 'SA', type: 'bool' }
                ]
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // initialize jqxGrid
            $("#jqxgrid1").jqxGrid(
            {
                width: 850,  
                height: "50%",
                source: dataAdapter,
                editable: false,
                enabletooltips: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'Nombre', datafield: 'NOMBRE', columntype: 'textbox', width: 301 },
                  { text: 'Plaza', datafield: 'PLAZA', columntype: 'textbox', width: 151 },                 
                  { text: 'Avance Diario', datafield: 'AD', columntype: 'checkbox', width: 132 },
                  { text: 'Materiales', datafield: 'SA', columntype: 'checkbox', width: 132 },
                  { text: 'Maquinaria', datafield: 'MAQ', columntype: 'checkbox', width: 132 }
                ]
            });
			
			// prepare the data
            var data = generatedata(200);
            var data = <?php echo json_encode($datos); ?>;  
            var data = <?php echo $datos; ?>;  
            var source =
            {
                localdata: data,
                datatype: "array",
                updaterow: function (rowid, rowdata, commit) {
                    // synchronize with the server - send update command
                    // call commit with parameter true if the synchronization with the server is successful 
                    // and with parameter false if the synchronization failder.
                    commit(true);
                },
                datafields:
                [
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'PLAZA', type: 'string' },                   
                    { name: 'AD', type: 'bool' },                 
                    { name: 'MAQ', type: 'bool' },
                    { name: 'SA', type: 'bool' }
                ]
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // initialize jqxGrid
			$("#jqxgrid2").jqxGrid(
            {
                width: 850,  
                height: "50%",
                source: dataAdapter,
                editable: false,
                enabletooltips: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'Nombre', columntype: 'textbox', datafield: 'NOMBRE', width: 301 },
                  { text: 'PLAZA', datafield: 'PLAZA', columntype: 'textbox', width: 151 },                 
                  { text: 'Avance Diario', datafield: 'AD', columntype: 'checkbox', width: 132 },               
                  { text: 'Materiales', datafield: 'SA', columntype: 'checkbox', width: 132 },
                  { text: 'Maquinaria', datafield: 'MAQ', columntype: 'checkbox', width: 132 }
                ]
            });
			
			// prepare the data
            var data = generatedata(200);
            var data = <?php echo json_encode($datos); ?>;  
            var data = <?php echo $datos; ?>;  
            var source =
            {
                localdata: data,
                datatype: "array",
                updaterow: function (rowid, rowdata, commit) {
                    // synchronize with the server - send update command
                    // call commit with parameter true if the synchronization with the server is successful 
                    // and with parameter false if the synchronization failder.
                    commit(true);
                },
                datafields:
                [
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'PLAZA', type: 'string' },                   
                    { name: 'AD', type: 'bool' },                 
                    { name: 'MAQ', type: 'bool' },
                    { name: 'SA', type: 'bool' }
                ]
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // initialize jqxGrid
			$("#jqxgrid3").jqxGrid(
            {
                width: 850,  
                height: "50%",
                source: dataAdapter,
                editable: false,
                enabletooltips: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'Nombre', columntype: 'textbox', datafield: 'NOMBRE', width: 301 },
                  { text: 'PLAZA', datafield: 'PLAZA', columntype: 'textbox', width: 151 },                 
                  { text: 'Avance Diario', datafield: 'AD', columntype: 'checkbox', width: 132 },               
                  { text: 'Materiales', datafield: 'SA', columntype: 'checkbox', width: 132 },
                  { text: 'Maquinaria', datafield: 'MAQ', columntype: 'checkbox', width: 132 }
                ]
            });
			
			// prepare the data
            var data = generatedata(200);
            var data = <?php echo json_encode($datos); ?>;  
            var data = <?php echo $datos; ?>;  
            var source =
            {
                localdata: data,
                datatype: "array",
                updaterow: function (rowid, rowdata, commit) {
                    // synchronize with the server - send update command
                    // call commit with parameter true if the synchronization with the server is successful 
                    // and with parameter false if the synchronization failder.
                    commit(true);
                },
                datafields:
                [
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'PLAZA', type: 'string' },                   
                    { name: 'AD', type: 'bool' },                 
                    { name: 'MAQ', type: 'bool' },
                    { name: 'SA', type: 'bool' }
                ]
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // initialize jqxGrid
			$("#jqxgrid4").jqxGrid(
            {
                width: 850,  
                height: "50%",
                source: dataAdapter,
                editable: false,
                enabletooltips: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'Nombre', columntype: 'textbox', datafield: 'NOMBRE', width: 301 },
                  { text: 'PLAZA', datafield: 'PLAZA', columntype: 'textbox', width: 151 },                 
                  { text: 'Avance Diario', datafield: 'AD', columntype: 'checkbox', width: 132 },               
                  { text: 'Materiales', datafield: 'SA', columntype: 'checkbox', width: 132 },
                  { text: 'Maquinaria', datafield: 'MAQ', columntype: 'checkbox', width: 132 }
                ]
            });
			
			// prepare the data
            var data = generatedata(200);
            var data = <?php echo json_encode($datos); ?>;  
            var data = <?php echo $datos; ?>;
            var source =
            {
                localdata: data,
                datatype: "array",
                updaterow: function (rowid, rowdata, commit) {
                    // synchronize with the server - send update command
                    // call commit with parameter true if the synchronization with the server is successful 
                    // and with parameter false if the synchronization failder.
                    commit(true);
                },
                datafields:
                [
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'PLAZA', type: 'string' },                   
                    { name: 'AD', type: 'bool' },                 
                    { name: 'MAQ', type: 'bool' },
                    { name: 'SA', type: 'bool' }
                ]
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // initialize jqxGrid
			$("#jqxgrid5").jqxGrid(
            {
                width: 850,  
                height: "50%",
                source: dataAdapter,
                editable: false,
                enabletooltips: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'Nombre', columntype: 'textbox', datafield: 'NOMBRE', width: 301 },
                  { text: 'PLAZA', datafield: 'PLAZA', columntype: 'textbox', width: 151 },                 
                  { text: 'Avance Diario', datafield: 'AD', columntype: 'checkbox', width: 132 },               
                  { text: 'Materiales', datafield: 'SA', columntype: 'checkbox', width: 132 },
                  { text: 'Maquinaria', datafield: 'MAQ', columntype: 'checkbox', width: 132 }
                ]
            });
			
			// prepare the data
            var data = generatedata(200);
            var data = <?php echo json_encode($datos); ?>;  
            var data = <?php echo $datos; ?>;  
            var source =
            {
                localdata: data,
                datatype: "array",
                updaterow: function (rowid, rowdata, commit) {
                    // synchronize with the server - send update command
                    // call commit with parameter true if the synchronization with the server is successful 
                    // and with parameter false if the synchronization failder.
                    commit(true);
                },
                datafields:
                [
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'PLAZA', type: 'string' },                   
                    { name: 'AD', type: 'bool' },                 
                    { name: 'MAQ', type: 'bool' },
                    { name: 'SA', type: 'bool' }
                ]
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // initialize jqxGrid
			$("#jqxgrid6").jqxGrid(
            {
                width: 850,  
                height: "50%",
                source: dataAdapter,
                editable: false,
                enabletooltips: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'Nombre', columntype: 'textbox', datafield: 'NOMBRE', width: 301 },
                  { text: 'PLAZA', datafield: 'PLAZA', columntype: 'textbox', width: 151 },                 
                  { text: 'Avance Diario', datafield: 'AD', columntype: 'checkbox', width: 132 },               
                  { text: 'Materiales', datafield: 'SA', columntype: 'checkbox', width: 132 },
                  { text: 'Maquinaria', datafield: 'MAQ', columntype: 'checkbox', width: 132 }
                ]
            });
			
			// prepare the data
            var data = generatedata(200);
            var data = <?php echo json_encode($datos); ?>;  
            var data = <?php echo $datos; ?>;  
            var source =
            {
                localdata: data,
                datatype: "array",
                updaterow: function (rowid, rowdata, commit) {
                    // synchronize with the server - send update command
                    // call commit with parameter true if the synchronization with the server is successful 
                    // and with parameter false if the synchronization failder.
                    commit(true);
                },
                datafields:
                [
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'PLAZA', type: 'string' },                   
                    { name: 'AD', type: 'bool' },                 
                    { name: 'MAQ', type: 'bool' },
                    { name: 'SA', type: 'bool' }
                ]
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // initialize jqxGrid
			$("#jqxgrid7").jqxGrid(
            {
                width: 850,  
                height: "50%",
                source: dataAdapter,
                editable: false,
                enabletooltips: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'Nombre', columntype: 'textbox', datafield: 'NOMBRE', width: 301 },
                  { text: 'PLAZA', datafield: 'PLAZA', columntype: 'textbox', width: 151 },                 
                  { text: 'Avance Diario', datafield: 'AD', columntype: 'checkbox', width: 132 },               
                  { text: 'Materiales', datafield: 'SA', columntype: 'checkbox', width: 132 },
                  { text: 'Maquinaria', datafield: 'MAQ', columntype: 'checkbox', width: 132 }
                ]
            });



        });//document.ready
    </script>
</head>
<body class='default'>
		<header>
		<a href="index.php"><img class="derecha" src="images/cerrarsesion.png"></a>
		<a href="Almacen.php"><span>Insumos</span></a>
		<a href="Salidas.php"><span>Salida Insumos</span></a>
		<a href="AlmacenMaq.php"><span>Maquinaria</span></a>
		<a href="SalidasMaq.php"><span>Entrada Maq</span></a>
		<a href="AvanceDiarioPlus.php"><span>Avance Diario</span></a>
		<a href="Contratos.php"><span>Contratos</span></a>
		<a href="Comparativo.php"><span>Comparativa</span></a>
	</header>
	<div id="encabezadoFijo">
    	<a href="#" data-toggle="modal" data-target=".filtro"><div class="izquierda">
			<span class="glyphicon glyphicon-search"></span> &rlm; Filtrar
		</div> </a>
	</div>
	<div id="contenido">
		<center>
        	<br>
            <span class="titulo">Fecha: <?php echo $fecha1; ?></span>
                <div id="jqxgrid1"></div>
                <!--<div id="jqxgrid2"></div>
                <div id="jqxgrid3"></div>
                <div id="jqxgrid4"></div>
                <div id="jqxgrid5"></div>
                <div id="jqxgrid6"></div>
                <div id="jqxgrid7"></div>-->
         </center>
     </div>

<!-- CAMBIAR PERFIL-->
<form action="CheckList.php" method="post" enctype="multipart/form-data" >
<div class="modal fade filtro" id="filtro" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">
        	<div class="col-lg-3">Fecha inicial:</div>
        	<div class="col-lg-4"><input type="date" id="fechaFiltro" name="fechaFiltro" value="<?php echo $fecha1; ?>" class="form-control"></div>
        	<div class="col-lg-1">Plaza:</div>
        	<div class="col-lg-4">
            	<select class="form-control" id="plazaFiltro" name="plazaFiltro">
                    <?php 	
				      $i=1;
 				     //$selected="";
 				     $sql = "SELECT DISTINCT(Plaza) from Accesos";
				      echo $sql;
				      $rs = odbc_exec( $conn, $sql );
					      if ( !$rs ) { 
  						     exit( "Error en la consulta SQL" ); 
 					     }while ( odbc_fetch_row($rs) ) { 
       						$plaza = odbc_result($rs, 'Plaza');
      					 echo "<option id='".$plaza."'>".$plaza."</option>";
      					 $i++;
      					}//While  
     				?>
                	<option value="TODAS">TODAS</option>
                </select>
            </div>
        </div>
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="refresh()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="filtrar" id="filtrar">Filtrar</button>
      </div>
    </div>
  </div>
</div>
</form>     
     
</body>
</html>