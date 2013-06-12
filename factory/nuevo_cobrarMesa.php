<?php
session_start(); 
include("../conexion.php");
$db =new MySQL();

 if (!isset($_SESSION['idusuarioF'])){
  header("Location: index.php");	
 }
 $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
 $tc = $db->getCampo('dolarcompra',$sql); 
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Factory</title>
<link rel="stylesheet" href="estilo_principal.css" type="text/css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<script type="text/javascript" src="evento.js"></script>
</head>

<body>
<div class="datosUsuario">
<table width="100%" border="0">
  <tr>
    <td width="495" height="17px"></td>
    <td width="172" align="right" class="textoUserNew2">FECHA</td>
    <td width="100" class="textoUserNew"><?php echo date('d/m/Y');?></td>
    <td width="17" class="user"></td>
    <td width="145" class="textoUserNew" align="left"><?php echo $_SESSION['nombreusuarioF'];?></td>
    <td width="21" class="userClose">&nbsp;</td>
    <td width="323" class="textoUserNew"><a style="color:#FFF" href="cerrar.php">Cerrar Sesión</a></td>
  </tr>
</table>
</div>
<div class="cuerpo">
	<div class="cabecera"><div class="cabeceraInterior"></div></div>    
    <div class="menuNew">
    <div class="tituloNew"><div class="titleNew"> <div class="textoInternoPrincipal">MENU BISTRON</div>  </div>  </div>
    <div class="optionNew2" onclick="location.href='inicio_restaurante.php'"><div class="textoInterno">Inicio</div></div>
    <?php
	function generarMenu($menu){
	  for ($i=0;$i<count($menu);$i++){
		 $clase = "optionNew"; 
		 if ($menu[$i]['titulo'] == "Reportes")
		  $clase =  "optionNew2"; 
		$url = $menu[$i]['url']."";
		echo '<div class='.$clase.' onclick="location.href=&#039'.$url.'&#039"><div class="textoInterno">'.$menu[$i]['titulo'].'</div></div>';
	  }
	}
	generarMenu($_SESSION['menuFactory']);
	?>
    </div>  
    <div class="contenNew">
    <table width="100%" height="100%" border="0">
  <tr>
    <td width="19%"><div class="contenMenu">
    <table width="100%" border="0">
  <tr>
    <td width="9%"></td>
    <td colspan="2" class="menuInterno" >Referencia</td>
  </tr>
  <tr>
    <td></td>
    <td width="28%" ><div class="referencia1"></div></td>
    <td width="63%" class="textoUserNew">Mesas en Atención</td>
  </tr>
  <tr>
    <td></td>
    <td ><div class="referencia2"></div></td>
    <td class="textoUserNew">Mesas Cerradas</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>

    
    </div></td>
    <td width="81%">
    <div class="contenDataNew">
    <div class="contenPrincipalNew">
    
       <div class="contenido_detalle">
          <div class="divForm">COBRAR MESAS</div>
          
          <div class="contenedorMesasAtencion">    
          <table width="100%" border="0" align="center">
          <tbody id="mesasAtencion">
          <?php
            $sql = "select n.idnotaventa,a.idatencion,a.nromesa,a.estado from notaventaF n,atencionF a 
			where n.idatencion=a.idatencion and (a.estado='cerrado' or a.estado='atencion');";
            $mesas = $db->consulta($sql);
            $i = 0;
            $cadena = "";
             while ($data = mysql_fetch_array($mesas)){
			  if ($data['estado'] == "atencion"){	 
			   $clase1 = "opcionMesa";	
			   $clase2 = "textoMesa"; 
			  }else{
			   $clase1 = "opcionMesa2";   
			    $clase2 = "textoMesa2"; 	  
			  }
			  
              $cadena = $cadena."<td width='14%'>
			  <div id='$clase1' onclick='getTotalMesa(&quot;$data[idnotaventa]&quot;,&quot;$data[idatencion]&quot;)'><div id='$clase2'>Nota #$data[idnotaventa] - M$data[nromesa]</div></div></td>";	
              $i++;
                if ($i == 7){
                  $i = 0;
                  echo "<tr>$cadena<tr>";
                  $cadena = "";	 
                }   
             }
             
             if ($i<7){
               for ($j=$i;$j<=7;$j++){
                $cadena = $cadena."<td width='14%'></td>";
              }
             }
             echo $cadena;          
           ?>
          </tbody>
         </table>
        </div>             
                 
       </div>    
    
    </div>
    </div>
    
    </td>
  </tr>
</table>

    
  </div>
  <div class="pie">© 2012 Consultora Guez. All rights reserved.</div>
    
    
    
 <div id="modal1" class="modal1"></div>
 <div id="modalInterior1" class="modalInterior1">
 <div class="headerInterior">
   <div class="tituloVentanaclave" id="tituloCobranza">CobrO de Mesa</div></div>  
  <div class="posicionCloseSub" onclick="closeVentanaClave();"><img src="../iconos/borrar2.gif" width="12" height="12"></div>
  <br />
  <table width="100%" border="0">
 
  <tr>
    <td height="25">&nbsp;</td>
    <td align="right" class="textoClave">CLIENTE:</td>
    <td colspan="2"><input type="text" name="cliente" id="cliente" size="28" onkeyup="getCambio()" value="0" readonly="readonly"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="12%">&nbsp;</td>
    <td width="27%" align="right" class="textoClave">Total</td>
    <td colspan="2"><input type="text" name="totalgeneral" id="totalgeneral" size="13" disabled="disabled"/>
    <input type="hidden" id="idnotaventa" name="idnotaventa" />
     <input type="hidden" id="idatencion" name="idatencion" />
    </td>
    <td width="8%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right" class="textoClave">Tipo VENTA:</td>
    <td colspan="2"><select name="tiponota" id="tiponota" onchange="limpiarDetalle();"  disabled="disabled" style="width:100px;background:#FFF;border:solid 1px #999;">
      <?php
			 $selec = $datosNota['tiponota']; 
			 $tipo = array("Contado","Credito");
			 for ($i=0;$i<count($tipo);$i++){
				$atributo = ""; 
				if ($selec == $tipo[$i]){
				$atributo = "selected='selected'";	
				}
				echo "<option value='$tipo[$i]' $atributo>$tipo[$i]</option>";
			 }	
			?>
    </select></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right" class="textoClave">Acuenta:</td>
    <td colspan="2"><input type="text" name="acuenta" id="acuenta" size="13" onkeyup="getCambio()" onkeypress="return soloNumeros(event)" value="0"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><hr /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right" class="textoClave">Efectivo bs:</td>
    <td colspan="2"><input type="text" name="efectivobs" id="efectivobs" size="13" onkeyup="getCambio()" value="0"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="textoClave" align="right">efectivo $us:</td>
    <td colspan="2"><input type="text" name="efectivods" id="efectivods" size="13" onkeyup="getCambio()" value="0"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input type="hidden" id="tipocambio" name="tipocambio" value="<?php echo $tc;?>" /></td>
    <td class="textoClave" align="right">Cortesia/vale:</td>
    <td colspan="2"><input type="text" name="cortesia" id="cortesia" size="13" onkeyup="getCambio()" value="0"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="textoClave" align="right">cambio:</td>
    <td colspan="2"><input type="text" name="cambio" id="cambio" disabled="disabled" size="13" /></td>
    <td>&nbsp;</td>
  </tr> 
</table>
<table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="75%"><input type="button"  value="Guardar" class="opcion_contenido" onclick="insertarCobranza()" /></td>
    <td width="25%"><input type="button"  value="Cancelar" class="opcion_contenido" onclick="closeVentanaClave();"/></td>
  </tr>
</table>

  
 </div> 
    
    
</div>
</body>
</html>