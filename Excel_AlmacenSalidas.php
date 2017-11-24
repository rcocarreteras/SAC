<?php
 $dsn = "Driver={SQL Server};
 
 Server=192.168.130.129;Database=Sac;Integrated Security=SSPI;Persist Security Info=False;";
  
 // Se realiza la conexón con los datos especificados anteriormente
 $conn = odbc_connect( $dsn, 'sa', 'S1st3m45' );
 if (!$conn) { 
 exit( "Error al conectar: " . $conn);
 }
 
header( 'Content-type: text/html; charset=iso-8859-1' );

/** Error reporting */
error_reporting(E_ALL);
date_default_timezone_set('America/Mexico_City');

/** PHPExcel */
include ('../PHPExcel/Classes/PHPExcel.php');
include ('../PHPExcel/Classes/PHPExcel/IOFactory.php');


// if (isset($_POST['Descargar'])){	
// 	$semana = $_POST['Semana'];	
// }else{
// 	$semana = "Del 2016-05-02 al 2016-05-08";
// }
$salida="SAL-0003-16";
//Creamos un Documento nuevo
$objPHPExcel = new PHPExcel();

//Cargamos un documento
$objPHPExcel = PHPExcel_IOFactory::load("../Sac/xls/Salidas Almacen.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC Development")
							 ->setLastModifiedBy("SAC Development")
							 ->setTitle("Formato de Salidas de Almacen")
							 ->setSubject("Formato de Salidas de Almacen")
							 ->setDescription("Formato de Salidas de Almacen SAC")
							 ->setKeywords("Formato de Salidas de Almacen SAC")
							 ->setCategory("SAC Formato de Salidas de Almacen");


  $cont=8;
  $id = 1;			
  $sql = "SELECT * FROM Salidas where Id_Salida='".$salida."'";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) {
	  $articulo = odbc_result($rs, 'ARTICULO');
	  $descr = utf8_decode(odbc_result($rs, 'DESCRIPCION'));
	  $importe = odbc_result($rs, 'IMPORTE');
	  $unidad = odbc_result($rs, 'UNIDAD');
	 
		
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$cont, $id)			
			->setCellValue('B'.$cont, $articulo)  			
			->setCellValue('C'.$cont, $descr)			
			->setCellValue('D'.$cont, $unidad)
			->setCellValue('G'.$cont, $importe);
	$cont++;
	$id++;
  }			

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Prenómina');

// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);






//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Formato de Prenómina.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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