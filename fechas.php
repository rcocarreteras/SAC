<?php
//OBTENEMOS LAS FECHAS DE LA SEMANA ACTUAL, PASADA Y SIGUIENTE
date_default_timezone_set('America/Mexico_City');
//$hr = date('H:i:s');
//echo $hr;
$fecha = date('Y-m-j');
$fechanue = strtotime ( '+7 day' , strtotime ( $fecha ) ) ;
$fechanue = date ( 'Y-m-j' , $fechanue );

$fechavie = strtotime ( '-7 day' , strtotime ( $fecha ) ) ;
$fechavie = date ( 'Y-m-j' , $fechavie );

//SEMANA ACTUAL
$year=substr($fecha, 0,4);
$month=substr($fecha, 5,2);
$day=substr($fecha, 8,2);

# Obtenemos el numero de la semana
$numsemactual=date("W",mktime(0,0,0,$month,$day,$year));
# Obtenemos el día de la semana de la fecha dada
$diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
# el 0 equivale al domingo...
if($diaSemana==0)
    $diaSemana=7;
# A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
$primerDia=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
# A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
$ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
$semanactual = "Del " . $primerDia . " Al " . $ultimoDia;  
//echo "Actual: ".$semanactual."<br>"; 
$buscarsemana=$semanactual;

//SEMANA PASADA
$year=substr($fechavie, 0,4);
$month=substr($fechavie, 5,2);
$day=substr($fechavie, 8,2);
 
$semanavie=date("W",mktime(0,0,0,$month,$day,$year));
$diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
if($diaSemana==0)
    $diaSemana=7;
$primerDia=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
$ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
$semanapasada = "Del " . $primerDia . " Al " . $ultimoDia;




$anioAntes = strtotime ( '-1 year' , strtotime ( $fecha ) ) ;
$anioAntes = date ( 'Y' , $anioAntes );
$anioDes = strtotime ( '+1 year' , strtotime ( $fecha ) ) ;
$anioDes = date ( 'Y' , $anioDes );
//echo $anio;
//echo "Pasada: ".$semanapasada."<br>"; 

/*
$fechames = "10";
$fechames = strtotime ( '-2 month' , strtotime ( $fechames ) ) ;
$fechames = date ( 'm' , $fechames );
echo $fechames;
$fechames = strtotime ( '-1 month' , strtotime ( $fecha ) ) ;
$fechames = date ( 'Y-m-j' , $fechames );
//echo "26-".date("m")."-2016";*/

?>