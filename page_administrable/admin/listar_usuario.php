<?php
    include("conexion.php");
    $db = new MySQL();  
	session_start();
	
    if (!isset($_SESSION['userID'])) {
        header("Location: index.php");	
	}	
	
    if (isset($_POST['idusuario'])) {
        $sql = "update usuario set estado=0 where idusuario=$_POST[idusuario]";	 
        $db->consulta($sql);
        header("Location: listar_usuario.php");
    }
	
	function insertarFila($nro, $nombre, $login, $id) 
	{
	  $estilo = "";	
	  if ( ($nro % 2 ) == 0) {
		$estilo = "background-color:#ECECEC;";  
	  } 
	  
	  echo "<tr>
	  <td  class='filaTableI' style='".$estilo."' align='center'>$nro</td>
	  <td  class='filaTable' style='".$estilo."'>$nombre</td>
	  <td  class='filaTable' style='".$estilo."'>$login</td>
	  <td  class='filaTable' style='".$estilo."' align='center'><div  class='optionref'>  
	  <img src='img/user_edit.png'  title='Modificar' border='0' onclick='goModificar($id)'/></div></td>
	  <td  class='filaTableF' style='".$estilo."' align='center'><div  class='optionref'>
	  <img src='img/trash.png' alt='Eliminar' title='Eliminar' border='0' onclick='goEliminar($id)'  class='optionref'/>
	  </div></td>
	</tr>";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Panel Administrativo</title>
<link rel="stylesheet" type="text/css" href="styles/style.css" />

<script>
    var goModificar = function(codigo){
	 location.href = "nuevo_usuario.php?nro="+codigo;	
	}
	
	var goEliminar = function(codigo){
	  $$("idusuario").value = codigo;		 	
	  $$("modal_tituloCabecera").innerHTML = 'Advertencia';
	  $$("modal_contenido").innerHTML = '¿Desea anular este Usuario?';
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

<div class="head"> 
<div class="acopleCabecera">
  <div class="logeo">
  <a href="cerrar.php">
    <div class="imagenLogeo"></div>
    <div class="textoLogeo">Salir</div>
  </a>
  </div>  

   <div class="tituloPrincipal">Panel Administrativo</div>
   <div class="tituloSecundario">Gestión y Configuración de Paginas Web.</div>
    <div class="tituloUsuario">Bienvenido<span style="color:#666"> <?php echo $_SESSION['userName'];?> </span></div>
   
   <div class="panelOpciones">
     <div class="opcionesPanel">            
            <div class="opcion1"><a href="admingeneral.php">
            <div class="icono"><img src="img/icon_dashboard.png" width="48" height="48"/></div>
            <div class="titulo">Inicio</div> </a> </div>
            <div class="opcion2"><a href="nuevo_usuario.php">
            <div class="icono"><img src="img/icon_users.png" width="48" height="48"/></div>
            <div class="titulo">Usuario</div></a>  </div>            
     </div>
   </div>
   </div>
 </div>
 
 <div id="overlay" class="overlays"></div>

<div id="modal_mensajes" class="modal_mensajes">
  <div class="modal_cabecera">
     <div id="modal_tituloCabecera" class="modal_tituloCabecera">Advertencia</div>
     <div class="modal_cerrar"><img src="img/borrar2.gif" width="12" height="12" style="cursor:pointer" onclick="closeMensaje()"></div>
  </div>
  <div class="modal_icono_modal"><img src="img/alerta.png" width="24" height="24"></div>
  <div id="modal_contenido" class="modal_contenido">No Existen Pagos Realizados</div>
  <div class="modal_boton2"><input type="button" value="Aceptar" class="boton_modal" onclick="eliminarTransaccion()"/></div>
  <div class="modal_boton1"><input type="button" value="Cancelar" class="boton_modal" onclick="closeMensaje()"/></div>
</div>
 
 
<div class="contem">
   <div class="titleFrom">
      <div class="imagenFrom"><img  src="img/icon_dashboard_small.gif" width="26" height="26"/> </div>
      <div class="nombreFrom">Usuario</div>
   </div>
   <div class="titleCaso">
      
      <div class="optionSubMenu" onclick="location.href='nuevo_usuario.php'">Nuevo Usuario</div>
      <div class="optionSubMenu2" onclick="location.href='listar_usuario.php'">Listar Usuario</div>
      
   </div>
   <form id="formulario" name="formulario" method="post" action="listar_usuario.php">
   <div class="contemDataFrom">
      <br />
      <input type="hidden" id="idusuario" name="idusuario" value="" />
      <table border="0" width="80%" cellpadding="0" cellspacing="0" align="center">
          <tr>
              <th width="75"   class="table-header-check">Nº</th>
              <th width="252"  class="table-header-checkI">Nombre</th>
              <th width="242"  class="table-header-checkI">Login</th>
              <th width="151"  class="table-header-checkF" colspan="2">Opciones</th>
          </tr>
          <?php								
          $sql = "select * from usuario where estado=1";	
          $dato = $db->consulta($sql);
          $nro = 0;
          while ($data = mysql_fetch_array($dato)) {
              $nro++;
              insertarFila($nro, $data['nombre'], $data['login'], $data['idusuario'] );
          }
          ?>               
      </table>   
   </div>
   </form>
   
</div>


<div class="pie">
    <div class="acoplePie">
    <div class="autor">Copyright © Consultora Guez – Diseñado y Desarrollado</div>
    </div>
</div>


</body>
</html>