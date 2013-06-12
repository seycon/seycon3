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
    <td width="94%" class="menuInterno" onclick="setNuevaAtencion();">Adicionar Mesa</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td ></td>
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
          <div class="divForm">ATENCION DE MESAS</div>
          
          <div class="contenedorMesasAtencion">    
          <table width="100%" border="0" align="center">
          <tbody id="mesasAtencion">
          <?php
            $sql = "select *from atencionF where idusuario='$_SESSION[idusuarioF]' and estado='atencion'";
            $mesas = $db->consulta($sql);
            $i = 0;
            $cadena = "";
             while ($data = mysql_fetch_array($mesas)){
              $cadena = $cadena."<td width='14%'><div id='opcionMesa' onclick='setNroPedido($data[idatencion])'><div id='textoMesa'>Mesa #$data[nromesa]</div></div></td>";	
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
    
    
</div>
</body>
</html>