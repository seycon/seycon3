<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
  session_start();
  include("../conexion.php");
  $db = new MySQL();

  if (!isset($_SESSION['softLogeoadmin'])) {
	  header("Location: ../index.php");	
  }
  
  $transaccion = $_GET['transaccion'];

  if ($transaccion == "referenciaMaestro") {
	  $sql = "select month(b.fecha)as 'mes',year(b.fecha)as 'anio',t.idsucursal from bono b,trabajador t 
	  where b.idtrabajador=t.idtrabajador and b.idbono=$_GET[idbono];";
	  $datoBono = $db->arrayConsulta($sql);
	  $sql = "select p.* from planilla p,trabajador t  
	  where p.idtrabajador=t.idtrabajador and month(p.fecha)=$datoBono[mes] and
	  year(p.fecha)=$datoBono[anio] and t.idsucursal=$datoBono[idsucursal] and p.estado=1;";
	  echo $db->getnumRow($sql);
	  exit();
  }

  if ($transaccion == "consulta") {
	$mes = $_GET['mes'];  
	$anio = $_GET['anio'];
	$fecha = $_GET['anio']."/".$_GET['mes']."/01";
	$sucursal = $_GET['sucursal'];  
	$sql = "select idbono from bono b,trabajador t where b.idtrabajador=t.idtrabajador and month(b.fecha)='$mes'
	 and year(b.fecha)='$anio' and t.idsucursal = $sucursal and b.estado=1;";
	$cantidad = $db->getnumRow($sql);
	   if ($cantidad == 0) {
		 $sql = "select * from trabajador where idsucursal=$sucursal and (modalidadcontrato='Temporal'
		  or modalidadcontrato='Indefinido') and estado=1;";  
		 $lista = $db->consulta($sql);
		 while ($dato = mysql_fetch_array($lista)) {
			$sql = "insert into bono values(null,'$dato[idtrabajador]','$fecha','$dato[sueldobasico]'
			,'$dato[bonoproduccion]','0','$dato[transporte]',
			'$dato[puntualidad]','0','$dato[asistencia]','$_SESSION[id_usuario]',1);";
			$db->consulta($sql); 
		 }
	   }
	   
	
	  $sql = "select b.idbono,left(concat(t.nombre,' ',t.apellido),25)as 'trabajador',
        date_format(t.fechaingreso,'%d/%m/%Y')as 'fechaingreso',round(t.sueldobasico,2)as 'sueldobasico'
		,round(b.bonoproduccion,2)as 'bonoproduccion',
		round(b.transporte,2) as 'transporte',round(b.puntualidad,2)as 'puntualidad',
        round(b.asistencia,2) as 'asistencia',round(b.comisiones,2)as 'comisiones',b.horasextras  
		from bono b,trabajador t where b.idtrabajador=t.idtrabajador
        and month(b.fecha)='$mes' and year(b.fecha)='$anio' and t.idsucursal = $sucursal and b.estado=1;";   
	
	 
	$resul = $db->consulta($sql);
	$i = 0;
	  while ($dato = mysql_fetch_array($resul)) {
		$i++; 
		echo "
		<tr bgcolor='#FFFFFF'>
		  <td align='center'><img src='bonos/edit.png' title='Modificar' alt='Modificar' 
		   onclick='recuperarDatos($i,$dato[idbono])' class='seleccion'/></td>
		  <td class='letra'>$i</td>
		  <td class='letra'>$dato[trabajador]</td>
		  <td class='letra'>$dato[fechaingreso]</td>
		  <td class='letra'>".number_format($dato['sueldobasico'],2)."</td>
		  <td class='letra'>".number_format($dato['bonoproduccion'],2)."</td>
		  <td class='letra'>$dato[horasextras]</td>
		  <td class='letra'>".number_format($dato['transporte'],2)."</td>
		  <td class='letra'>".number_format($dato['puntualidad'],2)."</td>
		  <td class='letra'>".number_format($dato['comisiones'],2)."</td>
		  <td class='letra'>".number_format($dato['asistencia'],2)."</td>		  
		</tr>
		";  
	  }
	  exit();	  
  }


 if ($transaccion == "consulta2") {
	$mes = $_GET['mes'];  
	$anio = $_GET['anio'];
	$fecha = $_GET['anio']."/".$_GET['mes']."/".date("d");
	$sucursal = $_GET['sucursal'];     
	
    $sql = "select b.idbono,left(concat(t.nombre,' ',t.apellido),25)as 'trabajador',
        date_format(t.fechaingreso,'%d/%m/%Y')as 'fechaingreso',round(t.sueldobasico,2)as 'sueldobasico'
		,round(b.bonoproduccion,2)as 'bonoproduccion',
		round(b.transporte,2) as 'transporte',round(b.puntualidad,2)as 'puntualidad',
        round(b.asistencia,2) as 'asistencia',round(b.comisiones,2)as 'comisiones',b.horasextras  
		from bono b,trabajador t where b.idtrabajador=t.idtrabajador
        and month(b.fecha)='$mes' and year(b.fecha)='$anio' and t.idsucursal = $sucursal and b.estado=1;";   
	
	 
	$resul = $db->consulta($sql);
	$i = 0;
	  while ($dato = mysql_fetch_array($resul)) {
		 $i++; 
		echo "
		<tr bgcolor='#FFFFFF'>
		  <td><img src='bonos/edit.png' onclick='recuperarDatos($i,$dato[idbono])' class='seleccion'/></td>
		  <td class='letra'>$i</td>
		  <td class='letra'>$dato[trabajador]</td>
		  <td class='letra'>$dato[fechaingreso]</td>
		  <td class='letra'>".number_format($dato['sueldobasico'],2)."</td>
		  <td class='letra'>".number_format($dato['bonoproduccion'],2)."</td>
		  <td class='letra'>$dato[horasextras]</td>
		  <td class='letra'>".number_format($dato['transporte'],2)."</td>
		  <td class='letra'>".number_format($dato['puntualidad'],2)."</td>
		  <td class='letra'>".number_format($dato['comisiones'],2)."</td>
		  <td class='letra'>".number_format($dato['asistencia'],2)."</td>		  
		</tr>
		";  
	  }
	  exit();	  
  }  


 if ($transaccion == "insertar") {
   $sql = "update bono set bonoproduccion='$_GET[bonoproduccion]',horasextras='$_GET[horasextras]'
   ,transporte='$_GET[transporte]',puntualidad='$_GET[puntualidad]',comisiones='$_GET[comision]'
   ,asistencia='$_GET[asistencia]',idusuario='$_SESSION[id_usuario]' where idbono='$_GET[idbono]';";	 
   $db->consulta($sql);
   echo "";	 
   exit();	 
 }


?>