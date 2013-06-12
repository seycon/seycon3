<?php
    include("../conexion.php");
    $db = new MySQL(); 
    $transaccion = "insertar";
	
	if (!isset($_SESSION['softLogeoadmin'])){
        header("Location: ../index.php");	
    }
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<link href="../autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script src="Nreporte.js"></script>
<script src="../autocompletar/funciones.js"></script>
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="../js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="../js/jquery.validate.js"></script>

<script>

 $(document).ready(function()
 {
	 
	$("#formulario").validate({});
	 
	$('#fechar3').datepicker({
    showOn: 'button',
    buttonImage: '../css/images/calendar.gif',
    buttonImageOnly: true,
    dateFormat: 'dd/mm/yy' });
	
	$('#fechar2').datepicker({
    showOn: 'button',
    buttonImage: '../css/images/calendar.gif',
    buttonImageOnly: true,
    dateFormat: 'dd/mm/yy' });
 });
 
 
 var verReporteSucursal = function() {	 
	 window.open('ventas/reporte_ventasucursal.php?sucursal='
	 +$$('sucursal').value+'&fecha='+$$("fechar2").value,'target:_blank');
 }
 
 var getReporte3 = function() {
	 window.open('ventas/reporte_planillaapoyo.php?trabajador='
	 +$$('trabajadorapoyo').value+'&fecha='+$$("fechar3").value,'target:_blank'); 
 }
 
</script>
</head>

<body>
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
       <div class="tituloTransaccion"><div class="textoTituloTransaccion">Reportes</div></div>
          <div class="separador"></div>
            <table width="98%" border="0" align="center">
        <tr>
    <td width="17%" height="3"></td>
    <td width="23%"></td>
    <td width="24%" align="right"></td>
    <td width="7%"></td>
    <td width="21%"></td>
    <td width="8%"></td>
  </tr>
  <tr>
    <td><div id="textoConfiguracion" onclick="efectoClick('cgarzon')" >Caja Sucursal</div></td>
    <td><div id="textoConfiguracion" onclick="efectoClick('cventasucursal')" >Venta por Sucursal</div></td>
    <td><div id="textoConfiguracion" onclick="efectoClick('cdinero')" >Planilla de Apoyo</div></td>
    <td></td>
    <td>&nbsp;</td>
    <td><input type="hidden" id="transaccion" name="transaccion" value="<?php echo $transaccion;?>"/></td>
  </tr>
  </table>
            
       <form id="formulario" name="formulario" method="post" >     
       <?php

		$sql = "(select tb.idtrabajador,left(concat(tb.nombre,' ',tb.apellido),20)as 'nombre',				
				(
				select sum(precio*cantidad) from atencion a,detalleatencion d,usuariorestaurante u 
				where d.idatencion=a.idatencion and d.estado=0 and a.idusuariorestaurante=u.idusuario 
				and u.idtrabajador=tb.idtrabajador and u.tipo='fijo' and a.estado='cobrado' group by u.idtrabajador
				) as 'nulos'
				,
				(
				 select round(sum(a.efectivo),2) from atencion a,usuariorestaurante u
				 where a.credito=1 and socio=0 and a.idusuariorestaurante=u.idusuario 
				 and a.estado='cobrado' 
				 and u.idtrabajador=tb.idtrabajador and u.tipo='fijo' group by u.idtrabajador
				 ) as 'credito',
				(select sum(a.cortesia) from atencion a,usuariorestaurante u 
				where a.idusuariorestaurante=u.idusuario and a.credito=0 and a.socio=0 and a.estado='cobrado'
				 and u.idtrabajador=tb.idtrabajador and u.tipo='fijo' group by u.idtrabajador
				)as 'cortesia',
				(select round(sum(a.efectivo),2) from atencion a,usuariorestaurante u  
				 where a.idusuariorestaurante=u.idusuario and a.estado='cobrado' and
				 a.credito=0 and a.socio=0 and u.tipo='fijo' 
				 and u.idtrabajador=tb.idtrabajador group by u.idtrabajador
				)as 'efectivo',
				(select round(sum(acumulado),2)as 'haber' from entregadinero where idtrabajador=tb.idtrabajador 
		        and tipo='fijo' and estado=1 group by idtrabajador)as 'entregado',
				(select round(sum(nulo),2)as 'haber' from entregadinero where idtrabajador=tb.idtrabajador 
		        and tipo='fijo' and estado=1 group by idtrabajador)as 'nuloE',
				(select round(sum(cortesia),2)as 'haber' from entregadinero where idtrabajador=tb.idtrabajador 
		        and tipo='fijo' and estado=1 group by idtrabajador)as 'cortesiaE',
				(select round(sum(credito),2)as 'haber' from entregadinero where idtrabajador=tb.idtrabajador 
		        and tipo='fijo' and estado=1 group by idtrabajador)as 'creditoE'
				,left(c.cargo,15)as 'cargo','F' as 'tipo',left(s.nombrecomercial,20) as 'sucursal' 
				 from usuariorestaurante u,trabajador tb
				,cargo c,sucursal s where tb.idtrabajador=u.idtrabajador and tb.idcargo=c.idcargo and
				 u.tipo='fijo' and u.estado=1 and u.idsucursal=s.idsucursal  
				  group by tb.idtrabajador 				 
				 ) union all 
							
				(select tb.idpersonalapoyo as 'idtrabajador',left(concat(tb.nombre,' ',tb.apellido),20)as 'nombre',				
				(
				select sum(precio*cantidad) from atencion a,detalleatencion d,usuariorestaurante u 
				where d.idatencion=a.idatencion and d.estado=0 and a.idusuariorestaurante=u.idusuario 
				and u.idtrabajador=tb.idpersonalapoyo and u.tipo='apoyo' and a.estado='cobrado' group by u.idtrabajador
				) as 'nulos'
				,
				(
				 select round(sum(a.efectivo),2) from atencion a,usuariorestaurante u
				 where a.credito=1 and socio=0 and a.idusuariorestaurante=u.idusuario and a.estado='cobrado' 
				 and u.idtrabajador=tb.idpersonalapoyo and u.tipo='apoyo' group by u.idtrabajador
				 ) as 'credito',
				(select sum(a.cortesia) from atencion a,usuariorestaurante u 
				where a.idusuariorestaurante=u.idusuario and a.credito=0 and a.socio=0 and a.estado='cobrado' 
				 and u.idtrabajador=tb.idpersonalapoyo and u.tipo='apoyo' group by u.idtrabajador
				)as 'cortesia',
				(select round(sum(a.efectivo),2) from atencion a,usuariorestaurante u  
				 where a.idusuariorestaurante=u.idusuario and a.estado='cobrado' and
				 a.credito=0 and a.socio=0 and u.tipo='apoyo' 
				 and u.idtrabajador=tb.idpersonalapoyo group by u.idtrabajador
				)as 'efectivo',
				(select round(sum(acumulado),2)as 'haber' from entregadinero where idtrabajador=tb.idpersonalapoyo 
		        and tipo='apoyo' and estado=1 group by idtrabajador)as 'entregado',
				(select round(sum(nulo),2)as 'haber' from entregadinero where idtrabajador=tb.idpersonalapoyo 
		        and tipo='apoyo' and estado=1 group by idtrabajador)as 'nuloE',
				(select round(sum(cortesia),2)as 'haber' from entregadinero where idtrabajador=tb.idpersonalapoyo 
		        and tipo='apoyo' and estado=1 group by idtrabajador)as 'cortesiaE',
				(select round(sum(credito),2)as 'haber' from entregadinero where idtrabajador=tb.idpersonalapoyo 
		        and tipo='apoyo' and estado=1 group by idtrabajador)as 'creditoE'
				,left(tb.cargo,15)as 'cargo','A' as 'tipo',left(s.nombrecomercial,20) as 'sucursal' 				
				 from usuariorestaurante u,personalapoyo tb,sucursal s 
				 where tb.idpersonalapoyo=u.idtrabajador and s.idsucursal=u.idsucursal and 
				 u.tipo='apoyo' and u.estado=1 group by tb.idpersonalapoyo ) order by nombre" ;
		 $res = $db->consulta($sql);
	
		
      ?>  
  
  
  <table width="100%" border="0" align="center">
  <tr>
    <td width="37%" colspan="6" valign="top">
      <div class="contenedorConfiguracion3" id="cgarzon">    
       <table width="100%" border="0">
  <tr>
    <td width="4%">&nbsp;</td>
    <td width="68%" align="right"></td>
    <td width="5%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="4%"></td>
    <td width="4%"><input type="hidden" id="idpersonal" name="idpersonal"  value=""/></td>
  </tr>
  <tr>
    <td height="186">   
    </td>
    <td colspan="6" valign="top">
    <div style="position:relative;overflow:auto;height:274px;border:1px solid #24160D;width:100%;margin:0 auto;">
    <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellpadding="0" cellspacing="0">
            <tr style=" background-image:url(../images/fondofinal.jpg);color:#FFF; ">
              <th width="68" class="lateralDerecho">Nº</th>
              <th width="300" class="lateralDerecho">Nombre</th>
              <th width="180" class="lateralDerecho">Sucursal</th>
              <th width="110" class="lateralDerecho">Nulo</th>
              <th width="110" align="center" class="lateralDerecho">Cortesia</th>
              <th width="110" align="center" class="lateralDerecho">Credito</th>
              <th width="118" align="center" >Efectivo</th>
            </tr>
            <tbody id="detalleS1">
            <?php
			    $contadorCuentas = 0;
				$codigoCuentas = array();					
  			    $sql = "select *from configuracionrestaurante";
				$configuracion = $db->arrayConsulta($sql);
				$tipo = 'Apoyo' ;
				$num = 0;
				while ($data = mysql_fetch_array($res)) {	   
				 $campo = $data['cargo'].$data['honorario'];      
				 
				 
				 if ( ( $data['efectivo'] - $data['entregado'] ) > 0) {
					 $num++;
				     $clase = "";
				     if ($num % 2 == 0){
			             $clase = "cebra"; 
				     }
				    echo "
					<tr class=".$clase.">
					  <td align='left'>&nbsp;$data[tipo]-$data[idtrabajador]</td>
					  <td >&nbsp; $data[nombre]</td>
					  <td >&nbsp; $data[sucursal]</td>
					  <td align='left'>&nbsp;".number_format($data['nulos'] - $data['nuloE'],2)."</td>
					  <td align='left'>&nbsp;".number_format($data['cortesia'] - $data['cortesiaE'],2)."</td>
					  <td align='left'>&nbsp;".number_format($data['credito'] - $data['creditoE'],2)."</td>
					  <td align='left'>&nbsp;".number_format($data['efectivo'] - $data['entregado'],2)."</td>
					</tr>
					";	
				 }
				}       
			
		   ?>       
            
             </tbody>                        
          </table> 
     </div>
    
    
    </td>
    <td>&nbsp;</td>
  </tr>
</table>
        
        </div>
      
      
      
      
      <div class="contenedorConfiguracion4" id="cventasucursal">    
        <table width="100%" border="0" align="center">
          <tr>
            <td width="33%">&nbsp;</td>
            <td width="29%" align="center" class="letra2">&nbsp;</td>
            <td width="7%" align="center" class="letra2">&nbsp;</td>
            <td width="13%" align="center" class="letra2">&nbsp;</td>
            <td width="18%">&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Sucursal:</td>
            <td align="left"><select name="sucursal" id="sucursal" style="width:135px;">
              <option value='' selected='selected'>-- Seleccione --</option>
              <?php
                  $sql = "select s.idsucursal,left(s.nombrecomercial,25)as 'nombrecomercial' from sucursal s,
			      configuracionsucursal cs where cs.idsucursal=s.idsucursal and s.estado=1;";
                  $db->imprimirCombo($sql);
              ?>
              </select></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right"><span class="letra">Fecha:</span></td>
            <td><input type="text" name="fechar2" size="10" id="fechar2" class="date" value="<?php echo date("d/m/Y"); ?>"/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td height="23">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="button" value="Imprimir" id="botonrestaurante" onclick="verReporteSucursal()"/></td>
            <td><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </table>
        
        </div>
        
        <div class="contenedorConfiguracion4" id="cdinero">    
        <table width="100%" border="0" align="center">
          <tr>
            <td width="33%">&nbsp;</td>
            <td width="29%" align="center" class="letra2">&nbsp;</td>
            <td width="7%" align="center" class="letra2">&nbsp;</td>
            <td width="13%" align="center" class="letra2">&nbsp;</td>
            <td width="18%">&nbsp;</td>
            </tr>
            <tr>
            <td align="right" class="letra"></td>
            <td align="left">           
            
            </td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Fecha</td>
            <td align="left">
            <input type="text" name="fechar3" size="10" id="fechar3" class="date" value="<?php echo date("d/m/Y"); ?>"/>
            </td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra">Trabajador:</td>
            <td align="left">
            <select name="trabajadorapoyo" id="trabajadorapoyo" style="width:140px;">
              <option value='' selected='selected'>-- Seleccione --</option>
              <?php
                  $sql = "select idpersonalapoyo,left(concat(nombre,' ',apellido),40) as 'nombre' 
				  from personalapoyo where estado=1";
                  $db->imprimirCombo($sql);
              ?>
            </select></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          
          <tr>
            <td align="right"><span class="letra"></span></td>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra"></td>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td align="right" class="letra"></td>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td >&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="button" value="Imprimir" id="botonrestaurante" onclick="getReporte3()"/></td>
            <td><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </table>
        
        </div>
        
        
      
      </td>
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