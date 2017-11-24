// JavaScript Document FORMULAS DEL AVANCE DIARIO
function consultaUnidad1(valor){
	//alert(valor);
	$.post("consulta.php", { concepto: valor}, function(data){
		var campos = data;
		//alert(campos);		
			//DEJAMOS LOS CAMPOS LIMPIOS
			$('#formula').val(campos);					   
		    $('#longitud').removeAttr('readonly');
			$('#longitud').removeAttr('required');
			$('#longitud').css('background-color' , '#FFFFFF');					
			$('#ancho').removeAttr('readonly');
			$('#ancho').removeAttr('required');
			$('#ancho').css('background-color' , '#FFFFFF');
			$('#espesor').removeAttr('readonly');
			$('#espesor').removeAttr('required');
			$('#espesor').css('background-color' , '#FFFFFF');
			$('#cantidad').removeAttr('readonly');
			$('#cantidad').removeAttr('required');
			$('#cantidad').css('background-color' , '#FFFFFF');					
			
			switch(campos) {
				case '1':
				  //alert("Entre a la formula 1");	
				  $('#ancho').val('1');	
				  $('#espesor').val('1');				  
				  $('#longitud').attr('required', 'true');						  						  
				  $('#ancho').attr('readonly', 'true');
				  $('#espesor').attr('readonly', 'true');	
				  $('#cantidad').attr('readonly', 'true');
				  $('#ancho').css('background-color' , '#DEDEDE');						  
				  $('#espesor').css('background-color' , '#DEDEDE');
				  $('#cantidad').css('background-color' , '#DEDEDE');						 
				break;
				case '2':
				  //alert("Entre a la formula 2");	
				  $('#longitud').val('1');					  						  						  
				  $('#ancho').attr('required', 'true');
				  $('#espesor').attr('required', 'true');						 					 
				  $('#longitud').css('background-color' , '#DEDEDE');
				  $('#longitud').css('background-color' , '#DEDEDE');
				  $('#longitud').attr('readonly', 'true');
				  $('#longitud').attr('readonly', 'true');
				break;
				case '3':
				  //alert("Entre a la formula 3");						  
				  $('#longitud').attr('required', 'true');
				  $('#ancho').attr('required', 'true');
				  $('#espesor').attr('required', 'true');
				  $('#cantidad').css('background-color' , '#DEDEDE');
				  $('#cantidad').attr('readonly', 'true');
				break;
				case '4':
				  //alert("Entre a la formula 4");
				  $("#longitud").val('0');
		          $("#ancho").val('0');
			      $("#espesor").val('0');
			      $("#cantidad").val('0');
				  $('#longitud').attr('readonly', 'true');
				  $('#ancho').attr('readonly', 'true');
				  $('#espesor').attr('readonly', 'true');	
				  $('#longitud').css('background-color' , '#DEDEDE');
				  $('#ancho').css('background-color' , '#DEDEDE');
				  $('#espesor').css('background-color' , '#DEDEDE');		
				  $('#cantidad').attr('required', 'true');						  						  
				break;
				case '5':
				  //alert("Entre a la formula 5");
				  $('#cantidad').val('0');
				  $('#longitud').attr('readonly', 'true');
				  $('#ancho').attr('readonly', 'true');
				  $('#espesor').attr('readonly', 'true');
				  $('#cantidad').attr('readonly', 'true');	
				  					  
				  $('#longitud').css('background-color' , '#DEDEDE');
				  $('#ancho').css('background-color' , '#DEDEDE');
				  $('#espesor').css('background-color' , '#DEDEDE');						  
				  $('#cantidad').css('background-color' , '#DEDEDE');
				  $('#cantidad').attr('required', 'true');						  						  
				break;
				case '6':
				  //alert("Entre a la formula 6");
				  $('#longitud').attr('readonly', 'true');
				  $('#ancho').attr('readonly', 'true');
				  $('#espesor').attr('readonly', 'true');
				  $('#cantidad').attr('readonly', 'true');	
				  $('#longitud').css('background-color' , '#DEDEDE');
				  $('#ancho').css('background-color' , '#DEDEDE');
				  $('#espesor').css('background-color' , '#DEDEDE');						  
				  $('#cantidad').css('background-color' , '#DEDEDE');
				  
				  $("#cantidad").jqxInput({value: '1', minLength: 1});	
				break;
				case '7':
			   //alert("Entre a la formula 7");		
				  $("#ancho").val('0');
			      $("#espesor").val('0');				  
				  $('#longitud').attr('required', 'true');						  						  
				  $('#ancho').attr('readonly', 'true');
				  $('#espesor').attr('readonly', 'true');	
				  $('#cantidad').attr('readonly', 'true');
				  $('#ancho').css('background-color' , '#DEDEDE');						  
				  $('#espesor').css('background-color' , '#DEDEDE');
				  $('#cantidad').css('background-color' , '#DEDEDE');						 
				break;
				case '8':
				  //alert("Entre a la formula 8");						  
				  $('#longitud').attr('required', 'true');		
				  $('#ancho').attr('required', 'true');
				  $('#longitud').attr('readonly', 'true');	
				  $('#espesor').attr('readonly', 'true');	
				  $('#cantidad').attr('readonly', 'true');		
				  $('#longitud').css('background-color' , '#DEDEDE');				 						  
				  $('#espesor').css('background-color' , '#DEDEDE');
				  $('#cantidad').css('background-color' , '#DEDEDE');						 
				break;
			}
	});	          	
}

function calculaLon1(longitud) {	
	var ancho = $("#ancho").val();
	var espesor = $("#espesor").val();
	var cantidad = longitud * ancho * espesor;
	
	$("#cantidad").val(cantidad);	
}	
function calculaAnc1(ancho) {	
	var longitud = $("#longitud").val();
	var espesor = $("#espesor").val();
	var cantidad = longitud * ancho * espesor;
	
	$("#cantidad").val(cantidad);	
}
function calculaEsp1(espesor) {	
	var longitud = $("#longitud").val();
	var ancho = $("#ancho").val();
	var cantidad = longitud * ancho * espesor;
	
	$("#cantidad").val(cantidad);	
}

function calculaKm1(valor) {	
	if( $('#calculakm').prop('checked') ) {		
		var km1 = $("#km_ini").val() + $("#enca_ini").val();
		var km2 = $("#km_fin").val() + $("#enca_fin").val();	
		var total = km2 - km1;
		if (total < 0){
			total = total * -1;			
		}
		
		switch ($('#formula').val()){			
			case '1':
			$("#longitud").val(total);
		    $("#cantidad").val(total);	        
	        break;
	        case '2':
			$("#longitud").val(total);
			$("#cantidad").val($('#longitud').val()*$('#ancho').val()*$('#espesor').val());
	        break;
			case '3':
			$("#longitud").val(total);
			$("#cantidad").val($('#longitud').val()*$('#ancho').val()*$('#espesor').val());
	        break;
			case '7':
	        $("#longitud").val(total);
			$("#cantidad").val($('#longitud').val()/1000);
	        break;	
			case '8':
	        $("#longitud").val(total);
			$("#cantidad").val(($('#longitud').val()*$('#ancho').val())/10000);
	        break;	   			
	    }
			
	}else{
		switch ($('#formula').val()){			
			case '1':
	        $("#longitud").val('0');
			$("#cantidad").val('0');
	        break;
	        case '2':
	        $("#longitud").val('1');
			$("#cantidad").val($('#longitud').val()*$('#ancho').val()*$('#espesor').val());
	        break;
			case '3':
	        $("#longitud").val('0');
			$("#cantidad").val($('#longitud').val()*$('#ancho').val()*$('#espesor').val());
	        break;
			case '7':
	        $("#longitud").val('0');
			$("#cantidad").val($('#longitud').val()/1000);
	        break;
			case '8':
	        $("#longitud").val('0');
			$("#cantidad").val(($('#longitud').val()*$('#ancho').val())/10000);
	        break;
	    }//switch
	}
}

function MostrarFilas(Fila) {
	var quitar = Fila -1;	
	document.getElementById("sac"+quitar).style.display = "none";
	
	var elementos = document.getElementsByName(Fila);    
    for (i = 0; i< elementos.length; i++) {
        if(navigator.appName.indexOf("Microsoft") > -1){
               var visible = 'block'
        } else {
               var visible = 'table-row';
        }
	elementos[i].style.display = visible;
        }
}

function OcultarFilas(Fila) {	
    var elementos = document.getElementsByName(Fila);
    for (k = 0; k< elementos.length; k++) {
               elementos[k].style.display = "none";
    }
}

//--------------------------------------------------------------------MULTIPUNTO----------------------------------------------------------------
function consultaUnidad(valor){		
            var campos = valor;			
			//DEJAMOS LOS CAMPOS LIMPIOS				   
		    $('#longitudmulti').removeAttr('readonly');
			$('#longitudmulti').removeAttr('required');
			$('#longitudmulti').css('background-color' , '#FFFFFF');					
			$('#anchomulti').removeAttr('readonly');
			$('#anchomulti').removeAttr('required');
			$('#anchomulti').css('background-color' , '#FFFFFF');
			$('#espesormulti').removeAttr('readonly');
			$('#espesormulti').removeAttr('required');
			$('#espesormulti').css('background-color' , '#FFFFFF');
			$('#cantidadmulti').removeAttr('readonly');
			$('#cantidadmulti').removeAttr('required');
			$('#cantidadmulti').css('background-color' , '#FFFFFF');					
			
			switch(campos) {
				case '1':
				  //alert("Entre a la formula 1");	
				  $('#anchomulti').val('1');	
				  $('#espesormulti').val('1');				  
				  $('#longitudmulti').attr('required', 'true');						  						  
				  $('#anchomulti').attr('readonly', 'true');
				  $('#espesormulti').attr('readonly', 'true');	
				  $('#cantidadmulti').attr('readonly', 'true');
				  $('#anchomulti').css('background-color' , '#DEDEDE');						  
				  $('#espesormulti').css('background-color' , '#DEDEDE');
				  $('#cantidadmulti').css('background-color' , '#DEDEDE');						 
				break;
				case '2':
				  //alert("Entre a la formula 2");	
				  $('#longitudmulti').val('1');					  						  						  
				  $('#anchomulti').attr('required', 'true');
				  $('#espesormulti').attr('required', 'true');						 					 
				  $('#longitudmulti').css('background-color' , '#DEDEDE');
				  $('#cantidadmulti').css('background-color' , '#DEDEDE');
				  $('#longitudmulti').attr('readonly', 'true');
				  $('#cantidadmulti').attr('readonly', 'true');
				break;
				case '3':
				  $('#longitudmulti').attr('required', 'true');
				  //alert("Entre a la formula 3");						  
				  $('#anchomulti').attr('required', 'true');
				  $('#espesormulti').attr('required', 'true');
				  $('#cantidadmulti').css('background-color' , '#DEDEDE');
				  $('#cantidadmulti').attr('readonly', 'true');
				break;
				case '4':
				  //alert("Entre a la formula 4");
				  $("#longitudmulti").val('0');
		          $("#anchomulti").val('0');
			      $("#espesormulti").val('0');
			      $("#cantidadmulti").val('0');
				  $('#longitudmulti').attr('readonly', 'true');
				  $('#anchomulti').attr('readonly', 'true');
				  $('#espesormulti').attr('readonly', 'true');	
				  $('#longitudmulti').css('background-color' , '#DEDEDE');
				  $('#anchomulti').css('background-color' , '#DEDEDE');
				  $('#espesormulti').css('background-color' , '#DEDEDE');		
				  $('#cantidadmulti').attr('required', 'true');						  						  
				break;
				case '5':
				  //alert("Entre a la formula 5");
				  $('#cantidadmulti').val('0');
				  $('#longitudmulti').attr('readonly', 'true');
				  $('#anchomulti').attr('readonly', 'true');
				  $('#espesormulti').attr('readonly', 'true');
				  $('#cantidadmulti').attr('readonly', 'true');	
				  					  
				  $('#longitudmulti').css('background-color' , '#DEDEDE');
				  $('#anchomulti').css('background-color' , '#DEDEDE');
				  $('#espesormulti').css('background-color' , '#DEDEDE');						  
				  $('#cantidadmulti').css('background-color' , '#DEDEDE');
				  $('#cantidadmulti').attr('required', 'true');						  						  
				break;
				case '6':
				  //alert("Entre a la formula 6");
				  $('#longitudmulti').attr('readonly', 'true');
				  $('#anchomulti').attr('readonly', 'true');
				  $('#espesormulti').attr('readonly', 'true');
				  $('#cantidadmulti').attr('readonly', 'true');	
				  $('#longitudmulti').css('background-color' , '#DEDEDE');
				  $('#anchomulti').css('background-color' , '#DEDEDE');
				  $('#espesormulti').css('background-color' , '#DEDEDE');						  
				  $('#cantidadmulti').css('background-color' , '#DEDEDE');
				  
				  $("#cantidadmulti").jqxInput({value: '1', height: 15, width: 80, minLength: 1});	
				break;
				case '7':
			   //alert("Entre a la formula 7");		
				  $("#anchomulti").val('0');
			      $("#espesormulti").val('0');				  
				  $('#longitudmulti').attr('required', 'true');						  						  
				  $('#anchomulti').attr('readonly', 'true');
				  $('#espesormulti').attr('readonly', 'true');	
				  $('#cantidadmulti').attr('readonly', 'true');
				  $('#anchomulti').css('background-color' , '#DEDEDE');						  
				  $('#espesormulti').css('background-color' , '#DEDEDE');
				  $('#cantidadmulti').css('background-color' , '#DEDEDE');						 
				break;
				case '8':
				  //alert("Entre a la formula 8");						  
				  $('#longitudmulti').attr('required', 'true');		
				  $('#anchomulti').attr('required', 'true');
				  $('#longitudmulti').attr('readonly', 'true');	
				  $('#espesormulti').attr('readonly', 'true');	
				  $('#cantidadmulti').attr('readonly', 'true');		
				  $('#longitudmulti').css('background-color' , '#DEDEDE');				 						  
				  $('#espesormulti').css('background-color' , '#DEDEDE');
				  $('#cantidadmulti').css('background-color' , '#DEDEDE');						 
				break;
			}		
}

function calculaLon(longitud) {	
	//alert(longitud);
	var ancho = $("#anchomulti").val();
	var espesor = $("#espesormulti").val();
	var cantidad = longitud * ancho * espesor;
	
	$("#cantidadmulti").val(cantidad);	
}	
function calculaAnc(ancho) {	
	var longitud = $("#longitudmulti").val();
	var espesor = $("#espesormulti").val();
	var cantidad = longitud * ancho * espesor;
	
	$("#cantidadmulti").val(cantidad);
}
function calculaEsp(espesor) {	
	var ancho = $("#anchomulti").val();
	var longitud = $("#longitudmulti").val();
	var cantidad = longitud * ancho * espesor;
	
	$("#cantidadmulti").val(cantidad);	
}

function calculaKm(valor) {
	
	if( $('#calcular').prop('checked') ) {
		var km1 = $("#km_inimulti").val() + $("#enca_inimulti").val();
		var km2 = $("#km_finmulti").val() + $("#enca_finmulti").val();	
		
		var total = km2 - km1;
		//alert(total);
		if (total < 0){
			total = total * -1;			
		}//if
		switch ($('#formulamulti').val()){			
			case '1':
			$("#longitudmulti").val(total);
		    $("#cantidadmulti").val(total);	        
	        break;
	        case '2':
			$("#longitudmulti").val(total);
			$("#cantidadmulti").val($('#longitudmulti').val()*$('#anchomulti').val()*$('#espesormulti').val());
	        break;
			case '3':
			$("#longitudmulti").val(total);
			$("#cantidadmulti").val($('#longitudmulti').val()*$('#anchomulti').val()*$('#espesormulti').val());
	        break;
			case '7':
	        $("#longitudmulti").val(total);
			$("#cantidadmulti").val($('#longitudmulti').val()/1000);
	        break;	
			case '8':
	        $("#longitudmulti").val(total);
			$("#cantidadmulti").val(($('#longitudmulti').val()*$('#anchomulti').val())/10000);
	        break;	   			
	    }//switch
			
	}else{
		switch ($('#formulamulti').val()){			
			case '1':
	        $("#longitudmulti").val('0');
			$("#cantidadmulti").val('0');
	        break;
	        case '2':
	        $("#longitudmulti").val('1');
			$("#cantidadmulti").val($('#longitudmulti').val()*$('#anchomulti').val()*$('#espesormulti').val());
			//alert($("#cantidadmulti").val());
	        break;
			case '3':
	        $("#longitudmulti").val('0');
			$("#cantidadmulti").val($('#longitudmulti').val()*$('#anchomulti').val()*$('#espesormulti').val());
	        break;
			case '7':
	        $("#longitudmulti").val('0');
			$("#cantidadmulti").val($('#longitudmulti').val()/1000);
	        break;
			case '8':
	        $("#longitudmulti").val('0');
			$("#cantidadmulti").val(($('#longitudmulti').val()*$('#anchomulti').val())/10000);
	        break;
	    }//switch
	}//else	
}//function