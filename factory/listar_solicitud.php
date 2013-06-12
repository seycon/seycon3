<?php
session_start(); 
include("../conexion.php");
$db =new MySQL();

 if (!isset($_SESSION['idusuarioF'])){
  header("Location: index.php");	
 }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Factory</title>
<link rel="stylesheet" href="estilo_principal.css" type="text/css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<script type="text/javascript"  src="evento.js"></script>
<script>
var goModificar = function(codigo){
 location.href = "nuevo_solicitud.php?nro="+codigo;	
}

var goReporte = function(codigo){
 window.open('imprimir_SolitudProductos.php?idsolicitud='+codigo+'&logo=true','target:_blank');		
 location.href = "listar_solicitud.php";
}
</script>

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
    <td width="6%"></td>
    <td width="94%" class="menuInterno" onclick="location.href='nuevo_solicitud.php'">Nuevo Registro</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="menuInterno" onclick="location.href='listar_solicitud.php'">Listar Registro</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="menuInterno">Referencia de Solicitud</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td ><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="32%"><div class='estadoLLegada'></div></td>
        <td width="68%" class="textoUserNew">&nbsp;&nbsp; En Espera</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td></td>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="32%"><div class='estadoAtendido'></div></td>
        <td width="68%" class="textoUserNew">&nbsp;&nbsp; Atendido</td>
      </tr>
    </table></td>
  </tr>
  
</table>

    </div></td>
    <td width="81%">
    <div class="contenDataNew">
    <div class="contenPrincipalNew">

      <div class="contenido_detalle">      
        <div class="divForm">LISTA DE SOLICITUDES</div>
      
                 
             <form id="form_usuario" action="nuevo_usuario.php" method="post">
               <br />
              <div style="text-align: center; width:100%; height: 350px;">
                <div class="listado">
                  <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellpadding="0" cellspacing="0" >
                    <tr style="background-image: url(../fondo.jpg); color:#FFF; ">
                      <td width="38" style="border-right:1px solid; border-right-color:#FFF;border-bottom:1px solid #666;">&nbsp;</td>
                      <th width="38" class="lateralDerecho"></th>
                      <th width="38" class="lateralDerecho"></th>
                      <th width="38" class="lateralDerecho">Nº</th>
                      <th width="88" class="lateralDerecho" >Fecha</th>
                      <th width="250" align="center" class="lateralDerecho">Almacen</th>
                      <th width="300" align="center" class="lateralDerecho">Glosa</th>
                      <th width="300" align="center" class="lateralDerecho">Responsable</th>
                    </tr>
                    <tbody id="detalleS1">
              <?php
			  $nro = 1;				
			  $sql = "select s.estadoatencion,s.idsolicitud,s.fecha,a.nombre,left(s.detalle,20)as 'detalle',left(concat(t.nombre,' ',t.apellido),20) as 'responsable' 
               from solicitudF s,usuarioF u,trabajador t,almacen a where s.idusuario=u.idusuario and u.idtrabajador=t.idtrabajador 
			   and s.idalmacen=a.idalmacen and s.estado=1 order by s.idsolicitud desc;";				
			  $dato = $db->consulta($sql);								
			  while ($data = mysql_fetch_array($dato)){							
			  if ($nro%2!=0) echo "<tr bgcolor='#EAEAEA'>"; else echo "<tr bgcolor='#FFFFFF'>";	
			   if ($data['estadoatencion'] == "Atencion"){
				 $clase = "estadoAtendido"; 
			   }else{
   			     $clase = "estadoLLegada";   
			   }
			  
			  		         
			  echo "
			    <td ><div class=$clase></div></td>
				<td align='center'><img src='../css/images/edit.gif' style='cursor:pointer' title='Modificar' onclick='goModificar($data[idsolicitud])' /></td>
				<td align='center'><img src='../css/images/imprimir.gif' style='cursor:pointer' title='Modificar' onclick='goReporte($data[idsolicitud])' /></td>
				<td >$data[idsolicitud]</td>
				<td >".$db->GetFormatofecha($data['fecha'],'-')."</td>
				<td align='left'>$data[nombre]</td>
				<td align='left'>$data[detalle]</td>
				<td align='center'>$data[responsable]</td>
			  </tr>
			  ";
			  $nro++;	
			  }         
		      ?>
                    </tbody>
                  </table>
                </div>
              </div>            
                  
              
              </form>
          
                  
    
    </div>
     
    </div>
    
    </td>
  </tr>
</table>  
  </div>
  <div class="pie">© 2012 Consultora Guez. All rights reserved.</div>    
</div>
</body>
</html>