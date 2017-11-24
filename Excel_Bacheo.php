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
$objPHPExcel = PHPExcel_IOFactory::load("../sac/xls/Bacheo.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC") // creador del documento
							 ->setLastModifiedBy("SAC")  //ultima modificacion
							 ->setTitle("Bacheo") // titulo del doc
							 ->setSubject("Bacheo")
							 ->setDescription("Bacheo")//descripcion
							 ->setKeywords("Bacheo")//palabras clave
							 ->setCategory("Bacheo");// categoria

//print_r($_POST);
if (isset($_POST['exportar_excel'])){	
    $tramo = $_POST['tramoExcel'];
    $fecha_exportar= $_POST['anioExcel'];
	
	/*if($tramo == "Zapotlanejo - Lagos de Moreno"){
		$tramo = "Zapotlanejo - El Desperdicio','El Desperdicio - Lagos de Moreno','Zapotlanejo - Lagos de Moreno";	
	}else{
		$tramo = $_POST['tramo_exportar'];
	}*/
	
}
//ASIGNAMOS VARIABLES LOCALES PARA PRUEBA
//$tramo = "ZAPOTLANEJO - LAGOS DE MORENO";

$cont= 16;
$m3_1 = 0;
$m3_2 = 0;
$m3_3 = 0;
$m3_4 = 0;
$carpeta_1 = 0;
$carpeta_2 = 0;
$carpeta_3 = 0;
$carpeta_4 = 0;


$sql = "SELECT * FROM GeneradorBacheo WHERE ACTIVIDAD = 'Bacheo Superficial' AND TRAMO IN ('".$tramo."') AND FECHA LIKE '".$fecha_exportar."%' ORDER BY ID";
//echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) {
	$ensaye = odbc_result($rs, 'ENSAYE');   
	$num_bache = odbc_result($rs, 'NUM_BACHE'); 
    $fecha = odbc_result($rs, 'FECHA');
	$km_ini = odbc_result($rs, 'KM_INI');
	$km_fin = odbc_result($rs, 'KM_FIN');
	$cuerpo = odbc_result($rs, 'CUERPO');
	$carril = odbc_result($rs, 'CARRIL');
	$long = odbc_result($rs, 'LONGITUD');
	$ancho = odbc_result($rs, 'ANCHO');
	$espesor = odbc_result($rs, 'ESPESOR');
	$m3_fresado = odbc_result($rs, 'M3_FRESADO');
	$acu_fresado = odbc_result($rs, 'ACUMULADO_FRESADO');
	$m3_carpeta = odbc_result($rs, 'M3_CARPETA');
	$acu_carpeta = odbc_result($rs, 'ACUMULADO_CARPETA');

	switch($cont){
		case "47":
		//$m3_1 += $acu_fresado;
		//$carpeta_1 += $acu_carpeta;
		$cont = 70;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'. $cont, utf8_encode($ensaye))
				->setCellValue('D'. $cont, utf8_encode($num_bache))
				->setCellValue('E'. $cont, utf8_encode($fecha))
				->setCellValue('F'. $cont, utf8_encode($km_ini))			
				->setCellValue('G'. $cont, utf8_encode($km_fin))
				->setCellValue('H'. $cont, utf8_encode($cuerpo))
				->setCellValue('I'. $cont, utf8_encode($carril))
				->setCellValue('J'. $cont, utf8_encode($tramo))
				->setCellValue('K'. $cont, utf8_encode($long))
				->setCellValue('L'. $cont, utf8_encode($ancho))
				->setCellValue('M'. $cont, utf8_encode($espesor))
				->setCellValue('N'. $cont, utf8_encode($m3_fresado))
				->setCellValue('O'. $cont, utf8_encode($acu_fresado))
				->setCellValue('P'. $cont, utf8_encode($m3_carpeta))
				->setCellValue('Q'. $cont, utf8_encode($acu_carpeta));
				//->setCellValue('O101', utf8_encode($m3_1))
				//->setCellValue('Q101', utf8_encode($carpeta_12));
		$cont++;
		break;
		case "101":
		//$m3_2 += $acu_fresado;
		//$carpeta_2 += $acu_carpeta;
		$cont = 124;
			$objPHPExcel->setActiveSheetIndex(0)				
				->setCellValue('C'. $cont, utf8_encode($ensaye))
				->setCellValue('D'. $cont, utf8_encode($num_bache))
				->setCellValue('E'. $cont, utf8_encode($fecha))
				->setCellValue('F'. $cont, utf8_encode($km_ini))			
				->setCellValue('G'. $cont, utf8_encode($km_fin))
				->setCellValue('H'. $cont, utf8_encode($cuerpo))
				->setCellValue('I'. $cont, utf8_encode($carril))
				->setCellValue('J'. $cont, utf8_encode($tramo))
				->setCellValue('K'. $cont, utf8_encode($long))
				->setCellValue('L'. $cont, utf8_encode($ancho))
				->setCellValue('M'. $cont, utf8_encode($espesor))
				->setCellValue('N'. $cont, utf8_encode($m3_fresado))
				->setCellValue('O'. $cont, utf8_encode($acu_fresado))
				->setCellValue('P'. $cont, utf8_encode($m3_carpeta))
				->setCellValue('Q'. $cont, utf8_encode($acu_carpeta));
				//->setCellValue('O155', utf8_encode($m3_2))
				//->setCellValue('Q155', utf8_encode($carpeta_2));
		$cont++;
		break;
		case "155":
		//$m3_3 += $acu_fresado;
		//$carpeta_3 += $acu_carpeta;
		$cont = 178;
			$objPHPExcel->setActiveSheetIndex(0)				
				->setCellValue('C'. $cont, utf8_encode($ensaye))
				->setCellValue('D'. $cont, utf8_encode($num_bache))
				->setCellValue('E'. $cont, utf8_encode($fecha))
				->setCellValue('F'. $cont, utf8_encode($km_ini))			
				->setCellValue('G'. $cont, utf8_encode($km_fin))
				->setCellValue('H'. $cont, utf8_encode($cuerpo))
				->setCellValue('I'. $cont, utf8_encode($carril))
				->setCellValue('J'. $cont, utf8_encode($tramo))
				->setCellValue('K'. $cont, utf8_encode($long))
				->setCellValue('L'. $cont, utf8_encode($ancho))
				->setCellValue('M'. $cont, utf8_encode($espesor))
				->setCellValue('N'. $cont, utf8_encode($m3_fresado))
				->setCellValue('O'. $cont, utf8_encode($acu_fresado))
				->setCellValue('P'. $cont, utf8_encode($m3_carpeta))
				->setCellValue('Q'. $cont, utf8_encode($acu_carpeta));
				//->setCellValue('O209', utf8_encode($m3_3))
				//->setCellValue('Q209', utf8_encode($carpeta_3));
		$cont++;
		break;
		default:
		//$m3_4 += $acu_fresado;
		//$carpeta_4 += $acu_carpeta;
		     $objPHPExcel->setActiveSheetIndex(0)				
				->setCellValue('C'. $cont, utf8_encode($ensaye))
				->setCellValue('D'. $cont, utf8_encode($num_bache))
				->setCellValue('E'. $cont, utf8_encode($fecha))
				->setCellValue('F'. $cont, utf8_encode($km_ini))			
				->setCellValue('G'. $cont, utf8_encode($km_fin))
				->setCellValue('H'. $cont, utf8_encode($cuerpo))
				->setCellValue('I'. $cont, utf8_encode($carril))
				->setCellValue('J'. $cont, utf8_encode($tramo))
				->setCellValue('K'. $cont, utf8_encode($long))
				->setCellValue('L'. $cont, utf8_encode($ancho))
				->setCellValue('M'. $cont, utf8_encode($espesor))
				->setCellValue('N'. $cont, utf8_encode($m3_fresado))
				->setCellValue('O'. $cont, utf8_encode($acu_fresado))
				->setCellValue('P'. $cont, utf8_encode($m3_carpeta))
				->setCellValue('Q'. $cont, utf8_encode($acu_carpeta));
				//->setCellValue('O47', utf8_encode($m3_4))
				//->setCellValue('Q47', utf8_encode($carpeta_4));	
		$cont++;
		break;
	}

  }
  
  $objPHPExcel->setActiveSheetIndex(0)
  		->setCellValue('C5', $tramo);
		//->setCellValue('J16', $tramo);
		
$cont = 16;

$sql = "SELECT * FROM GeneradorBacheo WHERE ACTIVIDAD = 'Bacheo Profundo' AND TRAMO IN ('".$tramo."') AND FECHA LIKE '".$fecha_exportar."%' ORDER BY ID";
//echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }
  while ( odbc_fetch_row($rs) ) {
	$ensaye = odbc_result($rs, 'ENSAYE');   
	$num_bache = odbc_result($rs, 'NUM_BACHE'); 
    $fecha = odbc_result($rs, 'FECHA');
	$km_ini = odbc_result($rs, 'KM_INI');
	$km_fin = odbc_result($rs, 'KM_FIN');
	$cuerpo = odbc_result($rs, 'CUERPO');
	$carril = odbc_result($rs, 'CARRIL');
	$long = odbc_result($rs, 'LONGITUD');
	$ancho = odbc_result($rs, 'ANCHO');
	$espesor = odbc_result($rs, 'ESPESOR');
	$m3_fresado = odbc_result($rs, 'M3_FRESADO');
	$acu_fresado = odbc_result($rs, 'ACUMULADO_FRESADO');
	$m3_carpeta = odbc_result($rs, 'M3_CARPETA');
	$acu_carpeta = odbc_result($rs, 'ACUMULADO_CARPETA');

	switch($cont){
		case "47":
		$cont = 70;
			$objPHPExcel->setActiveSheetIndex(1)				
				->setCellValue('C'. $cont, utf8_encode($ensaye))
				->setCellValue('D'. $cont, utf8_encode($num_bache))
				->setCellValue('E'. $cont, utf8_encode($fecha))
				->setCellValue('F'. $cont, utf8_encode($km_ini))			
				->setCellValue('G'. $cont, utf8_encode($km_fin))
				->setCellValue('H'. $cont, utf8_encode($cuerpo))
				->setCellValue('I'. $cont, utf8_encode($carril))
				->setCellValue('J'. $cont, utf8_encode($tramo))
				->setCellValue('K'. $cont, utf8_encode($long))
				->setCellValue('L'. $cont, utf8_encode($ancho))
				->setCellValue('N'. $cont, utf8_encode($espesor))
				->setCellValue('Q'. $cont, utf8_encode($m3_fresado))
				->setCellValue('R'. $cont, utf8_encode($acu_fresado))
				->setCellValue('S'. $cont, utf8_encode($m3_carpeta))
				->setCellValue('T'. $cont, utf8_encode($acu_carpeta));	
		$cont++;
		break;
		case "101":
		$cont = 124;
			$objPHPExcel->setActiveSheetIndex(1)				
				->setCellValue('C'. $cont, utf8_encode($ensaye))
				->setCellValue('D'. $cont, utf8_encode($num_bache))
				->setCellValue('E'. $cont, utf8_encode($fecha))
				->setCellValue('F'. $cont, utf8_encode($km_ini))			
				->setCellValue('G'. $cont, utf8_encode($km_fin))
				->setCellValue('H'. $cont, utf8_encode($cuerpo))
				->setCellValue('I'. $cont, utf8_encode($carril))
				->setCellValue('J'. $cont, utf8_encode($tramo))
				->setCellValue('K'. $cont, utf8_encode($long))
				->setCellValue('L'. $cont, utf8_encode($ancho))
				->setCellValue('N'. $cont, utf8_encode($espesor))
				->setCellValue('Q'. $cont, utf8_encode($m3_fresado))
				->setCellValue('R'. $cont, utf8_encode($acu_fresado))
				->setCellValue('S'. $cont, utf8_encode($m3_carpeta))
				->setCellValue('T'. $cont, utf8_encode($acu_carpeta));	
		$cont++;
		break;
		case "155":
		$cont = 178;
			$objPHPExcel->setActiveSheetIndex(1)				
				->setCellValue('C'. $cont, utf8_encode($ensaye))
				->setCellValue('D'. $cont, utf8_encode($num_bache))
				->setCellValue('E'. $cont, utf8_encode($fecha))
				->setCellValue('F'. $cont, utf8_encode($km_ini))			
				->setCellValue('G'. $cont, utf8_encode($km_fin))
				->setCellValue('H'. $cont, utf8_encode($cuerpo))
				->setCellValue('I'. $cont, utf8_encode($carril))
				->setCellValue('J'. $cont, utf8_encode($tramo))
				->setCellValue('K'. $cont, utf8_encode($long))
				->setCellValue('L'. $cont, utf8_encode($ancho))
				->setCellValue('N'. $cont, utf8_encode($espesor))
				->setCellValue('Q'. $cont, utf8_encode($m3_fresado))
				->setCellValue('R'. $cont, utf8_encode($acu_fresado))
				->setCellValue('S'. $cont, utf8_encode($m3_carpeta))
				->setCellValue('T'. $cont, utf8_encode($acu_carpeta));		
		$cont++;
		break;
		default:
		     $objPHPExcel->setActiveSheetIndex(1)				
				->setCellValue('C'. $cont, utf8_encode($ensaye))
				->setCellValue('D'. $cont, utf8_encode($num_bache))
				->setCellValue('E'. $cont, utf8_encode($fecha))
				->setCellValue('F'. $cont, utf8_encode($km_ini))			
				->setCellValue('G'. $cont, utf8_encode($km_fin))
				->setCellValue('H'. $cont, utf8_encode($cuerpo))
				->setCellValue('I'. $cont, utf8_encode($carril))
				->setCellValue('J'. $cont, utf8_encode($tramo))
				->setCellValue('K'. $cont, utf8_encode($long))
				->setCellValue('L'. $cont, utf8_encode($ancho))
				->setCellValue('N'. $cont, utf8_encode($espesor))
				->setCellValue('Q'. $cont, utf8_encode($m3_fresado))
				->setCellValue('R'. $cont, utf8_encode($acu_fresado))
				->setCellValue('S'. $cont, utf8_encode($m3_carpeta))
				->setCellValue('T'. $cont, utf8_encode($acu_carpeta));	
		$cont++;
		break;
	}

  }
  
  $objPHPExcel->setActiveSheetIndex(1)
  		->setCellValue('C5', $tramo);
		//->setCellValue('J16', $tramo);

// Rename sheet
//$objPHPExcel->getActiveSheet()->setTitle('Bacheo');

// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);

//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Bacheo.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
/* }

// Guardamos como Excel 2007
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// Guardamos como Excel 20039
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