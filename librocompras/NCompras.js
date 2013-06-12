
  var servidor = "Dcompras.php";
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
	tipolibro = $$('a').rows[index].cells[17].innerHTML;
	$$('id').value =  $$('a').rows[index].cells[16].innerHTML;
	seleccionarCombo('codigocuenta', cuenta);
	seleccionarCombo('tipo', tipolibro);
	$$("a").deleteRow(index);
	sumarTotales();
  }


  function limpiarIngresoDatos(){
     var datos = ['fecha','nit','razonsocial','numerofactura','numeroautorizacion','codigocontrol','importetotal',
	 'ice','excento','neto','iva','codigocuenta'];
	 for (var j=0;j<datos.length;j++){		 
		$$(datos[j]).value = ""; 
	 }
     $$("fila").value = "-1";
	 seleccionarCombo('tipo', "1");
	 seleccionarCombo('codigocuenta', '0');
  }
   
  var ejecutarTransaccion = function(){
   	adaptarDatos();
	  if (datosCompletos()){
	     if($$("fila").value == "-1"){
		   nuevo("insertar");
	     }else{
   		   nuevo("modificar");
	     }		 
      }
  }
  
  function nuevo(tipo){	  
     filtro = "transaccion="+tipo+"&sucursal="+$$("sucursal").value+"&mes="+$$("mes").value+"&anio="+$$("anio").value
      +"&dia="+$$("fecha").value+"&nit="+$$("nit").value+"&razonsocial="+$$("razonsocial").value+"&numerofactura="+$$("numerofactura").value+
	  "&numeroautorizacion="+$$("numeroautorizacion").value+"&importetotal="+$$("importetotal").value+
	  "&ice="+$$("ice").value+"&excento="+$$("excento").value+"&neto="+$$("neto").value+"&iva="+$$("iva").value+
	  "&codigocontrol="+$$("codigocontrol").value+"&idtransaccion="+$$('id').value+"&tipolibro="+$$("tipo").value
	  +"&cuenta="+$$("codigocuenta").value;  
	  //alert(filtro);
	  enviarGeneral(filtro,resultadoTransaccion);	 
   }
 
  var resultadoTransaccion = function(resultado){
	   $$("mensajeLibro").style.visibility = "hidden";
	   $$('fila').value = '-1';
	   if (resultado != "-1"){	   
    	  $$('id').value = resultado;
	      insertarNewItem('a');
	      $$('cubrir').style.visibility = 'hidden';
	      seleccionarCombo('tipo', "1");
   	      seleccionarCombo('codigocuenta', '0');
	      $$('fecha').focus();
	   }else{
		  $$("mensajeLibro").innerHTML = "Sin Movimiento";
		  $$("mensajeLibro").style.visibility = "visible";  
	   }
  }
   
  var insertarNewItem = function(tabladestino){
	var formato = getFormatoColumna();
	var n =  $$(tabladestino).rows.length + 1;
	var tipolibro = $$("tipo").value;
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
	{data :"LC" , type:"set"},
	{id :"codigocuenta", type:"get"},
	{id :"id" , type:"get"},
	{data : tipolibro , type:"set"},
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
	   filas = $$('a').rows.length;
	   for (i=0;i<filas;i++){
	        $$('a').rows[i].cells[2].innerHTML = i+1;
		   if (i%2==0)
        	 $$('a').rows[i].style.background = "#FFF";
	       else
	         $$('a').rows[i].style.background = "#CCC";	
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
	var dia = parseFloat($$('fecha').value);
	if (tabla.rows.length==0)
      return 0;	
	   for(var i=0;i<tabla.rows.length;i++){
		 pos = i;  
		 var cadena = tabla.rows[i].cells[3].innerHTML;
		 var fecha = cadena.split("/");	
		   if (parseFloat(fecha[0])>dia)
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
		        m=peticion.responseText.split('---');
				$$('razonsocial').value = m[0];
				$$('numeroautorizacion').value = m[1];
				$$('numerofactura').focus();
	      } 
	   } 
	    peticion.send(null);
     }

  function ajaxx() {
	 return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 
  }
  
  function enviarGeneral(filtro,funcion){
    var  pedido =  ajaxx();	  
     pedido.open("GET",servidor+"?"+filtro,true);
     pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){
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
	  $$("a").innerHTML = datos[1];
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
	{data :"LC" , type:"set"},
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
		openMensaje("Advertencia","Debe Seleccionar el Mes");
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


 
  