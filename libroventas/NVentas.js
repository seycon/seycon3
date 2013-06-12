
  var servidor = "DVentas.php";
  var totalTransaccion = {factura :0, ice:0, excento:0, neto:0, fiscal:0};

  var adaptarDatos = function(){	
	($$("codigocontrol").value == "") ? ($$("codigocontrol").value = "0"): 0;
	($$("importetotal").value == "") ? ($$("importetotal").value = "0"): 0;
	($$("ice").value == "") ? ($$("ice").value = "0"): 0;
	($$("excento").value == "") ? ($$("excento").value = "0"): 0;
	($$("neto").value == "") ? ($$("neto").value = "0"): 0;
	($$("iva").value == "") ? ($$("iva").value = "0"): 0;
	($$("razonsocial").value == "") ? ($$("razonsocial").value = "0"): 0;
  }

  function editar(valor){
	$$('cubrir').style.visibility = 'visible';
	var td = valor.parentNode;
    var tr = td.parentNode;
	index = tr.rowIndex -1 ;
    $$("fila").value = index;
    $$('fecha').value = $$('a').rows[index].cells[3].innerHTML.split('/')[0];
    $$('nit').value = $$('a').rows[index].cells[4].innerHTML;
    $$('razonsocial').value = $$('a').rows[index].cells[18].innerHTML;
	$$('numerofactura').value = $$('a').rows[index].cells[6].innerHTML;
	$$('numeroautorizacion').value = $$('a').rows[index].cells[7].innerHTML;
	$$('codigocontrol').value = $$('a').rows[index].cells[8].innerHTML;
	$$('importetotal').value = desconvertirFormatoNumber($$('a').rows[index].cells[9].innerHTML);
	$$('ice').value = desconvertirFormatoNumber($$('a').rows[index].cells[10].innerHTML);
	$$('excento').value = desconvertirFormatoNumber($$('a').rows[index].cells[11].innerHTML);
	$$('neto').value = desconvertirFormatoNumber($$('a').rows[index].cells[12].innerHTML);
	$$('iva').value = desconvertirFormatoNumber($$('a').rows[index].cells[13].innerHTML);
	cuenta = $$('a').rows[index].cells[15].innerHTML;
	estado = $$('a').rows[index].cells[17].innerHTML;
	$$('id').value =  $$('a').rows[index].cells[16].innerHTML;
	seleccionarRadio('formulario','tipocuenta',cuenta);
	seleccionarCombo('estado', estado);
	$$("a").deleteRow(index);
	sumarTotales();
  }


  function limpiarIngresoDatos(){
     var datos = ['fecha','nit','razonsocial','numerofactura','numeroautorizacion','codigocontrol','importetotal',
	 'ice','excento','neto','iva'];
	 for (var j=0;j<datos.length;j++){		 
		$$(datos[j]).value = ""; 
	 }
     $$("fila").value = "-1";
	 seleccionarCombo('estado', "V");
	 seleccionarRadio('formulario','tipocuenta','servicios');
  }
   
  var ejecutarTransaccion = function(){
	  adaptarDatos();
	  if (datosCompletos()){
	     if ($$("fila").value == "-1") {
			 var dato = $$("razonsocial").value.split("-");
			 if ($$("estado").value == "A" && dato[1] != null && isvalidoNumero(dato[1])) {
				if (parseFloat(dato[1]) >= parseFloat($$("numerofactura").value)) 
				   verificarFactura(dato[1]); 
				else
				   openMensaje("Advertencia","El número de factura limite debe ser mayor al número de factura actual.");
			 } else {
				nuevo("insertar"); 
			 }
		     
	     } else {
   		     nuevo("modificar");
	     }
      }
  }
  
  function nuevo(tipo){	
     var cuenta = obtenerSeleccionRadio('formulario','tipocuenta');
     filtro = "transaccion="+tipo+"&sucursal="+$$("sucursal").value+"&mes="+$$("mes").value+"&anio="+$$("anio").value
      +"&dia="+$$("fecha").value+"&nit="+$$("nit").value+"&razonsocial="+$$("razonsocial").value
	  +"&numerofactura="+$$("numerofactura").value+
	  "&numeroautorizacion="+$$("numeroautorizacion").value+"&importetotal="+$$("importetotal").value+
	  "&ice="+$$("ice").value+"&excento="+$$("excento").value+"&neto="+$$("neto").value+"&iva="+$$("iva").value+
	  "&codigocontrol="+$$("codigocontrol").value+"&idtransaccion="+$$('id').value+"&estado="+$$("estado").value
	  +"&cuenta="+cuenta;  
	  
	  if(tipo == "insertar") 
	    enviarGeneral(filtro,resultadoTransaccion);	 
	  else
	    enviarGeneral(filtro,resultadoTModificar);
  }
   
  function verificarFactura(nrofinal) {    
      var filtro = "numero="+$$("numerofactura").value+"&final="+nrofinal
	  +"&transaccion=disponible"+"&sucursal="+$$("sucursal").value;
	  enviarGeneral(filtro,resultadoFacturas);
  } 
  
  var resultadoFacturas = function(resultado){
	 if (resultado == "limite") {
		openMensaje("Advertencia","Número de facturas insuficientes."); 
	 } else {
		var dato = $$("razonsocial").value.split("-");
		var cuenta = obtenerSeleccionRadio('formulario','tipocuenta');
		 filtro = "transaccion=insertar&sucursal="+$$("sucursal").value+"&mes="+$$("mes").value+"&anio="+$$("anio").value
		  +"&dia="+$$("fecha").value+"&nit="+$$("nit").value+"&razonsocial="+dato[0]
		  +"&numerofactura="+$$("numerofactura").value+
		  "&numeroautorizacion="+$$("numeroautorizacion").value+"&importetotal="+$$("importetotal").value+
		  "&ice="+$$("ice").value+"&excento="+$$("excento").value+"&neto="+$$("neto").value+"&iva="+$$("iva").value+
		  "&codigocontrol="+$$("codigocontrol").value+"&idtransaccion="+$$('id').value+"&estado="+$$("estado").value
		  +"&cuenta="+cuenta+"&limiteregistro="+dato[1]; 
		  enviarGeneral(filtro,resultadoTransaccion);
	 }
  }
  
  var isvalidoNumero = function(value){	
   return (isNaN(value)) ? false : true; 
  } 
   
  var cambioEstado = function(tipo){
	  if(tipo == 'A'){
		 $$("razonsocial").value = "Anulado";
		 $$("importetotal").value = 0;
		 $$("ice").value = 0;
    	 $$("excento").value = 0;
		 $$("neto").value = 0;
		 $$("iva").value = 0;
    	 $$("codigocontrol").value = 0;
		 $$("nit").value = 0;
		 seleccionarCombo('estado','A');
		 $$("Guardar").focus();
	  }
	  else{
		 $$("razonsocial").value = "";
		 $$("importetotal").value = "";
		 $$("ice").value = "";
    	 $$("excento").value = "";
		 $$("neto").value = "";
		 $$("iva").value = "";
    	 $$("codigocontrol").value = "";
		 $$("nit").value = "";
	  }
  }
   
   
  var resultadoTModificar = function(resultado){
	   var datos = resultado.split("---");
	   insertarNewItem('a');
	   $$('fila').value = '-1';	   
	   if (datos[0] == "fecha" || datos[0] == "numero"){
		  if (datos[0] == "fecha"){
			$$("mensajeLibro").innerHTML = "Fecha Limite Emision";  
		  }else{
			$$("mensajeLibro").innerHTML = "Facturas Agotadas";			  
		  }		  
       $$("mensajeLibro").style.visibility = "visible";	   
	  }else{
	   $$("mensajeLibro").style.visibility = "hidden";	   
	   $$("numerofactura").value = datos[1];
	   $$("numeroautorizacion").value = datos[2];
	   $$('cubrir').style.visibility = 'hidden';
	   seleccionarCombo('estado', "V");
   	   seleccionarRadio('formulario','tipocuenta','servicios');
	   $$('fecha').focus();
	  }
  }
 
  var resultadoTransaccion = function(resultado){
	  var datos = resultado.split("---");
	  if (datos[0] == "multiple") {
		realizarConsulta();
		return;  
	  }
	  
	  
	  if (datos[0] == "fecha" || datos[0] == "numero"){
		  if (datos[0] == "fecha"){
			$$("mensajeLibro").innerHTML = "Fecha Limite Emision";  
		  }else{
			$$("mensajeLibro").innerHTML = "Facturas Agotadas";			  
		  }		  
       $$("mensajeLibro").style.visibility = "visible";	   
	  }else{	  
	   $$("mensajeLibro").style.visibility = "hidden";
	   $$('fila').value = '-1';
	   if (datos[0] != "-1"){
	     $$('id').value = datos[0];
	     insertarNewItem('a');
	     $$("numerofactura").value = datos[1];
	     $$("numeroautorizacion").value = datos[2];
	     $$('cubrir').style.visibility = 'hidden';
	     seleccionarCombo('estado', "V");
   	     seleccionarRadio('formulario','tipocuenta','servicios');
	     $$('fecha').focus();
	   }else{
		 $$("mensajeLibro").innerHTML = "Sin Movimiento"; 
		 $$("mensajeLibro").style.visibility = "visible";
	   }
	  }
  }
   
  var insertarNewItem = function(tabladestino){
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var estado = $$("estado").value;
	var cuenta = obtenerSeleccionRadio('formulario','tipocuenta');
	var textoRazonSocial = $$("razonsocial").value;
	textoRazonSocial = textoRazonSocial.substring(0,25);	
	fechaCompra = $$("fecha").value+"/"+$$("mes").value+"/"+$$("anio").value;
	var datosIngreso =[
	{data:"<img src='../iconos/edit.png' style='cursor:pointer' title='Modificar' onclick='editar(this)'/>", type:"set"},
	{data:"<img src='../iconos/borrar.gif' style='cursor:pointer' title='Eliminar' onclick='eliminarItem(this)'/>", type:"set"},
	{data : n , type:"set"},
	{data : fechaCompra , type:"set" },
	{id :"nit", type:"get"},
	{data : textoRazonSocial, type:"set"},
	{id :"numerofactura", type:"get"},
	{id :"numeroautorizacion", type:"get"},
	{id :"codigocontrol", type:"get"},
	{id :"importetotal", type:"get"},
	{id :"ice", type:"get"},
	{id :"excento", type:"get"},
	{id :"neto", type:"get"},
	{id :"iva", type:"get"},
	{data :"LV" , type:"set"},
	{data :cuenta, type:"set"},
	{id :"id" , type:"get"},
	{data : estado , type:"set"},
	{id :"razonsocial", type:"get"}
	];
	
	pos = posicionInsertar();
	var total = cargarDatos(formato,datosIngreso,tabladestino,'sizeLetra',pos);
	if (total.length > 0){
	 $$("fecha").value = "";
	 cargarTotales(total[0],total[1],total[2],total[3],total[4]);	
	}
  }

  var cargarTotales = function(factura,ice,excento,neto,fiscal){
   	totalTransaccion.factura = parseFloat(totalTransaccion.factura) + parseFloat(factura);
	totalTransaccion.ice = parseFloat(totalTransaccion.ice) + parseFloat(ice);
	totalTransaccion.excento = parseFloat(totalTransaccion.excento) + parseFloat(excento);
	totalTransaccion.neto = parseFloat(totalTransaccion.neto) + parseFloat(neto);
	totalTransaccion.fiscal = parseFloat(totalTransaccion.fiscal) + parseFloat(fiscal);		
	$$("totalf").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.factura).toFixed(2));
	$$("totali").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.ice).toFixed(2));
    $$("totale").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.excento).toFixed(2));
    $$("totaln").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.neto).toFixed(2));	
    $$("totald").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.fiscal).toFixed(2));	
	asc();
  }



  function sumarTotales(){
	   filas = $$('a').rows.length;
	   totalTransaccion = {factura :0, ice:0, excento:0, neto:0, fiscal:0};
	   for (var i=0;i<filas;i++){
		 factura =(desconvertirFormatoNumber(""+$$('a').rows[i].cells[9].innerHTML+""));
		 ice =(desconvertirFormatoNumber(""+$$('a').rows[i].cells[10].innerHTML+""));
		 excento =(desconvertirFormatoNumber(""+$$('a').rows[i].cells[11].innerHTML+""));
		 neto =(desconvertirFormatoNumber(""+$$('a').rows[i].cells[12].innerHTML+""));
		 fiscal =(desconvertirFormatoNumber(""+$$('a').rows[i].cells[13].innerHTML+""));
		 totalTransaccion.factura = parseFloat(totalTransaccion.factura) + parseFloat(factura);
         totalTransaccion.ice = parseFloat(totalTransaccion.ice) + parseFloat(ice);
	     totalTransaccion.excento = parseFloat(totalTransaccion.excento) + parseFloat(excento);
	     totalTransaccion.neto = parseFloat(totalTransaccion.neto) + parseFloat(neto);
	     totalTransaccion.fiscal = parseFloat(totalTransaccion.fiscal) + parseFloat(fiscal);	
	   }
	   $$("totalf").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.factura).toFixed(2));
	   $$("totali").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.ice).toFixed(2));
       $$("totale").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.excento).toFixed(2));
       $$("totaln").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.neto).toFixed(2));	
       $$("totald").innerHTML = convertirFormatoNumber(parseFloat(totalTransaccion.fiscal).toFixed(2));		
  }


  var getFormatoColumna = function(){
	var formato = [
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'normal', numerico : 'no', aling : 'left'},
	{type : 'contable', numerico : 'si', aling : 'center'},	
	{type : 'contable', numerico : 'si', aling : 'center'},	
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'contable', numerico : 'si', aling : 'center'},
	{type : 'contable', numerico : 'si', aling : 'center'},	
	{type : 'normal', numerico : 'no', aling : 'center'},
	{type : 'normal', numerico : 'no', aling : 'center' ,display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'},
	{type : 'normal', numerico : 'no', aling : 'center', display : 'none'}
	];
	return formato;	
  }

  
  function calculaIva(){
	  $$('iva').value = d($$('importetotal').value*0.13);
	  $$('codigocontrol').focus();
  }
  
  function calculaNeto(){
	  $$('neto').value = $$('importetotal').value - $$('ice').value - $$('excento').value; 
	  $$('iva').focus();
  }
  
 
  function d(numero){
	  nu = Math.round(numero*100) / 100; 
	  return nu;
  }
 

   function asc(){
	 var filas = $$('a').rows.length;
	 var numero = filas;  
	   for (i=0;i<filas;i++){
	        $$('a').rows[i].cells[2].innerHTML = numero;
		   if (i%2==0)
        	 $$('a').rows[i].style.background = "#FFF";
	       else
	         $$('a').rows[i].style.background = "#CCC";	
		   numero--;	 
	   }
   }


  var $$ = function(id){
	  return document.getElementById(id); 
  }

  function posicionInsertar(){
	var cadena;
	var fecha;
	var pos = 0;
	tabla = $$('a');
	var dia = parseFloat($$('numerofactura').value);
	if (tabla.rows.length==0)
      return 0;	
	   for(var i=0;i<tabla.rows.length;i++){
		 pos = i;  
		 var numero = tabla.rows[i].cells[6].innerHTML;
		   if (parseFloat(numero) < dia)
			   return pos;	  
	   }	   
	  return pos+1; 
  }
   
   function imprimir(pagina){
		window.open(pagina+'?mes='+$$('mes').value+'&anio='+$$('anio').value+'&sucursal='+$$('sucursal').value);
	}
	
   function recuperaNombre(nit, pagina){
	    peticion = ajaxx();   
	    peticion.open('GET', pagina+'?nit='+nit, true); 
	    peticion.onreadystatechange = function() { 	
	       if (peticion.readyState == 4) { 
		      if (peticion.responseText.length == '3') {$$('razonsocial').focus(); return}
		        m = peticion.responseText.split('---');
				$$('razonsocial').value = m[0];
				$$('numerofactura').focus();
	      } 
	   } 
	    peticion.send(null);
     }

  function ajaxx() {
	 return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 
  }
  
  function enviarGeneral(filtro,funcion){
    $$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";  
    var  pedido =  ajaxx();	  
     pedido.open("GET",servidor+"?"+filtro,true);
     pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){
		  $$('overlay').style.visibility = "hidden";
          $$('gif').style.visibility = "hidden";   
		  respuesta = pedido.responseText;
	      if (funcion != null) 
     	    funcion(respuesta);	   
	   }	   
     }
     pedido.send(null);   	
  }
 
 
  function realizarConsulta(){
    $$("mensajeLibro").style.visibility = "hidden";
	if ($$("sucursal").value != "" && $$("mes").value != ""){
	 var filtro = "sucursal="+$$("sucursal").value+"&mes="+$$("mes").value+"&ano="+$$("anio").value+
	 "&transaccion=consultar";
	 enviarGeneral(filtro,resultadoConsulta);
	}
 }

  function resultadoConsulta(resultado){
	  var datos = resultado.split("---");
	  
	  if (datos[0] == "fecha" || datos[0] == "numero"){
		   if (datos[0] == "fecha"){
			$$("mensajeLibro").innerHTML = "Fecha Limite Emision";  
		  }else{
			$$("mensajeLibro").innerHTML = "Facturas Agotadas";			  
		  }		  
       $$("mensajeLibro").style.visibility = "visible";	
	  }else{	  
	    $$("numerofactura").value = datos[0];	    
	  }
	  $$("numeroautorizacion").value = datos[1];
      $$("a").innerHTML = datos[2];
	  sumarTotales();
	  $$("fecha").focus();
  }

  function generarSinMovimiento(){
	 if ($$("sucursal").value != "" && $$("mes").value != ""){ 
	   var filtro = "sucursal="+$$("sucursal").value+"&mes="+$$("mes").value+"&ano="+$$("anio").value+"&transaccion=sinMovimiento"; 
	   enviarGeneral(filtro,resultadoSinMovimiento);
	 }
	 else{
		if ($$('sucursal').value == ''){
		 openMensaje("Advertencia","Debe Seleccionar la Sucursal");
		 return false;
	    }	
	    if ($$('mes').value == ''){
		 openMensaje("Advertencia","Debe Seleccionar el Mes");
		 return false;
	    } 
	 }
  }
  
  function resultadoSinMovimiento(resultado){
	  var dato = resultado.split("---");
	  if (dato[0] == -1){
		  $$("mensajeLibro").innerHTML = "Ya Existe Movimiento";
		  $$("mensajeLibro").style.visibility = "visible";
	  }else{
		  insertarItemMovimiento(dato[1],dato[2]);
		  $$("mensajeLibro").style.visibility = "hidden";
	  }
	  $$("fecha").focus();
  }

  function ocultarMensaje(){
	 $$("mensajeLibro").style.visibility = "hidden"; 
  }
  
  function eventoText(evt){
   var tecla = (document.all) ? evt.keyCode : evt.which;
    if (tecla == 13){
    ejecutarTransaccion();
   }
  }

 var insertarItemMovimiento = function(fecha,idlibro){
	var formato = getFormatoColumna();
	var n =  $$('a').rows.length + 1;
	var datosIngreso =[
	{data:"<img src='../iconos/edit.png' style='cursor:pointer' title='Modificar' onclick='editar(this)'/>", type:"set"},
	{data:"<img src='../iconos/borrar.gif' style='cursor:pointer' title='Eliminar' onclick='eliminarItem(this)'/>", type:"set"},
	{data : n , type:"set"},
	{data : fecha , type:"set" },
	{data : 0, type:"set"},
	{data : 0, type:"set"},
	{data : 0, type:"set"},
	{data : 0, type:"set"},
	{data : 0, type:"set"},
	{data : 0, type:"set"},
	{data : 0, type:"set"},
	{data : 0, type:"set"},
	{data : 0, type:"set"},
	{data : 0, type:"set"},
	{data :"LV" , type:"set"},
	{data : 0, type:"set"},
	{data : idlibro, type:"set"},
	{data : 0, type:"set"},
	{data : 0, type:"set"}
	];	
	cargarDatos(formato,datosIngreso,'a','sizeLetra',n-1);
	$$("fecha").value = "";
	asc();
  }


  function eliminarItem(puntero){
	var filtro = "";
	var td = puntero.parentNode;
    var tr = td.parentNode;
    var table = tr.parentNode;
    table.removeChild(tr);
	asc();
	cargarTotales(-desconvertirFormatoNumber(tr.cells[9].innerHTML),-desconvertirFormatoNumber(tr.cells[10].innerHTML)
	,-desconvertirFormatoNumber(tr.cells[11].innerHTML),-desconvertirFormatoNumber(tr.cells[12].innerHTML),
	-desconvertirFormatoNumber(tr.cells[13].innerHTML));	
	filtro = "transaccion=eliminar&idlibro="+tr.cells[16].innerHTML;
	enviarGeneral(filtro,null);
  }
 
 var datosCompletos = function(){
	if ($$('sucursal').value == ''){
		openMensaje("Advertencia","Debe Seleccionar la Sucursal");
		return false;
	}	
	if ($$('mes').value == ''){
		openMensaje("Advertencia","Debe Seleccionar el Periodo");
		return false;
	}
	if ($$('fecha').value == ''){
		openMensaje("Advertencia","Debe Ingresar el Dia");
		return false;
	}
	if (parseFloat($$('fecha').value) > 31){
		openMensaje("Advertencia","El Dia, esta Fuera de rango");
		return false;
	}
	if ($$('nit').value == ''){
		openMensaje("Advertencia","Debe Ingresar el Nit");
		return false;
	}
	if ($$('razonsocial').value == ''){
		openMensaje("Advertencia","Debe ingresar el Nombre o Razon Social");
		return false;
	}
	
	return true; 
 }
 
  var cambiarFoco = function(evt,texto,destino){
   var tecla = (document.all) ? evt.keyCode : evt.which;
   var n = texto.length;
    if (tecla == 8){
     return true;  
	}    
    if (n == 1){
     $$(destino).focus();
     return true;
    }
    if (n >= 2){
      $$(destino).focus();
      return false;
    }
    return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
  }   


 var atajoAnulado = function(texto){
	  if (texto == "00")	
       cambioEstado('A');
  }   

  var openMensaje = function(titulo,contenido){
	$$("modal_tituloCabecera").innerHTML = titulo;
	$$("modal_contenido").innerHTML = contenido;
	$$("modal_mensajes").style.visibility = "visible";
    $$("overlay").style.visibility = "visible";  
  }
  
  var closeMensaje = function(){
	$$("modal_mensajes").style.visibility = "hidden";
    $$("overlay").style.visibility = "hidden";    
  }
  