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
$objPHPExcel = PHPExcel_IOFactory::load("../sac/xls/ProMO.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC") // creador del documento
							 ->setLastModifiedBy("SAC")  //ultima modificacion
							 ->setTitle("ProrrateoMO") // titulo del doc
							 ->setSubject("ProrrateoMO")
							 ->setDescription("ProrrateoMO")//descripcion
							 ->setKeywords("ProrrateoMO")//palabras clave
							 ->setCategory("ProrrateoMO");// categoria
							 
//print_r($_POST);
if (isset($_POST['descargar_mo'])){	
    $periodo= $_POST['rangoExcel'];
	$base = $_POST['baseExcel'];
	
}

    /*$periodo = "De 2016-09-26 A 2016-10-25";
	$base = "TO01";*/
	$tramo1 = "";
	$subcuenta1 = "";
	$suma = 0;
	$suma1 = 0;
	$x=0;

		
	$sql = "SELECT SUBCUENTA, SUM(HORAS) AS HORAS, SUM (COSTO) AS COSTO, TRAMO FROM ProrrateoMO WHERE BASE = '".$base."' AND PERIODO = '".$periodo."' GROUP BY SUBCUENTA, TRAMO";		
		$rs = odbc_exec( $conn, $sql);
		if ( !$rs ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs) ) {  
			$subcuenta = odbc_result($rs, 'SUBCUENTA');
			$costo = odbc_result($rs, 'COSTO');
			$tramo = odbc_result($rs, 'TRAMO');	
			
			if ($x==0) {
				$tramo1 = $tramo;
				$x++;							
			}

			if($tramo1 == $tramo){
				$suma +=$costo;
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C11', utf8_encode($tramo));
				switch($subcuenta){
					case "AdA":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C12', utf8_encode($costo));	
					break;
					case "DdV":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C13', utf8_encode($costo));	
					break;
					case "Dren":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C14', utf8_encode($costo));	
					break;
					case "SdR":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C15', utf8_encode($costo));	
					break;
					case "SH":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C16', utf8_encode($costo));	
					break;
					case "SUP":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C17', utf8_encode($costo));	
					break;
					case "SV":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C18', utf8_encode($costo));	
					break;
				}
			}else{
				$suma1 +=$costo;
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('D11', utf8_encode($tramo));
				switch($subcuenta){
					case "AdA":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('D12', utf8_encode($costo));	
					break;
					case "DdV":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('D13', utf8_encode($costo));	
					break;
					case "Dren":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('D14', utf8_encode($costo));	
					break;
					case "SdR":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('D15', utf8_encode($costo));	
					break;
					case "SH":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('D16', utf8_encode($costo));	
					break;
					case "SUP":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('D17', utf8_encode($costo));	
					break;
					case "SV":
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('D18', utf8_encode($costo));	
					break;
				}
						
			}
			$costo = 0;
	}

	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('C19', utf8_encode($suma))
		->setCellValue('D19', utf8_encode($suma1));	

	
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
			->setCellValue('D4', $base )	
			->setCellValue('D6', $periodo );
	
		
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Prorrateo Mano de Obra');

// Establecer índice de hoja activa a la primera hoja, por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);

 

//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ProrrateoMO.xlsx"');
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