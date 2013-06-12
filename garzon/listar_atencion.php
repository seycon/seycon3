<?php
    include("../conexion.php");
    $db = new MySQL();  
    $transaccion = "insertar"; 
	if (!isset($_SESSION['softLogeoadmin'])){
        header("Location: ../index.php");	
    }
	
    if (isset($_GET['idatencion']) && $_GET['idatencion'] != "") {
        $sql = "update atencion set estado='atencion' where idatencion=$_GET[idatencion]";	 
        $db->consulta($sql);
        header("Location: listar_atencion.php");
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>

<script>
    var goModificar = function(codigo){
	 location.href = "nuevo_usuario.php?nro="+codigo;	
	}
	
	var goEliminar = function(codigo){
	  $$("idatencion").value = codigo;		 		  
      $$("overlay").style.visibility = "visible";
	  $$("gif").style.visibility = "visible";
	  var filtro = "tipo=verificarEntrega&idatencion="+ codigo;
	  enviar("Datencion.php",filtro,resultadoEliminacion);
	}
	
	var resultadoEliminacion = function(resultado){
	  $$("gif").style.visibility = "hidden";	
	  $$("modal_tituloCabecera").innerHTML = 'Advertencia';
	  
	  if (parseFloat(resultado) > 0) {
		$$("modal_contenido").innerHTML = 'Antes de modificar la Venta, debe eliminar la Entrega de dinero.';  
		$$("boton_eliminar").style.visibility = "hidden";
		$$("modalBoton2").style.visibility = "hidden";
	  } else {		
	    $$("modalBoton2").style.visibility = "visible";
	    $$("boton_eliminar").style.visibility = "visible";
	    $$("modal_contenido").innerHTML = '¿Desea modificar esta venta realizada?';  
	  }

	  $$("modal_mensajes").style.visibility = "visible";
	}
	
	var $$ = function(id){
	  return document.getElementById(id);
	}
	
	var closeMensaje2 = function(){
		$$("idatencion").value = "";
		$$("modalBoton2").style.visibility = "hidden";
	    $$("modal_mensajes").style.visibility = "hidden";
        $$("overlay").style.visibility = "hidden";    
		$$("boton_eliminar").style.visibility = "hidden";
    }
	
	var eliminarTransaccion = function(){
	    $$("formulario").submit();
	}
	
    var setImpresion = function(idatencion){
		$$("overlay").style.visibility = "visible";
	    $$("gif").style.visibility = "visible";
        var filtro = "transaccion=reporte2&idatencion=" + idatencion; 
        enviar("Dreporte.php", filtro, setReporteListado);   
    }
	
	
	var setReporteListado = function(resultado) {
      var datos = eval(resultado);
      var cadena = setCabeceraR2(datos[0][0], datos[0][1], datos[0][3], datos[0][4], datos[0][8]);

	  var total = 0;
	   for (var i = 1; i < datos.length; i++) {
		  total = total + datos[i][3]; 
		  cadena = cadena + setContenido( datos[i][0], datos[i][1], datos[i][2], datos[i][3]);
	 }
	  cadena = cadena + setTotalR2(total, datos[0][5], datos[0][6], datos[0][7], datos[0][4]);
	  cadena = cadena + setFirmaR2(datos[0][2], datos[0][8]);

	  printListado(cadena);	  
	  
   }
   
   var saltarListado = function() {	
	 location.href = "listar_atencion.php";		   
   }
   
   function printListado(datos) {
         var applet = document.jzebra;
		 
		  if (applet != null) {
			   applet.findPrinter();  
	   
			   applet.append (datos);			  
			   for (var i=0; i<=10; i++){
				applet.append ("  \n ");   
			   }
				
			   applet.print();
		  } else {
		      alert("Error: Debe Instalar el plugin de Java.");  
		  }
		$$("overlay").style.visibility = "hidden";
	    $$("gif").style.visibility = "hidden";
    } 
	
</script>
<script src="Npedido.js"></script>
<script type="text/javascript" src="js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="js/html2canvas.js"></script>
<script type="text/javascript" src="js/jquery.plugin.html2canvas.js"></script>
</head>

<body>

<applet name="jzebra" code="jzebra.PrintApplet.class" archive="./jzebra.jar" width="0px" height="0px">
      <param name="printer" value="zebra">
   </applet><br />

<div id="overlay" class="overlays"></div>
<div id="gif" class="gifLoader"></div>
<div id="modal_mensajes" class="modal_mensajes">
  <div class="modal_cabecera">
     <div id="modal_tituloCabecera" class="modal_tituloCabecera">Advertencia</div>
     <div class="modal_cerrar"><img src="../iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje2()"></div>
  </div>
  <div class="modal_icono_modal"><img src="../iconos/alerta.png" width="24" height="24"></div>
  <div id="modal_contenido" class="modal_contenido">No Existen Pagos Realizados</div>
  <div class="modal_boton2" id="modalBoton2"><input type="button" id="boton_eliminar" value="Aceptar" class="boton_modal" onclick="eliminarTransaccion()"/></div>
  <div class="modal_boton1"><input type="button" value="Cancelar" class="boton_modal" onclick="closeMensaje()"/></div>
</div>

 <div class="contendedor">
 
   <div class="tela_izq"></div>
   <div class="tela_cierreizq"></div>
   <div class="tela_der"></div>
   <div class="tela_cierreder"></div> 
   <div class="derechosReservados">Copyright © Consultora Guez – Diseñado y Desarrollado
   </div>
   <div class="header"><div class="gradient7"><h1><span></span>Scav</h1></div>  </div>
   <div class="subTitulo">Software Contable de Administración y Ventas.</div>
   
   <table width="90%" border="0" align="center">
  <tr>
    <td width="21%">&nbsp;</td>
    <td width="79%"></td>
  </tr>
  <tr>
    <td width="21%">
    <div class="menu1">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="36%">&nbsp;</td>
    <td width="64%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><div class="tituloMenu"><< BUFFALO >></div></td>
    </tr>
  <tr>
    <td height="336" colspan="2">
    <div class="contenedorMenu">
     <div id="opcion1" onclick="location.href='inicio_restaurante.php'"><div class="sombraButon"></div> 
     <div id="textoOpcion">Inicio</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='listar_personal.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Personal Apoyo</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='listar_usuario.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Usuario</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_configuracion.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Configuración</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_entrega.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Entregar dinero</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_planilla.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Planilla Apoyo</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_reporte.php'"><div class="sombraButon"></div>
     <div id="textoOpcion">Reportes</div></div>
    </div>
    </td>
    </tr>
</table>

    
    </div>
    </td>
    <td width="79%">
      <div class="menu2">
       <div class="tituloTransaccion"><div class="textoTituloTransaccion">Listar Atención</div></div>
          <div class="separador"></div>

            
       <form id="formulario" name="formulario" method="get" action="listar_atencion.php">
       <?php
	      
		$currentPage = $_SERVER["PHP_SELF"];
		$hasta = 15;
		$numeroPagina = 0;
		if (isset($_GET['numPage'])) {
			$numeroPagina = $_GET['numPage'];
		}
		$desde = $numeroPagina * $hasta;
	    $condicion = "";
       	if ($_GET['filtro'] != "") {
			$condicion = "  and $_GET[campo] like '$_GET[filtro]%' ";
		}

	    $sql = "(select a.idatencion,date(a.fecha)as 'fecha'
		,left(s.nombrecomercial,20) as 'sucursal',left(concat(t.nombre,' ',t.apellido) ,25)as 'usuario',u.tipo,
		a.efectivo as 'total',a.descuento,a.credito,a.cortesia  
		 from atencion a,sucursal s,usuariorestaurante u,trabajador t    
		where a.estado='cobrado' and s.idsucursal=a.idsucursal and u.tipo='fijo' and 
		a.idusuariorestaurante=u.idusuario and t.idtrabajador=u.idtrabajador $condicion) union all (
		
		select a.idatencion,date(a.fecha)as 'fecha'
		,left(s.nombrecomercial,20) as 'sucursal',left(concat(t.nombre,' ',t.apellido) ,25)as 'usuario',u.tipo,
		a.efectivo as 'total',a.descuento,a.credito,a.cortesia   
		 from atencion a,sucursal s,usuariorestaurante u,personalapoyo t    
		where a.estado='cobrado' and s.idsucursal=a.idsucursal and u.tipo='apoyo' and 
		a.idusuariorestaurante=u.idusuario and t.idpersonalapoyo=u.idtrabajador $condicion) order by idatencion desc ";
		mysql_query("SET NAMES 'utf8'");
	
		$sqllimit = sprintf("%s LIMIT %d, %d", $sql, $desde, $hasta);
		$res = $db->consulta($sqllimit);
		$n = mysql_num_rows($res);
	
		if (isset($_GET['totalFilas'])) {
			$totalPaginas = $_GET['totalFilas'];
		} else {
			$totalPaginas = $db->getnumRow($sql);
		}
		$totalPages_Recordset1 = ceil($totalPaginas/$hasta)-1;
		
		$consultaRegistro = "";
		if (!empty($_SERVER['QUERY_STRING'])) {
		  $params = explode("&", $_SERVER['QUERY_STRING']);
		  $newParams = array();
		  foreach ($params as $param) {
			if (stristr($param, "numPage") == false && 
				stristr($param, "totalFilas") == false) {
			  array_push($newParams, $param);
			}
		  }
		  if (count($newParams) != 0) {
			$consultaRegistro = "&" . htmlentities(implode("&", $newParams));
		  }
		}
		$consultaRegistro = sprintf("&totalFilas=%d%s", $totalPaginas, $consultaRegistro);
      ?>        
  <table width="100%" border="0">
    <tr>
      <td>&nbsp;</td>
      <td align="right"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td width="4%">&nbsp;</td>
    <td width="0%" align="right"></td>
    <td width="12%"><input type="text"  name="filtro" id="filtro" class="caja_busqueda" value="<?php echo $_GET['filtro'];?>"/></td>
    <td width="11%">
    <select id="campo" name="campo" class="combo">
     <?php
		 $selec = $_GET['campo']; 
		 $tipo = array("a.idatencion","s.nombrecomercial","t.nombre");
		 $texto = array("Nº", "Sucursal", "Usuario");
		 for ($i = 0; $i < count($tipo); $i++) {
			$atributo = ""; 
			if ($selec == $tipo[$i]) {
			    $atributo = "selected='selected'";	
			}
			echo "<option value='$tipo[$i]' $atributo>$texto[$i]</option>";
		 }	
	 ?>
    </select></td>
    <td width="47%"><input type="submit" value="Buscar" id="botonrestaurante"/></td>
    <td width="4%">
    <?php if ($numeroPagina > 0) { ?>
    <a href="<?php printf("%s?numPage=%d%s#t4", $currentPage, 0, $consultaRegistro); ?>">
    <img src="../images/first.png" title="Primero"/></a>
    <?php } ?>
    </td>
    <td width="4%">
    <?php if ($numeroPagina > 0) {  ?>  
    <a href="<?php printf("%s?numPage=%d%s#t4", $currentPage, max(0, $numeroPagina - 1), $consultaRegistro); ?>">
    <img src="../images/prev.png" title="Anterior"/></a>
    <?php }?>
    </td>
    <td width="4%">
    <?php if ($numeroPagina < $totalPages_Recordset1) {  ?>
    <a href="<?php printf("%s?numPage=%d%s#t4", $currentPage, min($totalPages_Recordset1, $numeroPagina + 1), $consultaRegistro); ?>">    <img src="../images/next.png" title="Siguiente"/></a>
    <?php } ?>
    </td>
    <td width="4%">
    <?php if ($numeroPagina < $totalPages_Recordset1) {  ?>
    <a href="<?php printf("%s?numPage=%d%s#t4", $currentPage, $totalPages_Recordset1, $consultaRegistro); ?>">
    <img src="../images/last.png" title="Ultimo"/></a>
    <?php }  ?>
    </td>
    <td width="6%"></td>
    <td width="4%"><input type="hidden" id="idatencion" name="idatencion"  value=""/></td>
  </tr>
  
  <tr>
    <td height="186">   
    </td>
    <td colspan="9" valign="top">
    <div style="position:relative;overflow:auto;height:324px;border:1px solid #24160D;width:100%;margin:0 auto;">
    <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellpadding="0" cellspacing="0">
            <tr style=" background-image:url(../images/fondofinal.jpg);color:#FFF; ">
              <td width="24" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
              <td width="24" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
              <td width="30" class="lateralDerecho" align="center">Nº</td>
              <th width="81" class="lateralDerecho">Fecha</th>
              <th width="193" class="lateralDerecho" >Sucursal</th>
              <th width="211" align="center" class="lateralDerecho">Usuario</th>
              <th width="108" align="center" class="lateralDerecho">Monto</th>
              <th width="92" align="center" class="lateralDerecho">Descuento</th>
              <th width="72" align="center" >Venta</th>
            </tr>
            <tbody id="detalleS1">
            <?php			  
				
				  $num = 0;
				  while ($data = mysql_fetch_array($res)) {
					  $num++;
				      $clase = "";
				      if ($num % 2 == 0){
			              $clase = "cebra"; 
				      }	      
					  $fecha = $db->GetFormatofecha($data['fecha'], "-");   
					  $tipo = "F";
					  if ($data['tipo'] == "apoyo"){
						$tipo = "A";  
					  }
					  
					  $tipoVenta = "Contado";
					  $monto = $data['total'];
					  if ($data['credito'] == 1) {
					      $tipoVenta = "Crédito";
					  }
					  
					  if ($data['cortesia'] > 0) {
						  $tipoVenta = "Cortesía";  
						  $monto = $data['cortesia'];
					  }
					  
				      echo "
					   <tr class=".$clase.">
						<td align='center'>
						<img src='../css/images/edit.gif' style='cursor:pointer' title='Modificar' 
						onclick='goEliminar($data[idatencion])' /></td>
						<td align='center'>
						<img src='../css/images/imprimir.gif' style='cursor:pointer' title='Imprimir' 
						onclick='setImpresion($data[idatencion])' /></td>
						<td align='center'>$data[idatencion]</td>
						<td align='center'>$fecha</td>
						<td >&nbsp;$data[sucursal]</td>
						<td align='left'>&nbsp;$tipo-$data[usuario]</td>
						<td align='left'>".number_format($monto, 2)."</td>
						<td align='left'>".number_format($data['descuento'], 2)."</td>
						<td align='center'>$tipoVenta</td>
					   </tr>
					  ";				
				   }				
				
		   ?>         
            
             </tbody>                        
          </table> 
     </div>
    
    
    </td>
    <td>&nbsp;</td>
  </tr>
</table>
       
       </form>
      </div>
    </td>
  </tr>
</table>
   
 </div>
</body>
</html>