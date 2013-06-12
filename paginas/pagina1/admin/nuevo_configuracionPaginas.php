<?php
    include("conexion.php");
    $db = new MySQL();
    session_start();
    
	/*if (!isset($_SESSION['userID'])) {
       header("Location: index.php");	
	}*/
	
	if (isset($_GET['pagina'])) {
	    $_SESSION['IDpagina'] = $_GET['pagina'];
	}
	
	
    function filtro($cadena)
    {
        return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);	
    }
	
	
 
    if (isset($_POST['transaccion'])) {  
	    $sql = array();	
        
		$sql[0] = "delete from contenido where idpagina=$_SESSION[IDpagina] and session!='imagen contenido 2' 
		 and session!='imagen contenido 1' and session!='imagen galeria 1' and session!='imagen galeria 2' 
		 and session!='imagen galeria 3' and session!='imagen galeria 4' and session!='imagen galeria 5' 
		 and session!='imagen lateral' ";
	    $sql[1] = "insert into contenido(session,contem,idpagina) values('titulo principal','$_POST[Ctitulop]',$_SESSION[IDpagina])";
		$sql[2] = "insert into contenido(session,contem,idpagina) values('titulo secundario','$_POST[Ctitulos]',$_SESSION[IDpagina])";
		
		$sql[3] = "insert into contenido(session,contem,idpagina) values
		('titulo contenido principal','$_POST[Otitulo1]',$_SESSION[IDpagina])";
		$sql[4] = "insert into contenido(session,contem,idpagina) values
		('contenido principal','$_POST[Ocontenido1]',$_SESSION[IDpagina])";
		$sql[5] = "insert into contenido(session,contem,idpagina) values
		('titulo contenido secundario','$_POST[Otitulo2]',$_SESSION[IDpagina])";
		$sql[6] = "insert into contenido(session,contem,idpagina) values
		('contenido secundario','$_POST[Ocontenido2]',$_SESSION[IDpagina])";
		$sql[7] = "insert into contenido(session,contem,idpagina) values
		('contenido lateral','$_POST[Ocontenido3]',$_SESSION[IDpagina])";
		$sql[8] = "insert into contenido(session,contem,idpagina) values
		('titulo contenido central 1','$_POST[Otitulo4]',$_SESSION[IDpagina])";
		$sql[9] = "insert into contenido(session,contem,idpagina) values
		('contenido central 1','$_POST[Ocontenido4]',$_SESSION[IDpagina])";
		$sql[10] = "insert into contenido(session,contem,idpagina) values
		('titulo contenido central 2','$_POST[Otitulo5]',$_SESSION[IDpagina])";
		$sql[11] = "insert into contenido(session,contem,idpagina) values
		('contenido central 2','$_POST[Ocontenido5]',$_SESSION[IDpagina])";
		$sql[12] = "insert into contenido(session,contem,idpagina) values
		('titulo contenido central 3','$_POST[Otitulo6]',$_SESSION[IDpagina])";
		$sql[13] = "insert into contenido(session,contem,idpagina) values
		('contenido central 3','$_POST[Ocontenido6]',$_SESSION[IDpagina])";
		
		
		
		$sql[14] = "insert into contenido(session,contem,idpagina) values
		('titulo pie session1','$_POST[Ptitulo1]',$_SESSION[IDpagina])";
		$sql[15] = "insert into contenido(session,contem,idpagina) values
		('contenido pie session1','$_POST[Pcontenido1]',$_SESSION[IDpagina])";
		$sql[16] = "insert into contenido(session,contem,idpagina) values
		('titulo pie session2','$_POST[Ptitulo2]',$_SESSION[IDpagina])";
		$sql[17] = "insert into contenido(session,contem,idpagina) values
		('contenido pie session2','$_POST[Pcontenido2]',$_SESSION[IDpagina])";
		$sql[18] = "insert into contenido(session,contem,idpagina) values
		('titulo pie session3','$_POST[Ptitulo3]',$_SESSION[IDpagina])";
		$sql[19] = "insert into contenido(session,contem,idpagina) values
		('contenido pie session3','$_POST[Pcontenido3]',$_SESSION[IDpagina])";
		$sql[20] = "insert into contenido(session,contem,idpagina) values
		('autor pagina','$_POST[Pautor]',$_SESSION[IDpagina])";
		
		$numConsulta = 20;
	   //imagenes Cabecera
	   $hora = time();	   
	   $nombre = $_FILES['Oimagen']['name'];
	   if ($nombre != "" ) {
	       copy($_FILES['Oimagen']['tmp_name'],"../file/$hora$nombre");		   
		   $sql[$numConsulta + 1] = "delete from contenido where session='imagen lateral' and idpagina=$_SESSION[IDpagina];";
		   $sql[$numConsulta + 2] = "insert into contenido(session,contem,idpagina) values
		   ('imagen lateral','file/$hora$nombre',$_SESSION[IDpagina])";
		   $numConsulta = $numConsulta + 2; 
	   }
	   
	   //imagenes Galeria
	   $hora = time();
	   $nombre = $_FILES['Gimagen1']['name'];
	   if ($nombre != "" ) {
	       copy($_FILES['Gimagen1']['tmp_name'],"../file/$hora$nombre");
		   $sql[$numConsulta + 1] = "delete from contenido where session='imagen galeria 1' and idpagina=$_SESSION[IDpagina];";
		   $sql[$numConsulta + 2] = "insert into contenido(session,contem,idpagina) values
		   ('imagen galeria 1','file/$hora$nombre',$_SESSION[IDpagina])";
		   $numConsulta = $numConsulta + 2;
	   }
	   $hora = time();
	   $nombre = $_FILES['Gimagen2']['name'];
	   if ($nombre != "" ) {
	       copy($_FILES['Gimagen2']['tmp_name'],"../file/$hora$nombre");
		   $sql[$numConsulta + 1] = "delete from contenido where session='imagen galeria 2' and idpagina=$_SESSION[IDpagina];";
		   $sql[$numConsulta + 2] = "insert into contenido(session,contem,idpagina) values
		   ('imagen galeria 2','file/$hora$nombre',$_SESSION[IDpagina])";
		   $numConsulta = $numConsulta + 2;
	   }
	   $hora = time();
	   $nombre = $_FILES['Gimagen3']['name'];
	   if ($nombre != "" ) {
	       copy($_FILES['Gimagen3']['tmp_name'],"../file/$hora$nombre");
		   $sql[$numConsulta + 1] = "delete from contenido where session='imagen galeria 3' and idpagina=$_SESSION[IDpagina];";
		   $sql[$numConsulta + 2] = "insert into contenido(session,contem,idpagina) values
		   ('imagen galeria 3','file/$hora$nombre',$_SESSION[IDpagina])";
		   $numConsulta = $numConsulta + 2;
	   }
	   $hora = time();
	   $nombre = $_FILES['Gimagen4']['name'];
	   if ($nombre != "" ) {
	       copy($_FILES['Gimagen4']['tmp_name'],"../file/$hora$nombre");
		   $sql[$numConsulta + 1] = "delete from contenido where session='imagen galeria 4' and idpagina=$_SESSION[IDpagina];";
		   $sql[$numConsulta + 2] = "insert into contenido(session,contem,idpagina) values
		   ('imagen galeria 4','file/$hora$nombre',$_SESSION[IDpagina])";
		   $numConsulta = $numConsulta + 2;
	   }
	   $hora = time();
	   $nombre = $_FILES['Gimagen5']['name'];
	   if ($nombre != "" ) {
	       copy($_FILES['Gimagen5']['tmp_name'],"../file/$hora$nombre");
		   $sql[$numConsulta + 1] = "delete from contenido where session='imagen galeria 5' and idpagina=$_SESSION[IDpagina];";
		   $sql[$numConsulta + 2] = "insert into contenido(session,contem,idpagina) values
		   ('imagen galeria 5','file/$hora$nombre',$_SESSION[IDpagina])";
		   $numConsulta = $numConsulta + 2;
	   }
	   
	   //imagenes contenido
	   $hora = time();
	   $nombre = $_FILES['Oimgen1']['name'];
	   if ($nombre != "" ) {
	       copy($_FILES['Oimgen1']['tmp_name'],"../file/$hora$nombre");
		   $sql[$numConsulta + 1] = "delete from contenido where session='imagen contenido 1' and idpagina=$_SESSION[IDpagina];";
		   $sql[$numConsulta + 2] = "insert into contenido(session,contem,idpagina) values
		   ('imagen contenido 1','file/$hora$nombre',$_SESSION[IDpagina])";
		   $numConsulta = $numConsulta + 2;
	   }
	   $hora = time();
	   $nombre = $_FILES['Oimagen2']['name'];
	   if ($nombre != "" ) {
	       copy($_FILES['Oimagen2']['tmp_name'],"../file/$hora$nombre");
		   $sql[$numConsulta + 1] = "delete from contenido where session='imagen contenido 2' and idpagina=$_SESSION[IDpagina];";
		   $sql[$numConsulta + 2] = "insert into contenido(session,contem,idpagina) values
		   ('imagen contenido 2','file/$hora$nombre',$_SESSION[IDpagina])";
		   $numConsulta = $numConsulta + 2;
	   }
	   
		
		foreach ( $sql as $consulta ) {
		  $db->consulta($consulta);	
		}
		

        header("Location: nuevo_configuracionPaginas.php?estadoT=v");
    }   
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Panel Administrativo</title>
<link rel="stylesheet" type="text/css" href="styles/style.css" />
<script>
var $$ = function(id) {
   return document.getElementById(id);	
}

var efectoClick = function(id, opcion){
	var ides = ['Mcabecera','Mgaleria','Mcontenido','Mpie']; 
	var idesO = ['Ocabecera','Ogaleria','Ocontenido','Opie']; 
	$$(id).style.display = "block"; 
	$$(opcion).style.background = "-moz-linear-gradient(top, rgba(76,76,76,1) 0%, rgba(89,89,89,1) 12%, rgba(102,102,102,1) 25%,"    +"rgba(71,71,71,1) 39%, rgba(44,44,44,1) 50%, rgba(0,0,0,1) 51%, rgba(17,17,17,1) 60%, rgba(43,43,43,1) "
	+"76%, rgba(28,28,28,1) 91%, rgba(19,19,19,1) 100%)";
	$$(opcion).style.background = "-webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(76,76,76,1)),"
	+" color-stop(12%,rgba(89,89,89,1)), color-stop(25%,rgba(102,102,102,1)), color-stop(39%,rgba(71,71,71,1)),"
	+" color-stop(50%,rgba(44,44,44,1)), color-stop(51%,rgba(0,0,0,1)), color-stop(60%,rgba(17,17,17,1)),"
	+" color-stop(76%,rgba(43,43,43,1)), color-stop(91%,rgba(28,28,28,1)), color-stop(100%,rgba(19,19,19,1)))";
	for (var j=0;j<ides.length;j++){
		if (ides[j] != id) {
		   $$(ides[j]).style.display = "none"; 
		   $$(idesO[j]).style.background = "-moz-linear-gradient(top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0) 100%)"; 
		}
	}
 }
</script>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.filestyle.js" type="text/javascript"></script>
<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
	
$(document).ready(function()
{	
	
	$("input[type=file]").filestyle({ 
     image: "img/file.png",
     imageheight : 21,
     imagewidth : 80,
     width : 130
   });
   
   
  });
	
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
   <div class="tituloSecundario">Gestión y Configuración de Paginas Web.    </div>
    <div class="tituloUsuario">Bienvenido<span style="color:#EFEFEF"> <?php echo $_SESSION['userName'];?> </span></div>
   
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
<div class="contem2">
   <div class="titleFrom">
      <div class="imagenFrom"><img  src="img/icon_dashboard_small.gif" width="26" height="26"/> </div>
      <div class="nombreFrom">Configuración de Pagina</div>
   </div>
   <div class="titleCaso2">
      
      <div id="Ocabecera" class="optionSubMenu" onclick="efectoClick('Mcabecera','Ocabecera')">Cabecera</div>
      <div id="Ogaleria" class="optionSubMenu2" onclick="efectoClick('Mgaleria','Ogaleria')">Galeria</div>
      <div id="Ocontenido" class="optionSubMenu3" onclick="efectoClick('Mcontenido','Ocontenido')">Contenido</div>
      <div id="Opie" class="optionSubMenu4" onclick="efectoClick('Mpie','Opie')">Pie</div>
      
   </div>
   <br />
   <div class="contemDataFrom2">
   <form id="formulario" name="formulario" method="post" action="nuevo_configuracionPaginas.php" enctype="multipart/form-data">   
   
   <div class="boton1M"><input type="submit" class="boton" value="Guardar"/></div>
   <div class="boton2M"><input type="reset" class="boton" value="Cancelar"/></div>
   
     <div id="Mcabecera" >
     <?php
	  $sql = "select * from contenido where session='titulo principal' and idpagina=$_SESSION[IDpagina]";
	  $tp = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='titulo secundario' and idpagina=$_SESSION[IDpagina]";
	  $ts = $db->arrayConsulta($sql);
	 ?>
     
        <table width="90%" border="0" align="center" >
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><input type="hidden" id="transaccion" name="transaccion" value="insertar"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="12%">&nbsp;</td>
          <td width="20%">&nbsp;</td>
          <td width="29%">&nbsp;</td>
          <td width="39%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Titulo Principal:</td>
          <td><input type="text" name="Ctitulop" id="Ctitulop" class="field" value="<?php echo $tp['contem']?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Titulo Secundario:</td>
          <td><input type="text" name="Ctitulos" id="Ctitulos" class="field" value="<?php echo $ts['contem']?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>

     </div>
      
     <div id="Mgaleria" class="contemMenu">
     
      <table width="90%" border="0" align="center">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        
        <tr>
          <td width="12%">&nbsp;</td>
          <td width="12%" align="right" class="textoSubContem">Imagen 1:</td>
          <td width="29%"><input type="file" name="Gimagen1" id="Gimagen1" class="file_1"/></td>
          <td width="47%"><div class="globo"><div class="globoleft"></div><div class="globomensaje">200 Ancho x 200 Alto</div></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Imagen 2:</td>
          <td><input type="file" name="Gimagen2" id="Gimagen2" class="file_1"/></td>
          <td><div class="globo"><div class="globoleft"></div><div class="globomensaje">200 Ancho x 200 Alto</div></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Imagen 3:</td>
          <td><input type="file" name="Gimagen3" id="Gimagen3" class="file_1"/></td>
          <td><div class="globo"><div class="globoleft"></div><div class="globomensaje">200 Ancho x 200 Alto</div></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Imagen 4:</td>
          <td><input type="file" name="Gimagen4" id="Gimagen4" class="file_1"/></td>
          <td><div class="globo"><div class="globoleft"></div><div class="globomensaje">200 Ancho x 200 Alto</div></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Imagen 5:</td>
          <td><input type="file" name="Gimagen5" id="Gimagen5" class="file_1"/></td>
          <td><div class="globo"><div class="globoleft"></div><div class="globomensaje">200 Ancho x 200 Alto</div></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
     </div>
     
     <div id="Mcontenido" class="contemMenu">
      <?php
	  $sql = "select * from contenido where session='titulo contenido principal' and idpagina=$_SESSION[IDpagina]";
	  $tc = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido principal' and idpagina=$_SESSION[IDpagina]";
	  $cont = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo contenido secundario' and idpagina=$_SESSION[IDpagina]";
	  $tcs = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido secundario' and idpagina=$_SESSION[IDpagina]";
	  $conts = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo contenido lateral' and idpagina=$_SESSION[IDpagina]";
	  $tcl = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido lateral' and idpagina=$_SESSION[IDpagina]";
	  $contl = $db->arrayConsulta($sql);
	  
	  
	  $sql = "select * from contenido where session='titulo contenido central 1' and idpagina=$_SESSION[IDpagina]";
	  $tcs1 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido central 1' and idpagina=$_SESSION[IDpagina]";
	  $conts1 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='titulo contenido central 2' and idpagina=$_SESSION[IDpagina]";
	  $tcs2 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido central 2' and idpagina=$_SESSION[IDpagina]";
	  $conts2 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='titulo contenido central 3' and idpagina=$_SESSION[IDpagina]";
	  $tcs3 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido central 3' and idpagina=$_SESSION[IDpagina]";
	  $conts3 = $db->arrayConsulta($sql);
	 ?>
     <table width="100%" border="0" align="center">
        <tr>
          <td>&nbsp;</td>
          <td width="12%">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="textoTitulo">Contenido Central 1</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="textoTitulo" align="right"><span class="textoSubContem">Titulo:</span></td>
          <td colspan="2" class="textoTitulo"><input type="text" name="Otitulo4" id="Otitulo4" class="field" value="<?php echo $tcs1['contem'];?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="textoTitulo" align="right" valign="top"><span class="textoSubContem">Contenido:</span></td>
          <td colspan="2" class="textoTitulo">
          <textarea id="Ocontenido4" name="Ocontenido4" style="width:500px;height:200px;">
		  <?php echo $conts1['contem'];?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="textoTitulo">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="textoTitulo">Contenido Central 2</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="textoTitulo" align="right"><span class="textoSubContem">Titulo:</span></td>
          <td colspan="2" class="textoTitulo"><input type="text" name="Otitulo5" id="Otitulo5" class="field" value="<?php echo $tcs2['contem'];?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="textoTitulo" align="right" valign="top"><span class="textoSubContem" >Contenido:</span></td>
          <td colspan="2" class="textoTitulo">
          <textarea id="Ocontenido5" name="Ocontenido5" style="width:500px;height:200px;">
		  <?php echo $conts2['contem'];?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="textoTitulo">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="textoTitulo">Contenido Central 3          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="textoTitulo" align="right"><span class="textoSubContem">Titulo:</span></td>
          <td colspan="2" class="textoTitulo"><input type="text" name="Otitulo6" id="Otitulo6" class="field" value="<?php echo $tcs3['contem'];?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="textoTitulo" align="right" valign="top"><span class="textoSubContem">Contenido:</span></td>
          <td colspan="2" class="textoTitulo">
          <textarea id="Ocontenido6" name="Ocontenido6" style="width:500px;height:200px;">
		  <?php echo $conts3['contem'];?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="textoTitulo">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="textoTitulo">Contenido Principal</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="5%"></td>
          <td colspan="3" align="right"><hr /></td>
          <td width="15%"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Titulo:</td>
          <td colspan="2"><input type="text" name="Otitulo1" id="Otitulo1" class="field" value="<?php echo $tc['contem'];?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Imagen:</td>
          <td><input type="file" name="Oimgen1" id="Oimgen1" class="file_1"/></td>
          <td><div class="globo"><div class="globoleft"></div><div class="globomensaje">200 Ancho x 200 Alto</div></td>
          <td></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" valign="top" class="textoSubContem">Contenido:</td>
          <td colspan="2"><textarea id="Ocontenido1" name="Ocontenido1" style="width:500px;height:200px;">
		  <?php echo $cont['contem'];?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="textoTitulo">Contenido Secundario</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td></td>
          <td colspan="3"><hr /></td>
          <td></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Titulo:</td>
          <td colspan="2"><input type="text" name="Otitulo2" id="Otitulo2" class="field" value="<?php echo $tcs['contem'];?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Imagen:</td>
          <td width="26%"><input type="file" name="Oimagen2" id="Oimagen2" class="file_1"/></td>
          <td width="42%" align="left"><div class="globo"><div class="globoleft"></div><div class="globomensaje">200 Ancho x 200 Alto</div></td>
          <td ></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" valign="top" class="textoSubContem">Contenido:</td>
          <td colspan="2"><textarea id="Ocontenido2" name="Ocontenido2" style="width:500px;height:200px;">
		  <?php echo $conts['contem'];?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3" class="textoTitulo">Contenido Lateral</td>
        </tr>
        <tr>
          <td></td>
          <td colspan="3" align="right"><hr /></td>
          <td></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Imagen:</td>
          <td><input type="file" name="Oimagen" id="Oimagen" class="file_1"/></td>
          <td><div class="globo"><div class="globoleft"></div><div class="globomensaje">60 Ancho x 50 Alto</div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" valign="top" class="textoSubContem">Contenido:</td>
          <td colspan="2"><textarea id="Ocontenido3" name="Ocontenido3" style="width:500px;height:200px;">
          <?php echo $contl['contem'];?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td></td>
          <td colspan="3" align="right">&nbsp;</td>
          <td></td>
        </tr>
      </table>
     </div>
     
     <div id="Mpie" class="contemMenu">
     <?php
	  $sql = "select * from contenido where session='titulo pie session1' and idpagina=$_SESSION[IDpagina]";
	  $tp1 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido pie session1' and idpagina=$_SESSION[IDpagina]";
	  $cont1 = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo pie session2' and idpagina=$_SESSION[IDpagina]";
	  $tp2 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido pie session2' and idpagina=$_SESSION[IDpagina]";
	  $cont2 = $db->arrayConsulta($sql);
	  
	  $sql = "select * from contenido where session='titulo pie session3' and idpagina=$_SESSION[IDpagina]";
	  $tp3 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='contenido pie session3' and idpagina=$_SESSION[IDpagina]";
	  $cont3 = $db->arrayConsulta($sql);
	  $sql = "select * from contenido where session='autor pagina' and idpagina=$_SESSION[IDpagina]";
	  $at = $db->arrayConsulta($sql);
	 ?>
        <table width="100%" border="0" align="center">
        <tr>
          <td>&nbsp;</td>
          <td width="13%">&nbsp;</td>
          <td width="65%">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="textoTitulo">Sesión 1</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="6%"></td>
          <td colspan="2" align="right"><hr /></td>
          <td width="16%"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Titulo:</td>
          <td><input type="text" name="Ptitulo1" id="Ptitulo1" class="field" value="<?php echo $tp1['contem'];?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" valign="top" class="textoSubContem">Contenido:</td>
          <td><textarea id="Pcontenido1" name="Pcontenido1" style="width:500px;height:200px;">
          <?php echo $cont1['contem'];?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="textoTitulo">Sesión 2</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td></td>
          <td colspan="2"><hr /></td>
          <td></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Titulo:</td>
          <td><input type="text" name="Ptitulo2" id="Ptitulo2" class="field" value="<?php echo $tp2['contem'];?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" valign="top" class="textoSubContem">Contenido:</td>
          <td><textarea id="Pcontenido2" name="Pcontenido2" style="width:500px;height:200px;">
          <?php echo $cont2['contem'];?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" class="textoTitulo">Sesión 3</td>
        </tr>
        <tr>
          <td></td>
          <td colspan="2" align="right"><hr /></td>
          <td></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" class="textoSubContem">Titulo:</td>
          <td><input type="text" name="Ptitulo3" id="Ptitulo3" class="field" value="<?php echo $tp3['contem'];?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right" valign="top" class="textoSubContem">Contenido:</td>
          <td><textarea id="Pcontenido3" name="Pcontenido3" style="width:500px;height:200px;"><?php echo $cont3['contem'];?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td></td>
          <td colspan="2" align="right">&nbsp;</td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td align="right" class="textoSubContem">Autor:</td>
          <td align="left"><input type="text" name="Pautor" id="Pautor" class="field" value="<?php echo $at['contem'];?>"/></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td colspan="2" align="right">&nbsp;</td>
          <td></td>
        </tr>
      </table>

     </div>
     
     
     
        

   </form>
   </div>
   
   
</div>

<br />
<div class="pie2">
  <div class="acoplePie">
    <div class="autor">Copyright © Consultora Guez – Diseñado y Desarrollado</div>
  </div>  
</div>

</body>
</html>