// JavaScript Document
 var datosFactura ={dia :"", nit: "" ,razonsocial:"",numfactura:"", numeroautorizacion:"",importetotal:"",ice:"",excento:"",neto:"",iva:"",codigocontrol:"" };
 var total_bolivianos = 0;
 var total_Dolares = 0;
 var idUTransaccion = 0;
 var transaccion = "insertar";
 var servidor = "ingresoalmacen/DIngreso.php";
 var irDireccion = "listar_ingresoproducto.php#t3";
 var dirLocal = "nuevo_ingresoalmacen.php";
 var rItem = null;

 var $$ = function(id){
  return document.getElementById(id);	 
 }

 var ajax = function(){
  return (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); 	
 }

 //teclas de atajo
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
   switch(tecla){
	case 115://F4	  
      if ($$("cancelar") != null)
	   salir();
	break;
	case 113://F2
	if($$('overlay').style.visibility != "visible")
	   enviarMaestro();
	break;	 
   }
 }
 
 
  var tipoBusqueda = function(e,id){
	   var sql;
	   tipocliente = $$('receptor').value;
	   if (tipocliente!="otros"){ 
	    idconsulta = "id"+tipocliente;   
		switch(tipocliente){
		 case 'trabajador':
		   sql = "select t.idtrabajador,left(concat_WS(' ',t.nombre,t.apellido),20) as 'nombre' from trabajador t where estado=1 and  ";
		 break;	
		 case 'cliente':
		   sql = "select idcliente,left(nombre,20) as 'nombre' from cliente where estado=1 and  ";
		 break;
		 case 'proveedor':
		   sql = "select idproveedor,left(nombre,20) as 'nombre' from proveedor where estado=1 and ";
		 break;
		}		
	    eventoTeclas(e,id,'cliente',tipocliente,'nombre',idconsulta,'eventoResultadoEgreso','autocompletar/consultor.php',sql,'','autoL2');
	   }
   }
   
   var eventoResultadoEgreso = function(resultado,codigo){
	 $$("texto").value= resultado;
	 $$("idpersonarecibida").value = codigo;	   
 }
   
    var autocompletar = function(e,id){
  if ($$("almacen").value != "" ){	
    var consulta = "select idproducto,left(nombre,25)as 'nombre' from producto where estado=1 and "; 
    eventoTeclas(e,id,'resultados','producto','nombre','idproducto','eventoResultado','autocompletar/consultor.php',consulta,'','autoL1');	
  }else{
	 openMensaje("Advertencia","Debe seleccionar el almacen."); 
	 $$(id).value = "";
	 $$(id).focus();
  }
 }
   
 var cambiarDependencias = function(){
	 $$("texto").value = "";
	 $$("idpersonarecibida").value = "0";
 }
  
 function consultar(filtro,funcion){
 var  pedido = ajax();	
 pedido.open("GET",servidor+"?"+filtro,true);
   pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){     	
          var resultado = pedido.responseText.split("---"); 
    	  funcion(resultado);   
	   }	   
   }
   pedido.send(null);
 }


var $$U = function(id){
  return encodeURIComponent($$(id).value);	
}



 var enviarMaestro = function(){
  if (esvalido()){	
    $$('overlay').style.visibility = "visible";
    $$('gif').style.visibility = "visible";
    factura = JSON.stringify(datosFactura);	
    var costo = ($$("costooperativo").value == "") ? 0 : $$("costooperativo").value;
    montototal = desconvertirFormatoNumber($$("subtotalBS").value); 
	montototal = parseFloat(montototal) + parseFloat(costo); 
    filtro ="fecha="+$$("fecha").value+"&almacen="+$$('almacen').value+
    "&facproveedor="+$$('facproveedor').value+"&moneda="+$$('moneda').value+"&glosa="+$$U("glosa")
    +"&monto="+montototal+"&transaccion="+transaccion+"&idregistro="+$$("idRegistro").value
    +"&efectivo="+$$('efectivo').value+"&credito="+$$('credito').value+"&fvencimiento="+$$('fvencimiento').value+
	"&cuentacontable="+$$('cuentacontable').value+"&caja="+$$("caja").value+"&tc="+$$("tipoCambioBs").value
	+"&tipoingreso="+$$("tipoingreso").value+"&nombreasignado="+$$U("texto")+"&factura="+factura+
	"&costooperativo="+$$("costooperativo").value+"&receptor="+$$U("receptor")
	+"&idpersonarecibida="+$$('idpersonarecibida').value+"&devolucion="+$$("tipocheck").value; 		
    enviarDetalle(filtro);
  }
 }



 function enviar(datos){
  var  pedido = ajax();	  
  pedido.open("GET",servidor+"?"+datos,true);
  pedido.onreadystatechange = function(){
	   if (pedido.readyState == 4){
		  datos = pedido.responseText.split("---"); 
		  if (datos[1] == "1"){
		   idUTransaccion = datos[0];		   
		    $$('overlay').style.visibility = "visible";
            $$('modal_vendido').style.visibility = "visible";  
			$$('gif').style.visibility = "hidden";
		  }else{
			cerrarPagina();  
		  }		  
	   }	   
   }
   pedido.send(null);   	
 }

 function accionPostRegistro(){
	window.open('ingresoalmacen/imprimir_ingresoProductos.php?idingreso='
	+idUTransaccion+'&logo='+$$("logo").checked,'target:_blank');	
	cerrarPagina();
 }

 function accion(){
	 $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
	 $$("msjsubnumero").style.visibility = "hidden";
	 $$("dato").value = "";
	 $$("dato").focus();	 
 }

function esvalido(){
  if (!validarFecha($$("fecha").value)){
	openMensaje("Advertencia",'Ingrese una fecha valida.');
    return false;  
  }  
  if ($$('almacen').value == ""){
    openMensaje("Advertencia",'Debe seleccionar un almacén.');
    return false;
  }
  if ($$('texto').value == ""){
   openMensaje("Advertencia",'Debe ingresar el nombre de la persona asignada.');
   return false;
  }
  if ($$("tipocheck").checked && $$("tipoingreso").value == "Factura") {
	 openMensaje("Advertencia",'Si habilita cuenta contable no puede trabajar con factura.');
   return false;  
  }
  if ($$('detalleSolicitud').rows.length == 0){
   openMensaje("Advertencia",'Debe ingresar detalle del ingreso.');
   return false;
  } 
  	 	
  if (parseFloat($$("efectivo").value) >0 && $$("caja").value == ""){
   openMensaje("Advertencia",'Debe seleccionar una Caja/Banco.');  
   return false;
  }
  
  if (parseFloat($$("efectivo").value) <= 0 && parseFloat($$("credito").value) <= 0 && $$("cuentacontable").value == ""){
   openMensaje("Advertencia",'Debe seleccionar una cuenta contable');  
   return false; 
  }
  
  if (parseFloat($$("credito").value) > 0 && !validarFecha($$("fvencimiento").value)){
	openMensaje("Advertencia",'Ingrese una fecha de crédito valida.');
    return false;  
  } 
  
   var montoactual = desconvertirFormatoNumber($$("subtotalBS").value);  
  
  return true;
 }

 var eventoIngresoCantidad = function(evento){
	var tecla = (document.all) ? evento.keyCode : evento.which;		
	if (tecla == 13)
	agrega_celda();	
 }

 var enviarDetalle = function(filtro) {		
     nfilas = $$('detalleSolicitud').rows.length;    	
     json = new Array();
     for(i=0;i<nfilas;i++) {
	   vector = new Array();
	   vector[0]=$$('detalleSolicitud').rows[i].cells[1].innerHTML;		
	   vector[1]=$$('detalleSolicitud').rows[i].cells[3].innerHTML;
	   vector[2]=$$('detalleSolicitud').rows[i].cells[4].innerHTML;								
	   vector[3]=$$('detalleSolicitud').rows[i].cells[5].innerHTML;	
	   vector[4]=$$('detalleSolicitud').rows[i].cells[6].innerHTML;	
	   vector[5]=$$('detalleSolicitud').rows[i].cells[7].innerHTML;	
	   vector[6]=$$('detalleSolicitud').rows[i].cells[8].innerHTML;	
	   vector[7]=$$('detalleSolicitud').rows[i].cells[9].innerHTML;	
	   json[i] = vector;	 		
     }
     dato = JSON.stringify(json);	 
     filtro = filtro + '&detalle=' + dato; 	 	
     enviar(filtro);
 }

 var eventoResultado = function(resultado,codigo){
	 var filtro ="transaccion=consulta&codigo="+codigo+"&idalmacen="+$$("almacen").value; 	     
     consultar(filtro,cargarCantidad); 
     $$("dato").value =resultado;
	 $$("codidproducto").value = codigo; 
	 $$("overlay").style.visibility = "visible";
	 $$('gif').style.visibility = "visible"; 
     $$("msjsubnumero").style.visibility = "hidden";
	 $$("cant").value="";       		
 }
 
 
 var cargarCantidad = function(resultado){
	 unidadM = resultado[0];
	 unidadA = resultado[1];
	 conversiones = resultado[2];
	 precio = resultado[3];
	 cantunidadM = resultado[4];
	 cantunidadA = resultado[5];
	 var cantProducto;
	 var total;
	 unidadA = (unidadA == '') ? 0 : unidadA;
	 cantunidadM = (cantunidadM == '') ? 0 : cantunidadM;
	 cantunidadA = (cantunidadA == '') ? 0 : cantunidadA;
	 $$("cantUM").value ="0";
	 $$("plote").value ="";
	 $$("ptotal").value ="0.00";
	 $$("punidadmedida").value = unidadM;
	 $$("punidadalternativa").value = unidadA;
	 $$("pconversiones").value = conversiones; 
	 $$("ppreciounitario").value = parseFloat(precio).toFixed(4);
	 $$("ptextounidad").innerHTML = unidadM;
 	 $$("ptextounidadalternativa").innerHTML = unidadA;
	 var ppreciound = precio / conversiones;
	 $$("ppreciounitarioalternativa").value = ppreciound.toFixed(4); 
	 cantProducto = parseFloat(cantunidadA / conversiones);
	 total = parseFloat(cantunidadM) + cantProducto ;
	 cantUM = parseInt(total);
	 total = total - parseFloat(cantUM);
	 total = total * conversiones;
	 $$("Pdisponible").value = cantUM;
	 $$("Pdisponible2").value = total.toFixed(4);
	 $$('gif').style.visibility = "hidden";
	 $$("modal").style.visibility = "visible";
	 document.form1.plote.focus();
 }
  
  function calcularUnidadMedida(cantidad,tipoUnidad){
	  if (tipoUnidad == "precioUA"){
		  $$("ppreciounitario").value = parseFloat($$("pconversiones").value * cantidad).toFixed(4);
		  cantidad = $$("cant").value;	
	  }
	  
	  if (tipoUnidad == "precioU"){
		  $$("ppreciounitario").value = cantidad;
		  var ppreciound = cantidad / $$("pconversiones").value;
		  cantidad = $$("cant").value;		   
		   $$("ppreciounitarioalternativa").value = ppreciound.toFixed(4); 
	  }
	  
	  if (tipoUnidad == "UM" || tipoUnidad == "precioU" || tipoUnidad == "precioUA"){
		 var pprod = ($$("ppreciounitario").value == "") ? 0 : $$("ppreciounitario").value; 
		 pprod = pprod * cantidad;
		 $$("ptotal").value =  pprod.toFixed(4);
		 cantUm = parseFloat(cantidad * $$("pconversiones").value);
		 $$("cantUM").value = cantUm.toFixed(4);
	  }
	  if (tipoUnidad == "UA"){
		var pprod = ($$("ppreciounitario").value == "") ? 0 : $$("ppreciounitario").value;  
		pprod = pprod * (cantidad / $$("pconversiones").value);
		$$("ptotal").value =  pprod.toFixed(4);  
		 cantUm = parseFloat(cantidad / $$("pconversiones").value);
		 $$("cant").value = cantUm.toFixed(4);
	  }
  }
  
 
 var insertarFila = function(datos,tabla){
   var Data = new Array();	 
   var x = $$(tabla).insertRow($$(tabla).rows.length);
   x.bgColor= "#F6F6F6" ;
     for (var i = 0;i < datos.length;i++){
        var y = x.insertCell(i);
		Data = datos[i];
		y.align = Data[1];			
        y.innerHTML = Data[0];
		if (Data.length > 2)
		y.style.display = Data[2];
     }
 }

 var eliminarFila = function(resultado){
	 if (resultado[0]<=0){
	   var td = rItem.parentNode;
	   var tr = td.parentNode;
	   $$("credito").value = 0;
	   setTotal(-parseFloat(desconvertirFormatoNumber(tr.cells[8].innerHTML)),$$("moneda").value);
	   var table = tr.parentNode;
	   table.removeChild(tr);
	}else{
	  openMensaje("Advertencia",'Este Ítem no puede ser eliminado porque ya se realizaron movimientos.');	
	}	
 }
 
 
 var getReferencia = function(t){
	 rItem = t;
     var td = rItem.parentNode;
     var tr = td.parentNode;
	 if (tr.cells[9].innerHTML != "-2"){
	  var filtro = "transaccion=referencia&iddetalle="+tr.cells[9].innerHTML;
	  consultar(filtro,eliminarFila);
	 }else{
	  eliminarFila([0]); 
	 }
 }
 
 
 
 function validarSubVentana(){
	if ($$("plote").value == ""){
  	  $$("msjsubnumero").innerHTML  = "Debe ingresar el Numero de lote";
	  $$("msjsubnumero").style.visibility = "visible";
	  return false;		
	}	

	if (!isvalidoNumero("cant") || !isvalidoNumero("cantUM")){
	  $$("msjsubnumero").style.visibility = "visible";
	  $$("msjsubnumero").innerHTML = "La cantidad ingresada es Incorrecta"; 
	  return false;		
	}	
	if (!isvalidoNumero("ppreciounitario") || !isvalidoNumero("ppreciounitarioalternativa")){
	  $$("msjsubnumero").style.visibility = "visible";
  	  $$("msjsubnumero").innerHTML = "El precio ingresado es Incorrecto"; 
	  return false;		
	}	
	if(parseFloat($$("ptotal").value) <= 0){
	  $$("msjsubnumero").style.visibility = "visible";
  	  $$("msjsubnumero").innerHTML = "Debe ingresar una cantidad mayor a cero"; 
	  return false;	
	}
	$$("msjsubnumero").style.visibility = "hidden";
	return true;
 }
 
 
 
 var isvalidoNumero = function(id){	
   return (isNaN($$(id).value)) ? false : true; 
  }
 
 function ingresoProducto(){	
	 if (validarSubVentana())
	   agrega_celda();
 }
 
 var agrega_celda = function(){	
     var pCantidad = 0;
     var pprecio = 0;
	 var pUndMedida = "";
	 var selector = obtenerSeleccionRadio("form1","pselectorU");
	 	 
	 if (selector == "UP"){
		pCantidad = $$("cant").value;
		pUndMedida = $$("punidadmedida").value;
		pprecio = parseFloat($$("ppreciounitario").value);
	 }else{
		pCantidad = $$("cantUM").value;
		pUndMedida = $$("punidadalternativa").value; 
		pprecio = parseFloat($$("ppreciounitarioalternativa").value);
	 }
	 	 
	 	 
	 var datos = new Array();
	 precio = pprecio;
     datos[0] = new Array("<img src='css/images/borrar.gif' title='Eliminar' alt='borrar' onclick='getReferencia(this)' />","center");
	 datos[1] = new Array($$("codidproducto").value, "center");
	 datos[2] = new Array($$("dato").value, "left");
	 datos[3] = new Array($$("plote").value, "left", "none");
 	 datos[4] = new Array($$("pfvencimiento").value, "center", "none");
	 datos[5] = new Array(parseFloat(pCantidad).toFixed(4), "center");
	 datos[6] = new Array(pUndMedida,"center");
	 datos[7] = new Array(convertirFormatoNumber(precio.toFixed(4)), "center");
	 total = parseFloat($$("ptotal").value);
     datos[8] = new Array(convertirFormatoNumber(total.toFixed(4)), "center");
	 datos[9] = [-2, "left", "none"];
	 insertarFila(datos,'detalleSolicitud');
	 setTotal(total,$$("moneda").value);
     $$("overlay").style.visibility = "hidden";
	 $$("modal").style.visibility = "hidden"; 
     $$("dato").value=""; 
     document.form1.dato.focus();   
 }
 
 
 var setDevolucion = function(b) {
	if (b) {
		$$("tipocheck").value = 1;
		$$("credito").value = "0.00";
		$$("efectivo").value = "0.00";	
		$$("efectivo").disabled = "disabled";	
	    document.form1.cuentacontable.disabled = false;
		document.form1.caja.disabled = true;		
	} else {
		$$("tipocheck").value = 0;
		calcularCredito2();
		document.form1.cuentacontable.disabled = true;
		document.form1.caja.disabled = false;
		$$("efectivo").disabled = "";
	}
	seleccionarCombo("caja", "");
    seleccionarCombo("cuentacontable", "");
 }
 
 function setTotal(total,moneda){	 
	 if (moneda == "Bolivianos"){
	   total_bolivianos = total_bolivianos + total;
	 }
	 
	 if (moneda == "Dolares"){
		total = total * ($$("tipoCambioBs").value);
		total_bolivianos = total_bolivianos + total;
	 }
	 
	 var costo = ($$("costooperativo").value == "")? 0 : $$("costooperativo").value;
     total_Dolares = parseFloat(total_bolivianos) + parseFloat(costo);
	 $$("subtotalBS").value = convertirFormatoNumber(total_bolivianos.toFixed(2));
	 $$("subtotalDL").value = convertirFormatoNumber(total_Dolares.toFixed(2));
	 if ($$("tipocheck").checked == false)
	 calcularCredito2();
 }
 
 
 var cambiarTotal = function(cantidad){	 
     cantidad = (cantidad == "") ? 0 : cantidad; 
	 total = parseFloat(cantidad) + parseFloat(total_bolivianos);
     $$("subtotalDL").value = convertirFormatoNumber(total.toFixed(2));
	 calcularCredito2();
 }
 
  
 function limpiarDetalle(){
	  $$("detalleSolicitud").innerHTML = "";  
	  setTotal(-total_bolivianos,"Bolivianos");  
 }
 
 
 function salir(){
   if ($$('detalleSolicitud').rows.length>0){
	if (confirm("Cuenta con detalles de Ingreso desea Salir ?"))
	 location.href = irDireccion;  
   }
   else{
	 location.href = irDireccion;  
   }
 }

 function cerrarPagina(){
	window.location = "nuevo_ingresoalmacen.php";	 
 }
 
 function calcularCredito(cantidad){
	 var costo = ($$("costooperativo").value == "") ? 0 : parseFloat($$("costooperativo").value);
	 $$("credito").value = parseFloat(total_bolivianos + costo - cantidad).toFixed(2);
 }
 
  function calcularCredito2(){
	 var costo = ($$("costooperativo").value == "") ? 0 : parseFloat($$("costooperativo").value);
	 var credito = ($$("credito").value == "") ? 0 : parseFloat($$("credito").value);
	 if ($$("tipocheck").value == 1){
	  $$("tipocheck").checked = true;	 
	 }else{
	 $$("efectivo").value = parseFloat(total_bolivianos + costo - credito).toFixed(2);
	 }
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
  
  
  var cambiarFoco = function(evt){
   var tecla = (document.all) ? evt.keyCode : evt.which;
   var n = $$("diaF").value.length;
    if (tecla == 8){
     return true;  
	}    
    if (n == 1){
     $$("nitF").focus();
     return true;
    }
    if (n >= 2){
      $$("nitF").focus();
      return false;
    }
    return ((tecla>47 && tecla<58)|| tecla == 8 || tecla == 0 || tecla == 46);
 }

 function recuperaNombre(nit, pagina){
   peticion = ajax();   
   peticion.open('GET', pagina+'?nit='+nit+'&transaccion=nit', true); 
   peticion.onreadystatechange = function() { 	
      if (peticion.readyState == 4) { 
        if (peticion.responseText.length == '3') {$$('razonsocialF').focus(); return}
	      m = peticion.responseText.split('---');
	      $$('razonsocialF').value = m[0];
		  $$('numeroautorizacionF').value = m[1];
		  $$('numerofactura').focus();
	    } 
	  } 
  peticion.send(null);
 }


 function d(numero){
  resultado = Math.round(numero*100) / 100; 
  return resultado;
 }

 function calculaNeto(){
   $$('netoF').value = $$('importetotalF').value - $$('iceF').value - $$('excentoF').value; 
   $$('ivaF').focus();
 }

 function calculaIva(){
   $$('ivaF').value = d($$('importetotalF').value*0.13);
   $$('codigocontrolF').focus();
 }
  
 var openSubVentana = function(){	
    cargarFactura();
	$$("subventana").style.visibility = "visible";
	$$("overlays").style.visibility = "visible";
	$$('diaF').focus();
 }


 var cerrarSubVentana = function(){
   $$("subventana").style.visibility = "hidden";
   $$("overlays").style.visibility = "hidden";
 }


 var datosCompletos = function(){
	 $$("numeroautorizacionF").value = ($$("numeroautorizacionF").value == "") ? "0" : $$("numeroautorizacionF").value;
	 $$("importetotalF").value = ($$("importetotalF").value == "") ? "0" : $$("importetotalF").value;
 	 $$("iceF").value = ($$("iceF").value == "") ? "0" : $$("iceF").value;
 	 $$("excentoF").value = ($$("excentoF").value == "") ? "0" : $$("excentoF").value;
	 $$("netoF").value = ($$("netoF").value == "") ? "0" : $$("netoF").value;
	 $$("ivaF").value = ($$("ivaF").value == "") ? "0" : $$("ivaF").value;
	 $$("codigocontrolF").value = ($$("codigocontrolF").value == "") ? "0" : $$("codigocontrolF").value;
	 
	 if ($$('diaF').value == ''){
		openMensaje("Advertencia","Debe Ingresar el Dia");
		return false;
	 }
	 if ($$('nitF').value == ''){
		openMensaje("Advertencia","Debe Ingresar el Nit");
		return false;
	}
	if ($$('razonsocialF').value == ''){
		openMensaje("Advertencia","Debe ingresar el Nombre o Razon Social");
		return false;
	}	 
	if ($$('numerofactura').value == ''){
		openMensaje("Advertencia","Debe Ingresar el numero de factura");
		return false;
	}	
	if (parseFloat($$('diaF').value) > 31){
		openMensaje("Advertencia","El Dia, esta Fuera de rango");
		return false;
	}	
	
	return true; 
 }


 var insertarNewFactura = function(){
   if (datosCompletos()){       
    datosFactura.dia = $$("diaF").value;
    datosFactura.nit = $$("nitF").value;
    datosFactura.razonsocial = $$("razonsocialF").value;
    datosFactura.numfactura = $$("numerofactura").value;
    datosFactura.numeroautorizacion = $$("numeroautorizacionF").value;
    datosFactura.importetotal = $$("importetotalF").value;
    datosFactura.ice = $$("iceF").value;
    datosFactura.excento = $$("excentoF").value;
    datosFactura.neto = $$("netoF").value;
    datosFactura.iva = $$("ivaF").value;
    datosFactura.codigocontrol = $$("codigocontrolF").value;  	
	cerrarSubVentana();
   }
 }

 var cargarFactura = function(){
	$$("diaF").value = datosFactura.dia;
    $$("nitF").value = datosFactura.nit;
    $$("razonsocialF").value = datosFactura.razonsocial;
    $$("numerofactura").value = datosFactura.numfactura;
    $$("numeroautorizacionF").value = datosFactura.numeroautorizacion;
    $$("importetotalF").value = datosFactura.importetotal;
    $$("iceF").value = datosFactura.ice;
    $$("excentoF").value = datosFactura.excento;
    $$("netoF").value = datosFactura.neto;
    $$("ivaF").value = datosFactura.iva;
    $$("codigocontrolF").value = datosFactura.codigocontrol;  
 }
  

  
  
  
  