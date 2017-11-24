<?php
 // Se define la cadena de conexión  
 $dsn = "Driver={SQL Server};

 Server=192.168.130.129;Database=SAC;Integrated Security=SSPI;Persist Security Info=False;";
 date_default_timezone_set('America/Mexico_City');

// Se realiza la conexón con los datos especificados anteriormente
 $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar: " . $conn);
 }
 
if (!isset($_SESSION)) {
  session_start();
} 
/** Error reporting */
error_reporting(E_ALL);


/** PHPExcel */
include ('../PHPExcel/Classes/PHPExcel.php');
include ('../PHPExcel/Classes/PHPExcel/IOFactory.php');

//Creamos un Documento nuevo
$objPHPExcel = new PHPExcel();

//Cargamos un documento
$objPHPExcel = PHPExcel_IOFactory::load("../sac/xls/ProMAQ.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC") // creador del documento
							 ->setLastModifiedBy("SAC")  //ultima modificacion
							 ->setTitle("ProrrateoMaq") // titulo del doc
							 ->setSubject("ProrrateoMaq")
							 ->setDescription("ProrrateoMaq")//descripcion
							 ->setKeywords("ProrrateoMaq")//palabras clave
							 ->setCategory("ProrrateoMaq");// categoria
							 
//print_r($_POST);
if (isset($_POST['descargar_maq'])){	
    $periodo= $_POST['rangoExcel'];
	$base = $_POST['baseExcel'];
	
}else{
	$periodo = "De 2016-09-26 A 2016-10-25";
	$base = "TO01";
}

/***************************************************************RCA*********************************************************/
	$tramo1 = "";
	$subcuenta1 = "";
	$activo1 = 0;
	$renta1 = 0;
	$combustible1 = 0;
	$mtto1 = 0;
	$llantas1 = 0;
	$seguros1 = 0;
	$otros1 = 0;
	$activo2 = 0;
	$renta2 = 0;
	$combustible2 = 0;
	$mtto2 = 0;
	$llantas2 = 0;
	$seguros2 = 0;
	$otros2 = 0;
	$total = 0;
	$total1 = 0;
	$total2 = 0;
	$total3 = 0;
	$x = 0;

		
	$sql = "SELECT SUBCUENTA, SUM(ACTIVO_FIJO) AS ACTIVO, SUM(COMBUSTIBLE) AS COMBUSTIBLE, SUM(MANTENIMIENTO) AS MTTO, SUM(RENTA) AS RENTA, SUM(LLANTAS) AS LLANTAS, SUM(SEGUROS) AS SEGUROS, SUM(OTROS) AS OTROS, TRAMO FROM ProrrateoMAQ WHERE BASE = '".$base."' AND PERIODO = '".$periodo."' AND NO_ECO LIKE 'RCA%' GROUP BY SUBCUENTA, TRAMO";
		$rs = odbc_exec( $conn, $sql);
		if ( !$rs ) {
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) {  
			$subcuenta = odbc_result($rs, 'SUBCUENTA');
			$activo = odbc_result($rs, 'ACTIVO');
			$combustible = odbc_result($rs, 'COMBUSTIBLE');	
			$mtto = odbc_result($rs, 'MTTO');
			$renta = odbc_result($rs, 'RENTA');
			$llantas = odbc_result($rs, 'LLANTAS');	
			$seguros = odbc_result($rs, 'SEGUROS');
			$otros = odbc_result($rs, 'OTROS');
			$tramo = odbc_result($rs, 'TRAMO');	
			
			if ($x==0) {
				$tramo1 = $tramo;
				$x++;							
			}

			if($tramo1 == $tramo){
				$activo1 += $activo;
				$combustible1 += $combustible;
				$mtto1 += $mtto;
				$renta1 += $renta;
				$llantas1 += $llantas;
				$seguros1 += $seguros;
				$otros1 += $otros;			
							
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C10', utf8_encode($tramo));
				switch($subcuenta){
					case "AdA":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C12', utf8_encode($activo))
						->setCellValue('D12', utf8_encode($combustible))
						->setCellValue('E12', utf8_encode($mtto))
						->setCellValue('F12', utf8_encode($renta))
						->setCellValue('G12', utf8_encode($seguros))
						->setCellValue('H12', utf8_encode($otros))
						->setCellValue('I12', utf8_encode($llantas))
						->setCellValue('J12', utf8_encode($total));	
					break;
					case "DdV":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;	
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C13', utf8_encode($activo))
						->setCellValue('D13', utf8_encode($combustible))
						->setCellValue('E13', utf8_encode($mtto))
						->setCellValue('F13', utf8_encode($renta))
						->setCellValue('G13', utf8_encode($seguros))
						->setCellValue('H13', utf8_encode($otros))
						->setCellValue('I13', utf8_encode($llantas))
						->setCellValue('J13', utf8_encode($total));	
					break;
					case "Dren":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;	
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C14', utf8_encode($activo))
						->setCellValue('D14', utf8_encode($combustible))
						->setCellValue('E14', utf8_encode($mtto))
						->setCellValue('F14', utf8_encode($renta))
						->setCellValue('G14', utf8_encode($seguros))
						->setCellValue('H14', utf8_encode($otros))
						->setCellValue('I14', utf8_encode($llantas))
						->setCellValue('J14', utf8_encode($total));	
					break;
					case "SdR":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;	
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C15', utf8_encode($activo))
						->setCellValue('D15', utf8_encode($combustible))
						->setCellValue('E15', utf8_encode($mtto))
						->setCellValue('F15', utf8_encode($renta))
						->setCellValue('G15', utf8_encode($seguros))
						->setCellValue('H15', utf8_encode($otros))
						->setCellValue('I15', utf8_encode($llantas))
						->setCellValue('J15', utf8_encode($total));	
					break;
					case "SH":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C16', utf8_encode($activo))
						->setCellValue('D16', utf8_encode($combustible))
						->setCellValue('E16', utf8_encode($mtto))
						->setCellValue('F16', utf8_encode($renta))
						->setCellValue('G16', utf8_encode($seguros))
						->setCellValue('H16', utf8_encode($otros))
						->setCellValue('I16', utf8_encode($llantas))
						->setCellValue('J16', utf8_encode($total));	
					break;
					case "SUP":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C17', utf8_encode($activo))
						->setCellValue('D17', utf8_encode($combustible))
						->setCellValue('E17', utf8_encode($mtto))
						->setCellValue('F17', utf8_encode($renta))
						->setCellValue('G17', utf8_encode($seguros))
						->setCellValue('H17', utf8_encode($otros))
						->setCellValue('I17', utf8_encode($llantas))
						->setCellValue('J17', utf8_encode($total));	
					break;
					case "SV":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C18', utf8_encode($activo))
						->setCellValue('D18', utf8_encode($combustible))
						->setCellValue('E18', utf8_encode($mtto))
						->setCellValue('F18', utf8_encode($renta))
						->setCellValue('G18', utf8_encode($seguros))
						->setCellValue('H18', utf8_encode($otros))
						->setCellValue('I18', utf8_encode($llantas))
						->setCellValue('J18', utf8_encode($total));	
					break;
				}
				$total2 += $total;
			}else{
				$activo2 += $activo;
				$combustible2 += $combustible;
				$mtto2 += $mtto;
				$renta2 += $renta;
				$llantas2 += $llantas;
				$seguros2 += $seguros;
				$otros2 += $otros;
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C22', utf8_encode($tramo));
				switch($subcuenta){
					case "AdA":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C24', utf8_encode($activo))
						->setCellValue('D24', utf8_encode($combustible))
						->setCellValue('E24', utf8_encode($mtto))
						->setCellValue('F24', utf8_encode($renta))
						->setCellValue('G24', utf8_encode($seguros))
						->setCellValue('H24', utf8_encode($otros))
						->setCellValue('I24', utf8_encode($llantas))
						->setCellValue('J24', utf8_encode($total1));	
					break;
					case "DdV":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C25', utf8_encode($activo))
						->setCellValue('D25', utf8_encode($combustible))
						->setCellValue('E25', utf8_encode($mtto))
						->setCellValue('F25', utf8_encode($renta))
						->setCellValue('G25', utf8_encode($seguros))
						->setCellValue('H25', utf8_encode($otros))
						->setCellValue('I25', utf8_encode($llantas))
						->setCellValue('J25', utf8_encode($total1));	
					break;
					case "Dren":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C26', utf8_encode($activo))
						->setCellValue('D26', utf8_encode($combustible))
						->setCellValue('E26', utf8_encode($mtto))
						->setCellValue('F26', utf8_encode($renta))
						->setCellValue('G26', utf8_encode($seguros))
						->setCellValue('H26', utf8_encode($otros))
						->setCellValue('I26', utf8_encode($llantas))
						->setCellValue('J26', utf8_encode($total1));	
					break;
					case "SdR":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C27', utf8_encode($activo))
						->setCellValue('D27', utf8_encode($combustible))
						->setCellValue('E27', utf8_encode($mtto))
						->setCellValue('F27', utf8_encode($renta))
						->setCellValue('G27', utf8_encode($seguros))
						->setCellValue('H27', utf8_encode($otros))
						->setCellValue('I27', utf8_encode($llantas))
						->setCellValue('J27', utf8_encode($total1));	
					break;
					case "SH":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C28', utf8_encode($activo))
						->setCellValue('D28', utf8_encode($combustible))
						->setCellValue('E28', utf8_encode($mtto))
						->setCellValue('F28', utf8_encode($renta))
						->setCellValue('G28', utf8_encode($seguros))
						->setCellValue('H28', utf8_encode($otros))
						->setCellValue('I28', utf8_encode($llantas))
						->setCellValue('J28', utf8_encode($total1));	
					break;
					case "SUP":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C29', utf8_encode($activo))
						->setCellValue('D29', utf8_encode($combustible))
						->setCellValue('E29', utf8_encode($mtto))
						->setCellValue('F29', utf8_encode($renta))
						->setCellValue('G29', utf8_encode($seguros))
						->setCellValue('H29', utf8_encode($otros))
						->setCellValue('I29', utf8_encode($llantas))
						->setCellValue('J29', utf8_encode($total1));	
					break;
					case "SV":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C30', utf8_encode($activo))
						->setCellValue('D30', utf8_encode($combustible))
						->setCellValue('E30', utf8_encode($mtto))
						->setCellValue('F30', utf8_encode($renta))
						->setCellValue('G30', utf8_encode($seguros))
						->setCellValue('H30', utf8_encode($otros))
						->setCellValue('I30', utf8_encode($llantas))
						->setCellValue('J30', utf8_encode($total1));	
					break;
				}
						
			}	
				$total3 += $total1;
			//$costo = 0;
	}

	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('C19', utf8_encode($activo1))
		->setCellValue('D19', utf8_encode($combustible1))
		->setCellValue('E19', utf8_encode($mtto1))
		->setCellValue('F19', utf8_encode($renta1))
		->setCellValue('G19', utf8_encode($seguros1))
		->setCellValue('H19', utf8_encode($otros1))
		->setCellValue('I19', utf8_encode($llantas1))
		->setCellValue('J19', utf8_encode($total2))
		
		->setCellValue('C31', utf8_encode($activo2))
		->setCellValue('D31', utf8_encode($combustible2))
		->setCellValue('E31', utf8_encode($mtto2))
		->setCellValue('F31', utf8_encode($renta2))
		->setCellValue('G31', utf8_encode($seguros2))
		->setCellValue('H31', utf8_encode($otros2))
		->setCellValue('I31', utf8_encode($llantas2))
		->setCellValue('J31', utf8_encode($total3));

/***************************************************************RCO*********************************************************/
	$tramo1 = "";
	$subcuenta1 = "";
	$activo1 = 0;
	$renta1 = 0;
	$combustible1 = 0;
	$mtto1 = 0;
	$llantas1 = 0;
	$seguros1 = 0;
	$otros1 = 0;
	$activo2 = 0;
	$renta2 = 0;
	$combustible2 = 0;
	$mtto2 = 0;
	$llantas2 = 0;
	$seguros2 = 0;
	$otros2 = 0;
	$total = 0;
	$total1 = 0;
	$total2 = 0;
	$total3 = 0;
	$x = 0;

		
	$sql = "SELECT SUBCUENTA, SUM(ACTIVO_FIJO) AS ACTIVO, SUM(COMBUSTIBLE) AS COMBUSTIBLE, SUM(MANTENIMIENTO) AS MTTO, SUM(RENTA) AS RENTA, SUM(LLANTAS) AS LLANTAS, SUM(SEGUROS) AS SEGUROS, SUM(OTROS) AS OTROS, TRAMO FROM ProrrateoMAQ WHERE BASE = '".$base."' AND PERIODO = '".$periodo."' AND NO_ECO LIKE 'RCO%' GROUP BY SUBCUENTA, TRAMO";
		$rs = odbc_exec( $conn, $sql);
		if ( !$rs ) {
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) {  
			$subcuenta = odbc_result($rs, 'SUBCUENTA');
			$activo = odbc_result($rs, 'ACTIVO');
			$combustible = odbc_result($rs, 'COMBUSTIBLE');	
			$mtto = odbc_result($rs, 'MTTO');
			$renta = odbc_result($rs, 'RENTA');
			$llantas = odbc_result($rs, 'LLANTAS');	
			$seguros = odbc_result($rs, 'SEGUROS');
			$otros = odbc_result($rs, 'OTROS');
			$tramo = odbc_result($rs, 'TRAMO');	
			
			if ($x==0) {
				$tramo1 = $tramo;
				$x++;							
			}

			if($tramo1 == $tramo){
				$activo1 += $activo;
				$combustible1 += $combustible;
				$mtto1 += $mtto;
				$renta1 += $renta;
				$llantas1 += $llantas;
				$seguros1 += $seguros;
				$otros1 += $otros;			
							
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C34', utf8_encode($tramo));
				switch($subcuenta){
					case "AdA":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C36', utf8_encode($activo))
						->setCellValue('D36', utf8_encode($combustible))
						->setCellValue('E36', utf8_encode($mtto))
						->setCellValue('F36', utf8_encode($renta))
						->setCellValue('G36', utf8_encode($seguros))
						->setCellValue('H36', utf8_encode($otros))
						->setCellValue('I36', utf8_encode($llantas))
						->setCellValue('J36', utf8_encode($total));	
					break;
					case "DdV":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;	
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C37', utf8_encode($activo))
						->setCellValue('D37', utf8_encode($combustible))
						->setCellValue('E37', utf8_encode($mtto))
						->setCellValue('F37', utf8_encode($renta))
						->setCellValue('G37', utf8_encode($seguros))
						->setCellValue('H37', utf8_encode($otros))
						->setCellValue('I37', utf8_encode($llantas))
						->setCellValue('J37', utf8_encode($total));	
					break;
					case "Dren":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;	
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C38', utf8_encode($activo))
						->setCellValue('D38', utf8_encode($combustible))
						->setCellValue('E38', utf8_encode($mtto))
						->setCellValue('F38', utf8_encode($renta))
						->setCellValue('G38', utf8_encode($seguros))
						->setCellValue('H38', utf8_encode($otros))
						->setCellValue('I38', utf8_encode($llantas))
						->setCellValue('J38', utf8_encode($total));	
					break;
					case "SdR":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;	
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C39', utf8_encode($activo))
						->setCellValue('D39', utf8_encode($combustible))
						->setCellValue('E39', utf8_encode($mtto))
						->setCellValue('F39', utf8_encode($renta))
						->setCellValue('G39', utf8_encode($seguros))
						->setCellValue('H39', utf8_encode($otros))
						->setCellValue('I39', utf8_encode($llantas))
						->setCellValue('J39', utf8_encode($total));	
					break;
					case "SH":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C40', utf8_encode($activo))
						->setCellValue('D40', utf8_encode($combustible))
						->setCellValue('E40', utf8_encode($mtto))
						->setCellValue('F40', utf8_encode($renta))
						->setCellValue('G40', utf8_encode($seguros))
						->setCellValue('H40', utf8_encode($otros))
						->setCellValue('I40', utf8_encode($llantas))
						->setCellValue('J40', utf8_encode($total));	
					break;
					case "SUP":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C41', utf8_encode($activo))
						->setCellValue('D41', utf8_encode($combustible))
						->setCellValue('E41', utf8_encode($mtto))
						->setCellValue('F41', utf8_encode($renta))
						->setCellValue('G41', utf8_encode($seguros))
						->setCellValue('H41', utf8_encode($otros))
						->setCellValue('I41', utf8_encode($llantas))
						->setCellValue('J41', utf8_encode($total));	
					break;
					case "SV":
					$total = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C42', utf8_encode($activo))
						->setCellValue('D42', utf8_encode($combustible))
						->setCellValue('E42', utf8_encode($mtto))
						->setCellValue('F42', utf8_encode($renta))
						->setCellValue('G42', utf8_encode($seguros))
						->setCellValue('H42', utf8_encode($otros))
						->setCellValue('I42', utf8_encode($llantas))
						->setCellValue('J42', utf8_encode($total));	
					break;
				}
				$total2 += $total;
			}else{
				$activo2 += $activo;
				$combustible2 += $combustible;
				$mtto2 += $mtto;
				$renta2 += $renta;
				$llantas2 += $llantas;
				$seguros2 += $seguros;
				$otros2 += $otros;
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C46', utf8_encode($tramo));
				switch($subcuenta){
					case "AdA":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C48', utf8_encode($activo))
						->setCellValue('D48', utf8_encode($combustible))
						->setCellValue('E48', utf8_encode($mtto))
						->setCellValue('F48', utf8_encode($renta))
						->setCellValue('G48', utf8_encode($seguros))
						->setCellValue('H48', utf8_encode($otros))
						->setCellValue('I48', utf8_encode($llantas))
						->setCellValue('J48', utf8_encode($total1));	
					break;
					case "DdV":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C49', utf8_encode($activo))
						->setCellValue('D49', utf8_encode($combustible))
						->setCellValue('E49', utf8_encode($mtto))
						->setCellValue('F49', utf8_encode($renta))
						->setCellValue('G49', utf8_encode($seguros))
						->setCellValue('H49', utf8_encode($otros))
						->setCellValue('I49', utf8_encode($llantas))
						->setCellValue('J49', utf8_encode($total1));	
					break;
					case "Dren":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C50', utf8_encode($activo))
						->setCellValue('D50', utf8_encode($combustible))
						->setCellValue('E50', utf8_encode($mtto))
						->setCellValue('F50', utf8_encode($renta))
						->setCellValue('G50', utf8_encode($seguros))
						->setCellValue('H50', utf8_encode($otros))
						->setCellValue('I50', utf8_encode($llantas))
						->setCellValue('J50', utf8_encode($total1));	
					break;
					case "SdR":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C51', utf8_encode($activo))
						->setCellValue('D51', utf8_encode($combustible))
						->setCellValue('E51', utf8_encode($mtto))
						->setCellValue('F51', utf8_encode($renta))
						->setCellValue('G51', utf8_encode($seguros))
						->setCellValue('H51', utf8_encode($otros))
						->setCellValue('I51', utf8_encode($llantas))
						->setCellValue('J51', utf8_encode($total1));	
					break;
					case "SH":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C52', utf8_encode($activo))
						->setCellValue('D52', utf8_encode($combustible))
						->setCellValue('E52', utf8_encode($mtto))
						->setCellValue('F52', utf8_encode($renta))
						->setCellValue('G52', utf8_encode($seguros))
						->setCellValue('H52', utf8_encode($otros))
						->setCellValue('I52', utf8_encode($llantas))
						->setCellValue('J52', utf8_encode($total1));	
					break;
					case "SUP":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C53', utf8_encode($activo))
						->setCellValue('D53', utf8_encode($combustible))
						->setCellValue('E53', utf8_encode($mtto))
						->setCellValue('F53', utf8_encode($renta))
						->setCellValue('G53', utf8_encode($seguros))
						->setCellValue('H53', utf8_encode($otros))
						->setCellValue('I53', utf8_encode($llantas))
						->setCellValue('J53', utf8_encode($total1));	
					break;
					case "SV":
					$total1 = $activo+$combustible+$mtto+$renta+$seguros+$otros+$llantas;
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C54', utf8_encode($activo))
						->setCellValue('D54', utf8_encode($combustible))
						->setCellValue('E54', utf8_encode($mtto))
						->setCellValue('F54', utf8_encode($renta))
						->setCellValue('G54', utf8_encode($seguros))
						->setCellValue('H54', utf8_encode($otros))
						->setCellValue('I54', utf8_encode($llantas))
						->setCellValue('J54', utf8_encode($total1));	
					break;
				}
						
			}	
				$total3 += $total1;
			//$costo = 0;
	}

	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('C43', utf8_encode($activo1))
		->setCellValue('D43', utf8_encode($combustible1))
		->setCellValue('E43', utf8_encode($mtto1))
		->setCellValue('F43', utf8_encode($renta1))
		->setCellValue('G43', utf8_encode($seguros1))
		->setCellValue('H43', utf8_encode($otros1))
		->setCellValue('I43', utf8_encode($llantas1))
		->setCellValue('J43', utf8_encode($total2))
		
		->setCellValue('C55', utf8_encode($activo2))
		->setCellValue('D55', utf8_encode($combustible2))
		->setCellValue('E55', utf8_encode($mtto2))
		->setCellValue('F55', utf8_encode($renta2))
		->setCellValue('G55', utf8_encode($seguros2))
		->setCellValue('H55', utf8_encode($otros2))
		->setCellValue('I55', utf8_encode($llantas2))
		->setCellValue('J55', utf8_encode($total3));
	
	switch($base){
	case "TO01":
		$base = "Tonala";
	break;
	case "TE01":
		$base = "Tepatitlan";
	break;
	case "JA01":
		$base = "Jalostotitlan";
	break;
	case "OC01":
		$base = "Ocotlan";
	break;
	case "USA01":
		$base = "Union de San Antonio";
	break;
	case "EN01":
		$base = "Encarnacion";
	break;
	case "PA01":
		$base = "Panindicuaro";
	break;
	case "ZI01":
		$base = "Zinapecuaro";
	break;	
}

$objPHPExcel->setActiveSheetIndex(0)				
			->setCellValue('E4', $base )	
			->setCellValue('E6', $periodo );
	
		
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Prorrateo de Maquinaria');

// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);

 

//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ProrrateoMAQ.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
 //}
/*
// Guardamos como Excel 2007
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// Guardamos como Excel 2003
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
*/
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>
<body>
</body>
</html>