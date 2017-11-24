<?php
$options="";

//--------------------------------------------------------------------------CATALOGO DE CUERPOS----------------------------------------------------------------------

if ($_POST["cuerpo"]=='A' or $_POST["cuerpo"]=='B') {
    $options= '
	<option value="Acotamiento Exterior"> Acot. Exterior</option>
	<option value="Baja"> Baja </option> 
	<option value="Alta"> Alta </option>
	<option value="Acotamiento Interior"> Acot. Interior </option>
	<option value="Corona"> Corona </option> 
	<option value="3er. Carril"> 3er. Carril </option>   
	<option value="Lateral"> Lateral </option>   
    ';    
}
if ($_POST["cuerpo"]=='C') {
    $options= '
	<option value="DVa"> DVa </option>
	<option value="Camellon"> Camellon </option> 
	<option value="DVb"> DVb </option>
    ';    
}
//---------------------------------------------------------------------------------------------------------------------------
echo $options;    
?>