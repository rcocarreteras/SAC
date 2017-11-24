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

//Cargamos un documento
$objPHPExcel = PHPExcel_IOFactory::load("../sac/xls/PresupuestoCAPEX.xlsx");

//Propiedades del documento
$objPHPExcel->getProperties()->setCreator("SAC") // creador del documento
							 ->setLastModifiedBy("SAC")  //ultima modificacion
							 ->setTitle("Presupuesto CAPEX") // titulo del doc
							 ->setSubject("Presupuesto CAPEX")
							 ->setDescription("Presupuesto CAPEX")//descripcion
							 ->setKeywords("Presupuesto CAPEX")//palabras clave
							 ->setCategory("Presupuesto CAPEX");// categoria

$centrado = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$anio = date("Y");
$cont = 11;
$contrato2 = "";
$subcuenta2 = "";
$prog1 = 0;
$prog2 = 0;
$prog3 = 0;
$prog4 = 0;
$prog5 = 0;
$prog6 = 0;
$prog7 = 0;
$prog8 = 0;
$prog9 = 0;
$prog10 = 0;

$contra1 = 0;
$contra2 = 0;
$contra3 = 0;
$contra4 = 0;
$contra5 = 0;
$contra6 = 0;
$contra7 = 0;
$contra8 = 0;
$contra9 = 0;
$contra10 = 0;

$eje1 = 0;
$eje2 = 0;
$eje3 = 0;
$eje4 = 0;
$eje5 = 0;
$eje6 = 0;
$eje7 = 0;
$eje8 = 0;
$eje9 = 0;
$eje10 = 0;
# WHERE SUBCUENTA='ESTUDIOS Y PROYECTOS'
$sql1 = "SELECT DISTINCT(SUBCUENTA) FROM PresupuestoDet";
//echo $sql1;
$rs = odbc_exec( $conn, $sql1);
if ( !$rs ) {
	exit( "Error en la consulta SQL" );
}
while ( odbc_fetch_row($rs) ) { 
	$subcuenta = odbc_result($rs, 'SUBCUENTA');

	//OBTENEMOS LOS TOTALES POR TRAMO
	$totalEje = array(0,0,0,0,0,0,0,0,0);
	$monto = array(0,0,0,0,0,0,0,0,0,0);

	for ($i=0; $i < 10 ; $i++) { 
		switch ($i) {
			case '0':
				$tramo = "El Desperdicio - Lagos de Moreno";//
				break;
			case '1':
				$tramo = "El Desperdicio - Santa Maria de En Medio";//
				break;
			case '2':
				$tramo = "Leon - Aguascalientes";//
				break;
			case '3':
				$tramo = "Los Fresnos - Zapotlanejo";//
				break;
			case '4':
				$tramo = "Maravatio - Los Fresnos";//
				break;
			case '5':
				$tramo = "Zapotlanejo - El Desperdicio";//
				break;
			case '6':
				$tramo = "Zapotlanejo - Guadalajara";//
				break;
			case '7':
				$tramo = "La Barca - Jiquilpan";//
				break;
			case '8':
				$tramo = "Tepic - San Blas";//
				break;
			case '9':
				$tramo = "Zacapu - Panindicuaro";//
				break;
		}//switch
		
		//PROGRAMADO
	 	$sql2 = "SELECT ISNULL(SUM(IMPORTE),0) AS TOTAL FROM PresupuestoDet WHERE TRAMO= '".$tramo."' AND SUBCUENTA ='".$subcuenta."' AND CLASIFICACION = 'CAPEX' AND PERIODO='".$anio."'";
	 	//echo $sql2."<br>";
		$rs2 = odbc_exec( $conn2, $sql2);
		if ( !$rs2 ) { 
			exit( "Error en la consulta SQL" );
		}
		while ( odbc_fetch_row($rs2) ) { 
			$programado[$i] = odbc_result($rs2, 'TOTAL');
		}//while

		 //CONTRATADO
		 $sql2 = "SELECT SUM(MONTO) AS TOTAL FROM Contratos WHERE (TRAMO = '".$tramo."') AND (CLASIFICACION = 'CAPEX') AND (SUBCUENTA = '".$subcuenta."') AND YEAR(FECHA_INICIO) = '".$anio."'";
	  	//echo $sql2."<br>";
		 $rs2 = odbc_exec( $conn2, $sql2);
		 if ( !$rs2 ) { 
		 	exit( "Error en la consulta SQL" );
		 }
		 while ( odbc_fetch_row($rs2) ) { 
		 	$monto[$i] = odbc_result($rs2, 'TOTAL');
		 }//whileContratado

		 //EJECUTADO TOTAL
		 $sql2 = "SELECT ISNULL(SUM(Estimaciones.MONTO), 0) - ISNULL(SUM(Estimaciones.RETENCION), 0) - ISNULL(SUM(Estimaciones.PENALIZACION), 0) + ISNULL(SUM(Estimaciones.DEVOLUCION), 0) - ISNULL(SUM(Estimaciones.AMORTIZACION), 0) AS TOTAL FROM Estimaciones INNER JOIN Contratos ON Estimaciones.CONTRATO = Contratos.CONTRATO AND Estimaciones.TRAMO = Contratos.TRAMO WHERE (Estimaciones.CLASIFICACION = 'CAPEX') AND (YEAR(Estimaciones.FECHA_INICIO) = '".$anio."') AND (Estimaciones.TRAMO = '".$tramo."') AND (Contratos.SUBCUENTA='".$subcuenta."')";
		 //echo $sql2."<br>";
		 $rs2 = odbc_exec( $conn2, $sql2);
		 if ( !$rs2 ) { 
		 	exit( "Error en la consulta SQL" );
		 }
		 while ( odbc_fetch_row($rs2) ) { 
		 $totalEje[$i] = odbc_result($rs2, 'TOTAL');
		 }//whileEjecutado
	}//for

	if ($subcuenta != $subcuenta2){
		$subcuenta2 = $subcuenta;
		
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('B'.$cont, utf8_encode($subcuenta))
		->setCellValue('D'.$cont, $programado[0])
		->setCellValue('F'.$cont, $monto[0])
		->setCellValue('H'.$cont, $totalEje[0])
		->setCellValue('J'.$cont, $programado[1])
		->setCellValue('L'.$cont, $monto[1])
		->setCellValue('N'.$cont, $totalEje[1])
		->setCellValue('P'.$cont, $programado[2])
		->setCellValue('R'.$cont, $monto[2])
		->setCellValue('T'.$cont, $totalEje[2])
		->setCellValue('V'.$cont, $programado[3])
		->setCellValue('X'.$cont, $monto[3])
		->setCellValue('Z'.$cont, $totalEje[3])
		->setCellValue('AB'.$cont, $programado[4])
		->setCellValue('AD'.$cont, $monto[4])
		->setCellValue('AF'.$cont, $totalEje[4])
		->setCellValue('AH'.$cont, $programado[5])
		->setCellValue('AJ'.$cont, $monto[5])
		->setCellValue('AL'.$cont, $totalEje[5])
		->setCellValue('AN'.$cont, $programado[6])
		->setCellValue('AP'.$cont, $monto[6])
		->setCellValue('AR'.$cont, $totalEje[6])
		->setCellValue('AT'.$cont, $programado[7])
		->setCellValue('AV'.$cont, $monto[7])
		->setCellValue('AX'.$cont, $totalEje[7])
		->setCellValue('AZ'.$cont, $programado[8])
		->setCellValue('BB'.$cont, $monto[8])
		->setCellValue('BD'.$cont, $totalEje[8])
		->setCellValue('BF'.$cont, $programado[9])
		->setCellValue('BH'.$cont, $monto[9])
		->setCellValue('BJ'.$cont, $totalEje[9]);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$cont)->applyFromArray($centrado);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$cont.':BJ'.$cont)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('A4A4A4');
		$cont++;
		$prog1 += $programado[0];
		$prog2 += $programado[1];
		$prog3 += $programado[2];
		$prog4 += $programado[3];
		$prog5 += $programado[4];
		$prog6 += $programado[5];
		$prog7 += $programado[6];
		$prog8 += $programado[7];
		$prog9 += $programado[8];
		$prog10 += $programado[9];
		
		$contra1 += $monto[0];
		$contra2 += $monto[1];
		$contra3 += $monto[2];
		$contra4 += $monto[3];
		$contra5 += $monto[4];
		$contra6 += $monto[5];
		$contra7 += $monto[6];
		$contra8 += $monto[7];
		$contra9 += $monto[8];
		$contra10 += $monto[9];
		
		$eje1 += $totalEje[0];
		$eje2 += $totalEje[1];
		$eje3 += $totalEje[2];
		$eje4 += $totalEje[3];
		$eje5 += $totalEje[4];
		$eje6 += $totalEje[5];
		$eje7 += $totalEje[6];
		$eje8 += $totalEje[7];
		$eje9 += $totalEje[8];
		$eje10 += $totalEje[9];
		

			 //CONTRATADO
			$sql2 = "SELECT SUM(MONTO) AS TOTAL, CONTRATO, TRAMO, ACTIVIDAD FROM Contratos WHERE (CLASIFICACION = 'CAPEX') AND (SUBCUENTA = '".$subcuenta."') AND YEAR(FECHA_INICIO) = '".$anio."' GROUP BY CONTRATO, TRAMO, ACTIVIDAD ORDER BY CONTRATO";
			//echo $sql2."<br>";
			$rs2 = odbc_exec( $conn2, $sql2);
			if ( !$rs2 ) {
				exit( "Error en la consulta SQL" );  
			}
			while ( odbc_fetch_row($rs2) ) {
				$contratado1 = odbc_result($rs2, 'TOTAL');
				$contrato1 = trim(odbc_result($rs2, 'CONTRATO'));
				$actividad = trim(odbc_result($rs2, 'ACTIVIDAD'));
				$tramo1 = trim(odbc_result($rs2, 'TRAMO'));

			//EJECUTADO
			$sql3 = "SELECT ISNULL(SUM(Estimaciones.MONTO), 0) - ISNULL(SUM(Estimaciones.RETENCION), 0) - ISNULL(SUM(Estimaciones.PENALIZACION), 0) + ISNULL(SUM(Estimaciones.DEVOLUCION), 0) - ISNULL(SUM(Estimaciones.AMORTIZACION), 0) AS TOTAL FROM Estimaciones INNER JOIN Contratos ON Estimaciones.CONTRATO = Contratos.CONTRATO AND Estimaciones.TRAMO = Contratos.TRAMO WHERE (Estimaciones.CLASIFICACION = 'CAPEX') AND (YEAR(Estimaciones.FECHA_INICIO) = '".$anio."') AND (Estimaciones.CONTRATO = '".$contrato1."') AND (Estimaciones.TRAMO = '".$tramo1."') AND (Contratos.SUBCUENTA='".$subcuenta."')";
			//echo $sql3."<br>";
			$rs3 = odbc_exec( $conn3, $sql3);
			if ( !$rs3 ) {
				exit( "Error en la consulta SQL" );  
			}
			while ( odbc_fetch_row($rs3) ) {
				$ejecutado = odbc_result($rs3, 'TOTAL');

							$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B'.$cont, utf8_encode($contrato1))
								->setCellValue('C'.$cont, utf8_encode($actividad));
							switch($tramo1){
								case "El Desperdicio - Lagos de Moreno":
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('H'.$cont, $ejecutado);
								break;
								case "El Desperdicio - Santa Maria de En Medio":
									$objPHPExcel->getActiveSheet()->setCellValue('L'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('N'.$cont, $ejecutado);
								break;
								case "Leon - Aguascalientes":
									$objPHPExcel->getActiveSheet()->setCellValue('R'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('T'.$cont, $ejecutado);
								break;
									case "Los Fresnos - Zapotlanejo":
									$objPHPExcel->getActiveSheet()->setCellValue('X'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('Z'.$cont, $ejecutado);
								break;
								case "Maravatio - Los Fresnos":
									$objPHPExcel->getActiveSheet()->setCellValue('AD'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('AF'.$cont, $ejecutado);
								break;
								case "Zapotlanejo - El Desperdicio":
									$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('AL'.$cont, $ejecutado);
								break;
								case "Zapotlanejo - Guadalajara":
									$objPHPExcel->getActiveSheet()->setCellValue('AP'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('AR'.$cont, $ejecutado);
								break;
								case "La Barca - Jiquilpan":
									$objPHPExcel->getActiveSheet()->setCellValue('AV'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('AX'.$cont, $ejecutado);
								break;
								case "Tepic - San Blas":
									$objPHPExcel->getActiveSheet()->setCellValue('BB'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('BD'.$cont, $ejecutado);
								break;
								case "Zacapu - Panindicuaro":
									$objPHPExcel->getActiveSheet()->setCellValue('BH'.$cont, $contratado1);
									$objPHPExcel->getActiveSheet()->setCellValue('BJ'.$cont, $ejecutado);
								break;
							}//switch
						$cont++;
				}//whileEjecutado
			}//whileContratado
	}//SUBCUENTA IF
}//subcuenta

$objPHPExcel->getActiveSheet()->mergeCells('B'.$cont.':C'.$cont.'');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, "IMPORTES CANCELADOS");
$objPHPExcel->getActiveSheet()->getStyle('B'.$cont)->applyFromArray($centrado);		
$objPHPExcel->getActiveSheet()->getStyle('B'.$cont.':BJ'.$cont)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9FC2CE');
$cont++;

$sql = "SELECT DISTINCT(CONTRATO), IMPORTE_CANCELAR, TRAMO FROM Contratos WHERE IMPORTE_CANCELAR<>'' AND CLASIFICACION='CAPEX' AND YEAR(FECHA_INICIO) = '".$anio."'";
//echo $sql3."<br>";
$rs = odbc_exec( $conn, $sql);
if ( !$rs ) {
	exit( "Error en la consulta SQL" );  
}
while ( odbc_fetch_row($rs) ) {
	$nombre = odbc_result($rs, 'CONTRATO');
	$importe = odbc_result($rs, 'IMPORTE_CANCELAR');
	$tramo = odbc_result($rs, 'TRAMO');
	
	
	$sql2 = "SELECT SUM(MONTO) TOTAL FROM Estimaciones WHERE CONTRATO='".$nombre."' AND TRAMO='".$tramo."'";
	//echo $sql3."<br>";
	$rs2 = odbc_exec( $conn2, $sql2);
	if ( !$rs ) {
		exit( "Error en la consulta SQL" );  
	}
	while ( odbc_fetch_row($rs2) ) {
		$est = odbc_result($rs2, 'TOTAL');
	}
	
	$sql2 = "SELECT SUM(MONTO) TOTAL FROM Contratos WHERE CONTRATO='".$nombre."' AND TRAMO='".$tramo."'";
	//echo $sql3."<br>";
	$rs2 = odbc_exec( $conn2, $sql2);
	if ( !$rs ) {
		exit( "Error en la consulta SQL" );  
	}
	while ( odbc_fetch_row($rs2) ) {
		$contr = odbc_result($rs2, 'TOTAL');
	}
		
		$imp_can = ($contr - $est) * -1;
	
	   $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$cont, utf8_encode($nombre));
		switch($tramo){
			case "El Desperdicio - Lagos de Moreno":
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$cont, $imp_can);
				$eje1 += $imp_can;
				$contra1 += $imp_can;
				$prog1 += $imp_can;
			break;
			case "El Desperdicio - Santa Maria de En Medio":
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$cont, $imp_can);
				$eje2 += $imp_can;
				$contra2 += $imp_can;
				$prog2 += $imp_can;
			break;
			case "Leon - Aguascalientes":
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$cont, $imp_can);
				$eje3 += $imp_can;
				$contra3 += $imp_can;
				$prog3 += $imp_can;
			break;
			case "Los Fresnos - Zapotlanejo":
				$objPHPExcel->getActiveSheet()->setCellValue('X'.$cont, $imp_can);
				$eje4 += $imp_can;
				$contra4 += $imp_can;
				$prog4 += $imp_can;
			break;
			case "Maravatio - Los Fresnos":
				$objPHPExcel->getActiveSheet()->setCellValue('AD'.$cont, $imp_can);
				$eje5 += $imp_can;
				$contra5 += $imp_can;
				$prog5 += $imp_can;
			break;
			case "Zapotlanejo - El Desperdicio":
				$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$cont, $imp_can);
				$eje6 += $imp_can;
				$contra6 += $imp_can;
				$prog6 += $imp_can;
			break;
			case "Zapotlanejo - Guadalajara":
				$objPHPExcel->getActiveSheet()->setCellValue('AP'.$cont, $imp_can);
				$eje7 += $imp_can;
				$contra7 += $imp_can;
				$prog7 += $imp_can;
			break;
			case "La Barca - Jiquilpan":
				$objPHPExcel->getActiveSheet()->setCellValue('AV'.$cont, $imp_can);
				$eje8 += $imp_can;
				$contra8 += $imp_can;
				$prog8 += $imp_can;
			break;
			case "Tepic - San Blas":
				$objPHPExcel->getActiveSheet()->setCellValue('BB'.$cont, $imp_can);
				$eje9 += $imp_can;
				$contra9 += $imp_can;
				$prog9 += $imp_can;
			break;
			case "Zacapu - Panindicuaro":
				$objPHPExcel->getActiveSheet()->setCellValue('BH'.$cont, $imp_can);
				$eje10 += $imp_can;
				$contra10 += $imp_can;
				$prog10 += $imp_can;
			break;
		}//switch

		$cont++;
}
$cont++;
$cont++;

$objPHPExcel->getActiveSheet()->mergeCells('B'.$cont.':C'.$cont.'');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$cont, "TOTALES");
$objPHPExcel->getActiveSheet()->getStyle('B'.$cont)->applyFromArray($centrado);		
$objPHPExcel->getActiveSheet()->getStyle('B'.$cont.':BJ'.$cont)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5DADE2');
$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('D'.$cont, $prog1)
	->setCellValue('F'.$cont, $contra1)
	->setCellValue('H'.$cont, $eje1)
	->setCellValue('J'.$cont, $prog2)
	->setCellValue('L'.$cont, $contra2)
	->setCellValue('N'.$cont, $eje2)
	->setCellValue('P'.$cont, $prog3)
	->setCellValue('R'.$cont, $contra3)
	->setCellValue('T'.$cont, $eje3)
	->setCellValue('V'.$cont, $prog4)
	->setCellValue('X'.$cont, $contra4)
	->setCellValue('Z'.$cont, $eje4)
	->setCellValue('AB'.$cont, $prog5)
	->setCellValue('AD'.$cont, $contra5)
	->setCellValue('AF'.$cont, $eje5)
	->setCellValue('AH'.$cont, $prog6)
	->setCellValue('AJ'.$cont, $contra6)
	->setCellValue('AL'.$cont, $eje6)
	->setCellValue('AN'.$cont, $prog7)
	->setCellValue('AP'.$cont, $contra7)
	->setCellValue('AR'.$cont, $eje7)
	->setCellValue('AT'.$cont, $prog8)
	->setCellValue('AV'.$cont, $contra8)
	->setCellValue('AX'.$cont, $eje8)
	->setCellValue('AZ'.$cont, $prog9)
	->setCellValue('BB'.$cont, $contra9)
	->setCellValue('BD'.$cont, $eje9)
	->setCellValue('BF'.$cont, $prog10)
	->setCellValue('BH'.$cont, $contra10)
	->setCellValue('BJ'.$cont, $eje10);/**/

// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);
 
//**********************************************************************************************************************************
// Descargamos el archivo por el web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Presupuesto CAPEX.xlsx"');
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