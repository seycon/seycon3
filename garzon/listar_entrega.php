<?php
    include("../conexion.php");
    $db = new MySQL();  
    $transaccion = "insertar"; 
	
	if (!isset($_SESSION['softLogeoadmin'])){
        header("Location: ../index.php");	
    }
	
    if (isset($_GET['identrega']) && $_GET['identrega'] != "") {
        $sql = "update entregadinero set estado=0 where identrega=$_GET[identrega]";	 
        $db->consulta($sql);		
		
		$sql = "select idlibrodiario,idsucursal from librodiario where transaccion='Entrega Dinero Restaurante'"
	    ." and idtransaccion=$_GET[identrega];"; 
	    $libro = $db->arrayConsulta($sql); 
		if (isset($libro['idlibrodiario'])) {
		    $sql = "update librodiario set estado=0 where idlibrodiario=$libro[idlibrodiario]";
		    $db->consulta($sql);
		}
        header("Location: listar_entrega.php");
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<script src="Nreporte.js"></script>
<script>
    var goModificar = function(codigo){
	 location.href = "nuevo_entrega.php?nro="+codigo;	
	}
	
	var goEliminar = function(codigo){
	  $$("identrega").value = codigo;		 	
	  $$("modal_tituloCabecera").innerHTML = 'Advertencia';
	  $$("modal_contenido").innerHTML = '¿Desea anular esta Entrega?';
	  $$("modal_mensajes").style.visibility = "visible";
      $$("overlay").style.visibility = "visible";
	}
	
	var $$ = function(id){
	  return document.getElementById(id);
	}
	
	var closeMensaje = function(){
		$$("identrega").value = "";
	    $$("modal_mensajes").style.visibility = "hidden";
        $$("overlay").style.visibility = "hidden";    
    }
	
	var eliminarTransaccion = function(){
	    $$("formulario").submit();
	}
</script>
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
     <div class="modal_cerrar"><img src="../iconos/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
  </div>
  <div class="modal_icono_modal"><img src="../iconos/alerta.png" width="24" height="24"></div>
  <div id="modal_contenido" class="modal_contenido">No Existen Pagos Realizados</div>
  <div class="modal_boton2"><input type="button" value="Aceptar" class="boton_modal" onclick="eliminarTransaccion()"/></div>
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
       <div class="tituloTransaccion"><div class="textoTituloTransaccion">Listar Entrega de Dinero</div></div>
          <div class="separador"></div>
          <table width="98%" border="0" align="center">
           <tr>
            <td width="17%" height="3"></td>
            <td width="38%"></td>
            <td width="16%" align="right"></td>
            <td width="17%"></td>
            <td width="4%"></td>
            <td width="8%"></td>
          </tr>
          <tr>
            <td><div id="textoConfiguracion" onclick="location.href='nuevo_entrega.php'" >Nuevo Entrega</div></td>
            <td></td>
            <td></td>
            <td></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          </table>
            
       <form id="formulario" name="formulario" method="get" action="listar_entrega.php">
       <?php
		$currentPage = $_SERVER["PHP_SELF"];
		$hasta = 12;
		$numeroPagina = 0;
		if (isset($_GET['numPage'])) {
			$numeroPagina = $_GET['numPage'];
		}
		$desde = $numeroPagina * $hasta;
	
        $condicion = "";
       	if ($_GET['filtro'] != "") {
			$condicion = "  and $_GET[campo] like '$_GET[filtro]%' ";
		} 
	    $sql = "(select u.identrega,u.fecha,u.monto,left(concat(t.nombre,' ',t.apellido),20)as 'nombre'"
		   .",left(s.nombrecomercial,20)as 'sucursal',left(c.cargo,15)as 'cargo' from entregadinero u,trabajador t"
		   .",usuariorestaurante a,sucursal s,cargo c where t.idtrabajador=u.idtrabajador and t.idcargo=c.idcargo and"
		   ." u.tipo='fijo' and u.estado=1 and s.idsucursal=u.idsucursal and a.idtrabajador=u.idtrabajador "
		   ." and a.tipo='fijo' $condicion )union "
		   ."(select u.identrega,u.fecha,u.monto,left(concat(t.nombre,' ',t.apellido),20)as 'nombre'"
		   .",left(s.nombrecomercial,20)as 'sucursal',left(t.cargo,15)as 'cargo' from entregadinero u,"
		   ."usuariorestaurante a,personalapoyo t,sucursal s where t.idpersonalapoyo=u.idtrabajador "
		   ." and a.idtrabajador=u.idtrabajador and a.tipo='apoyo' and "
		   ." u.tipo='apoyo' and u.estado=1 and s.idsucursal=u.idsucursal $condicion) order by identrega desc";
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
    <td></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="4%">&nbsp;</td>
    <td width="1%" align="right"></td>
    <td width="11%"><input type="text"  name="filtro" id="filtro" class="caja_busqueda" value="<?php echo $_GET['filtro'];?>"/></td>
    <td width="11%">
    <select id="campo" name="campo" class="combo">
      <?php
		 $selec = $_GET['campo']; 
		 $tipo = array("u.identrega", "t.nombre", "s.nombrecomercial");
		 $texto = array("Nº", "Nombre", "Sucursal");
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
    <td width="5%"></td>
    <td width="5%"><input type="hidden" id="identrega" name="identrega"  value=""/></td>
  </tr>
  
  <tr>
    <td height="186">   
    </td>
    <td colspan="9" valign="top">
    <div style="position:relative;overflow:auto;height:274px;border:1px solid #24160D;width:100%;margin:0 auto;">
    <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellpadding="0" cellspacing="0">
            <tr style=" background-image:url(../images/fondofinal.jpg);color:#FFF; ">
              <td width="38" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
              <td width="38" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
              <td width="38" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
              <th width="38" class="lateralDerecho">Nº</th>
              <th width="350" class="lateralDerecho" >Nombre Trabajador</th>
              <th width="250" align="center" class="lateralDerecho">Fecha</th>
              <th width="250" align="center" class="lateralDerecho">Sucursal</th>
              <th width="88" align="center" >Monto</th>
            </tr>
            <tbody id="detalleS1">
            <?php			
				  							
				
				  while ($data = mysql_fetch_array($res)) {	         
				      $num++;
				      $clase = "";
				      if ($num % 2 == 0){
			              $clase = "cebra"; 
				      }	
				      $fecha = $db->GetFormatofecha($data['fecha'],"-");
				      echo "
					   <tr class=".$clase.">
						<td align='center'>
						<img src='../css/images/edit.gif' style='cursor:pointer' title='Modificar' 
						onclick='goModificar($data[identrega])' /></td>
						<td align='center'>
						<img src='../css/images/borrar.gif' style='cursor:pointer' title='Anular' 
						onclick='goEliminar($data[identrega])' /></td>
						<td align='center'>
						<img src='../css/images/imprimir.gif' style='cursor:pointer' title='Imprimir' 
						onclick='getDatosVentaR3($data[identrega])' /></td>
						<td align='center'>$data[identrega]</td>
						<td >$data[nombre]</td>
						<td align='center'>$fecha</td>
						<td align='left'>$data[sucursal]</td>
						<td align='left'>".number_format($data['monto'],2)."</td>
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