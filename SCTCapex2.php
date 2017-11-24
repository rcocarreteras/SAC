<?php 
require_once('Connections/sac2.php');
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');

#print_r($_POST);

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
 	
		
$anio = date("Y");
$mes = date("m");
$month = $mes - 1;

if($mes == "01"){
	$month = "12";
	$anio = $anio - 1;
}

#echo "Mes:".$mes." Anio:".$anio;
	
if (isset($_POST['guardar'])) {
	$tramo = $_POST["tramo"];
	$actividad = $_POST["actividad"];	
	$periodo = $_POST["periodo"];	
	$sem1 = $_POST["sem1"];	
	$sem2 = $_POST["sem2"];	
	$sem3 = $_POST["sem3"];	
	$sem4 = $_POST["sem4"];	
	
	 //VALIDACION DE VARIABLES
	 if (isset($_POST['cuerpo1'])){
		  $cuerpo1 = $_POST['cuerpo1'];
	  }else{
		   $cuerpo1 ="";
	  }
	 if (isset($_POST['kmini1'])){
		  $kmini1 = $_POST['kmini1'];
	  }else{
		   $kmini1 = 0;
	  }
	 if (isset($_POST['kmfin1'])){
		  $kmfin1 = $_POST['kmfin1'];
	  }else{
		   $kmfin1 = 0;
	  }
	 if (isset($_POST['cuerpo2'])){
		  $cuerpo2 = $_POST['cuerpo2'];
	  }else{
		   $cuerpo2 = "";
	  }
	 if (isset($_POST['kmini2'])){
		  $kmini2 = $_POST['kmini2'];
	  }else{
		   $kmini2 = 0;
	  }
	 if (isset($_POST['kmfin2'])){
		  $kmfin2 = $_POST['kmfin2'];
	  }else{
		   $kmfin2 = 0;
	  }
	 if (isset($_POST['cuerpo3'])){
		  $cuerpo3 = $_POST['cuerpo3'];
	  }else{
		   $cuerpo3 = "";
	  }
	 if (isset($_POST['kmini3'])){
		  $kmini3 = $_POST['kmini3'];
	  }else{
		   $kmini3 = 0;
	  }
	 if (isset($_POST['kmfin3'])){
		  $kmfin3 = $_POST['kmfin3'];
	  }else{
		   $kmfin3 = 0;
	  }
	 if (isset($_POST['cuerpo4'])){
		  $cuerpo4 = $_POST['cuerpo4'];
	  }else{
		   $cuerpo4 = "";
	  }
	 if (isset($_POST['kmini4'])){
		  $kmini4 = $_POST['kmini4'];
	  }else{
		   $kmini4 = 0;
	  }
	 if (isset($_POST['kmfin4'])){
		  $kmfin4 = $_POST['kmfin4'];
	  }else{
		   $kmfin4 = 0;
	  }
	 if (isset($_POST['cuerpo5'])){
		  $cuerpo5 = $_POST['cuerpo5'];
	  }else{
		   $cuerpo5 = "";
	  }
	 if (isset($_POST['kmini5'])){
		  $kmini5 = $_POST['kmini5'];
	  }else{
		   $kmini5 = 0;
	  }
	 if (isset($_POST['kmfin5'])){
		  $kmfin5 = $_POST['kmfin5'];
	  }else{
		   $kmfin5 = 0;
	  }
	
	$id = explode("*",$actividad);

	// /**********************************************ARCHIVOS FOTOS*********************************************************************/
    include("../funcionesInternas/clasesInternas.php"); 
    $objeto_imagen = new imagen;
    $carpeta = "../Global/Sac/Capex/".$tramo."/".$periodo."/";

    $foto1 = $foto2 = $foto3 = $foto4 = $foto5 = $foto6 = "";

    // SE BUSCA EL ULIMO REGISTRO INSERTADO
    $sql1 = "SELECT MAX(ID_REGISTRO) AS ULTIMO FROM InformacionCapexSCT  ";
    //echo $sql1;
    $rs1 = odbc_exec( $conn, $sql1 );
    if ( !$rs1 ) { 
        exit( "Error en la consulta SQL" );
    } 
    while ( odbc_fetch_row($rs1) ) { 
        $ultimoRegistro = odbc_result($rs1, 'ULTIMO');
    }
    $ultimoRegistro++;


    if ($_FILES["foto1"]["size"] > 0) {
        $foto = $objeto_imagen->comprimir($_FILES['foto1'], $carpeta, $ultimoRegistro."-1",600,800,80);
        $foto1 = $carpeta.$foto;
    }

    if ($_FILES["foto2"]["size"] > 0) {   
        $foto = $objeto_imagen->comprimir($_FILES['foto2'], $carpeta, $ultimoRegistro."-2",600,800,80);      
        $foto2 =$carpeta.$foto;
    }  

    if ($_FILES["foto3"]["size"] > 0) {
        $foto = $objeto_imagen->comprimir($_FILES['foto3'], $carpeta, $ultimoRegistro."-3",600,800,80);      
        $foto3 = $carpeta.$foto;
    }

    if ($_FILES["foto4"]["size"] > 0) {
        $foto = $objeto_imagen->comprimir($_FILES['foto4'], $carpeta, $ultimoRegistro."-4",600,800,80);
        $foto4 = $carpeta.$foto;
    }

    if ($_FILES["foto5"]["size"] > 0) {   
        $foto = $objeto_imagen->comprimir($_FILES['foto5'], $carpeta, $ultimoRegistro."-5",600,800,80);      
        $foto5 =$carpeta.$foto;
    }  

    if ($_FILES["foto6"]["size"] > 0) {
        $foto = $objeto_imagen->comprimir($_FILES['foto6'], $carpeta, $ultimoRegistro."-6",600,800,80);      
        $foto6 = $carpeta.$foto;
    }

	
	$sql = "INSERT INTO InformacionCapexSCT VALUES ('".$tramo."','".$periodo."','".$id[0]."','".$id[1]."','".$sem1."','".$sem2."','".$sem3."','".$sem4."','".$cuerpo1."','".$kmini1."','".$kmfin1."','".$cuerpo2."','".$kmini2."','".$kmfin2."','".$cuerpo3."','".$kmini3."','".$kmfin3."','".$cuerpo4."','".$kmini4."','".$kmfin4."','".$cuerpo5."','".$kmini5."','".$kmfin5."','".$foto1."','".$foto2."','".$foto3."','".$foto4."','".$foto5."','".$foto6."')";
    #echo $sql;
    $rs = odbc_exec( $conn, $sql );
    if ( !$rs ) { 
    	exit( "Error en la consulta Capex SCT" ); 
    }
		
}

#$tramoFiltro = $_SESSION['S_Tramo'];
#$mostrar = $_SESSION['S_Tramo'];
$tabla = "";
/*if($_SESSION['S_Tramo'] == "El Desperdicio - Lagos de Moreno','El Desperdicio - Santa Maria de En Medio','La Barca - Jiquilpan','La Barca - Jiquilpan','Leon - Aguascalientes','Leon - Aguascalientes','Los Fresnos - Zapotlanejo','Los Fresnos - Zapotlanejo','Los Fresnos - Zapotlanejo','Maravatio - Los Fresnos','Maravatio - Los Fresnos','Maravatio - Los Fresnos','Zapotlanejo - El Desperdicio','Zapotlanejo - El Desperdicio','Zapotlanejo - El Desperdicio','Zapotlanejo - Guadalajara"){
	$mostrar = "Farac";
}*/
$filtro = "";
$tramoFiltro = "Farac";
/***************************************************FILTRO*************************************************/	
	if (isset($_REQUEST['filtro'])) {
		$tramoFiltro = $_POST["tramoFiltro"];
		
		if ($tramoFiltro != "TODOS"){
			$filtro = " AND TRAMO = '".$tramoFiltro."' ";
		}else{
			$tramoFiltro = "Farac";
		}
	}

	//CARGAMOS LA TABLA
	$sql1 = "SELECT * FROM InformacionCapexSCT WHERE PERIODO='".$anio.$month."' " .$filtro."  ORDER BY TRAMO, ACTIVIDAD";
	#echo "SQL1: ".$sql1;
	$rs = odbc_exec( $conn, $sql1);
	if ( !$rs ) { 
		exit( "Error en la consulta SQL" );
	}
	while ( odbc_fetch_row($rs) ) { 
		$tramo = odbc_result($rs, 'TRAMO');
		$actividad = odbc_result($rs, 'ACTIVIDAD');
		$sem1 = odbc_result($rs, 'SEMANA1');
		$sem2 = odbc_result($rs, 'SEMANA2');
		$sem3 = odbc_result($rs, 'SEMANA3');
		$sem4 = odbc_result($rs, 'SEMANA4');
		$cuerpo1 = odbc_result($rs, 'CUERPO1');
		$kmini1 = odbc_result($rs, 'KMINI1');
		$kmfin1 = odbc_result($rs, 'KMFIN1');
		$cuerpo2 = odbc_result($rs, 'CUERPO2');
		$kmini2 = odbc_result($rs, 'KMINI2');
		$kmfin2 = odbc_result($rs, 'KMFIN2');
		$cuerpo3 = odbc_result($rs, 'CUERPO3');
		$kmini3 = odbc_result($rs, 'KMINI3');
		$kmfin3 = odbc_result($rs, 'KMFIN3');
		$cuerpo4 = odbc_result($rs, 'CUERPO4');
		$kmini4 = odbc_result($rs, 'KMINI4');
		$kmfin4 = odbc_result($rs, 'KMFIN4');
		$cuerpo5 = odbc_result($rs, 'CUERPO5');
		$kmini5 = odbc_result($rs, 'KMINI5');
		$kmfin5 = odbc_result($rs, 'KMFIN5');
		
		if($cuerpo1 == ""){
			$cuerpo1 = "-";
			$kmini1 = "-";
			$kmfin1 = "-";
		}
		if($cuerpo2 == ""){
			$cuerpo2 = "-";
			$kmini2 = "-";
			$kmfin2 = "-";
		}
		if($cuerpo3 == ""){
			$cuerpo3 = "-";
			$kmini3 = "-";
			$kmfin3 = "-";
		}
		if($cuerpo4 == ""){
			$cuerpo4 = "-";
			$kmini4 = "-";
			$kmfin4 = "-";
		}
		if($cuerpo5 == ""){
			$cuerpo5 = "-";
			$kmini5 = "-";
			$kmfin5 = "-";
		}
					
		$tabla.="<tr class = 'tabla'>";
		$tabla.="<td width='300'>".$tramo."</td>";
		$tabla.="<td align='left' width='550'>".$actividad."</td>";
		$tabla.="<td align='center'>".$sem1."</td>";
		$tabla.="<td align='center'>".$sem2."</td>";
		$tabla.="<td align='center'>".$sem3."</td>";
		$tabla.="<td align='center'>".$sem4."</td>";
		$tabla.="<td align='center'>".$cuerpo1."</td>";
		$tabla.="<td align='center'>".$kmini1." - ".$kmfin1."</td>";
		$tabla.="<td align='center'>".$cuerpo2."</td>";
		$tabla.="<td align='center'>".$kmini2." - ".$kmfin2."</td>";
		$tabla.="<td align='center'>".$cuerpo3."</td>";
		$tabla.="<td align='center'>".$kmini3." - ".$kmfin3."</td>";
		$tabla.="<td align='center'>".$cuerpo4."</td>";
		$tabla.="<td align='center'>".$kmini4." - ".$kmfin4."</td>";
		$tabla.="<td align='center'>".$cuerpo5."</td>";
		$tabla.="<td align='center'>".$kmini5." - ".$kmfin5."</td></tr>";
	}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Contratos</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="CssLocal/menuSac.css"><!--Necesario para Menu 1--> 
	<link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="scripts/demos.js"></script>	
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script>
    <link href="css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
    <script type="text/javascript" src="js/jqueryFileTree.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdraw.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxchart.core.js"></script>
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
		.tramo{
			font-family: 'commandocommando';
			font-size: 18px;
		}
		.fixed{
			position:fixed;
			border-bottom: 2px solid #0057B3; 
			top:0			
		}
		.titulo{			
			font-family: ubuntu_titlingbold;
			font-size: 20px;
			background-color:#C0DCF3;
			
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
		.ezdz-dropzone{
			height: 80px!important;
			line-height: 60px!important;
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
			// ENCABEZADO FIJO
			$(window).scroll(function() {    
    			posicionarMenu();
			});
			function posicionarMenu() {
			    var alturaMenuSup = $('header').outerHeight(true);
			    var alturaEnabezadoFijo = $('#encabezadoFijo').outerHeight(true);

			    if ($(window).scrollTop() >= alturaMenuSup){
			        $('#encabezadoFijo').addClass('fixed');		        
			        $('section').css('margin-top', (alturaEnabezadoFijo) + 'px');
			    } else {
			        $('#encabezadoFijo').removeClass('fixed');
			        $('section').css('margin-top', '0');
			    }
			}
			
		});//document.ready
		
		function MostrarFilas(Fila) {
			var quitar = Fila -1;	
			document.getElementById("sac"+quitar).style.display = "none";
			
			var elementos = document.getElementsByName(Fila);    
			for (i = 0; i< elementos.length; i++) {
				if(navigator.appName.indexOf("Microsoft") > -1){
					   var visible = 'block'
				} else {
					   var visible = 'table-row';
				}
			elementos[i].style.display = visible;
				}
		}
		
		function OcultarFilas(Fila) {	
			var elementos = document.getElementsByName(Fila);
			for (k = 0; k< elementos.length; k++) {
					   elementos[k].style.display = "none";
			}
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
		<a href="PresupuestosOpex.php"><span>Ptto Opex</span></a>
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
	<div id="encabezadoFijo">
		<a href="#" data-toggle="modal" data-target=".carga"><div class="izquierda">
			<span class="glyphicon glyphicon-upload"></span> &rlm; Carga de informacion
		</div> </a>
		<a href="#" data-toggle="modal" data-target=".filtro"><div class="derecha">
			<span class="glyphicon glyphicon-search"></span> &rlm; Filtrar
		</div> </a>
	</div>	

    <div id="contenido">	
		<center><span class="tramo"><?php echo $tramoFiltro; ?></span>
			<table width="100%" height="auto" border="1" id="tabla">
				<thead>
				<tr align="center" class="titulo">
			      <td width="auto">TRAMO</td>
			      <td width="auto">ACTIVIDAD</td>	     
			      <td width="auto">SEM 1</td>
			      <td width="auto">SEM 2</td>
			      <td width="auto">SEM 3</td>
			      <td width="auto">SEM 4</td>
			      <td width="auto">CUERPO 1</td>
			      <td width="auto">RANGO 1</td>
			      <td width="auto">CUERPO 2</td>
			      <td width="auto">RANGO 2</td>
			      <td width="auto">CUERPO 3</td>
			      <td width="auto">RANGO 3</td>
			      <td width="auto">CUERPO 4</td>
			      <td width="auto">RANGO 4</td>
			      <td width="auto">CUERPO 5</td>
			      <td width="auto">RANGO 5</td>
				</tr>
			  </thead>
			  <tbody>
			  	<?php echo $tabla; ?>
			  </tbody>
			</table>	
			<br>
			<div id='chartContainer' style="width:100%; height:400px"></div>	
		</center>
	</div>
    
    
    
<!-- FILTRO -->
<form action="SCTCapex2.php" method="post" enctype="multipart/form-data" >
<div class="modal fade filtro" id="filtro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center>  <img src="images/filtro_header.png" height="40"> Filtro de informaci&oacute;n <img src="images/filtro_header1.png" height="40">  </center></h4>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">
        	<!--<div class="col-lg-3"></div>-->
        	<div class="col-lg-2">Tramo:</div>
        	<div class="col-lg-8">
            	<select class="form-control" id="tramoFiltro" name="tramoFiltro">
					<?php
					  $i=1;
					  $sql = "SELECT DISTINCT TRAMO FROM CatTramos";
					  echo $sql;
					  $rs = odbc_exec( $conn, $sql );
					  if ( !$rs ) { 
					   exit( "Error en la consulta SQL" ); 
					  }     
					  while ( odbc_fetch_row($rs) ) { 
						$tramo = odbc_result($rs, 'TRAMO');
					   echo "<option id='".$i."'>".$tramo."</option>";
					   $i++;
					  }//While 	 
                	?>
                    <option value="TODOS">Farac</option>
                </select>
            </div>
        	<!--<div class="col-lg-1">A&ntilde;o:</div>
        	<div class="col-lg-3"><input type="number" class="form-control" id="anioFiltro" name="anioFiltro">
            </div>-->
        </div><br>        
        <!--FIN-->
   </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="filtro" id="filtro">Aceptar</button>
      </div>
    </div>
  </div>
  </div>
</form>    

<!--CARGA DE INFORMACION-->
<form action="SCTCapex2.php" method="post" enctype="multipart/form-data" >
<div class="modal fade carga" id="carga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center> Carga de informaci&oacute;n </center></h4>
      </div>
      <div class="modal-body">
      	<!--CUERPO-->
        <div class="row">
        	<div class="col-lg-1">Tramo:</div>
        	<div class="col-lg-7">
            	<select class="form-control" id="tramo" name="tramo">
					<?php
					  $i=1;
					  $sql = "SELECT DISTINCT TRAMO FROM CatTramos";
					  echo $sql;
					  $rs = odbc_exec( $conn, $sql );
					  if ( !$rs ) { 
					   exit( "Error en la consulta SQL" ); 
					  }     
					  while ( odbc_fetch_row($rs) ) { 
						$tramo = odbc_result($rs, 'TRAMO');
					   echo "<option id='".$i."'>".$tramo."</option>";
					   $i++;
					  }//While 	 
                	?>
                </select>
            </div>
        	<div class="col-lg-1">Periodo:</div>
        	<div class="col-lg-3"><input type="number" class="form-control" id="periodo" name="periodo" value="<?php echo $anio.$month; ?>" readonly>
            </div>
        </div> 
        <br> 
        <div class="row">
        	<div class="col-lg-1">Actividad:</div>
        	<div class="col-lg-7">
            	<select class="form-control" id="actividad" name="actividad">
					<?php
					  $i=1;
					  $sql = "SELECT DISTINCT ACTIVIDAD, ID FROM PresupuestoCapexSCT";
					  echo $sql;
					  $rs = odbc_exec( $conn, $sql );
					  if ( !$rs ) { 
					   exit( "Error en la consulta SQL" ); 
					  }     
					  while ( odbc_fetch_row($rs) ) { 
						$actividad = odbc_result($rs, 'ACTIVIDAD');
						$id = odbc_result($rs, 'ID');
					   echo "<option id='".$i."' value='".$id."*".$actividad."'>".$actividad."</option>";
					   $i++;
					  }//While 	 
                	?>
                </select>
            </div>
        </div>
        <hr>
        <center><span><strong>Cantidades ejecutadas por semana</strong></span></center>
        <br>
        <div class="row">
        	<div class="col-lg-1">Sem 1:</div>
        	<div class="col-lg-2"><input type="number" class="form-control" id="sem1" name="sem1" value="0">
            </div> 
        	<div class="col-lg-1">Sem 2:</div>
        	<div class="col-lg-2"><input type="number" class="form-control" id="sem2" name="sem2" value="0">
            </div> 
        	<div class="col-lg-1">Sem 3:</div>
        	<div class="col-lg-2"><input type="number" class="form-control" id="sem3" name="sem3" value="0">
            </div> 
        	<div class="col-lg-1">Sem 4:</div>
        	<div class="col-lg-2"><input type="number" class="form-control" id="sem4" name="sem4" value="0">
            </div> 
        </div> 
        <hr>
        <center><span><strong>Rango de KM ejecutados</strong></span></center>
        <br>
        <table>
		<?php 
           for ($i = 1; $i <= 5; $i++) { 
               $x = $i + 100;
               $z = $x + 1;
               if ($i==1){
                   echo "<tr name='".$x."'>
                            <td width='50'>".$i."</td>
                            <td width='140' align='center'>Cuerpo:</td>
                            <td width='158'>
								<select id='cuerpo".$i."' name='cuerpo".$i."' class='form-control' required>
									<option value=''>Elige</option>
									<option value='A'>A</option>
									<option value='B'>B</option>
								</select>
							</td>
                            <td width='207' align='center'>Km Inicial:</td>
                            <td width='144'><input type='number' class='form-control' id='kmini".$i."' name='kmini".$i."' min='1' value='0'></td>
                            <td width='150' align='center''>Km Final:</td>
                            <td width='144'><input type='number' class='form-control' id='kmfin".$i."' name='kmfin".$i."' min='1' value='0'></td>
                            <td width='80' align='center'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>
                        </tr>";
               }else{
				   echo "<tr name='".$x."' style='display:none'>
                            <td width='50'>".$i."</td>
                            <td width='140' align='center'>Cuerpo:</td>
                            <td width='158'>
								<select id='cuerpo".$i."' name='cuerpo".$i."' class='form-control'>
									<option value=''>Elige</option>
									<option value='A'>A</option>
									<option value='B'>B</option>
								</select>
							</td>
                            <td width='207' align='center'>Km Inicial:</td>
                            <td width='144'><input type='number' class='form-control' id='kmini".$i."' name='kmini".$i."' value='0'></td>
                            <td width='150' align='center'>Km Final:</td>
                            <td width='144'><input type='number' class='form-control' id='kmfin".$i."' name='kmfin".$i."' value='0'></td>
                            <td width='80' align='center'><a href=\"javascript:MostrarFilas('".$z."')\"><img id='sac".$x."' src='images/plus2.png' width='30' height='30'></a></td>
                        </tr>";
               }	   
           }	
        ?>
        </table>
        <hr>
        <center><span><strong>Fotos</strong></span></center>
        <br>
        <div class="row"> 
	        <div class="col-lg-12 m-heading-1 border-green m-bordered">
		        <div class="portlet-body form">
		            <div class="form-body">                                                                    
		                <div class="form-group">                                                      
		                    <div class="row">
		                        <div class="col-md-2">
		                            <input type="file" name="foto1" id="foto1" accept="application/pdf, image/png, image/jpeg, image/jpg, image/bmp" >
		                            <img id="imgFoto1" name="imgFoto1" class="imagenFoto">
		                            <input type="hidden" id="imgFoto1_r" name="imgFoto1_r" > 
		                        </div> 
		                        
		                        <div class="col-md-2">
		                            <input type="file" name="foto2" id="foto2" accept="application/pdf, image/png, image/jpeg, image/jpg, image/bmp" >
		                            <img id="imgFoto2" name="imgFoto2" class="imagenFoto">
		                            <input type="hidden" id="imgFoto2_r" name="imgFoto2_r" > 
		                        </div> 
		                        
		                        <div class="col-md-2">
		                            <input type="file" name="foto3" id="foto3" accept="application/pdf, image/png, image/jpeg, image/jpg, image/bmp" >
		                            <img id="imgFoto3" name="imgFoto3" class="imagenFoto">
		                            <input type="hidden" id="imgFoto3_r" name="imgFoto3_r" > 
		                        </div> 

		                        <div class="col-md-2">
		                            <input type="file" name="foto4" id="foto4" accept="application/pdf, image/png, image/jpeg, image/jpg, image/bmp" >
		                            <img id="imgFoto4" name="imgFoto4" class="imagenFoto">
		                            <input type="hidden" id="imgFoto4_r" name="imgFoto4_r" > 
		                        </div> 
		                        
		                        <div class="col-md-2">
		                            <input type="file" name="foto5" id="foto5" accept="application/pdf, image/png, image/jpeg, image/jpg, image/bmp" >
		                            <img id="imgFoto5" name="imgFoto5" class="imagenFoto">
		                            <input type="hidden" id="imgFoto5_r" name="imgFoto5_r" > 
		                        </div>

		                        <div class="col-md-2">
		                            <input type="file" name="foto6" id="foto6" accept="application/pdf, image/png, image/jpeg, image/jpg, image/bmp" >
		                            <img id="imgFoto6" name="imgFoto6" class="imagenFoto">
		                            <input type="hidden" id="imgFoto6_r" name="imgFoto6_r" > 
		                        </div>                                                                            
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
        <button type="submit" class="btn btn-primary" name="guardar" id="guardar">Aceptar</button>
      </div>
    </div>
  </div>
  </div>
</form>       	   	

<!-- BEGIN CORE PLUGINS -->
<link rel="stylesheet" href="../../Tools/js/jquery.ezdz.mini.css"> 
<script src="../../Tools/js/jquery.ezdz.min.js"></script>

<script>
    $('[id="foto1"]').ezdz({
        text: '1',
        validators: {
            maxWidth:  36480,
            maxHeight: 27360
        },
        reject: function(file, errors) {
            if (errors.mimeType) {
                alert(file.name + ' must be an image.');
            }

            if (errors.maxWidth) {
                alert(file.name + ' ');
            }

            if (errors.maxHeight) {
                alert(file.name + ' ');
            }
        }
    });  
    $('[id="foto2"]').ezdz({
        text: '2',
        validators: {
            maxWidth:  36480,
            maxHeight: 27360
        },
        reject: function(file, errors) {
            if (errors.mimeType) {
                alert(file.name + ' must be an image.');
            }

            if (errors.maxWidth) {
                alert(file.name + ' ');
            }

            if (errors.maxHeight) {
                alert(file.name + ' ');
            }
        }
    });  
    $('[id="foto3"]').ezdz({
        text: '3',
        validators: {
            maxWidth:  36480,
            maxHeight: 27360
        },
        reject: function(file, errors) {
            if (errors.mimeType) {
                alert(file.name + ' must be an image.');
            }

            if (errors.maxWidth) {
                alert(file.name + ' ');
            }

            if (errors.maxHeight) {
                alert(file.name + ' ');
            }
        }
    });
    $('[id="foto4"]').ezdz({
        text: '4',
        validators: {
            maxWidth:  36480,
            maxHeight: 27360
        },
        reject: function(file, errors) {
            if (errors.mimeType) {
                alert(file.name + ' must be an image.');
            }

            if (errors.maxWidth) {
                alert(file.name + ' ');
            }

            if (errors.maxHeight) {
                alert(file.name + ' ');
            }
        }
    });
    $('[id="foto5"]').ezdz({
        text: '5',
        validators: {
            maxWidth:  36480,
            maxHeight: 27360
        },
        reject: function(file, errors) {
            if (errors.mimeType) {
                alert(file.name + ' must be an image.');
            }

            if (errors.maxWidth) {
                alert(file.name + ' ');
            }

            if (errors.maxHeight) {
                alert(file.name + ' ');
            }
        }
    });
    $('[id="foto6"]').ezdz({
        text: '6',
        validators: {
            maxWidth:  36480,
            maxHeight: 27360
        },
        reject: function(file, errors) {
            if (errors.mimeType) {
                alert(file.name + ' must be an image.');
            }

            if (errors.maxWidth) {
                alert(file.name + ' ');
            }

            if (errors.maxHeight) {
                alert(file.name + ' ');
            }
        }
    });
</script>       

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../../Tools/Metronic/pages/scripts/profile.min.js" type="text/javascript"></script>
<script src="../../Tools/Metronic/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<script src="../../Tools/Metronic/pages/scripts/ui-modals.min.js" type="text/javascript"></script>
<script src="../../Tools/Metronic/pages/scripts/form-samples.min.js" type="text/javascript"></script>        
<script src="../../Tools/Metronic/pages/scripts/ui-blockui.min.js" type="text/javascript"></script>
<script src="../../Tools/Metronic/global/plugins/icheck/icheck.min.js" type="text/javascript"></script>
<script src="../../Tools/Metronic/pages/scripts/form-icheck.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->    

<script type="text/javascript" src="../jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxinput.js"></script>
<script type="text/javascript" src="../scripts/demos.js"></script>
<link rel="stylesheet" href="../jqwidgets/styles/jqx.base.css" type="text/css" /> 

</body>
</html>