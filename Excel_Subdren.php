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
$objPHPExcel = PHPExcel_IOFactory::load("../sac/xls/GeneradordeSubdren.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC") // creador del documento
							 ->setLastModifiedBy("SAC")  //ultima modificacion
							 ->setTitle("GenredordeSubdren") // titulo del doc
							 ->setSubject("GenredordeSubdren")
							 ->setDescription("GenredordeSubdren")//descripcion
							 ->setKeywords("GenredordeSubdren")//palabras clave
							 ->setCategory("GenredordeSubdren");// categoria
							 
//print_r($_POST);
if (isset($_POST['exportar_subdren'])){	
    $tramo= $_POST['tramoExcel'];
    $anio= $_POST['anioExcel'];
	
}

$cont= 11;

$objPHPExcel->setActiveSheetIndex(0)				
			->setCellValue('H6', date('Y-m-d') );
		
  $sql = "SELECT * FROM GeneradorSubdren where TRAMO ='".$tramo."' and ANIO='".$anio."'";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) {
	$num = odbc_result($rs, 'ID');   
	$mes = odbc_result($rs, 'MES'); 
    $anio = odbc_result($rs, 'ANIO');
	$tipo = odbc_result($rs, 'TIPO');
	$km_ini = odbc_result($rs, 'KM_INI');
	$km_fin = odbc_result($rs, 'KM_FIN');
	$cuerpo = odbc_result($rs, 'CUERPO');
	$tramo = odbc_result($rs, 'TRAMO');
	$long = odbc_result($rs, 'LONG');
	$acum = odbc_result($rs, 'ACUMULADO');
	$y1 = odbc_result($rs, 'Y1');
	$x1 = odbc_result($rs, 'X1');
	$y2 = odbc_result($rs, 'Y2');
	$x2 = odbc_result($rs, 'X2');
	
	
		
		$objPHPExcel->setActiveSheetIndex(0)				
			->setCellValue('A'. $cont, utf8_encode($num))
			->setCellValue('B'. $cont, utf8_encode($mes))
			->setCellValue('C'. $cont, utf8_encode($anio))
			->setCellValue('D'. $cont, utf8_encode($tipo))			
			->setCellValue('E'. $cont, utf8_encode($tramo))
			->setCellValue('F'. $cont, utf8_encode($cuerpo))
			->setCellValue('G'. $cont, utf8_encode($km_ini))
			->setCellValue('H'. $cont, utf8_encode($km_fin))
			->setCellValue('I'. $cont, utf8_encode($long))
			->setCellValue('J'. $cont, utf8_encode($acum))
			->setCellValue('K'. $cont, utf8_encode($y1))
			->setCellValue('L'. $cont, utf8_encode($x1))
			->setCellValue('M'. $cont, utf8_encode($y2))
			->setCellValue('N'. $cont, utf8_encode($x2));	
		
		$cont++;
	}
	
		
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Generador Subdren');

// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);

 

//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Subdren.xlsx"');
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