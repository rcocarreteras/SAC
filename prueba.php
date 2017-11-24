<!DOCTYPE HTML>
<html>
<head>
<title>Enviar formulario con Ajax Jquery</title>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script>   
$(function(){
 $("#btn_enviar").click(function(){
 var url = "dame-datos.php"; // El script a dónde se realizará la petición.
    $.ajax({
           type: "POST",
           url: url,
           data: $("#formulario").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               $("#respuesta").html(data); // Mostrar la respuestas del script PHP.
           }
         });

    return false; // Evitar ejecutar el submit del formulario.
 });
});
</script>
</head>
<body>
<p>Al enviar el formulario vía ajax, consultaremos en el archivo dame-datos.php si el valor del campo nombre se 
encuentra en el array y la respuestas será positiva o negativa, según su valor.</p>
<p>El Array contiene los siguientes nombres ... <b>antonio, pedro, alberto</b></p>
<center>
<form method="post" id="formulario">
<table>
<tr>
<td>Introduce un nombre:</td><td><input type="text" name="nombre"></td>
<td></td><td><input type="button" id="btn_enviar" value="Buscar nombre"></td>
</tr>
</table>
</form>
<div id="respuesta">
</div>
</center>
</body>
</html>