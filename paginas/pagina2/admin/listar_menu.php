<?php
    include("conexion.php");
    $db = new MySQL();  
	session_start();
	
	if (isset($_GET['plantilla'])) {
	    $_SESSION['IDplantilla'] = $_GET['plantilla'];
	}
	
	
    if (!isset($_SESSION['userID'])) {
        header("Location: index.php");	
	}	
	
    if (isset($_POST['idpagina'])) {
		$sql = "select *  from pagina where idpagina=$_POST[idpagina]";
		$datoPagina = $db->arrayConsulta($sql);
        $sql = "update pagina set estado=0 where idpagina=$_POST[idpagina]";	 
        $db->consulta($sql);
		$sql = "update pagina set numero=numero-1 where  idplantilla=$_SESSION[IDplantilla] and numero>$datoPagina[numero]";
		$db->consulta($sql);
        header("Location: listar_menu.php");
    }
	
	function insertarFila($nro, $nombre, $login, $id) 
	{
	  $estilo = "";	
	  if ( ($nro % 2 ) == 0) {
		$estilo = "background-color:#ECECEC;";  
	  } 
	  if ($nro == 1) {
		echo "<tr>
		<td  class='filaTableI' style='".$estilo."' align='center'>$nro</td>
		<td  class='filaTable' style='".$estilo."'>$nombre</td>
		<td  class='filaTable' style='".$estilo."'>$login</td>
		<td  class='filaTable' style='".$estilo."' align='center'>&nbsp;</td>
		<td  class='filaTable' style='".$estilo."' align='center'>&nbsp;</td>
		 <td  class='filaTableF' style='".$estilo."' align='center'><div  class='optionref'>
		<img src='img/photo.png' alt='Editar' title='Editar' border='0' onclick='goConfigurar($id)'  class='optionref'/>
		</div></td>
		</tr>";  
		  
	  } else {
		echo "<tr>
		<td  class='filaTableI' style='".$estilo."' align='center'>$nro</td>
		<td  class='filaTable' style='".$estilo."'>$nombre</td>
		<td  class='filaTable' style='".$estilo."'>$login</td>
		<td  class='filaTable' style='".$estilo."' align='center'><div  class='optionref'>  
		<img src='img/user_edit.png'  title='Modificar' border='0' onclick='goModificar($id)'/></div></td>
		<td  class='filaTable' style='".$estilo."' align='center'><div  class='optionref'>
		<img src='img/trash.png' alt='Eliminar' title='Eliminar' border='0' onclick='goEliminar($id)'  class='optionref'/>
		</div></td>
		 <td  class='filaTableF' style='".$estilo."' align='center'><div  class='optionref'>
		<img src='img/photo.png' alt='Editar' title='Editar' border='0' onclick='goConfigurar($id)'  class='optionref'/>
		</div></td>
		</tr>";
	  }
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
	 location.href = "nuevo_menu.php?nro="+codigo;	
	}
	
	var goEliminar = function(codigo){
	  $$("idpagina").value = codigo;		 	
	  $$("modal_tituloCabecera").innerHTML = 'Advertencia';
	  $$("modal_contenido").innerHTML = '¿Desea anular este Menu?';
	  $$("modal_mensajes").style.visibility = "visible";
      $$("overlay").style.visibility = "visible";
	}
	
	var goConfigurar = function(codigo) {
		  location.href = "nuevo_configuracionPage.php?pagina="+codigo;	
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
      <div class="nombreFrom">Menu de la plantilla</div>
   </div>
   <div class="titleCaso">
      
      <div class="optionSubMenu" style="background-color:#666;

      "><a href="listar_menu.php" class="link"> Listar Menú </a></div>
      <div class="optionSubMenu2"><a href="nuevo_menu.php" class="link"> Nuevo Menú</a></div>
      <div class="optionSubMenu3" style="left:270px;"><a href="nuevo_estilo.php" class="link"> Color de pagina</a></div>
      <div class="optionSubMenu4" style="left:405px;"><a href="nuevo_compartido.php" class="link"> Compartido</a></div>
      <div class="optionSubMenu4" style="left:540px;"><a href="listar_compartido.php" class="link">Listar Compartido</a></div>
   </div>
   <form id="formulario" name="formulario" method="post" action="listar_menu.php">
   <div class="contemDataFrom">
      <br />
      <input type="hidden" id="idplantilla" name="idplantilla" value="<?php echo $_SESSION['IDplantilla']; ?>" />
      <input type="hidden" id="idpagina" name="idpagina" value="" />
      <table border="0" width="80%" cellpadding="0" cellspacing="0" align="center">
          <tr>
              <th width="75"   class="table-header-check">Nº</th>
              <th width="252"  class="table-header-checkI">Nombre</th>
              <th width="242"  class="table-header-checkI">Url</th>
              <th width="151"  class="table-header-checkF" colspan="3">Opciones</th>
          </tr>
          <?php								
          $sql = "select * from pagina where estado=1 and idplantilla='$_SESSION[IDplantilla]' order by numero";	
          $dato = $db->consulta($sql);
          $nro = 0;
          while ($data = mysql_fetch_array($dato)) {
              $nro++;
              insertarFila($nro, $data['textomenu'], $data['url'], $data['idpagina'] );
          }
          ?>               
      </table>   
   </div>
   </form>
   
</div>


<div class="pie">
    <div class="acoplePie">
    <div class="autor">
    Copyright © Consultora Guez – Diseñado y Desarrollado</div>
    </div>
</div>


</body>
</html>