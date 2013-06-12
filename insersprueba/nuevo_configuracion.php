<?php
    include("../conexion.php");
    $db = new MySQL(); 
    $consulta = "select * from configuracionrestaurante;";
    $valores = $db->arrayConsulta($consulta);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<script src="Nconfiguracion.js"></script>
<script src="../lib/Jtable.js"></script>
</head>

<body>
 <div class="contendedor">
   <div class="header"><div class="gradient7"><h1><span></span>Discoteca</h1></div>  </div>
   
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
     <div id="opcion1" onclick="location.href='inicio_restaurante.php'"><div id="textoOpcion">Inicio</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='listar_personal.php'"><div id="textoOpcion">Personal Apoyo</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='listar_usuario.php'"><div id="textoOpcion">Usuario</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_configuracion.php'"><div id="textoOpcion">Configuración</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href='nuevo_reporte.php'"><div id="textoOpcion">Reportes</div></div>
    </div>
    </td>
    </tr>
  <tr>
    <td height="21" align="center" class="letra" colspan="2">Fecha: <?php echo date("d/m/Y");?></td>
  </tr>
</table>

    
    </div>
    </td>
    <td width="79%">
      <div class="menu2">
       <div class="tituloTransaccion">
         <div class="textoTituloTransaccion">Configuración</div></div>
          <div class="separador"></div>
            
            
       <form id="formulario" name="formulario" method="post" action="nuevo_configuracion.php">     
       <table width="98%" border="0" align="center">
        <tr>
    <td height="4"></td>
    <td></td>
    <td align="right"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><div id="textoConfiguracion" onclick="efectoClick('chonorario')" >Honorario</div></td>
    <td><div id="textoConfiguracion" onclick="efectoClick('cparametros')">Parametros</div></td>
    <td><div id="textoConfiguracion" onclick="efectoClick('cventa')">Venta</div></td>
    <td><div id="textoConfiguracion" onclick="efectoClick('cprecio')">Socios</div></td>
    <td><div id="textoConfiguracion" onclick="efectoClick('cturno')">Turno</div></td>    
    <td><div id="textoConfiguracion" onclick="efectoClick('cdescuento')">Descuento</div></td>
    <td><input type="hidden" id="transaccion" name="transaccion" value="<?php echo $transaccion;?>"/></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="230" colspan="7">
    <div class="contenedorConfiguracion2" id="chonorario">    
     <table width="100%" border="0" align="center">
  <tr>
    <td width="28%">&nbsp;</td>
    <td width="16%" align="center" class="letra2">Modalidad 1</td>
    <td width="17%" align="center" class="letra2">Modalidad 2</td>
    <td width="16%" align="center" class="letra2">Modalidad 3</td>
    <td width="23%">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="letra">Guardia:</td>
    <td align="center"><input type="text" id="guardiam1" name="guardiam1" size="12" value="<?php echo $valores['guardiam1'];?>"/></td>
    <td align="center"><input type="text" id="guardiam2" name="guardiam2" size="12" value="<?php echo $valores['guardiam2'];?>"/></td>
    <td align="center"><input type="text" id="guardiam3" name="guardiam3" size="12" value="<?php echo $valores['guardiam3'];?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="letra">Ayudante de Barra:</td>
    <td align="center"><input type="text" id="ayudantem1" name="ayudantem1" size="12" value="<?php echo $valores['ayudantem1'];?>"/></td>
    <td align="center"><input type="text" id="ayudantem2" name="ayudantem2" size="12" value="<?php echo $valores['ayudantem2'];?>"/></td>
    <td align="center"><input type="text" id="ayudantem3" name="ayudantem3" size="12" value="<?php echo $valores['ayudantem3'];?>"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="letra">Garzón:</td>
    <td align="center"><input type="text" id="garzonm1" name="garzonm1" size="12" value="<?php echo $valores['garzonm1'];?>"/></td>
    <td align="center"><input type="text" id="garzonm2" name="garzonm2" size="12" value="<?php echo $valores['garzonm2'];?>"/></td>
    <td align="center"><input type="text" id="garzonm3" name="garzonm3" size="12" value="<?php echo $valores['garzonm3'];?>"/></td>
    <td>&nbsp;</td>
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
    <div class="contenedorConfiguracion" id="cparametros">    
    <table width="100%" border="0">
  <tr>
    <td width="40%">&nbsp;</td>
    <td width="13%" align="center" class="letra2">Pantalla</td>
    <td width="11%" align="center" class="letra2">Impresión</td>
    <td width="16%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="letra">Mostrar Precio Unitario:</td>
    <td align="center"><input type="checkbox" name="pantallapu" id="pantallapu" <?php if ($valores['pantallapu']) echo ' checked ';?>
     onclick="setValorCheck(this.id)" value="<?php if (isset($valores['pantallapu'])) echo $valores['pantallapu']; else '0';?>"/></td>
    <td align="center"><input type="checkbox" name="impresionpu"  <?php if ($valores['impresionpu']) echo ' checked ';?> id="impresionpu" 
    onclick="setValorCheck(this.id)" value="<?php if (isset($valores['impresionpu'])) echo $valores['impresionpu']; else '0';?>"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="letra">Mostrar Precio Total:</td> 
    <td align="center"><input type="checkbox" name="pantallapt" id="pantallapt" <?php if ($valores['pantallapt']) echo ' checked ';?>
     onclick="setValorCheck(this.id)" value="<?php if (isset($valores['pantallapt'])) echo $valores['pantallapt']; else '0';?>"/></td>
    <td align="center"><input type="checkbox" name="impresionpt" id="impresionpt" <?php if ($valores['impresionpt']) echo ' checked ';?>
     onclick="setValorCheck(this.id)" value="<?php if (isset($valores['impresionpt'])) echo $valores['impresionpt']; else '0';?>"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="letra">Mostrar Total Venta:</td>
    <td align="center"><input type="checkbox" name="pantallatv" id="pantallatv" <?php if ($valores['pantallatv']) echo ' checked ';?>
     onclick="setValorCheck(this.id)" value="<?php if (isset($valores['pantallatv'])) echo $valores['pantallatv']; else '0';?>"/></td>
    <td align="center"><input type="checkbox" name="impresiontv" id="impresiontv" <?php if ($valores['impresiontv']) echo ' checked ';?>
     onclick="setValorCheck(this.id)" value="<?php if (isset($valores['impresiontv'])) echo $valores['impresiontv']; else '0';?>"/></td>
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
</table>

    </div>
    <div class="contenedorConfiguracion" id="cventa">    
    <table width="100%" border="0">
  <tr>
    <td width="7%">&nbsp;</td>
    <td width="22%" align="right">Sucursal:</td>
    <td width="29%"><select name="sucursal" id="sucursal" style="width:135px;">
      <option value='' selected='selected'>-- Seleccione --</option>
       <?php
           $almacen = "select idsucursal,left(nombrecomercial,25)as 'nombrecomercial' from sucursal where estado=1";
           $db->imprimirCombo($almacen);
       ?>
    </select></td>
    <td width="35%"><input type="button" onclick="insertarNewItem('detalleS1','sucursal');" name="agregar" id="botonrestaurante" value="Agregar"   /></td>
    <td width="7%">
    <select name="descuentoventa" id="descuentoventa"  style="width:190px;display:none">
          <option value="">--Seleccione una Cuenta--</option>
          <?php
	          $sql = "select (select pp.cuenta from plandecuenta pp where pp.codigo=( left(ph.codigo,2) ))as 'padre'"
		          .",ph.codigo,ph.cuenta,ph.nivel from plandecuenta ph  where ph.nivel>=5 and estado=1 order by ph.codigo;";		   
		      $arrayPlan= $db->getDatosArray($sql,4);
	          $db->imprimirComboGrupoArray($arrayPlan,'','');	     
		  ?>
    </select></td>
  </tr>
  <tr>
    <td height="186">   
    </td>
    <td colspan="3" valign="top">
    <div style="position:relative;overflow:auto;height:184px;border:1px solid #24160D;width:90%;margin:0 auto;">
    <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellpadding="0" cellspacing="0">
            <tr style=" background-image:url(../images/fondofinal.jpg);color:#FFF; ">
              <td width="38" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
              <td width="38" style="display:none" >Id</td>
              <td width="38" style="display:none" >Id</td>
              <th width="400" align="center" style="border-right:1px solid; border-right-color:#FFF;">Sucursal</th>
              <th width="250" align="center">Cuenta</th>
            </tr>
            <tbody id="detalleS1">
        <?php
		    $contadorCuentas = 0;
			$codigoCuentas = array();
            $sql = "select c.idsucursal,c.cuenta,s.nombrecomercial from configuracionsucursal c,
            sucursal s where s.idsucursal=c.idsucursal;";
			$dato = $db->consulta($sql);
			while ($configuracion = mysql_fetch_array($dato)) {
			    $id = "DS1_".$contadorCuentas;	
				echo "
				<tr>
				  <td width='38' align='center'><img src='../css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)'/></td>
				  <td width='38' style='display:none' >$id</td>
				  <td width='38' style='display:none' >$configuracion[idsucursal]</td>
				  <td width='400' align='center'>$configuracion[nombrecomercial]</td>
				  <td width='250' align='left'>
				  <select name='$id' id='$id'  style='width:190px;'>
				   <option value='' >--Seleccione una Cuenta--</option>";
					$db->imprimirComboGrupoArray($arrayPlan,'','',$configuracion['cuenta']);
				 echo " </select>
				  </td>
				  </tr>
				 ";
				$contadorCuentas++;	
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
    <div class="contenedorConfiguracion" id="cprecio">    
    <table width="100%" border="0">
  <tr>
    <td width="7%">&nbsp;</td>
    <td width="22%" align="right">Trabajador:</td>
    <td width="29%">
    <select name="trabajador" id="trabajador" style="width:180px;">
     <option value="">-- Seleccione --</option>
      <?php
	      $sql = "select idtrabajador,concat(nombre,' ',apellido)as 'nombre1' from trabajador where estado=1;";
	      $db->imprimirCombo($sql,$datoAlmacen['responsable']);     
      ?>
    </select></td>
    <td width="35%">
    <input type="button" onclick="insertarNewItemSocios('detalleS2','trabajador');" name="agregar" id="botonrestaurante" value="Agregar"   /></td>
    <td width="7%"></td>
  </tr>
  <tr>
    <td height="186">   
    </td>
    <td colspan="3" valign="top">
    <div style="position:relative;overflow:auto;height:184px;border:1px solid #24160D;width:90%;margin:0 auto;">
    <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellpadding="0" cellspacing="0">
            <tr style=" background-image:url(../images/fondofinal.jpg);color:#FFF; ">
              <td width="38" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
              <td width="38" style="display:none" >Id</td>
              <th width="400" align="center" style="border-right:1px solid; border-right-color:#FFF;">Nombre Socio</th>
            </tr>
            <tbody id="detalleS2">
            <?php
			    $codigoCuentas = array();
                $sql = "select t.idtrabajador,left(concat(t.nombre,' ',t.apellido),30)as 'nombre' "
			        ."from socio s,trabajador t where s.idtrabajador=t.idtrabajador;";
				$dato = $db->consulta($sql);
				while ($configuracion = mysql_fetch_array($dato)) {
				    echo "
				    <tr>
				    <td width='38' align='center'><img src='../css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' /></td>
				    <td width='38' style='display:none' >$configuracion[idtrabajador]</td>
				    <td width='400' >$configuracion[nombre]</td>             
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
   <tr>
    <td width="7%">&nbsp;</td>
    <td width="22%" align="right">Cuenta Socio:</td>
    <td width="29%">
    <select name="cuentasocio" id="cuentasocio" style="width:180px;">
     <option value="">-- Seleccione --</option>
      <?php	  
	      $db->imprimirComboGrupoArray($arrayPlan,'','',$valores['cuentasocio']);     
      ?>
    </select></td>
    <td width="35%"> </td>
    <td width="7%"></td>
  </tr>
</table>


    </div>
    
    
    
     <div class="contenedorConfiguracion" id="cdescuento">    
    <table width="100%" border="0">
  <tr>
    <td width="7%">&nbsp;</td>
    <td width="22%" align="right">Combinación:</td>
    <td width="29%">
    <select name="combinacion" id="combinacion" style="width:180px;">
     <option value="">-- Seleccione --</option>
      <?php
	      $sql = "select idcombinacion,left(nombre,25)as 'nombre' from combinacion where estado=1;";
	      $db->imprimirCombo($sql,$datoAlmacen['responsable']);     
      ?>
    </select></td>
    <td width="20%">Descuento: <input type="text" name="pdescuento" id="pdescuento" style="width:50px;"/></td>
    <td width="15%">
    <input type="button" onclick="insertarNewItemDescuento('detalleS3');" name="agregar" id="botonrestaurante" value="Agregar" /></td>
    <td width="7%"></td>
  </tr>
  <tr>
    <td height="186"> </td>
    <td colspan="4" valign="top">
    <div style="position:relative;overflow:auto;height:184px;border:1px solid #24160D;width:90%;margin:0 auto;">
    <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellpadding="0" cellspacing="0">
            <tr style=" background-image:url(../images/fondofinal.jpg);color:#FFF; ">
              <td width="38" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
              <td width="38" style="display:none" >Id</td>
              <th width="400" align="center" style="border-right:1px solid; border-right-color:#FFF;">Combinacion</th>
              <th width="400" align="center" style="border-right:1px solid; border-right-color:#FFF;">Descuento</th>
            </tr>
            <tbody id="detalleS3">
            <?php
                $sql = "select c.idcombinacion,left(c.nombre,30)as 'nombre',d.porcentaje  "
			        ."from descuento d,combinacion c where d.idcombinacion=c.idcombinacion;";
			    $dato = $db->consulta($sql);
				while ($configuracion = mysql_fetch_array($dato)){
				 echo "
				 <tr>
				  <td width='38' align='center'><img src='../css/images/borrar.gif' title='Borrar' onclick='eliminarFila(this)' /></td>
				  <td width='38' style='display:none' >$configuracion[idcombinacion]</td>
				  <td width='400' >$configuracion[nombre]</td> 
				  <td width='400' >$configuracion[porcentaje]</td>            
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


    </div>
    
    
    
    <div class="contenedorConfiguracion" id="cturno">   
    <table width="100%" border="0" align="center">
  <tr>
    <td width="28%">&nbsp;</td>
    <td width="16%" align="center" class="letra2">Hora Inicio</td>
    <td width="17%" align="center" class="letra2">Hora Cierre</td>
    <td width="16%" align="center" class="letra2"></td>
    <td width="23%">&nbsp;</td>
  </tr>
  <?php
      $sql = "select * from turnorestaurante where tipo='AM';";
      $Inicio = $db->arrayConsulta($sql);
      $sql = "select * from turnorestaurante where tipo='PM';";
      $Fin = $db->arrayConsulta($sql);
  ?>
  <tr>
    <td align="right" class="letra">Turno AM:</td>
    <td align="center"><input type="text" id="amdesde" name="amdesde" size="10" value="<?php echo  $Inicio['horainicio'];?>" 
    onkeypress="return mascarcaHora(this.value,this.id,event)"></td>
    <td align="center"><input type="text" id="amhasta" name="amhasta" size="10" value="<?php echo  $Inicio['horacierre'];?>" onkeypress="return mascarcaHora(this.value,this.id,event)"></td>
    <td align="center"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right" class="letra">Turno PM:</td>
    <td align="center"><input type="text" id="pmdesde" name="amdesde" size="10" value="<?php echo  $Fin['horainicio'];?>"
      onkeypress="return mascarcaHora(this.value,this.id,event)"></td>
    <td align="center"><input type="text" id="pmhasta" name="amhasta" size="10" value="<?php echo  $Fin['horacierre'];?>"
     onkeypress="return mascarcaHora(this.value,this.id,event)"></td>
    <td align="center"></td>
    <td>&nbsp;</td>
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
  <tr>
    <td></td>
    <td></td>
    <td align="right"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="11%"></td>
    <td width="14%"></td>
    <td width="11%" align="right"></td>
    <td width="14%">&nbsp;</td>
    <td width="13%" align="right">&nbsp;</td>
    <td width="16%" align="right"><input type="button" value="Guardar" id="botonrestaurante" onclick="ejecutarTransaccion()"/></td>
    <td width="21%"><input type="reset" value="Cancelar" id="botonrestaurante"/></td>
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