<?php
    include("../conexion.php");
    $db = new MySQL(); 
    $transaccion = "insertar";
    if (isset($_POST['idpersonal'])) {
        $sql = "update personalapoyo set estado=0 where idpersonalapoyo=$_POST[idpersonal]";	 
        $db->consulta($sql);
        header("Location: listar_personal.php");
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
 location.href = "nuevo_personalapoyo.php?nro="+codigo;	
}

var goEliminar = function(codigo){
  $$("idpersonal").value = codigo;	
  $$("modal_tituloCabecera").innerHTML = 'Advertencia';
  $$("modal_contenido").innerHTML = '¿Desea anular este Personal de Apoyo?';
  $$("modal_mensajes").style.visibility = "visible";
  $$("overlay").style.visibility = "visible";
}

var $$ = function(id){
 return document.getElementById(id);	
}

var closeMensaje = function(){
	$$("modal_mensajes").style.visibility = "hidden";
	$$("overlay").style.visibility = "hidden";    
}
	
var eliminarTransaccion = function(){
	$$("formulario").submit();
}
</script>


</head>

<body>

<div id="overlay" class="overlays"></div>

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
     <div id="opcion1" onclick="location.href='nuevo_configuracion.php'"><div id="textoOpcion">Configuracion</div></div>
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
       <div class="tituloTransaccion"><div class="textoTituloTransaccion">Listar Personal</div></div>
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
            <td><div id="textoConfiguracion" onclick="location.href='nuevo_personalapoyo.php'" >Nuevo Personal</div></td>
            <td></td>
            <td></td>
            <td></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          </table>
            
       <form id="formulario" name="formulario" method="post" action="listar_personal.php">
         
  <table width="100%" border="0">
  <tr>
    <td width="4%">&nbsp;</td>
    <td width="25%" align="right"></td>
    <td width="29%"></td>
    <td width="38%"></td>
    <td width="4%"><input type="hidden" id="idpersonal" name="idpersonal"  value=""/></td>
  </tr>
  <tr>
    <td height="186">   
    </td>
    <td colspan="3" valign="top">
    <div style="position:relative;overflow:auto;height:274px;border:1px solid #24160D;width:100%;margin:0 auto;">
    <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellpadding="0" cellspacing="0">
            <tr style=" background-image:url(../images/fondofinal.jpg);color:#FFF; ">
              <td width="38" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
               <td width="38" style="border-right:1px solid; border-right-color:#FFF;">&nbsp;</td>
              <th width="38" class="lateralDerecho">Nº</th>
              <th width="400" class="lateralDerecho">Nombre</th>
              <th width="150" class="lateralDerecho">Fecha Ing.</th>
              <th width="150" align="center" class="lateralDerecho">Honorario</th>
              <th width="138" align="center" >Cargo</th>
            </tr>
            <tbody id="detalleS1">
            <?php
			    $contadorCuentas = 0;
				$codigoCuentas = array();					
				$sql = "select idpersonalapoyo as 'id',left(concat(nombre,' ',apellido),20)as 'nombre'"
					.",date_format(fechaingreso,'%d/%m/%Y') as 'fecha',cargo,honorario from personalapoyo where estado=1 " 
					."order by idpersonalapoyo desc;";
				$dato = $db->consulta($sql);					
				$sql = "select *from configuracionrestaurante";
				$configuracion = $db->arrayConsulta($sql);
				$tipo = 'Apoyo' ;
				while ($data = mysql_fetch_array($dato)) {	   
				 $campo = $data['cargo'].$data['honorario'];      
				    echo "
					<tr>
					  <td align='center'>
					  <img src='../css/images/edit.gif' style='cursor:pointer' title='Modificar' onclick='goModificar($data[id])' /></td>
					  <td align='center'>
					  <img src='../css/images/borrar.gif' style='cursor:pointer' title='Anular' onclick='goEliminar($data[id])' /></td>
					  <td align='center'>$data[id]</td>
					  <td >$data[nombre]</td>
					  <td align='center'>$data[fecha]</td>
					  <td align='left'>".number_format($configuracion[$campo],2)."</td>
					  <td align='left'>$data[cargo]</td>
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