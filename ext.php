<?php 
$periodo = '2017-05';
$tramo = 'Zapotlanejo - Guadalajara';
$dir = "../Global/Sac/Avance/".$periodo."/".$tramo."/";

// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
      //echo "filename:" . $file . "<br>";
	  $ext = pathinfo($file, PATHINFO_EXTENSION);
	  if ($ext == 'jpg'){
		  echo $file;
		}
    }
    closedir($dh);
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Extensión</title>
</head>

<body>
</body>
</html>