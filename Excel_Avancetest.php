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
/** Error reporting */
error_reporting(E_ALL);


/** PHPExcel */
include ('../PHPExcel/Classes/PHPExcel.php');
include ('../PHPExcel/Classes/PHPExcel/IOFactory.php');

//Creamos un Documento nuevo
$objPHPExcel = new PHPExcel();

//Cargamos un documento
$objPHPExcel = PHPExcel_IOFactory::load("../sac/xls/ReporteDiarioActividades.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC")
							 ->setLastModifiedBy("SAC")
							 ->setTitle("ReporteDiarioActividades")
							 ->setSubject("ReporteDiarioActividades")
							 ->setDescription("ReporteDiarioActividades")
							 ->setKeywords("ReporteDiarioActividades")
							 ->setCategory("ReporteDiarioActividades");

/*if (isset($_POST['exportar_excel'])){	
    $tramo= $_POST['buscartramo'];
    $fecha_exportar= $_POST['fecha_exportar'];
}*/



$tramo='Los Fresnos - Zapotlanejo';
$fecha_exportar='2015-10-14';

$cont= 11;
$contMO = 0;
$contMA = 0;
$contIN = 0;

$objPHPExcel->setActiveSheetIndex(0)				
			->setCellValue('Q6', $fecha_exportar );

$sql = "select distinct(ACTIVIDAD), ACTIVIDAD_ID, AVANCE_ID,UNIDAD,KM_INI,KM_FIN,CUERPO,ZONA,LONGITUD,ANCHO,ESPESOR,CANTIDAD from AvanceDiario where ACTIVIDAD <> '' and FECHA='".$fecha_exportar."' order by ACTIVIDAD ";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) {
	$avanceId = odbc_result($rs, 'AVANCE_ID');   
	$actividad = odbc_result($rs, 'ACTIVIDAD'); 
    $subcta = odbc_result($rs, 'ACTIVIDAD_ID');
	$unidad = odbc_result($rs, 'UNIDAD');
	$km_ini = odbc_result($rs, 'KM_INI');
	$km_fin = odbc_result($rs, 'KM_FIN');
	$cuerpo = odbc_result($rs, 'CUERPO');
	$zona = odbc_result($rs, 'ZONA');
	$long = odbc_result($rs, 'LONGITUD');
	$ancho = odbc_result($rs, 'ANCHO');
	$espesor = odbc_result($rs, 'ESPESOR');
	$cant = odbc_result($rs, 'CANTIDAD');
		
	$objPHPExcel->setActiveSheetIndex(0)				
			->setCellValue('B'. $cont, utf8_encode($actividad))
			->setCellValue('E'. $cont, utf8_encode($unidad))
			->setCellValue('D'. $cont, utf8_encode($subcta))
			->setCellValue('F'. $cont, utf8_encode($km_ini))
			->setCellValue('G'. $cont, utf8_encode($km_fin))
			->setCellValue('H'. $cont, utf8_encode($cuerpo))
			->setCellValue('I'. $cont, utf8_encode($zona))
			->setCellValue('J'. $cont, utf8_encode($long))
			->setCellValue('K'. $cont, utf8_encode($ancho))
			->setCellValue('L'. $cont, utf8_encode($espesor))
			->setCellValue('M'. $cont, utf8_encode($cant));
			
	 
	$contMO = $cont;			
	//MANO DE OBRA
	$sql2 = "SELECT * FROM PuntoTrabajado WHERE FECHA = '".$fecha_exportar."' AND AVANCE_ID = '".$avanceId."' AND TIPO = 'MANO DE OBRA' and HORAS > 0";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
    	$nombre = odbc_result($rs2, 'NOMBRE');
		$horas = odbc_result($rs2, 'HORAS');
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('N'. $contMO, utf8_encode($nombre))
			->setCellValue('O'. $contMO, $horas);
			
			
		$contMO++;
    }
	
	$contMA = $cont;	
	//MAQUINARIA
	$sql2 = "SELECT * FROM PuntoTrabajado WHERE FECHA = '".$fecha_exportar."' AND AVANCE_ID = '".$avanceId."' AND TIPO = 'MAQUINARIA'";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
    	$nombre = odbc_result($rs2, 'NOMBRE');
		$horas = odbc_result($rs2, 'HORAS');
		$horIni = odbc_result($rs2, 'HK_INI');
		$horFin = odbc_result($rs2, 'HK_FIN');
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('P'. $contMA, utf8_encode($nombre))
			->setCellValue('Q'. $contMA, $horIni)
			->setCellValue('R'. $contMA, $horFin)
			->setCellValue('S'. $contMA, $horas);
			
			
		$contMA++;
    }
	
	$contIN = $cont;	
	//INSUMOS
	$sql2 = "SELECT * FROM PuntoTrabajado WHERE FECHA = '".$fecha_exportar."' AND AVANCE_ID = '".$avanceId."' AND TIPO = 'INSUMO'";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
    	$nombre = odbc_result($rs2, 'NOMBRE');
		$cantidad = odbc_result($rs2, 'CANTIDAD');	
		
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('T'. $contIN, utf8_encode($nombre))
			->setCellValue('V'. $contIN, $cantidad);

		$contIN++;
    }
	
	
	$dif1 = $contMO - $cont;
	$dif2 = $contMA - $cont;
	$dif3 = $contIN - $cont;
	
	if ($dif1 > $dif2){
		if($dif1 > $dif3){
			$cont = $cont + $dif1;
		}else{
			$cont = $cont + $dif3;
		}
	}else{
		if($dif2 > $dif3){
			$cont = $cont + $dif2;
		}else{
			$cont = $cont + $dif3;
		}		
	}
		
	//$cont++;
	
  }			
			
			
			
						 
$contComentario= 43;
//COMENTARIOS
	$sql2 = "SELECT OBSERVACIONES FROM AvanceDiario WHERE OBSERVACIONES<>'' AND FECHA='".$fecha_exportar."'";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
	$observaciones = odbc_result($rs2, 'OBSERVACIONES');
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'. $contComentario, utf8_encode($observaciones));
		$contComentario++;
    }
	
		
		
	//AUTOPISTA
	$sql2 = "select * from AvanceDiario WHERE TRAMO = '".$tramo."'";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
	$tramo = odbc_result($rs2, 'TRAMO');
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('F6', $tramo);
    }//FIN WHILE	
		
	/*//TRAMO
	$sql2 = "select * from AvanceDiario";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
	$plaza = odbc_result($rs2, 'TRAMO');
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('F4', $plaza);
    }
	
	
	
*/
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Avance Diario');

// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);



//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Avance diario.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

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