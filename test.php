<?php
$horaini = "08:05:18.000";
$horafin = "19:23:42.000";

$horai = substr($horaini,0,2);
$mini = substr($horaini,3,2);
$segi = substr($horaini,6,2);
 
$horaf = substr($horafin,0,2);
$minf = substr($horafin,3,2);
$segf = substr($horafin,6,2);
 
$ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);
$fin = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

$dif = $fin-$ini;

$difh = floor($dif / 3600);
$difm = floor(($dif - ($difh * 3600)) / 60);
$difs = $dif - ($difm * 60) - ($difh * 3600);
$hrlaboradas = date("H:i:s", mktime($difh, $difm, $difs));

if ($hrlaboradas <= 8){
	echo "<strong>Tipo de horas:</strong> Ordinario <br>";
}else{
	$extra = $hrlaboradas - 8;
	echo "<strong>Tipo de horas:</strong> Extraordinario <br>";
	echo "<strong>Total de horas extras:</strong> ".$extra."<br>";
}

echo "<strong>Hora Entrada: </strong>".$horaini."<br>";
echo "<strong>Hora Salida: </strong>".$horafin."<br>";
echo "<strong>Total de horas laboradas: </strong>".$hrlaboradas;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Resta de horas</title>
</head>

<body>
</body>
</html>