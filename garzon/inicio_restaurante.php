<?php
    session_start();
    include("../conexion.php");
//    $_SESSION['BDname'] = "bdkiwis";
//    $_SESSION['BDname'] = "jorge_bdinsersprueba";  
	$_SESSION['BDname'] = "jorge_bdinsersdiscoteca";
    if (!isset($_SESSION['softLogeoadmin'])){
        header("Location: ../index.php");	
    }
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
   <div class="derechosReservados">Copyright © Consultora Guez – Diseñado y Desarrollado

   </div>
   <div class="header"><div class="gradient7"><h1><span></span>Scav</h1></div>  </div>
   <div class="subTitulo">Software Contable de Administración y Ventas.</div>
   
   <table width="90%" height="460" border="0" align="center">
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
       <div class="tituloTransaccion"><div class="comboSucursal">
       <select name="sucursal" id="sucursal" style="width:170px;" onchange="getListaUsuarios()">
          <option value="" selected="selected">-- Seleccione Sucursal --</option>   
		   <?php
               $sql = "select s.idsucursal,left(s.nombrecomercial,25)as 'nombrecomercial' from sucursal s,
               configuracionsucursal cs where cs.idsucursal=s.idsucursal and s.estado=1;";
               $db->imprimirCombo($sql,$datoAlmacen['sucursal']);
           ?>     
      </select>
      </div> <div class="textoTituloTransaccion">Trabajadores Habilitados</div></div>
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
            <td><div id="textoConfiguracion" onclick="location.href='listar_atencion.php'" >Listar Atención</div></td>
            <td></td>
            <td></td>
            <td></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          </table>

         <div class="listaUsuario">
           <table width="94%" border="0" align="center">
           <tbody id="listaUser"></tbody>      
           </table>
         </div>
         
      </div>
    </td>
  </tr>
</table>

   
 </div>
 <div id="modal" class="modal"></div>
 <div id="modalInterior" class="modalInterior">
 <div class="headerInterior"><div class="tituloVentanaclave">Ingrese datos</div></div>
  
  <div class="posicionCloseSub" onclick="closeVentanaClave();"><img src="../iconos/borrar2.gif" width="12" height="12"></div>
  <br />
  <table width="100%" border="0">
  <tr>
    <td width="9%">&nbsp;</td>
    <td width="34%">&nbsp;</td>
    <td width="41%">&nbsp;</td>
    <td width="16%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right" class="textoClave">Contraseña:</td>
    <td><input type="password" name="clave" id="clave" onkeyup="eventoText(event)" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><input type="hidden" id="idtrabajador" name="idtrabajador"/></td>
    <td>&nbsp;</td>
    <td><div id="errorClave" class="errorClave"></div></td>
    <td>&nbsp;</td>
  </tr> 
</table>
  <div class="posboton1"><input type="button"  value="Ingresar" id="botonrestaurante" onclick="validarClave()" /></div>
  <div class="posboton2"><input type="button"  value="Salir" id="botonrestaurante" onclick="closeVentanaClave();"/></div>

</div>
</body>
</html>
