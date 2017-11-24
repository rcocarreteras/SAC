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
$objPHPExcel = PHPExcel_IOFactory::load("../sac/xls/ReporteDiarioActividades.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC") // creador del documento
							 ->setLastModifiedBy("SAC")  //ultima modificacion
							 ->setTitle("ReporteDiarioActividades") // titulo del doc
							 ->setSubject("ReporteDiarioActividades")
							 ->setDescription("ReporteDiarioActividades")//descripcion
							 ->setKeywords("ReporteDiarioActividades")//palabras clave
							 ->setCategory("ReporteDiarioActividades");// categoria
							 
//print_r($_POST);
if (isset($_POST['exportar_excel'])){	
    $tramo= $_POST['tramo_exportar'];
    $subtramo= $_POST['subtramo_exportar'];
    $fecha_exportar= $_POST['fecha_exportar'];
	$usuario_exportar = utf8_encode($_POST['usuario_exportar']);
	
}
/*//ASIGNAMOS VARIABLES LOCALES PARA PRUEBA
$tramo= "Los Fresnos - Zapotlanejo";
$subtramo= "Del 378 al 425";
$fecha_exportar= "2016-02-04";
$usuario_exportar = "Gonzalo Rene Perez Villegas";*/


$sql = "SELECT COUNT(*) AS Total FROM AvanceDiario WHERE FECHA='".$fecha_exportar."' AND SOBRESTANTE='".$usuario_exportar."'";
//echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  } while ( odbc_fetch_row($rs) ) {
	$total = odbc_result($rs, 'Total');	
  }
  if ($total == '0'){
	 // header('Location:AvanceDiario - copia.php');	  
  }else{
	  
$actividad2 = "";
$cont= 11;
$contMO = 0;
$contMA = 0;
$contIN = 0;
$dif = 0;

$objPHPExcel->setActiveSheetIndex(0)				
			->setCellValue('R6', $fecha_exportar );
		
		$sql = "SELECT DISTINCT(ACTIVIDAD), ACTIVIDAD_ID, AVANCE_ID,UNIDAD,KM_INI,KM_FIN,CUERPO,ZONA,LONGITUD,ANCHO,ESPESOR,CANTIDAD 
		from AvanceDiario where ACTIVIDAD <> '' and FECHA='".$fecha_exportar."'  and TRAMO = '".$tramo."' and SUBTRAMO = '".$subtramo."'
		 and SOBRESTANTE = '".$usuario_exportar."' order by ACTIVIDAD";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) {
	$avanceId = odbc_result($rs, 'AVANCE_ID');   
	$actividad = odbc_result($rs, 'ACTIVIDAD'); 
    $actividad_id = odbc_result($rs, 'ACTIVIDAD_ID');
	$unidad = odbc_result($rs, 'UNIDAD');
	$km_ini = odbc_result($rs, 'KM_INI');
	$km_fin = odbc_result($rs, 'KM_FIN');
	$cuerpo = odbc_result($rs, 'CUERPO');
	$zona = odbc_result($rs, 'ZONA');
	$long = odbc_result($rs, 'LONGITUD');
	$ancho = odbc_result($rs, 'ANCHO');
	$espesor = odbc_result($rs, 'ESPESOR');
	$cant = odbc_result($rs, 'CANTIDAD');
	
	$sql2 = "SELECT * FROM CatConcepto WHERE CvCpt = '".$actividad_id."'";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
    	$subcta = odbc_result($rs2, 'SubCtaDes');
    	$cvpu = odbc_result($rs2, 'cvpu');		
    }
	
		
	if ($actividad==$actividad2){
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'. $cont, utf8_encode($subcta))
			->setCellValue('D'. $cont, utf8_encode($cvpu))
			->setCellValue('E'. $cont, utf8_encode($unidad))
			->setCellValue('F'. $cont, utf8_encode($km_ini))
			->setCellValue('G'. $cont, utf8_encode($km_fin))
			->setCellValue('H'. $cont, utf8_encode($cuerpo))
			->setCellValue('I'. $cont, utf8_encode($zona))
			->setCellValue('J'. $cont, utf8_encode($long))
			->setCellValue('K'. $cont, utf8_encode($ancho))
			->setCellValue('L'. $cont, utf8_encode($espesor))
			->setCellValue('M'. $cont, utf8_encode($cant));
	}else{
		if ($cont >= $dif){
			$contMO = $cont;
			$contMA = $cont;
			$contIN = $cont;		
		}else{
			$cont = $dif;
			$contMO = $cont;
			$contMA = $cont;
			$contIN = $cont;	
		}
		
		$objPHPExcel->setActiveSheetIndex(0)				
			->setCellValue('B'. $cont, utf8_encode($actividad))
			->setCellValue('C'. $cont, utf8_encode($subcta))
			->setCellValue('D'. $cont, utf8_encode($cvpu))
			->setCellValue('E'. $cont, utf8_encode($unidad))			
			->setCellValue('F'. $cont, utf8_encode($km_ini))
			->setCellValue('G'. $cont, utf8_encode($km_fin))
			->setCellValue('H'. $cont, utf8_encode($cuerpo))
			->setCellValue('I'. $cont, utf8_encode($zona))
			->setCellValue('J'. $cont, utf8_encode($long))
			->setCellValue('K'. $cont, utf8_encode($ancho))
			->setCellValue('L'. $cont, utf8_encode($espesor))
			->setCellValue('M'. $cont, utf8_encode($cant));	
			$actividad2=$actividad;	
	}
	 
				
	//MANO DE OBRA
	$sql2 = "SELECT * FROM PuntoTrabajado WHERE AVANCE_ID = '".$avanceId."' AND HORAS > 0 AND TIPO = 'MANO DE OBRA' AND FECHA='".$fecha_exportar."'";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
    	$nombre = odbc_result($rs2, 'NOMBRE');
		$horas = odbc_result($rs2, 'HORAS');
		$horas_extras = odbc_result($rs2, 'HORA_EXTRA');
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('N'. $contMO, utf8_encode($nombre))
			->setCellValue('O'. $contMO, $horas)	
			->setCellValue('P'. $contMO, $horas_extras);		
			
		$contMO++;
    }
	
		
	//MAQUINARIA
	$sql2 = "SELECT * FROM PuntoTrabajado WHERE AVANCE_ID = '".$avanceId."' AND HORAS > 0 AND TIPO = 'MAQUINARIA' AND FECHA='".$fecha_exportar."'";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
    	$nombre = odbc_result($rs2, 'NOMBRE');
		$horas = odbc_result($rs2, 'HORAS');
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('Q'. $contMA, utf8_encode($nombre))
			->setCellValue('R'. $contMA, $horas);			
			
		$contMA++;
    }
	
			
	//INSUMOS
	$sql2 = "SELECT * FROM PuntoTrabajado WHERE AVANCE_ID = '".$avanceId."' AND CANTIDAD > 0  AND TIPO = 'INSUMO' AND FECHA='".$fecha_exportar."'";
    $rs2 = odbc_exec( $conn, $sql2 );
    if ( !$rs2 ) { 
    	exit( "Error en la consulta SQL" ); 
    }    
    while ( odbc_fetch_row($rs2) ) { 
    	$nombre = odbc_result($rs2, 'NOMBRE');
		$cantidad = odbc_result($rs2, 'CANTIDAD');	
		
		$sql3 = "SELECT * FROM CatInsumos WHERE DescIns = '".$nombre."'";
    	$rs3 = odbc_exec( $conn, $sql3 );
    	if ( !$rs3 ) { 
    		exit( "Error en la consulta SQL" ); 
    	}    
   		while ( odbc_fetch_row($rs3) ) {
			$unidad = odbc_result($rs3, 'Unid');		
		}
		
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('S'. $contIN, utf8_encode($nombre))
			->setCellValue('T'. $contIN, utf8_encode($unidad))
			->setCellValue('U'. $contIN, $cantidad);

		$contIN++;
    }
	
	if ($contMO > $contMA){
		if($contMO > $contIN){
			$dif = $contMO;
		}else{
			$dif = $contIN;
		}
	}else{
		if($contMA > $contIN){
			$dif = $contMA;
		}else{
			$dif = $contIN;
		}		
	}
		
	$cont++;
	
  }			
						 
$contComentario= 54;
//COMENTARIOS
	$sql2 = "SELECT TOP 6 OBSERVACIONES FROM AvanceDiario WHERE OBSERVACIONES <>'' AND FECHA='".$fecha_exportar."' AND TRAMO = '".$tramo."'  AND ALTA = '".$_SESSION['S_Usuario']."'";
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
	
	$objPHPExcel->setActiveSheetIndex(0)
		//->setCellValue('Q4', utf8_encode($_SESSION['S_Plaza']))
		->setCellValue('F6', utf8_encode($tramo));
		
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
 }
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