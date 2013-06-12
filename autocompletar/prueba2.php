<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema Empresarial y Contable</title>
<link rel="shortcut icon" href="favicon.ico">
<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<style>
 .forma{
   position:relative;
   color:#E2E2E2;
   width:120px;
   height:30px; 
 }

</style>

<script type="text/javascript">
  var $$ = function(id){
    return document.getElementById(id);	
  }
  
  String.prototype.trim = function(){ 
   return this.replace(/^\s+|\s+$/g,'') 
  }
  
  var recortar = function(){
    var a = new String(" marco");
	a.trim();
	alert(a);  
  }
 
 var validar = function(){
	valor = $$("numero").value; 
	if( isNaN(valor) ) {
      alert("Mal Formado el numero");
	  return;
    } 
	alert("Bien Formado");	
	alert(parseFloat(valor));
 }
 
 var obtenerPrecio = function(){
	var precios = Array(); 
	precios['1']={ '<39' : { 'Grande':8, 'Mini':2} };	
	//precios = $$("preciosFactory").value;
	alert(precios['1']['<39']['Grande'] );
	//alert(precios['1']['<39']['grande']);	 
 }
  

 var Calculadora = function(){
	 this.sumar = function(a,b){
	  return a + b;	
	 }	
	 
	 this.restar = function(a,b){		
	  return a - b;	
	}
	 
	 this.multiplicar = function(a,b){
	  return a * b;	
	}
 }

var calcular = function(){	

 Calculadora.prototype.dividir = function(a,b){
   return a/b;	 
 }

alert("Hola");
 var c = new Calculadora();
 alert(c.dividir(6,2));
}




 function email(value) {
			// contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
			return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
		}
		
		
		  function valida() {
			 valor = $$("entrada").value; 
			 alert(email(valor));
if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3,4})+$/.test(valor)){
alert("La dirección de email " + valor + " es correcta.");
} else {
alert("La dirección de email es incorrecta.");
}
}
			
		
		
		function verificar(){
 numero=1233.647454;
 alert(numero.toFixed(4));
}

		
		</script>
	
<style>
  .overlays{
	position:fixed; 
	top:0px; 
	left:0px; 
	width: 100%; 
	height: 100%; 
	z-index:3009; 
	background-color: #000;
	opacity:.50;
	-moz-opacity: 0.50;
	filter: alpha(opacity=50);
	visibility:hidden;
  }

  .gif{
	position:fixed;
	top:45%;
	left:40%;  
	width:75px;
	height:75px; 
	background-image:url(cargando.gif);  
	z-index:4002;   
	visibility:hidden;
  }

</style>
    	
<body>
<div id="overlay" class="overlays"></div>
<div class="gif"></div>
<script> 
//recortar();

</script>
<?php 
include("../conexion.php");
$db = new MySQL();

//$sql = "select a.idatencion, al.sucursal from atencion a, detalleatencion d,detallerequerimiento dr,detalleingresoproducto di,
//ingresoproducto i,almacen al
// where a.idatencion=d.idatencion and dr.iddetalleatencion=d.iddetalleatencion and 
//dr.iddetalleingreso=di.iddetalleingreso and di.idingresoprod=i.idingresoprod and
//i.idalmacen=al.idalmacen group by a.idatencion";
//
//$dato = $db->consulta($sql);
//
//while ($data = mysql_fetch_array($dato)) {
//  $sql = "update atencion set idsucursal=$data[sucursal] where idatencion=$data[idatencion]";
//  $db->consulta($sql);	
//}


/*for ($i = 1;$i <= 30; $i++){
	$fecha = "2012-11-$i";
    $sql = "insert into fechaasistencia values(null,'$fecha')";	
	$db->consulta($sql);
}

total = getCantidades("35.5000", 2);

echo $total[0]."---";


echo $total[1];

function getCantidades($cantidad, $conversion)
{
    $total = array(0,0);
    if ($cantidad != "") {
	  $dato = explode(".",$cantidad);
	  $total[0] = $dato[0];
	  $cant = "0.".$dato[1];
	  $total[1] = (float) $cant * $conversion;
    }
    return $total;	
}*/


/*$dato = md5("admin");
$dato1 = crc32($dato);
$dato2 = crypt($dato1, "xmas");
$dato3 = sha1("xmas".$dato2);
echo $dato3;*/


// $cantidadPedida = (int) 3.025;
 
 //echo $cantidadPedida;
	 
/*	 $sql = "select p.idproducto,p.nombre,d.cantidad,d.unidadmedida 
      from detallecombinacion d,producto p 
      where p.idproducto=d.idproducto and 
      d.idcombinacion=15;";
	  $consulta = $db->consulta($sql);
	  while ($data = mysql_fetch_array($consulta)){
		  $UMCombinacion = $data['unidadmedida'];
		  $cantidadSalida = $data['cantidad'] * $cantidadPedida;
		  $sql = "select p.nombre,p.unidaddemedida,p.conversiones,d.unidadmedida as 'UMingreso',d.cantidadactual,d.iddetalleingreso
          from detalleingresoproducto d,producto p where 
          p.idproducto=d.idproducto 
          and d.cantidadactual>0 
          and p.idproducto=$data[idproducto];";
		  $producto = $db->consulta($sql);
		    while($dato = mysql_fetch_array($producto)){
				echo "UMC. $UMCombinacion"."</br>";
				echo "UMI. $dato[UMingreso]"."</br>";
				echo "UMI. $dato[unidaddemedida]"."</br>";
				if ($UMCombinacion != $dato['UMingreso']){
					if ($UMCombinacion == $dato['unidadmedida']){
					   $cantidadSalida = $cantidadSalida * $dato['conversiones'];						
					}else{
					   $cantidadSalida = $cantidadSalida / $dato['conversiones'];
					}
					$UMCombinacion = $dato['UMingreso'];
				}
				
				if ($cantidadSalida > $dato['cantidadactual']){
				  $cantidadDescuento = $dato['cantidadactual'];
				}else{
				  $cantidadDescuento = $cantidadSalida;	
				}
				
				$sql = "update detalleingresoproducto set cantidadactual=cantidadactual-$cantidadDescuento 
				where iddetalleingreso=$dato[iddetalleingreso]";
				//$db->consulta($sql);
				
				$cantidadSalida = $cantidadSalida - $cantidadDescuento;
				if ($cantidadSalida<=0)
				 break;
			}
		  
	  }*/



/*include('../conexion.php');
$db = new MySQL();
$sql = "select p.nombre,p.url from privilegiosF p,detalleprivilegioF d where d.idprivilegios=p.idprivilegios 
and d.idusuario=1 order by p.idprivilegios;";


$preciosFactory = array();

for ($i=1;$i<=6;$i++){
	 $sql = "select *from preciosF where idcupcakes=$i;";
	 $dato = $db->consulta($sql);
	 $fila = array('1'=>0,'2'=>0,'3'=>0,'4'=>0);
	 $subPrecio = array();
	 while ($data = mysql_fetch_array($dato)){
		 if ($data['rango'] == "1-39" and $data['tipo'] == "Grandes")
		  $fila['1'] = $data['monto'];
		  if ($data['rango'] == "1-39" and $data['tipo'] == "Mini")
		  $fila['2'] = $data['monto'];
		  if ($data['rango'] == "40-100" and $data['tipo'] == "Grandes")
		  $fila['3'] = $data['monto'];
		  if ($data['rango'] == "40-100" and $data['tipo'] == "Mini")
		  $fila['4'] = $data['monto'];
	 }	 
	 $subPrecio['<39'] = array('grande'=>$fila['1'] ,'mini'=>$fila['2']);
	 $subPrecio['>39'] = array('grande'=>$fila['3'] ,'mini'=>$fila['4']);
	 $preciosFactory[$i] = $subPrecio;
}



print_r($preciosFactory['1']['<39']['grande']);
	 			  
				


$menuFactory = array();
$i = 0;
$consulta = $db->consulta($sql);
while ($data = mysql_fetch_array($consulta)){
  $menu = array();	
  $menu['titulo'] = $data['nombre'];
  $menu['url'] = $data['url'];	
  $menuFactory[$i] = $menu;
  $i++;
}

print_r($menuFactory);

generarMenu($menuFactory);

function generarMenu($menu){
  for ($i=0;$i<count($menu);$i++){
	echo "Menu : ".$menu[$i]['titulo'].",Link:".$menu[$i]['url']."<br />";  
  }
}

echo sprintf("select *from usuario where user='%s'", mysql_real_escape_string("' and '0'='0"));



$estructura = array('Administracion'=>'','Inventario'=>'','Recursos'=>'','Activo'=>'','Ventas'=>'','Contabilidad'=>'','Agenda'=>'');
	//$sql = "SELECT a.* FROM accion a ORDER BY a.idaccion;";
	$sql = "SELECT a.* FROM usuario u,detalleaccion d,accion a where 
    d.idaccion=a.idaccion and d.idgrupo=u.idgrupousuario and idusuario=13 ORDER BY a.idaccion;";
	$consulta = mysql_query($sql);
	$modulo = "";
	while ($data = mysql_fetch_array($consulta)){
		
		if ($data['modulo'] != $modulo){
		  if ($modulo != ""){
			$menu['Submenu'] = $submenu;	
			array_push($principal,$menu);  
			$estructura["$modulo"] = $principal;  
		  }
		  $modulo = $data['modulo'];
		  $seccion = "";
		  $principal = array();
		}
		
		
		 if($data['seccion']!= $seccion){
			if ($seccion != ""){
			  $menu['Submenu'] = $submenu;	
			  array_push($principal,$menu);	
			}
			$submenu = array();
			$menu = array('Menu'=>"$data[seccion]",'Submenu'=>'','Modificar'=>'No','Eliminar'=>'No');
			$seccion = $data['seccion'];
		 }
		 
		 if ($data['accion'] == 'nuevo' || $data['accion'] == 'listar' || $data['accion'] == 'reporte'){
			$option = array('Texto'=>"$data[texto]",'Enlace'=>"$data[url]"); 
			array_push($submenu,$option);
		 }
		 
		 if ($data['accion'] == 'modificar'){
			$menu['Modificar'] = 'Si'; 
		 }
	
		 if ($data['accion'] == 'eliminar'){
			$menu['Eliminar'] = 'Si'; 
		 }
		
	}
	$menu['Submenu'] = $submenu;	
	array_push($principal,$menu);
	$estructura["$modulo"] = $principal;
	print_r($estructura['Administracion']);
	
	echo "<br />";
	
	$fileAcceso = privilegiosFile($estructura['Administracion'],'Sucursal','nuevo_sucursal.php','listar_sucursal.php');
	echo "Acceso: ".$fileAcceso['Acceso']."<br />";	
    echo "Modificar: ".$fileAcceso['Modificar']."<br />";	 
    echo "Eliminar: ".$fileAcceso['Eliminar']."<br />";	
    echo "File 2:".$fileAcceso['File']."<br />";	
	
	
	function privilegiosFile($estructura,$casoUso,$file,$file2){
	 $result = array('Modificar'=>'No','Eliminar'=>'No','Acceso'=>'No','File'=>'No');	
	 if ($estructura != ""){	
	  for ($i=0;$i<=count($estructura);$i++){
		if ($estructura[$i]['Menu'] == $casoUso){
		 $result['Modificar'] = $estructura[$i]['Modificar'];	
		 $result['Eliminar'] = $estructura[$i]['Eliminar'];
		 $subMenu = $estructura[$i]['Submenu'];
		   for ($j=0;$j<=count($subMenu);$j++){
			 if ($subMenu[$j]['Enlace'] == $file){
				$result['Acceso'] = 'Si';
			 }
			 if ($subMenu[$j]['Enlace'] == $file2){
				$result['File'] = 'Si';
			 }
		   }
		   return $result;
		}
	  }
	 }
	  return $result;
	}*/
	
	//$preciosFactory = array('m'=>2);
	
?>
<script>


function imprimeTicket(texto)
{
var objFSO=new ActiveXObject("Scripting.FileSystemObject");


var objPrinter = objFSO.OpenTextFile("COM1:", 2,true);


objPrinter.WriteLine(texto);

objPrinter.Close();
}


</script>


<input type="button" onClick="imprimeTicket('audio1')" value="Imprimir" />
</body>
</html>