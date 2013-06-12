// JavaScript Document


 var $$ = function(id){
   return document.getElementById(id);
 }
 
 var efectoClick = function(id){
	var ides = ['chonorario','cparametros','cventa','cprecio','cturno','cdescuento','ccuentas','cbonos']; 
	$$(id).style.display = "block"; 
	for (var j=0;j<ides.length;j++){
		if (ides[j] != id)
		$$(ides[j]).style.display = "none"; 
	}
 }
  
 var setValorCheck = function(id){
	if ($$(id).checked){
	  $$(id).value = "1";	
	}else{
	  $$(id).value = "0";	
	}
 }
 
 var eliminarFila = function(t){
    var td = t.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;	
    table.removeChild(tr);
}
 
 var insertarNewItem = function(tabladestino,descripcion){
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$(descripcion).selectedIndex; 
    var texto = $$(descripcion).options[indice].text;
    var id = "DS1_" + n;
	
	var datosIngreso =[
	{data:"<img src='../css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data:id, type:"set"},
	{id :descripcion, type:"get"},
	{data:texto, type:"set"},
	{data :"<select id='"+id+"' style='width:190px;'></select>" , type:"set"}
    ];
    cargarDatos(formato,datosIngreso,tabladestino);
    $$(id).innerHTML = $$("descuentoventa").innerHTML;
}

 var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'}
	];
	return formato;	
 }


 var insertarNewItemSocios = function(tabladestino,descripcion){
	var formato = getFormatoColumnaSocios();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$(descripcion).selectedIndex; 
    var texto = $$(descripcion).options[indice].text;
    var id = $$(descripcion).value;
	
	var datosIngreso =[
	{data:"<img src='../css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data:id, type:"set"},
	{data:texto, type:"set"},
    ];
    cargarDatos(formato,datosIngreso,tabladestino);
}

 var getFormatoColumnaSocios = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'left'}
	];
	return formato;	
 }


var insertarNewItemDescuento = function(tabladestino){
  var formato = getFormatoColumnaDescuento();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("combinacion").selectedIndex; 
    var texto = $$("combinacion").options[indice].text;
    var id = $$("combinacion").value;
	
	var datosIngreso =[
	{data:"<img src='../css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' />", type:"set"},
	{data:id, type:"set"},
	{data:texto, type:"set"},
	{data:$$("pdescuento").value, type:"set"}
    ];
    cargarDatos(formato,datosIngreso,tabladestino);	
}


var consultaPrecio = function(){
	var filtro = "transaccion=consultarPrecio&codigo="+ $$("combinacionB").value;
    enviar("Dconfiguracion.php", filtro, resultadoConsultarPrecio);
}


var resultadoConsultarPrecio = function(resultado){
    insertarNewItemBono("detalleS4", resultado);  
}


var insertarNewItemBono = function(tabladestino, precio){
    var formato = getFormatoColumnaBono();
	var n =  $$(tabladestino).rows.length + 1;
	var indice = $$("combinacionB").selectedIndex; 
    var texto = $$("combinacionB").options[indice].text;
    var id = $$("combinacionB").value;
	
	var datosIngreso =[
	{data:"<img src='../css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' class='puntero'/>", type:"set"},
	{data:id, type:"set"},
	{data:texto, type:"set"},
	{data:parseFloat(precio), type:"set"},
	{data:$$("pdescuentoB").value, type:"set"}
    ];
    cargarDatos(formato,datosIngreso,tabladestino);	
}

var getFormatoColumnaBono = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'}
	];
	return formato;	
 }

 var getFormatoColumnaDescuento = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'}
	];
	return formato;	
 }

function ajaxx() {
	 return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
}

 var enviar = function(direccion,filtro,funcion){
	 peticion = ajaxx(); 
	 peticion.open('GET', direccion+"?"+filtro, true);	 	 
	 peticion.onreadystatechange = function() { 	
	   if (peticion.readyState == 4) {
		  resultado = peticion.responseText;
		  if (funcion != null)
		  funcion(resultado);		  
	   } 
	}
	peticion.send(null); 
 }
 
 function ejecutarTransaccion(){
	var cadena;
	nfilas = $$('detalleS1').rows.length;		 	    	
    json = new Array();
       for(i=0;i<nfilas;i++) {
	     vector = new Array();
		 vector[0]=$$('detalleS1').rows[i].cells[2].innerHTML;		
		 var id = $$('detalleS1').rows[i].cells[1].innerHTML;
		 vector[1]= $$(id).value;			
		 json[i] = vector;	 		
       }
     dato = JSON.stringify(json);

	 nfilas = $$('detalleS2').rows.length;		 	    	
    json = new Array();
       for(i=0;i<nfilas;i++) {
	     vector = new Array();
		 vector[0]=$$('detalleS2').rows[i].cells[1].innerHTML;		
		 json[i] = vector;	 		
       }
     datoSocio = JSON.stringify(json);
	 
	 nfilas = $$('detalleS3').rows.length;		 	    	
     json = new Array();
       for(i=0;i<nfilas;i++) {
	     vector = new Array();
		 vector[0]=$$('detalleS3').rows[i].cells[1].innerHTML;
		 vector[1]=$$('detalleS3').rows[i].cells[3].innerHTML;		
		 json[i] = vector;	 		
       }
     datoDescuento = JSON.stringify(json);
	 
	 nfilas = $$('detalleS4').rows.length;		 	    	
     json = new Array();
       for(i=0;i<nfilas;i++) {
	     vector = new Array();
		 vector[0]=$$('detalleS4').rows[i].cells[1].innerHTML;
		 vector[1]=$$('detalleS4').rows[i].cells[3].innerHTML;
		 vector[2]=$$('detalleS4').rows[i].cells[4].innerHTML;		
		 json[i] = vector;	 		
       }
     datoBono = JSON.stringify(json);
	 
	var cadena = "transaccion=insertar&guardiam1="+$$("guardiam1").value+"&guardiam2="+$$("guardiam2").value
	+"&guardiam3="+$$("guardiam2").value+"&ventaminima="+$$("ventaminima").value
	+"&ayudantem1="+$$("ayudantem1").value+"&ayudantem2="+$$("ayudantem2").value+"&ayudantem3="+$$("ayudantem3").value+
	"&garzonm1="+$$("garzonm1").value+"&garzonm2="+$$("garzonm2").value+"&garzonm3="+$$("garzonm3").value+
	"&pantallapu="+$$("pantallapu").value+"&impresionpu="+$$("impresionpu").value+"&pantallapt="+$$("pantallapt").value+
	"&impresionpt="+$$("impresionpt").value+"&pantallatv="+$$("pantallatv").value+"&impresiontv="+$$("impresiontv").value
	+"&impresionCM="+$$("impresionCM").value+ "&impresionFV="+ $$("impresionFV").value
	+"&detalle="+dato+"&amdesde="+$$("amdesde").value+"&amhasta="+$$("amhasta").value+"&pmdesde="+$$("pmdesde").value
	+"&pmhasta="+$$("pmhasta").value+"&socios="+datoSocio+"&descuentos="+datoDescuento+"&cuentasocio="+$$("cuentasocio").value
	+"&cuentacortesia="+$$("cuentacortesia").value+"&cuentadescuento="+$$("cuentadescuento").value
	+"&bonoproducto=" + datoBono + "&exigibleapoyo=" + $$("exigibleapoyo").value+ "&gastosapoyo="+ $$("gastosapoyo").value;	 
	enviar("Dconfiguracion.php",cadena,resultadoTransaccion); 
 }
 
 var resultadoTransaccion = function(resultado){
	location.href = 'nuevo_configuracion.php';
 }
 
 var seleccionarCombo = function(combo,opcion){	 
	 var cb = document.getElementById(combo)
	 for (var i=0; i<cb.length; i++){
		if (cb[i].value==opcion){
		cb[i].selected = true;
		break;
		}
	 }	 
 }
 
   var mascarcaHora = function(valor,id,evt){
	  var tecla = (document.all) ? evt.keyCode : evt.which;       
	  if (tecla!=0 && tecla!=8){
		if (tecla <48 || tecla>57)  
		 return false;
		if (valor.length==2){
			$$(id).value = valor+":";
		}else{
		  if (valor.length == 5)
			return false;  
		}
	  }
	return true;	
  }