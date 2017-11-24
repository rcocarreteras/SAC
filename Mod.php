<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0060)http://www.jose-aguilar.com/scripts/jquery/shadowbox-onload/ -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Ventana Shadowbox al cargar página con jQuery</title>
<link href="css/shadowbox.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/shadowbox.js"></script>
<script type="text/javascript"> Shadowbox.init({ language: "es", players:  ['img', 'html', 'iframe', 'qt', 'wmp', 'swf', 'flv'] }); </script>
<script type="text/javascript"> 
$(document).ready(function(){
	setTimeout(function() {
	    Shadowbox.open({
    	    content:    '<table width="580" height="120" border="0" align="center"><tr><td width="116" height="24">Actividad</td><td width="443"><input type="textbox" id="actividad" name="actividad" class="form-control" /></td></tr><tr><td height="24">Empleado</td><td><input type="textbox" id="nombre" name="nombre" class="form-control" accept-charset="utf-8" /></td></tr><tr><td height="24">Horas Trabajadas</td><td><input type="textbox" id="horas" name="horas" /></td></tr></table>',
    	    player:     "html",
    	    title:      "Informacion",
    	    width:      585,
    	    height:     250
    	});
	}, 50);
});
</script>
<style type="text/css">

.html, body {
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
}

.ejemplo {
	float:left;
	width:100%;
	padding:0px;
	margin:0px;
}

.ejemplo img {
	float:left;
	padding:2px;
	border:1px solid #999;
	margin-right:10px;
	margin-bottom:10px;
}

</style>
</head>
<body>
<div class="ejemplo">
Ventana Shadowbox al cargar página con jQuery
<br><br>
Contenido de prueba
</div>


<div id="sb-container" style="display: none; height: 955px; width: 1920px; visibility: hidden;"><div id="sb-overlay" style="opacity: 0; background-color: rgb(0, 0, 0);"></div><div id="sb-wrapper" style="top: 270px; left: 713px; width: 494px; visibility: hidden;"><div id="sb-title"><div id="sb-title-inner" style="margin-top: 0px;">Oferta</div></div><div id="sb-wrapper-inner" style="height: 367px;"><div id="sb-body"><div id="sb-body-inner"></div><div id="sb-loading" style="display: none;"><div id="sb-loading-inner"><span>cargando</span></div></div></div></div><div id="sb-info"><div id="sb-info-inner" style="margin-top: 0px;"><div id="sb-counter"></div><div id="sb-nav"><a id="sb-nav-close" title="Cerrar" onclick="Shadowbox.close()"></a><a id="sb-nav-next" title="Siguiente" onclick="Shadowbox.next()" style="display: none;"></a><a id="sb-nav-play" title="Reproducir" onclick="Shadowbox.play()" style="display: none;"></a><a id="sb-nav-pause" title="Pausa" onclick="Shadowbox.pause()" style="display: none;"></a><a id="sb-nav-previous" title="Anterior" onclick="Shadowbox.previous()" style="display: none;"></a></div></div></div></div></div></body></html>