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

// Se realiza la conexón con los datos especificados anteriormente
 $conn2 = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn2) { 
 exit( "Error al conectar: " . $conn2);
 }
 
 // Se realiza la conexón con los datos especificados anteriormente
 $conn3 = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn3) { 
 exit( "Error al conectar: " . $conn3);
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
$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

$tramo = "Leon - Aguascalientes";
$anio = "2017";
$mes = "10";
//Cargamos un documento
$objPHPExcel = PHPExcel_IOFactory::load("../sac/xls/".$tramo.".xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC") // creador del documento
							 ->setLastModifiedBy("SAC")  //ultima modificacion
							 ->setTitle("SCT CAPEX") // titulo del doc
							 ->setSubject("SCT CAPEX")
							 ->setDescription("SCT CAPEX")//descripcion
							 ->setKeywords("SCT CAPEX")//palabras clave
							 ->setCategory("SCT CAPEX");// categoria
							 

$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('G7', utf8_encode($anio))
	->setCellValue('H10', utf8_encode("FECHA DE ELABORACION: ".date("d/F/Y")))
	->setCellValue('H9', utf8_encode("Informe mensual de : ".date("d/F/Y")));
$objPHPExcel->setActiveSheetIndex(1)
	->setCellValue('G7', utf8_encode($anio))
	->setCellValue('I9', utf8_encode("FECHA DE ELABORACION: ".date("d/F/Y")))
	->setCellValue('I7', utf8_encode("Informe mensual de : ".date("d/F/Y")));
$objPHPExcel->setActiveSheetIndex(2)
	->setCellValue('G7', utf8_encode($anio))
	->setCellValue('H10', utf8_encode("FECHA DE ELABORACION: ".date("d/F/Y")))
	->setCellValue('H9', utf8_encode("Informe mensual de : ".date("d/F/Y")));
$objPHPExcel->setActiveSheetIndex(4)
	->setCellValue('G7', utf8_encode($anio))
	->setCellValue('I10', utf8_encode("FECHA DE ELABORACION: ".date("d/F/Y")))
	->setCellValue('I9', utf8_encode("Informe mensual de : ".date("d/F/Y")));


//HOJA 3 (EJECUTADO)
$objPHPExcel->setActiveSheetIndex(0);
for ($i=14; $i <= 80; $i++) {
//	$letra = 72;
//	$letra2 = "65";
	
	$valida = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
	if (trim($valida) == "") {
		continue;	
	}
		
		$sql2 = "SELECT * FROM PresupuestoCapexSCT WHERE TRAMO = '".$tramo."' AND ANIO = '".$anio."'";
		$rs2 = odbc_exec( $conn2, $sql2);
		if ( !$rs2 ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs2) ) {  
			$cantidad = odbc_result($rs2, 'CANTIDAD');	
			$precio = odbc_result($rs2, 'PRECIO_UNITARIO');	
			$total = $cantidad * $precio;

			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("G".$i, $cantidad)
				->setCellValue("I".$i, $precio)
				->setCellValue("J".$i, $total);
			$objPHPExcel->setActiveSheetIndex(1)
				->setCellValue("G".$i, $cantidad)
				->setCellValue("I".$i, $precio);
		}
}



// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);
 
//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Anexo 14 "'.$tramo.'".xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas();
$objWriter->save('php://output');
exit;
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