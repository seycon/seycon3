<?php
  session_start();
  if (!isset($_SESSION['idusuariorestaurante'])){
    header("Location: index.php");	  
  }
  include("../conexion.php");
  $db = new MySQL();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Restaurante</title>
<link rel="stylesheet" href="restaurante.css" type="text/css"/>
<script src="Nrestaurante.js"></script>
</head>

<body>
 <div class="contendedor">
 
   <div class="tela_izq"></div>
   <div class="tela_cierreizq"></div>
   <div class="tela_der"></div>
   <div class="tela_cierreder"></div> 
   <div class="derechosReservados"><!--Copyright © Consultora Guez – Diseñado y Desarrollado-->
   </div>
   <div class="header"><div class="gradient7"><h1><span></span>Discoteca</h1></div>  </div>
   <div class="subTitulo">Nuestros Servicios al Alcance del Cliente.</div>
   
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
    <td colspan="2" valign="top"><div class="tituloMenu"><< <?php echo ucfirst($_SESSION['sucursalrestaaurante']); ?> >></div></td>
    </tr>
  <tr>
    <td height="336" colspan="2">
    <div class="contenedorMenu">
     <div id="opcion1"><div class="sombraButon"></div><div id="textoOpcion">Inicio</div></div>
     <div class="separador"></div>
     <div id="opcion1" onclick="location.href = 'nuevo_cambiarclave.php'"><div class="sombraButon"></div><div id="textoOpcion">Cambiar Contraseña</div></div>
    </div>
    </td>
    </tr>
  <tr>
    <td height="21" align="center" class="letra" colspan="2">&nbsp;</td>
  </tr>
</table>

    <div class="contenedorUser"><div class="imgUser"></div><div class="nombreUser"><?php echo $_SESSION['nombretrestaurante'];?></div></div>
    </div>
    </td>
    <td width="79%">
      <div class="menu2">
       <div class="tituloTransaccion"><div class="textoTituloTransaccion">Atención de Mesas</div></div>
            <div class="separador"></div>
            
            <table width="98%" border="0" align="center">
        <tr>
    <td width="17%" height="2"></td>
    <td width="38%"></td>
    <td width="16%" align="right"></td>
    <td width="17%"></td>
    <td width="4%"></td>
    <td width="8%"></td>
  </tr>
  <tr>
    <td><div id="textoConfiguracion" onclick="setNuevaAtencion()" >Abrir Mesa</div></td>
    <td></td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td><input type="hidden" id="transaccion" name="transaccion" value="<?php echo $transaccion;?>"/></td>
  </tr>
  </table>
            
            
            
        <div class="contenedorMesasAtencion">    
       <table width="100%" border="0" align="center">
        <tbody id="mesasAtencion">
        <?php
          $sql = "select *from atencion where idusuariorestaurante='$_SESSION[idusuariorestaurante]' and estado='atencion'";
          $mesas = $db->consulta($sql);
          $i = 0;
          $cadena = "";
		   while ($data = mysql_fetch_array($mesas)){
			$cadena = $cadena."<td width='16%'><div id='opcionMesa' onclick='setNroPedido($data[idatencion])'><div id='textoMesa'>Mesa #$data[nromesa]</div></div></td>";	
			$i++;
			  if ($i == 6){
				$i = 0;
				echo "<tr>$cadena<tr>";
				$cadena = "";	 
			  }   
		   }
		   
		   if ($i<6){
			 for ($j=$i;$j<=6;$j++){
			  $cadena = $cadena."<td width='16%'></td>";
			}
		   }
		   echo $cadena;
        
        ?>
        </tbody>
</table>
</div>
     
      
      
            <div class="contenedorCerrar"><div class="imagenCerrar"></div><div id="textoCerrar" onclick="location.href='cerrar.php'">Cerrar</div></div>
      </div>
    </td>
  </tr>
</table>

   
 </div>
</body>
</html>