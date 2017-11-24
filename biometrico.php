<?php 
require_once('Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );
date_default_timezone_set('America/Mexico_City');
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
//print_r($_POST);
//echo $_SESSION['S_Base']."/n";
$query='';
$base = substr($_SESSION['S_Base'], 0,4);
//echo $base;
switch($base){
	case "TE01":
		$id = "1";
		$conect = "TE01";
	break;
	case "TO01":
		$id = "2";
		$conect = "TO01";
	break;
	case "OC01":
		$id = "1";
		$conect = "OC01";
	break;
	case "PA01":
		$id = "2";
		$conect = "PA01";
	break;
	case "ZI01":
		$id = "0";
		$conect = "ZI01";
	break;
	case "JA01":
		$id = "1";
		$conect = "JA01";
		$query = " AND (catEmployee.KeyEmployeeGroup='2')";
	break;
	case "EN01":
		$id = "2";
		$conect = "EN01";
	break;
	case "USA01":
		$id = "2";
		$conect = "OC01";
	break;
	default:
		$conect = "TO01"; 
		$id = "1";
}
//echo ;
require_once('Connections/'.$conect.'.php');
//---------------------------------------------------------FILTRO-----------------------------------
if (isset($_REQUEST['filtrar'])) {
	$fecha  = $_POST['fecha_filtro'];
	$base = $_POST['base_filtro'];
	//echo $base;
	
	switch($base){
		case "TE01":
			$id = "1";
			$conect = "TE01";
		break;
		case "TO01":
			$id = "2";
			$conect = "TO01";
		break;
		case "OC01":
			$id = "1";
			$conect = "OC01";
		break;
		case "PA01":
			$id = "2";
			$conect = "PA01";
		break;
		case "ZI01":
			$id = "0";
			$conect = "ZI01";
		break;
		case "JA01":
			$id = "1";
			$conect = "JA01";
			$query = " AND (catEmployee.KeyEmployeeGroup='2')";
		break;
		case "EN01":
			$id = "2";
			$conect = "EN01";
		break;
		case "USA01":
			$id = "2";
			$conect = "OC01";
		break;
		default:
			$conect = "TO01"; 
			$id = "1";
	}
	
	require_once('Connections/'.$conect.'.php');


//SEMANA ACTUAL
$year=substr($fecha, 0,4);
$month=substr($fecha, 5,2);
$day=substr($fecha, 8,2);

# Obtenemos el día de la semana de la fecha dada
$diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
# el 0 equivale al domingo...
if($diaSemana==0)
    $diaSemana=7;
# A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
$primerDia=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
$numsemactual=date("W",mktime(0,0,0,$month,$day,$year));
//echo $numsemactual;
# A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
$ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year)); 
$primerDia = date("Y-m-d",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
$dia1 = explode ("-", $primerDia);
$diaDos = strtotime ( '+1 day' , strtotime ( $primerDia ) ) ;
$diaDos = date ( 'Y-m-d' , $diaDos );	
$dia2 = explode ("-", $diaDos);	
$diaTres = strtotime ( '+2 day' , strtotime ( $primerDia ) ) ;
$diaTres = date ( 'Y-m-d' , $diaTres );	
$dia3 = explode ("-", $diaTres);	
$diaCuatro = strtotime ( '+3 day' , strtotime ( $primerDia ) ) ;
$diaCuatro = date ( 'Y-m-d' , $diaCuatro );	
$dia4 = explode ("-", $diaCuatro);	
$diaCinco = strtotime ( '+4 day' , strtotime ( $primerDia ) ) ;
$diaCinco = date ( 'Y-m-d' , $diaCinco );	
$dia5 = explode ("-", $diaCinco);
$diaSeis = strtotime ( '+5 day' , strtotime ( $primerDia ) ) ;
$diaSeis = date ( 'Y-m-d' , $diaSeis );	
$dia6 = explode ("-", $diaSeis);	
$diaSiete = strtotime ( '+6 day' , strtotime ( $primerDia ) ) ;
$diaSiete = date ( 'Y-m-d' , $diaSiete );	
$dia7 = explode ("-", $diaSiete);

$semana = "Del " .$primerDia. " al " .$diaSiete;
	
}else{
//require_once('Connections/tonala.php');
$fecha = date("Y-m-d");	
//SEMANA ACTUAL
$year=substr($fecha, 0,4);
$month=substr($fecha, 5,2);
$day=substr($fecha, 8,2);

# Obtenemos el día de la semana de la fecha dada
$diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
# el 0 equivale al domingo...
if($diaSemana==0)
    $diaSemana=7;
# A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
$primerDia=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
$numsemactual=date("W",mktime(0,0,0,$month,$day,$year));
//echo $numsemactual;
//echo $primerDia;
# A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
$ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year)); 
//echo $ultimoDia;
$primerDia = date("Y-m-d",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
$dia1 = explode ("-", $primerDia);
$diaDos = strtotime ( '+1 day' , strtotime ( $primerDia ) ) ;
$diaDos = date ( 'Y-m-d' , $diaDos );
$dia2 = explode ("-", $diaDos);	
$diaTres = strtotime ( '+2 day' , strtotime ( $primerDia ) ) ;
$diaTres = date ( 'Y-m-d' , $diaTres );	
$dia3 = explode ("-", $diaTres);	
$diaCuatro = strtotime ( '+3 day' , strtotime ( $primerDia ) ) ;
$diaCuatro = date ( 'Y-m-d' , $diaCuatro );	
$dia4 = explode ("-", $diaCuatro);	
$diaCinco = strtotime ( '+4 day' , strtotime ( $primerDia ) ) ;
$diaCinco = date ( 'Y-m-d' , $diaCinco );	
$dia5 = explode ("-", $diaCinco);
$diaSeis = strtotime ( '+5 day' , strtotime ( $primerDia ) ) ;
$diaSeis = date ( 'Y-m-d' , $diaSeis );	
$dia6 = explode ("-", $diaSeis);	
$diaSiete = strtotime ( '+6 day' , strtotime ( $primerDia ) ) ;
$diaSiete = date ( 'Y-m-d' , $diaSiete );	
$dia7 = explode ("-", $diaSiete);
$semana = "Del " .$primerDia. " al " .$diaSiete;
}

/*************************************************GRID*************************************************/
$x=0;
$filas = array();
$sql = "SELECT DISTINCT detPunches.Badge AS NUM_EMP, detPunches.BelongDate AS FECHA, detPunches.PunchTime AS HORA, detPunches.SoftKey AS ENTRADA_SALIDA, catCLOCKS.Descrip AS DESCRIP, catEmployee.Name1 + ' ' + catEmployee.Name2 + ' ' + catEmployee.LastName1 + ' ' + catEmployee.LastName2 AS NOMBRE, catEmployee.Number, DATEPART(HOUR, detPunches.PunchTime) AS HORA1 FROM detPunches INNER JOIN catEmployee ON detPunches.Badge = catEmployee.Badge INNER JOIN catCLOCKS ON detPunches.KeyTerminal = catCLOCKS.Terminal WHERE  (catCLOCKS.Terminal = '".$id."') AND (detPunches.Badge NOT IN ('01351', '91351')) AND (YEAR(detPunches.BelongDate) = '".$year."') AND (MONTH(detPunches.BelongDate) = '".$month."') AND (DAY(detPunches.BelongDate) = '".$day."')".$query;
//echo $sql;
  $rs = odbc_exec( $conn2, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {
  $filas[$x] = array_map('utf8_encode',$row);
   $x++;
 }//While
$datos =  $filas;    
//OBTENEMOS LA HORA
 while (odbc_fetch_array($rs)) {
	 $hora = odbc_result($rs, 'HORA1');
 #echo $hora;
 }//While
//echo json_encode($datos); 
//echo $_SESSION['S_Plaza'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title id='Description'>Biometrico</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />    
	<link rel="stylesheet" href="CssLocal/Menus.css"><!--Necesario para el Menu-->
	<link rel="stylesheet" href="css/styl.css"><!--Necesario para Menu 2-->
	<link rel="stylesheet" href="css/menuLateral.css"> <!--Necesario para Menu 3-->	
    <link href="css/bootstrap.min.css" rel="stylesheet" />
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
    <script type="text/javascript" src="jqwidgets/jqxnotification.js"></script>
    <!--CSS AJUSTE PANTALLA-->
	<style>		
       
	body, html {
		width: 100%;
		height: 100%;
		overflow: hidden;
	}
	.contenedor {
		width: 100%;
		height: 100%;
		overflow: hidden;		
		box-sizing: border-box;
		padding: 0px;
		margin: 0 auto;
		/*max-width: 1000px;*/
		font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;
	}

	.main {
		margin-left: 5px;
		margin-right: 15px;
		position: relative;		
		padding: 5px;
		width: 84%;
		height: 100%;
		float: left;		
		background: white;		
		overflow: hidden;
		box-sizing: border-box;
		overflow:hidden;
	}	

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
	.seleccion1{
		background: #e4e4e4;
	}
	.seleccion0{
		background:#FFFFFF;
	}
		
</style>
<script type="text/javascript">
$(document).ready(function () {
			 //EFECTOS EN EL MENU
            $("#menu2").mouseenter(function(e){
				$("#menu2").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu2").mouseleave(function(e){
				$("#menu2").css({"background": "#2c2c2c"});
			});
			$("#menu3").mouseenter(function(e){
				$("#menu3").css({"background": "#49A2FF", "border":"0px"});
			});
			$("#menu3").mouseleave(function(e){
				$("#menu3").css({"background": "#2c2c2c"});			  
			});			
			$("#menu4").mouseenter(function(e){
				$("#menu4").css({"background": "#49A2FF", "border":"0px"});
			});
			$("#menu4").mouseleave(function(e){
				$("#menu4").css({"background": "#2c2c2c"});
			});	
			$("#menu5").mouseenter(function(e){
				$("#menu5").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu5").mouseleave(function(e){
				$("#menu5").css({"background": "#2c2c2c"});
			});	
			$("#menu6").mouseenter(function(e){
				$("#menu6").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu6").mouseleave(function(e){
				$("#menu6").css({"background": "#2c2c2c"});
			});	
			$("#menu7").mouseenter(function(e){
				$("#menu7").css({"background": "#49A2FF", "border":"0px"});
			});
			$("#menu7").mouseleave(function(e){
				$("#menu7").css({"background": "#49A2FF"});
			});	
			$("#menu8").mouseenter(function(e){
				$("#menu8").css({"background": "#49A2FF", "border":"0px"});			  
			});
			$("#menu8").mouseleave(function(e){
				$("#menu8").css({"background": "#2c2c2c"});
			});	
			$("#menu9").mouseenter(function(e){
				$("#menu9").css({"background": "#49A2FF", "border":"0px"});
			});
			$("#menu9").mouseleave(function(e){
				$("#menu9").css({"background": "#2c2c2c"});			  
			});	

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

			//TOOLTIP
			$("#festivo").jqxTooltip({ content: '<b><p>Festivo Laborado</p></b>', position: 'mouse', name: 'Nota'});
			$("#prima").jqxTooltip({ content: '<b><p>Prima Dominical</p></b>', position: 'mouse', name: 'Nota'});
			$("#descanso").jqxTooltip({ content: '<b><p>Descanso Laborado</p></b>', position: 'mouse', name: 'Nota'});
			$("#otro").jqxTooltip({ content: '<b><p>Otros Descuentos</p></b>', position: 'mouse', name: 'Nota'});

			//CREAMOS EL TAB			
            $('#tabsWidget').jqxTabs({ width: '95%', height: '75%', position: 'top'});
            $('#tabsWidget').jqxTabs('focus');

			//DETALLE DE ASISTENCIAS			
			var data =  <?php echo json_encode($datos); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'Number', type: 'string' },	
					{ name: 'FECHA', type: 'string' },
					{ name: 'HORA', type: 'string' },
                    { name: 'ENTRADA_SALIDA', type: 'string' }, 
					{ name: 'DESCRIP', type: 'string' },
					{ name: 'NOMBRE', type: 'string' }
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#asistencia").jqxGrid(
            {
                width: '95%',
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'No. Emp', dataField: 'Number', width: 65 },
				  { text: 'Nombre', dataField: 'NOMBRE', width: 350 },  
				  { text: 'Biometrico', dataField: 'DESCRIP', width: 100 },
				  { text: 'Fecha y Hora', dataField: 'HORA', width: 200 },
				  { text: 'IN/OUT', dataField: 'ENTRADA_SALIDA', width: 70 }
                ]
            });

});//document.ready	

function Procesar(){
	var contador = $("#contador").text();
	var semana = $("#semana").val();
	var base = $("#base").val();
	var numsem = '<?php echo $numsemactual; ?>';
	
	$.post("consultaAvanceDiario.php", { BorrarSemana: '',  Semana: semana  }, function(data){
			
	});

	for (var i = 1; i <= contador; i++) {
		//GUARDAMOS LOS REGISTROS
		noemp = $("#num" + i).val();
		nombre = $("#nombre" + i).val();
		total = $("#total" + i).val();
		dia1 = $("#dia1" + i).val();
		dia2 = $("#dia2" + i).val();
		dia3 = $("#dia3" + i).val();
		dia4 = $("#dia4" + i).val();
		dia5 = $("#dia5" + i).val();
		dia6 = $("#dia6" + i).val();
		dia7 = $("#dia7" + i).val();
		ext2 = $("#ext2" + i).val();
		ext3 = $("#ext3" + i).val();
		festivo = $("#festivo" + i).val();
		descanso = $("#descanso" + i).val();
		prima = $("#prima" + i).val();
		otro = $("#otro" + i).val();
		observaciones = $("#observaciones" + i).val();

		$.post("consultaAvanceDiario.php", { GuardaBiometrico: '', Noemp: noemp, Nombre: nombre, Dia1: dia1, Dia2: dia2, Dia3: dia3, Dia4: dia4, Dia5: dia5, Dia6: dia6, Dia7: dia7, Total: total, Ext2: ext2, Ext3: ext3, Festivo: festivo, Descanso: descanso, Prima: prima, Otro: otro, Observaciones: observaciones, Semana: semana, Base: base, Numsem: numsem  }, function(data){
		});
	}

	alert("Semana '"+semana+"' procesada");
	//$("#jqxNotification").jqxNotification("open");
}
</script>    
</head>
<body>

<div class="contenedor">
  <div class="menusuperior">
			<div class="logo">
					<center><img src="images/HEADBIOMETRICO.png" width="153" height="46" ></center>
	  		</div>
			<a href="Almacen.php"><div class="submenu_superior" id="menu2">				
				Insumos en Tramo
			</div></a> 
			<a href="Salidas.php"><div class="submenu_superior" id="menu3">				
					Regreso Insumos
			</div></a>
            <a href="AlmacenMaq.php"><div class="submenu_superior" id="menu8">				
					Salida Maq
			</div></a>	
            <a href="SalidasMaq.php"><div class="submenu_superior" id="menu8">
					Entrada Maq
			</div></a>
			<a href="AvanceDiarioPlus.php"><div class="submenu_superior" id="menu4">				
					Avance Diario
			</div></a>
			<a href="biometrico.php"><div class="submenu_superior_sel" id="menu7">
					Biom&eacute;trico
			</div></a>
            <?php 
			 if($_SESSION['S_Privilegios'] == 'ADMINISTRADOR'){
			 ?>
             <a href="Contratos.php"><div class="submenu_superior" id="menu5">
					Contratos
			</div></a>
			<a href="Comparativo.php"><div class="submenu_superior" id="menu6">
					Comparativa
			</div></a>	
			<?php } ?>
			
            			
		</div>
		<div class="menulateral">
        <div class="submenu_lateral_encabezado">
       <span class="glyphicon glyphicon-wrench"></span> &rlm; HERRAMIENTAS
        </div>
            <a href="#" data-toggle="modal" data-target=".filtro"><div class="submenu_lateral" id="sub1">
				<span class="glyphicon glyphicon-filter"></span> &rlm; Filtro
			</div></a>
			<a href="#" onClick="Procesar()"><div class="submenu_lateral" id="sub2">
				<span class="glyphicon glyphicon-check"></span> &rlm; Procesar en SAC 
			</div></a>
			<!--<a href="#" data-toggle="modal" data-target=".exportar"><div class="submenu_lateral" id="sub3">
				<span class="glyphicon glyphicon-download"></span> &rlm; Descargar 
			</div></a>-->
		</div>
<div class="main">
<!-- Page content -->
      <br>
      <center>
        <div id='jqxWidget'>
          <div id="tabsWidget">
            <ul style="margin-left: 30px;">
              <li>Pren&oacute;mina</li>
              <li>Detalle</li>
            </ul>
            <div> 
            <div>
            <br>
            <div align="right"><strong>Semana:</strong> <label><?php echo $semana; ?></label></div>
            <div align="center"><strong>Base:</strong> <label><?php echo $base; ?></label></div>
                <br>
<form id="formulario" action="biometrico.php" method="post" enctype="multipart/form-data" >
<table width="100%" height="20" border="1">
  <tbody align="center">
    <tr bgcolor="#A2A2A2">
      <td width="30" height="42"><strong>#</strong></td>
      <td width="76"><strong>No. Emp</strong></td>
      <td width="250"><strong>Nombre</strong></td>
      <td width="30"><strong>L <?php echo $dia1[2]; ?></strong></td>
      <td width="30"><strong>M <?php echo $dia2[2]; ?></strong></td>
      <td width="30"><strong>M <?php echo $dia3[2]; ?></strong></td>
      <td width="30"><strong>J <?php echo $dia4[2]; ?></strong></td>
      <td width="30"><strong>V <?php echo $dia5[2]; ?></strong></td>
      <td width="30"><strong>S <?php echo $dia6[2]; ?></strong></td>
      <td width="30"><strong>D <?php echo $dia7[2]; ?></strong></td>
      <td width="30"><strong>Total</strong></td>
      <td width="30"><strong>Ext 2</strong></td>
      <td width="30"><strong>Ext 3</strong></td>
      <td width="30"><strong id="festivo">F.L.</strong></td>
      <td width="30"><strong id="descanso">D.L.</strong></td>
      <td width="30"><strong id="prima">P.D.</strong></td>
      <td width="30"><strong id="otro">O.D.</strong></td>
      <td width="280"><strong>Observaciones<input type="hidden" id="semana" name="semana" value="<?php echo $semana;  ?>"></strong></td>
    </tr>
  </tbody>
<?php 
$cont = 0;	
$obser = '';
$x=0;
$sql = "SELECT DISTINCT detPunches.Badge, catEmployee.Name1 + ' ' + catEmployee.Name2 + ' ' + catEmployee.LastName1 + ' ' + catEmployee.LastName2 AS NOMBRE, catCLOCKS.Descrip FROM detPunches LEFT OUTER JOIN catCLOCKS ON detPunches.TerminalID = catCLOCKS.Terminal LEFT OUTER JOIN catEmployee ON detPunches.Badge = catEmployee.Badge WHERE (detPunches.TerminalID = '".$id."') AND (detPunches.Badge NOT IN ('01351')) AND (detPunches.BelongDate BETWEEN '".$primerDia."' AND '".$diaSiete."')".$query." ORDER BY Nombre";
//echo $sql;
    $rs = odbc_exec($conn2, $sql);
    if ( !$rs ) { 
      exit( "Error en la consulta SQL" ); 
    } 		
	while ( odbc_fetch_row($rs) ) {
		$noemp = odbc_result($rs, 'Badge');
		$base = odbc_result($rs, 'Descrip');
		$cont ++;

		echo "<tr><input type='hidden' id='base' value='".$base."'>
				<td width='30' height='4' align='center' class ='seleccion".$x."'><input type='hidden' id='cont' value='".$cont."'>".$cont."</td>
				<td width='74' align='center' class ='seleccion".$x."'><input id='num".$cont."' name='num".$cont."' type='hidden' value='".odbc_result($rs, 'Badge')."'>".odbc_result($rs, 'Badge')."</td>
				<td width='250' class ='seleccion".$x."'><input id='nombre".$cont."' name='nombre".$cont."' type='hidden' value='".odbc_result($rs, 'NOMBRE')."'>".odbc_result($rs, 'NOMBRE')."</td>";
				$totaldias = 0;
				for($i = 1; $i <= 7; $i++){
					$bandera=false;
					$sql2 = "SELECT TOP 1 * FROM detPunches where Badge='".$noemp."' and BelongDate='".$primerDia."'";
					$rs2 = odbc_exec($conn2, $sql2);
					if ( !$rs2 ) { 
					  exit( "Error en la consulta SQL" ); 
					} 								
					while ( odbc_fetch_row($rs2) ) {
						$bandera = true;
						echo "<td width='30' id='".$i."' class ='seleccion".$x."'><input id='dia".$i.$cont."' name='dia".$i.$cont."' type='hidden' value='1'>1</td>";
						$totaldias ++;
					}//While 

					if ($bandera == false){
						echo "<td width='30' id='".$i."' class ='seleccion".$x."'><input id='dia".$i.$cont."' name='dia".$i.$cont."' type='hidden' value='0'>0</td>";
					}
					$sql3 = "SELECT * FROM Prenomina where NO_EMP='".$noemp."' and SEMANA like '%".$primerDia."%'";
					$rs3 = odbc_exec($conn, $sql3);
					if ( !$rs3 ) { 
					  exit( "Error en la consulta SQL" ); 
					}
					while ( odbc_fetch_row($rs3) ) {
						$obser = odbc_result($rs3, 'OBSERVACIONES');
					}
					$fechaSig = strtotime ( '+1 day' , strtotime ( $primerDia ) ) ;
					$primerDia = date ( 'Y-m-d' , $fechaSig );
				}//for
				$fechaOriginal = strtotime ( '-7 day' , strtotime ( $primerDia ) ) ;
				$primerDia = date ( 'Y-m-d' , $fechaOriginal );
				
   	      echo "<td width='30' align='center' class ='seleccion".$x."'><input id='total".$cont."' name='total".$cont."' type='hidden' value='".$totaldias."'>".$totaldias."</td>
				<td width='30' class ='seleccion".$x."'><input id='ext2".$cont."' name='ext2".$cont."' type='hidden' value='0'>0</td>
				<td width='30' class ='seleccion".$x."'><input id='ext3".$cont."' name='ext3".$cont."' type='hidden' value='0'>0</td>
				<td width='30' class ='seleccion".$x."'><input id='festivo".$cont."' name='festivo".$cont."' type='hidden' value='0'>0</td>
				<td width='30' class ='seleccion".$x."'><input id='descanso".$cont."' name='descanso".$cont."' type='hidden' value='0'>0</td>
				<td width='30' class ='seleccion".$x."'><input id='prima".$cont."' name='prima".$cont."' type='hidden' value='0'>0</td>
				<td width='30' class ='seleccion".$x."'><input id='otro".$cont."' name='otro".$cont."' type='hidden' value='0'>0</td>
				<td width='280' class ='seleccion".$x."'><textarea class='form-control' size='3' name='observaciones".$cont."' id='observaciones".$cont."' rows='1'>".$obser."</textarea></td>
			  </tr>";
			  
			  	if ($x == 0) {
					$x++;
				}else{
					$x--;
				}	
			  
	}//While
	echo "<span id='contador' style='display:none'>". $cont ." </span>";
?>
</table>
</form>
            </div>
            </div>
            <div> <br/>
              <center>
                <div id="asistencia"></div>
              </center>
            </div>
          </div>
        </div>
<!--End Page content-->            
</div>
</div>

<!-- FILTRO -->
<form id="formulario" action="biometrico.php" method="post" enctype="multipart/form-data" >      
<div class="modal fade filtro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><center>  <img src="images/filtro_header.png" height="40"> Filtro de informaci&oacute;n <img src="images/filtro_header1.png" height="40">  </center></h4>
      </div>
      <div class="modal-body">
      <!--CUERPO-->
   <div class="panel panel-default">
  	  <div class="panel-heading" role="tab" id="headingFour">
    	  <h4 class="panel-title">
    	     	<strong>Par&aacute;metros</strong>
    	  </h4>
   	   		<div class="panel-body"> 
            	<div class="row">
					<div class="col-lg-2">
                    	Fecha:
                    </div>  
					<div class="col-lg-5">
                    	<input class="form-control" type="date" name="fecha_filtro" id="fecha_filtro" step="1" value="<?php echo date("Y-m-d"); ?>" >
                    </div>
                 </div>
                 <br>
            	<div class="row">
					<div class="col-lg-2">
                    	Base:
                    </div>  
					<div class="col-lg-5">
                        <select name="base_filtro" id="base_filtro" class="form-control" required>
                            <?php	
                              $i=1;
							  $selected="";
							  $sql = "SELECT DISTINCT Base FROM Accesos WHERE Usuario_ID='".$_SESSION['S_UsuarioID']."'";
							  echo $sql;
							  $rs = odbc_exec( $conn, $sql );
							  if ( !$rs ) { 
							   exit( "Error en la consulta SQL" ); 
							  }     
							  while ( odbc_fetch_row($rs) ) { 
								$base1 = odbc_result($rs, 'Base');
							   echo "<option id='".$i."'>".$base1."</option>";
							   $i++;
							  }//While 
                            ?> 
                        </select>
                    </div>
                 </div> 
     		</div>
    	</div>
  	</div> 
    <!--FIN-->
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

<!--DESCARGAR-->
<form id="formulario3" action="Excel_Prenomina.php" method="post" enctype="multipart/form-data" >
<div class="modal fade exportar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title" id="myModalLabel">Periodo a Exportar</h4>
      </div>
      <div class="modal-body">
       <!--CUERPO-->
<div class="row">
<input type="hidden" id="l" name="l" value="<?php echo $dia1[2]; ?>">
<input type="hidden" id="m" name="m" value="<?php echo $dia2[2]; ?>">
<input type="hidden" id="mr" name="mr" value="<?php echo $dia3[2]; ?>">
<input type="hidden" id="j" name="j" value="<?php echo $dia4[2]; ?>">
<input type="hidden" id="v" name="v" value="<?php echo $dia5[2]; ?>">
<input type="hidden" id="s" name="s" value="<?php echo $dia6[2]; ?>">
<input type="hidden" id="d" name="d" value="<?php echo $dia7[2]; ?>">
	<br>
</div>     
<div class="row">
   <div class="col-md-2" align="center">
    </div>
   <div class="col-md-2" align="right">
   	Semana:
    </div>
   <div class="col-md-6">
   		<select name="Semana" id="semana" class="form-control" required>
        	<?php	
			  $i=1;
			  $sql = "SELECT TOP 2 SEMANA FROM Prenomina GROUP BY SEMANA ORDER BY SEMANA DESC";
			  echo $sql;
			  $rs = odbc_exec( $conn, $sql );
			  if ( !$rs ) { 
			   exit( "Error en la consulta SQL" ); 
			  }     
			  while ( odbc_fetch_row($rs) ) { 
			   $semanaF = odbc_result($rs, 'SEMANA');  
			   echo "<option id='".$i."'".$selected.">".$semanaF."</option>";
			   $i++;
			  }//While 
			?> 
        </select>
    </div>
   <div class="col-md-3">
    </div>
</div>
<div class="row">
	<br>
</div>     
<div class="row">
   <div class="col-md-12" align="center">
   <h6><strong>Nota:</strong> <em>Si la semana no aparece, ser&aacute; necesario procesarla</em> (<span class="glyphicon glyphicon-check"></span>).</h6>
    </div>
</div>
        <!--FIN-->
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="Limpiar()">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="Descargar" id="Descargar">Descargar</button>
      </div>
  </div>
    </div>
  </div>
</form>

<div id="jqxNotification" style="display:none">
	<p>Semana Procesada.</p>
</div>

</body>
</html>