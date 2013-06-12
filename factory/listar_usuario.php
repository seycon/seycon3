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
<script type="text/javascript"  src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript"  src="evento.js"></script>
<script>

var goModificar = function(codigo){
 location.href = "nuevo_usuario.php?nro="+codigo;	
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
    <td width="94%" class="menuInterno" >CONFIGURACION</td>
  </tr>
  <tr>
    <td></td>
    <td class="menuInterno" onclick="location.href='nuevo_usuario.php'">Registro Usuario</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="menuInterno" onclick="location.href='listar_usuario.php'">Listar Usuario</td>
  </tr>
 <tr>
    <td>&nbsp;</td>
    <td class="menuInterno" onclick="location.href='nuevo_precios.php'">Registro Precios</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

    </div></td>
    <td width="81%">
    <div class="contenDataNew">
    <div class="contenPrincipalNew">

      <div class="contenido_detalle">      
        <div class="divForm">LISTA  DE USUARIO</div>
      
                 
             <form id="form_usuario" action="nuevo_usuario.php" method="post">
               <br />
              <div style="text-align: center; width:100%; height: 350px;">
                <div class="listado">
                  <table width="100%" border="0" id="tabla" style="margin-top:0px;" cellpadding="0" cellspacing="0" >
                    <tr style="background-image: url(../fondo.jpg); color:#FFF; ">
                      <td width="38" style="border-right:1px solid; border-right-color:#FFF;border-bottom:1px solid #666;">&nbsp;</td>
                      <th width="38" class="lateralDerecho">Nº</th>
                      <th width="250" class="lateralDerecho" >Nombre</th>
                      <th width="350" align="center" class="lateralDerecho">Apellido</th>
                      <th width="250" align="center" class="lateralDerecho">Cargo</th>
                      <th width="88" align="center" class="lateralDerecho">Tipo</th>
                    </tr>
                    <tbody id="detalleS1">
              <?php
			  $nro = 1;				
			  $sql = "select u.idusuario,u.tipo,left(t.nombre,20)as 'nombre',left(t.apellido,20)as 'apellido',c.cargo 
                      from  trabajador t,usuarioF u,cargo c where t.idtrabajador=u.idtrabajador and t.idcargo=c.idcargo and u.estado=1;";	 
			 
			
			 $dato = $db->consulta($sql);								
			while ($data = mysql_fetch_array($dato)){							
			if ($nro%2!=0) echo "<tr bgcolor='#EAEAEA'>"; else echo "<tr bgcolor='#FFFFFF'>";			         
			echo "
			  <td align='center'><img src='../css/images/edit.gif' style='cursor:pointer' title='Modificar' onclick='goModificar($data[idusuario])' /></td>
              <td >$nro</td>
			  <td >$data[nombre]</td>
              <td align='left'>$data[apellido]</td>
              <td align='left'>$data[cargo]</td>
			  <td align='center'>$data[tipo]</td>
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