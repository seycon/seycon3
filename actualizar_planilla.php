<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
 session_start();
 include('conexion.php');
 include("aumentaComa.php");
 $db = new MySQL();
 
 if (!isset($_SESSION['softLogeoadmin'])) {
       header("Location: index.php");	
 }
 $seguroLD = 0;
 if (isset($_GET['seguroLD']) && $_GET['seguroLD'] == true){
	$seguroLD = 1; 
 }
 
  if (!function_exists("GetSQLValueString")) {
	  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	  {
		if (PHP_VERSION < 6) {
		  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
		}
	  
		$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) 
		: mysql_escape_string($theValue);
	  
		switch ($theType) {
		  case "text":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;    
		  case "long":
		  case "int":
			$theValue = ($theValue != "") ? intval($theValue) : "NULL";
			break;
		  case "double":
			$theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
			break;
		  case "date":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
		  case "defined":
			$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
			break;
		}
		return $theValue;
	  }
  }

 $currentPage = $_SERVER["PHP_SELF"];

 $editFormAction = $_SERVER['PHP_SELF'];
 if (isset($_SERVER['QUERY_STRING'])) {
     $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
 }

 if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
 
	$updateSQL = sprintf("UPDATE planilla SET idusuario=$_SESSION[id_usuario],diastrabajados=%s,afp="
	.desconvertir($_POST['afp'])." WHERE idplanilla=%s",                      
						 GetSQLValueString($_POST['diastrabajados'], "int"),                               
						 GetSQLValueString($_POST['idplanilla'], "int"));
					 
	$Result1 = $db->consulta($updateSQL);
	$sql = "select month(fecha)as 'mes',year(fecha)as 'anio',fecha
	,t.idsucursal as 'sucursal',p.idtrabajador as 'idtrabajador' 
	 from planilla p,trabajador t  
	 where p.idtrabajador=t.idtrabajador and idplanilla=$_POST[idplanilla] and p.estado=1;";
	$datoFecha = $db->arrayConsulta($sql);  
	
	$sql = "select sum(anticipo) as 'anticipo' from anticipo where month(fecha)=$datoFecha[mes] and 
		   year(fecha)=$datoFecha[anio] and idtrabajador=$datoFecha[idtrabajador] 
		   and estado=1 GROUP BY idtrabajador;";
			   
	$anticipoTotal = $db->arrayConsulta($sql);
	$anticipoCalculado = ($anticipoTotal['anticipo'] == "") ? 0 : $anticipoTotal['anticipo'];
	$sql = "update planilla set anticipo=$anticipoCalculado where idplanilla=$_POST[idplanilla];";  
	$db->consulta($sql);
	
	$sql = "update planilla set totaldescuento=afp+anticipo where idplanilla=$_POST[idplanilla];";
	$db->consulta($sql);
	$sql = "select round(t.sueldobasico,2)as 'sueldobasico' from planilla dp,trabajador t 
	where t.idtrabajador=dp.idtrabajador and dp.idplanilla=$_POST[idplanilla]; ";
	
	$sueldoB = $db->arrayConsulta($sql);
	$sueldo = ($_POST['diastrabajados'] * $sueldoB['sueldobasico']) / 30;
	$sql = "update planilla set sueldobasico=$sueldo where idplanilla=$_POST[idplanilla];";
	$db->consulta($sql);
	
	$sql = "select  (p.sueldobasico+p.bonoantiguedad+p.importehorasextras
	+b.bonoproduccion+b.transporte+b.puntualidad+b.comisiones+b.asistencia) as 'totalG'  
	 from planilla p,bono b where p.idbono=b.idbono and p.idplanilla=$_POST[idplanilla];";
	$total = $db->arrayConsulta($sql);
	$sql = "update planilla set totalganado='$total[totalG]' where idplanilla=$_POST[idplanilla];";  
	$db->consulta($sql);
	 
	$sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	$tc = $db->getCampo('dolarcompra',$sql); 		  
	$mes = $db->mes($datoFecha['mes']);
	$sql = "select *from configuracioncontable;";
	$cuentaContable = $db->arrayConsulta($sql);
	$detalleLibro = getDetalleLibro($db,$datoFecha['sucursal'],$mes);		  
	$idlibro = modificarLibro($datoFecha['sucursal'],$datoFecha['fecha'],'Bolivianos',$datoFecha['mes']
	,$datoFecha['anio'],0,$tc,$_SESSION['id_usuario'],$db,'Planilla de sueldos');
	
	$sql = "delete from detallelibrodiario where idlibro=$idlibro";  
	$db->consulta($sql);
	
	$sql = "select sum(p.sueldobasico)as 'sueldobasico',sum(p.bonoantiguedad) as 'antiguedad'
			,sum(p.importehorasextras) as 'horasextras',sum(p.anticipo)as 'anticipo'
			,sum(p.totalganado) as 'totalganado'		   
			 from planilla p,trabajador t 
			 where month(fecha)='$datoFecha[mes]' 
			 and year(fecha)='$datoFecha[anio]' 
			 and t.idtrabajador=p.idtrabajador 
			 and t.idsucursal=$datoFecha[sucursal] and p.estado=1;";
	$totalPlanilla = $db->arrayConsulta($sql);
	$sql = "select sum(b.bonoproduccion)as 'produccion',sum(b.transporte)as 'transporte',
	sum(b.puntualidad)as 'puntualidad',sum(b.comisiones)as 'comision',
	sum(b.asistencia)as 'asistencia' 
	from bono b,trabajador t 
	where month(b.fecha)='$datoFecha[mes]' 
	 and year(b.fecha)='$datoFecha[anio]' 
	 and b.idtrabajador=t.idtrabajador and b.estado=1 
	 and t.idsucursal=$datoFecha[sucursal];";
	$totalBono = $db->arrayConsulta($sql);
	$sql = "select * from datosplanilla where estado=1;";
	$datosPlanilla = $db->arrayConsulta($sql);
	$porcentajeAL = $datosPlanilla['segurovejez'] + $datosPlanilla['riesgocomun'] +
			 $datosPlanilla['comisionafp'] + $datosPlanilla['aportesolidario'];
	$porcentajeAP = $datosPlanilla['seguroprofesional'] + $datosPlanilla['provivienda'] +
			 $datosPlanilla['aportepatronal'];   
	$otrosBonos = $totalBono['transporte'] + $totalBono['puntualidad'] + $totalBono['comision'] + $totalBono['asistencia'];
	$salariosPagar = $totalPlanilla['sueldobasico'] + $totalPlanilla['antiguedad'] + 
			  $totalBono['produccion'] + $otrosBonos + $totalPlanilla['horasextras'] - $totalPlanilla['anticipo'];
	$seguromedico = $totalPlanilla['totalganado'] *  $datosPlanilla['seguromedico'];
	$aportePatronal = $totalPlanilla['totalganado'] * $porcentajeAP;
	$aporteLaboral =  $totalPlanilla['totalganado'] * $porcentajeAL;	
	$retenciones = $seguromedico + $aportePatronal + $aporteLaboral;	

    insertarDetalle($db,$idlibro,$cuentaContable['sueldossalarios'],$detalleLibro,$totalPlanilla['sueldobasico'],0,0);
	if ($totalPlanilla['antiguedad'] != 0)
	    insertarDetalle($db,$idlibro,$cuentaContable['bonoantiguedad'],$detalleLibro,$totalPlanilla['antiguedad'],0,0);
	if ($totalBono['produccion'] != 0)
	    insertarDetalle($db,$idlibro,$cuentaContable['bonoproduccion'],$detalleLibro,$totalBono['produccion'],0,0);
	if ($otrosBonos != 0)
	    insertarDetalle($db,$idlibro,$cuentaContable['otrosbonos'],$detalleLibro,$otrosBonos,0,0);
	if ($totalPlanilla['horasextras'] != 0)	
	    insertarDetalle($db,$idlibro,$cuentaContable['horasextras'],$detalleLibro,$totalPlanilla['horasextras'],0,0);
	if ($salariosPagar != 0)		
	    insertarDetalle($db,$idlibro,$cuentaContable['salariospagar'],$detalleLibro,0,$salariosPagar,0);
	if ($totalPlanilla['anticipo'] != 0)		
	    insertarDetalle($db,$idlibro,$cuentaContable['anticiposueldo'],$detalleLibro,0,$totalPlanilla['anticipo'],0);
	if ($seguroLD == 1) {	
		if ($retenciones != 0)
			insertarDetalle($db,$idlibro,$cuentaContable['aporteretenciones'],$detalleLibro,0,$retenciones,0);
		if ($seguromedico != 0)	
			insertarDetalle($db,$idlibro,$cuentaContable['seguromedico'],$detalleLibro,$seguromedico,0,0);
		if ($aportePatronal != 0)
			insertarDetalle($db,$idlibro,$cuentaContable['aportepatronal'],$detalleLibro,$aportePatronal,0,0);
		if ($aporteLaboral != 0)
			insertarDetalle($db,$idlibro,$cuentaContable['aportelaboral'],$detalleLibro,$aporteLaboral,0,0);
	}
  
}

  $maxRows_Recordset1 = 1;
  $pageNum_Recordset1 = 0;
  if (isset($_GET['pageNum_Recordset1'])) {
	$pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
  }
  $startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;
  $mesPlanilla = 01;
  $anioPlanilla = 2010;
  $codsucursal = 2;
  if (isset($_GET['meses'])) {
	  $mesPlanilla = $_GET['meses'];	
	  $anioPlanilla = $_GET['anio'];
	  $codsucursal = $_GET['sucursal'];
  }


  function getImporteHE($sueldo, $bono)
  {
	  return (($sueldo/30)/8) * $bono['horasextras'] * 2;
  }
  
  function getTotalBonos($bono)
  {
	return $bono['bonoproduccion'] + $bono['transporte'] + $bono['puntualidad'] + $bono['comisiones'] + $bono['asistencia'];	
  }
  
  function getAFP($ganado, $dPlanilla)
  {
	return ($dPlanilla['segurovejez']* $ganado) + ($dPlanilla['riesgocomun']* $ganado) + ($dPlanilla['comisionafp']* $ganado) + 
	($dPlanilla['aportesolidario']* $ganado);	
  }

  function getBonoAntiguedad($dias, $db)
  {
	  $cantAnios = $dias/360;
	  $sql = "select *from datosplanilla";
	  $porcentaje = $db->arrayConsulta($sql);
	  $salarioNacional = ($porcentaje['salariominimo'] * 3);
	  $antiguedad = 0;
	  
		if ($cantAnios >= 2 && $cantAnios <= 4) {
		   $antiguedad = $salarioNacional * $porcentaje['antiguedad1']; 
		}
		if ($cantAnios >= 5 && $cantAnios <= 7) {
		   $antiguedad = $salarioNacional * $porcentaje['antiguedad2'];  
		}
		if ($cantAnios >= 8 && $cantAnios <= 10) {
		   $antiguedad = $salarioNacional * $porcentaje['antiguedad3'];  
		}
		if ($cantAnios >= 11 && $cantAnios <= 14) {
		   $antiguedad = $salarioNacional * $porcentaje['antiguedad4'];  
		}
		if ($cantAnios >= 15 && $cantAnios <= 19) {
		   $antiguedad = $salarioNacional * $porcentaje['antiguedad5'];  
		}
		if ($cantAnios >= 20 && $cantAnios <= 24) {
		   $antiguedad = $salarioNacional * $porcentaje['antiguedad6'];  
		}
		if ($cantAnios >= 25) {
		   $antiguedad = $salarioNacional * $porcentaje['antiguedad7'];  
		}
	return $antiguedad;	
  }

    $error = "";
    //consulta de planilla 
    $sql = "select idplanilla from planilla p,trabajador t where p.idtrabajador=t.idtrabajador 
	and  month(p.fecha)=$mesPlanilla and year(p.fecha)=$anioPlanilla
	 and t.idsucursal=$codsucursal and p.estado=1";

	$sqlBonos = "select *from bono b,trabajador t where b.idtrabajador=t.idtrabajador and month(b.fecha)=$mesPlanilla and    
	year(b.fecha)=$anioPlanilla   and t.idsucursal=$codsucursal and b.estado=1;";
	 if ($db->getnumRow($sqlBonos) == 0) {
		 $error = "msj1";
	 }
	
	if ($db->getnumRow($sql) == 0 && $error == "") {   	
		  $fecha= "$anioPlanilla/$mesPlanilla/".date("d");		  
		  $sql = "select idtrabajador,sueldobasico,datediff(current_date,fechaingreso)as 'dias' from trabajador t where t.estado=1
		   and (modalidadcontrato='Temporal' or modalidadcontrato='Indefinido') and t.idsucursal = $codsucursal;";
		  $result = $db->consulta($sql);	  
		  
		  while($dato = mysql_fetch_array($result)) {
			 $idtrabajador = $dato['idtrabajador'];	   
			 $sql = "select *from bono where month(fecha)=$mesPlanilla and year(fecha)=$anioPlanilla 
			 and idtrabajador=$idtrabajador and estado=1";
			 $bono = $db->arrayConsulta($sql);
			 			 
			 $sueldo = $dato['sueldobasico'];			 
			 $importeHE = getImporteHE($sueldo,$bono);;
			 $totalBonos = getTotalBonos($bono);
			 $antiguedad = getBonoAntiguedad($dato['dias'],$db);
			 $totalG = $sueldo + $importeHE + $totalBonos + $antiguedad;
			 
			 $sql = "select *from datosplanilla where estado=1;";
			 $datoPlanilla = $db->arrayConsulta($sql);
			 $afp = getAFP($totalG,$datoPlanilla); 
			 
			 $sql = "select sum(anticipo) as 'anticipo' from anticipo where month(fecha)=$mesPlanilla and 
			 year(fecha)=$anioPlanilla and idtrabajador=$idtrabajador and estado=1 GROUP BY idtrabajador;";
			 $resultado = $db->arrayConsulta($sql);
			 $anticipo = ($resultado['anticipo'] == "") ? 0 : $resultado['anticipo'];
			 $totalD = $afp + $anticipo;
			 
			 $consultaplanilla = "insert into planilla values(null,'$idtrabajador','$bono[idbono]','".$fecha."',30,'$sueldo'
			 ,'$antiguedad','$importeHE','$totalG','$afp','$anticipo','$totalD','$_SESSION[id_usuario]',1);";			
			 $db->consulta($consultaplanilla);		 
		  }	   
		   
		  $sql = "select dolarcompra, dolarventa from indicadores order by idindicador desc limit 1";
	      $tc = $db->getCampo('dolarcompra',$sql); 		  
		  $mes = $db->mes($mesPlanilla);
		  $sql = "select *from configuracioncontable;";
		  $cuentaContable = $db->arrayConsulta($sql);
		  $detalleLibro = getDetalleLibro($db,$codsucursal,$mes);		  
		  $sql = "select sum(p.sueldobasico)as 'sueldobasico',sum(p.bonoantiguedad) as 'antiguedad'
		  ,sum(p.importehorasextras) as 'horasextras',sum(p.anticipo)as 'anticipo'
		  ,sum(p.totalganado) as 'totalganado'		   
		   from planilla p,trabajador t 
		   where month(fecha)='$mesPlanilla' 
           and year(fecha)='$anioPlanilla' 
		   and t.idtrabajador=p.idtrabajador 
		   and t.idsucursal=$codsucursal and p.estado=1;";
		  $totalPlanilla = $db->arrayConsulta($sql);
		  $sql = "select sum(b.bonoproduccion)as 'produccion',sum(b.transporte)as 'transporte',
		  sum(b.puntualidad)as 'puntualidad',sum(b.comisiones)as 'comision',
		  sum(b.asistencia)as 'asistencia' 
		  from bono b,trabajador t 
		  where month(b.fecha)=$mesPlanilla 
		   and year(b.fecha)=$anioPlanilla 
		   and b.idtrabajador=t.idtrabajador and b.estado=1 
		   and t.idsucursal=$codsucursal;";
		  $totalBono = $db->arrayConsulta($sql);
		  $sql = "select * from datosplanilla where estado=1;";
		  $datosPlanilla = $db->arrayConsulta($sql);
		  $porcentajeAL = $datosPlanilla['segurovejez'] + $datosPlanilla['riesgocomun'] +
		           $datosPlanilla['comisionafp'] + $datosPlanilla['aportesolidario'];
		  $porcentajeAP = $datosPlanilla['seguroprofesional'] + $datosPlanilla['provivienda'] +
		           $datosPlanilla['aportepatronal'];   
		  $otrosBonos = $totalBono['transporte'] + $totalBono['puntualidad'] + $totalBono['comision'] + $totalBono['asistencia'];
		  $salariosPagar = $totalPlanilla['sueldobasico'] + $totalPlanilla['antiguedad'] + 
 		            $totalBono['produccion'] + $otrosBonos + $totalPlanilla['horasextras'] - $totalPlanilla['anticipo'];
		  $seguromedico = $totalPlanilla['totalganado'] *  $datosPlanilla['seguromedico'];
		  $aportePatronal = $totalPlanilla['totalganado'] * $porcentajeAP;
		  $aporteLaboral =  $totalPlanilla['totalganado'] * $porcentajeAL;	
		  $retenciones = $seguromedico + $aportePatronal + $aporteLaboral;	
		  $idlibro = insertarLibro($codsucursal,'Bolivianos',$fecha,0,$tc,$_SESSION['id_usuario'],$db,'Planilla de sueldos');
		  insertarDetalle($db,$idlibro,$cuentaContable['sueldossalarios'],$detalleLibro,$totalPlanilla['sueldobasico'],0,0);
		  if ($totalPlanilla['antiguedad'] != 0)
    	    insertarDetalle($db,$idlibro,$cuentaContable['bonoantiguedad'],$detalleLibro,$totalPlanilla['antiguedad'],0,0);
		  if ($totalBono['produccion'] != 0)
		    insertarDetalle($db,$idlibro,$cuentaContable['bonoproduccion'],$detalleLibro,$totalBono['produccion'],0,0);
		  if ($otrosBonos != 0)
		    insertarDetalle($db,$idlibro,$cuentaContable['otrosbonos'],$detalleLibro,$otrosBonos,0,0);
		  if ($totalPlanilla['horasextras'] != 0)	
		    insertarDetalle($db,$idlibro,$cuentaContable['horasextras'],$detalleLibro,$totalPlanilla['horasextras'],0,0);
	      if ($salariosPagar != 0)		
  		    insertarDetalle($db,$idlibro,$cuentaContable['salariospagar'],$detalleLibro,0,$salariosPagar,0);
		  if ($totalPlanilla['anticipo'] != 0)		
		    insertarDetalle($db,$idlibro,$cuentaContable['anticiposueldo'],$detalleLibro,0,$totalPlanilla['anticipo'],0);
 	  	  if ($seguroLD == 1) {
			  if ($retenciones != 0)
				insertarDetalle($db,$idlibro,$cuentaContable['aporteretenciones'],$detalleLibro,0,$retenciones,0);
			  if ($seguromedico != 0)	
				insertarDetalle($db,$idlibro,$cuentaContable['seguromedico'],$detalleLibro,$seguromedico,0,0);
			  if ($aportePatronal != 0)
				insertarDetalle($db,$idlibro,$cuentaContable['aportepatronal'],$detalleLibro,$aportePatronal,0,0);
			  if ($aporteLaboral != 0)
				insertarDetalle($db,$idlibro,$cuentaContable['aportelaboral'],$detalleLibro,$aporteLaboral,0,0);
		  }
	}


	$query_Recordset1 = "select p.idplanilla,t.idtrabajador,t.nombre,t.apellido,t.sexo,t.carnetidentidad,c.cargo,
	t.fechaingreso,round(p.sueldobasico,2)as 'sueldobasico',round(b.bonoproduccion,2)as 
	'bonoproduccion',b.horasextras,b.transporte,b.puntualidad,b.comisiones,
	b.asistencia,round(p.anticipo,2)as 'anticipo',round(p.totalganado,2)as 'totalganado',round(p.totaldescuento,2)as 
	'totaldescuento',round(p.afp,2)as 'afp',
	p.bonoantiguedad,p.diastrabajados,(t.sueldobasico)as 'sueldoreal',p.importehorasextras   
	 from planilla p,trabajador t,cargo c,bono b 
	where p.idtrabajador=t.idtrabajador and t.idcargo=c.idcargo and b.idbono=p.idbono and 
	 month(p.fecha)=$mesPlanilla and year(p.fecha)=$anioPlanilla  and t.idsucursal=$codsucursal and p.estado=1";
    $Recordset1="";

  function insertarDetalle($db, $idlibro, $cuenta, $descripcion, $debe, $haber, $documento)
  {
	 $sql = "insert into detallelibrodiario(idlibro,idcuenta,descripcion,debe,haber,documento)
	 values($idlibro,'$cuenta','$descripcion','$debe','$haber','$documento')";
	 $db->consulta($sql);
  }

  function getDetalleLibro($db, $sucursal, $mes)
  {	
	$sql = "select * from sucursal where idsucursal=$sucursal";
	$datosSucursal = $db->arrayConsulta($sql);
	$descripcionLibro = "Sueldos y Salarios del Mes de $mes/Sucursal: $datosSucursal[nombrecomercial]";
	return $descripcionLibro;	
  }

  function insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario, $db, $glosa)
  {
	  $sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' 
	  from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";  
	  $num = $db->arrayConsulta($sql); 
	  if (!isset($num['num'])) {
		  $num['num'] = 1;
		  $num['sucursal'] = $sucursal;
	  }		 	
	  $sql = "insert into librodiario(numero,idsucursal,moneda,tipotransaccion,fecha,glosa
	  ,idtransaccion,tipocambio,idusuario,estado,transaccion) values(
	  '$num[num]','$num[sucursal]','$moneda','egreso','$fecha','$glosa','$codigo','$tc','$usuario',1,'Planilla Sueldos');"; 
	  $db->consulta($sql);
	  $libro = $db->getMaxCampo("idlibrodiario","librodiario"); 
	  return $libro;
  }


  function modificarLibro($sucursal, $fecha, $moneda, $mes, $year, $codigo, $tc, $usuario, $db, $glosa)
  {
	  $sql = "select idlibrodiario,idsucursal from librodiario where 
	  transaccion='Planilla Sueldos' and month(fecha)=$mes and year(fecha)=$year and idsucursal=$sucursal 
	  and estado=1;";  	
	  $libro = $db->arrayConsulta($sql); 
	  if ($libro['idlibrodiario'] == "") {
		  $idlibro = insertarLibro($sucursal, $moneda, $fecha, $codigo, $tc, $usuario, $db, $glosa);
		  $sql = "select * from librodiario where idlibrodiario=$idlibro";
		  $libro = $db->arrayConsulta($sql); 
	  }
   
	  if ($libro['idsucursal'] != $sucursal) {
		  $sql = "select max(l.numero)+1 as 'num',l.idsucursal as 'sucursal' 
		  from librodiario l where l.idsucursal=$sucursal GROUP BY l.idsucursal;";
		  $num = $db->arrayConsulta($sql);  	
			if (!isset($num['num'])) {
				$num['num'] = 1;
				$num['sucursal'] = $sucursal;
			}
			$update = "idsucursal='$num[sucursal]',numero=$num[num],";
	  } else {
		$update = "";	
	  }	
	  
	  $sql = "update librodiario set $update moneda='$moneda',fecha='$fecha',tipocambio='$tc'
	  ,idusuario='$usuario',glosa='$glosa'  where idlibrodiario=$libro[idlibrodiario];"; 
	  $db->consulta($sql);
	  return $libro['idlibrodiario'];
  }



  $datos = $db->consulta($query_Recordset1);
  if ($db->getnumRow($query_Recordset1) == 0) {
      header('Location: inicioactualizar_planilla.php?$error#t1');	
  }

  $query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
  $Recordset1 = mysql_query($query_limit_Recordset1) or die (mysql_error());
  $resultadoConsulta = mysql_fetch_assoc($Recordset1);

  if (isset($_GET['totalRows_Recordset1'])) {
      $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
  } else {
	  $all_Recordset1 = mysql_query($query_Recordset1);
	  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
  }
  $totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;
  
  $queryString_Recordset1 = "";
  if (!empty($_SERVER['QUERY_STRING'])) {
	$params = explode("&", $_SERVER['QUERY_STRING']);
	$newParams = array();
	foreach ($params as $param) {
	  if (stristr($param, "pageNum_Recordset1") == false && 
		stristr($param, "totalRows_Recordset1") == false) {
		array_push($newParams, $param);
	  }
	}
	if (count($newParams) != 0) {
	  $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
	}
  }
  $queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templaterecursos.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Sistema Empresarial y Contable – Seycon 3.0</title>
<script type="text/javascript" src="js/submenus.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="css/estilos.css" type="text/css"/>
<script src="js/jquery-1.5.1.min.js"></script>
<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<style type="text/css">
.planillaestilo {
	font-size: 20px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {

	
}
a:active {
	text-decoration: none;
}
.nuevo {
	font-size: 14em;
}
.nuevo5 {
	font-size: 14px;
}
.nuevo5 {
	font-weight: bold;
}

.session2_cabeceras{
  font-weight:bold;
  border-top: 1px solid #000;
  border-bottom: 1px solid #000;
  text-align:center;
  background:#E6E6E6;  	
}

.session3_titulosCabecera{
  font-size:10px;
  font-weight:bold;	
  text-align:right;
}

.session1_cuadrosucursal{
  position:relative;
  border: 1px solid #333;
  padding-left:2px;
  background:#E2E2E2;
  width:200px;	
}

.session1_cuadroanio{
  position:relative;
  border: 1px solid #333;
  margin-top:2px;
  padding-left:2px;
  background:#E2E2E2;
  width:200px;	
}
</style>
<!-- InstanceEndEditable -->
<script>
 $(document).ready(function(){ 
	$("ul.submenu").parent().append("<span></span>"); 	
	$("ul.menu li span").click(function() { 
		$(this).parent().find("ul.submenu").slideDown('fast').show(); 
		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.submenu").slideUp('slow'); 
		});
 
		}).hover(function() { 
			$(this).addClass("subhover"); 
		}, function(){	
			$(this).removeClass("subhover"); 
	});
	
	$("ul.menuH li span").click(function() { 		
		$(this).parent().find("ul.submenu").slideDown('fast').show();  
		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.submenu").slideUp('slow'); 
		});
 
		}).hover(function() { 
			$(this).addClass("subhover"); 
		}, function(){
			$(this).removeClass("subhover"); 
	});
 
});
</script>
<link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
</head> 
<body >
<div class="franjaCabecera">
<div class="franjaInicial"></div>
<div class="alineadorFrontalSeycon">
<?php
	  function setCabeceraTemplate($titulo) {
		  $cadenaNit = $_SESSION['nit'];
		  if (strlen($cadenaNit) > 15)	{			  
			  $cadenaNit = substr($cadenaNit,0,15);
		  }
		  $cadena = $_SESSION['nombreEmpresa'];
		  if (strlen($cadena) > 35) {				  
			  $cadena = substr($cadena,0,35);
		  }		
		  echo "
			  <div class='headerPrincipal'>
			   <div class='logoEmpresa'></div>			  
				  <div class='tituloEmpresa'>$titulo</div>
				  <div class='nitEmpresa'> 
				   $cadena-$cadenaNit
				  </div>
			  </div>
		  ";
	  }
	  
	  function setMenuTemplate($tituloP, $modulo) {
		 if ($modulo != "Administracion") 
		 echo "<a href='#'>$tituloP</a>"; 
		 $estructura = $_SESSION['estructura'];
		 $menus = $estructura[$modulo];
  	     echo  "<ul class='submenu'>"; 
		 if ($menus != "") {
		   for ($i = 0; $i < count($menus); $i++) {
			   $titulo = $menus[$i]['Menu']; 
			   echo "<li><a href='redireccion.php?mod=$modulo&opt=$titulo'>".$titulo."</a></li>";
		   }		   
		 } 
		 if ($modulo == "Administracion")
		     echo "<li><a href='cerrar.php'>Salir</a></li>";
		 echo "</ul>";
	  }
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td > 
    <?php setCabeceraTemplate("Sistema Empresarial y Contable");?>
    </td>
  </tr>
  <tr>
    <td >
     <div class="menu2"></div>
    </td>
  </tr>
</table>
  <div class="contenedorMenuFrontal">
   <ul class="menu"> 
      <li><?php setMenuTemplate("Inventario", "Inventario");?></li> 
      <li><?php setMenuTemplate("Recursos", "Recursos");?></li>
      <li><?php setMenuTemplate("Activos", "Activo");?></li> 
      <li><?php setMenuTemplate("Ventas", "Ventas");?></li> 
      <li><?php setMenuTemplate("Contabilidad", "Contabilidad");?></li> 
      <li><?php setMenuTemplate("Agenda", "Agenda");?></li>  
    </ul> 
    <div class="usuarioSistema">
      <div class="borde1Usuario"></div>
      <div class="borde2Usuario">
         <div class="sessionHerramienta">
         <ul class="menuH"> 
           <li>
		   <div class="imgHerramienta"></div>
		   <?php setMenuTemplate("Administracion", "Administracion");?></li>               
         </ul>
         </div>
         <div class="nombreUsuario">
		  <?php
          $cadena = $_SESSION['nombre_usuario'];
          $cadena = (strlen($cadena) > 15) ? $cadena = substr($cadena,0,15) : $cadena;
          echo ucfirst($cadena);				
          ?></div>
      </div>
    </div> 
         
    </div>       
   </div>  
</div>
<div class="container">
  <!-- InstanceBeginEditable name="Regioneditable" -->
<script src="autocompletar/FuncionesUtiles.js"></script>
<script>
 document.onkeydown = function(e){
   tecla = (window.event) ? event.keyCode : e.which;
	
	if (tecla == 115) //F4
     location.href = 'inicioactualizar_planilla.php#t1';
	
   if(tecla == 113){ //F2
	 document.form1.submit();
	  
	}
  }
  
  function $$(id){
	return document.getElementById(id);  
  }
  
  function calcularTotalIngreso(){
	  var dias = ($$("diastrabajados").value == "") ? 0 : parseInt($$("diastrabajados").value);
	  var sueldo = ($$("sueldoreal").value == "") ? 0 : parseFloat($$("sueldoreal").value);
	  var total = (dias * sueldo)/30;
	  $$("sueldobasico").value = convertirFormatoNumber(total.toFixed(2));
	  var bantiguedad =  parseFloat(desconvertirFormatoNumber($$("bonodantiguedad").value));
	  var hextras = parseFloat(desconvertirFormatoNumber($$("importehorasextras").value));
	  var bproduccion = parseFloat(desconvertirFormatoNumber($$("bonoproduccion").value));
	  var botros = parseFloat(desconvertirFormatoNumber($$("otrosbonos").value));
	  var totalIngreso = total + bantiguedad + hextras + bproduccion + botros;
	  $$("totalingreso").value = convertirFormatoNumber(totalIngreso.toFixed(2));
	  var liquido = parseFloat(desconvertirFormatoNumber($$("totalingreso").value)) 
	  - parseFloat(desconvertirFormatoNumber($$("totalegreso").value));
	  $$("liquidopagable").value = convertirFormatoNumber(liquido.toFixed(2));
  }
  
  
  function calcularEgreso(){
	var anticipo = parseFloat(desconvertirFormatoNumber($$("anticipo").value)); 
	var afp = parseFloat($$("afp").value);
	var total = anticipo + afp;
	$$("totalegreso").value = convertirFormatoNumber(total.toFixed(2));
	var liquido = parseFloat(desconvertirFormatoNumber($$("totalingreso").value)) 
	- parseFloat(desconvertirFormatoNumber($$("totalegreso").value));
	$$("liquidopagable").value = convertirFormatoNumber(liquido.toFixed(2));
  }
  
</script>

<div class="cabeceraFormulario">
<div class="menuTituloFormulario"> Recursos > Planilla de Sueldos </div>
<div class="menuFormulario"> 
 <?php
   $estructura = $_SESSION['estructura'];
   $menus = $estructura['Recursos'];
   $privilegios = $db->getOpciones($menus, "Planilla de Sueldos"); 
   $option = "";
   for ($i = 0; $i < count($privilegios); $i++) {	
	   $link = "location.href='".$privilegios[$i]["Enlace"]."'";
	   $option = "<div class='privilegioMenu' onclick=$link>".$privilegios[$i]['Texto']."</div>". $option;
   } 
   echo $option;
 ?>
</div>
</div>  
  
<div class="contenedorPrincipalPlan">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<div class="contemHeaderTop">
   <table cellpadding='0' cellspacing='0' width='100%'>
  <tr class='cabeceraInicialListar'> 
    <td height="92" colspan='2' >&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Guardar [F2]" class='botonNegro' />
    &nbsp;&nbsp;<input type="button" value="Cancelar [F4]" class='botonNegro'
     onclick="location.href='inicioactualizar_planilla.php#t1'" />
   </td>
  <td></td>
  <td colspan="3" align='right'>
  <table width="356" border="0">
    <tr>
      <td width="26" align="right"></td>
      <td width="320" align="center"> Reporte Planilla de Asistencia</td>
    </tr>
    <tr>
      <td colspan="2" align="center"></td>  
    </tr>
  </table>
  </td> 
    </tr>
    <tr><td colspan="6"></td> </tr>
  </table>
</div>

<table style="width:100%;" border="0">
 <tr>
 <td> 
<table width="100%" border="0" align="center" cellpadding='4' cellspacing='3' >

  <tr>
    <td class="letraTitulo">       
      <table width="741" border="0" cellspacing="0" cellpadding="0" >
        <tr>
          <td width="31">&nbsp;</td>
          <td width="64" >&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td width="96">&nbsp;</td>
          <td width="178">&nbsp;</td>
          <td width="27">&nbsp;</td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td >&nbsp;</td>
          <td width="134" align="right">Sucursal:</td>
          <td width="211"><div class="session1_cuadrosucursal">
            <?php 
		    $sql = "select left(nombrecomercial,20)as nombrecomercial from sucursal where idsucursal = $codsucursal";
			$dato = mysql_query($sql);
			$dato = mysql_fetch_array($dato);
			echo $dato['nombrecomercial'];
		  ?>
          </div></td>
          <td class="session1_aliniarTexto">Mes:</td>
          <td ><div class="session1_cuadrosucursal"><?php echo $db->mes($mesPlanilla);?></div></td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">Nº Trabajador:</td>
          <td><?php echo $resultadoConsulta['idtrabajador']; ?></td>
          <td class="session1_aliniarTexto">Año:</td>
          <td ><div class="session1_cuadroanio"><?php echo $anioPlanilla;?></div></td>
          <td class=>&nbsp;</td>
        </tr>
      </table>
      <br  />           
      
      <table width="743" height="296" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td width="357" height="115" class="contorno_datos_personales">
          <table width="356" border="0" bgcolor="#FFF" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="3" class="session2_cabeceras">DATOS DEL TRABAJADOR</td>
              </tr>
            <tr>
              <td width="4">&nbsp;</td>
              <td width="131">&nbsp;</td>
              <td width="204">&nbsp;</td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">Nombre:</td>
              <td>
              <input type="text" name="nombre" id="nombre" value="<?php echo $resultadoConsulta['nombre']; ?>" 
              disabled="disabled" style="width:80%"/></td>
            </tr>

            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">Apellido:</td>
              <td><input type="text" name="apellido" id="apellido"
               value="<?php echo $resultadoConsulta['apellido']; ?>" disabled="disabled" style="width:80%"/></td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">Sexo:</td>
              <td><input type="text" name="sexo" id="sexo"
               value="<?php echo $resultadoConsulta['sexo']; ?>" disabled="disabled" style="width:40%"/></td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">CI.:</td>
              <td><input type="text" name="ci" id="ci"
               value="<?php echo $resultadoConsulta['carnetidentidad']; ?>" disabled="disabled" style="width:50%"/></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td class="aliniar_textos">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table></td>
          <td width="386" rowspan="2" class="contorno_division" valign="top">
          <table width="385" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="3" class="session2_cabeceras">DATOS DE PLANILLA</td>
            </tr>
            <tr>
              <td width="3">&nbsp;</td>
              <td width="133">&nbsp;</td>
              <td width="231">&nbsp;</td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">Cargo:</td>
              <td><input type="text" name="cargo" id="cargo"  value="<?php echo $resultadoConsulta['cargo']; ?>"
               disabled="disabled" style="width:80%"/></td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">Fecha Ingreso:</td>
              <td>
              <input type="text" name="fechaingreso" id="fechaingreso" 
              value="<?php echo $db->GetFormatofecha($resultadoConsulta['fechaingreso'],"-"); ?>"
               disabled="disabled" style="width:40%;border:1px solid #000;background:#F00;color:#FFF;font-weight:bold;"/></td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td align="right"><span class="aliniar_textos">Días Trabajados:</span></td>
              <td><input type="text" name="diastrabajados" id="diastrabajados"
               value="<?php echo $resultadoConsulta['diastrabajados'];?>" style="width:40%" onkeyup="calcularTotalIngreso()" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td class="aliniar_textos">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td >&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
             <tr >
              <td height="9"></td>
              <td ></td>
              <td></td>
            </tr>

          </table>
          
          <table width="385" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="3" class="session2_cabeceras">EGRESOS</td>
            </tr>
            <tr>
              <td width="3">&nbsp;</td>
              <td width="133">&nbsp;</td>
              <td width="231">&nbsp;</td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">Anticipo: </td>
              <td align="left">
                <input type="text" name="anticipo" id="anticipo"
                 value="<?php echo htmlentities(number_format($resultadoConsulta['anticipo'],2), ENT_COMPAT, 'utf-8'); ?>"
                 style="width:40%" disabled="disabled"/>
              </td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">A.F.P.:</td>
              <td><input type="text"  name="afp" id="afp" 
              value="<?php echo htmlentities(number_format($resultadoConsulta['afp'],2), ENT_COMPAT, 'utf-8'); ?>" 
              onkeyup="calcularEgreso()" /></td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
           
          </table>      
          </td>
        </tr>
        <tr>
          <td class="contorno_datos_planilla" valign="top">
          <table width="357" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="3" class="session2_cabeceras">INGRESOS</td>
              </tr>
            <tr>
              <td width="4">&nbsp;</td>
              <td width="131">&nbsp;</td>
              <td width="204">
              <input type="hidden" id="sueldoreal" name="sueldoreal" value="<?php echo $resultadoConsulta['sueldoreal'];?>"/>
              <input type="hidden" id="importehorasextras" name="importehorasextras"
               value="<?php echo $resultadoConsulta['importehorasextras'] ?>" />
              </td>
              </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">Sueldo Básico: </td>
              <td><input type="text" name="sueldobasico" id="sueldobasico"
               value="<?php echo number_format($resultadoConsulta['sueldobasico'],2); ?>" disabled="disabled" style="width:
              40%"/></td>
              </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">Bono Antiguedad:</td>
              <td><input name="bonodantiguedad" type="text" id="bonodantiguedad" style="width:
              30%" value="<?php echo $resultadoConsulta['bonoantiguedad']; ?>" disabled="disabled"/></td>
              </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td class="aliniar_textos">Bono Produccion:</td>
              <td><input type="text" name="bonoproduccion" id="bonoproduccion" 
              value="<?php echo number_format($resultadoConsulta['bonoproduccion'],2); ?>"
               disabled="disabled" style="width:30%"/></td>
              </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td align="right"><span class="aliniar_textos">Horas Extras:</span></td>
              <td>
              <input type="text" name="horasextras"
               value="<?php echo htmlentities($resultadoConsulta['horasextras'], ENT_COMPAT, 'utf-8'); ?>" 
              style="width:30%" disabled="disabled"/></td>
              </tr>
            <tr>
              <td height="25">&nbsp;</td>
              <td align="right"><span class="aliniar_textos">Otros Bonos:</span></td>
              <td><input type="text" name="otrosbonos" id="otrosbonos" value="<?php 
			  $total = $resultadoConsulta['transporte'] + $resultadoConsulta['puntualidad']
			  + $resultadoConsulta['comisiones'] + $resultadoConsulta['asistencia'];
			  echo number_format($total,2); 
			  ?>" style="width:30%" disabled="disabled"/></td>
              </tr>
            <tr>
              <td height="53">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            </table></td>
        </tr>
        </table>
     
      <table width="80%" border="0" align="center">
  <tr>
    <td width="18%" align="right" class="session3_titulosCabecera">TOTAL INGRESO:</td>
    <td width="17%"><input name="totalingreso" type="text" id="totalingreso" style="width:60%" value="<?php 			
			  echo  number_format($resultadoConsulta['totalganado'],2); 
			  ?>" disabled="disabled"/></td>
    <td width="15%" align="right" class="session3_titulosCabecera">TOTAL EGRESO:</td>
    <td width="15%"><input name="totalegreso" type="text" id="totalegreso" style="width:60%" value="<?php 			
			  echo  number_format($resultadoConsulta['totaldescuento'],2); 
			  ?>" disabled="disabled"/></td>
    <td width="18%" align="right" class="session3_titulosCabecera">LIQUIDO PAGABLE:</td>
    <td width="17%"><input name="liquidopagable" type="text" id="liquidopagable" style="width:80%" value="<?php 			
			  echo  number_format($resultadoConsulta['totalganado'] - $resultadoConsulta['totaldescuento'],2); 
			  ?>
			  " disabled="disabled"/></td>
  </tr>
</table>
     
        
        <input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" name="idplanilla" value="<?php echo $resultadoConsulta['idplanilla']; ?>" />
    
      <div align="center">
      <table border="0">
        <tr>
          <td width="62" valign="top"><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?sucursal=$codsucursal&meses=$mesPlanilla&seguroLD=$seguroLD
			&anio=$anioPlanilla&pageNum_Recordset1=%d%s#t1", $currentPage, 0, $queryString_Recordset1); ?>"
             class="nuevo5"><img src="images/iniciar1.png" /></a>
            <?php } // Show if not first page ?></td>
          <td width="41" valign="top"><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?sucursal=$codsucursal&meses=$mesPlanilla&seguroLD=$seguroLD
			&anio=$anioPlanilla&pageNum_Recordset1=%d%s#t1"
			, $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>" 
            class="nuevo5"> <img src="images/adelante1.png" /> </a>
            <?php } // Show if not first page ?></td>
          <td width="64" valign="top"><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
            <a href="<?php printf("%s?sucursal=$codsucursal&meses=$mesPlanilla&anio=$anioPlanilla&seguroLD=$seguroLD
			&pageNum_Recordset1=%d%s#t1", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1)
			, $queryString_Recordset1); ?>" class="nuevo5"><img src="images/siguiente1.png" /></a>
            <?php }// Show if not first page ?></td>
          <td width="55" valign="top"><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
            <a href="<?php printf("%s?sucursal=$codsucursal&meses=$mesPlanilla&anio=$anioPlanilla&seguroLD=$seguroLD
			&pageNum_Recordset1=%d%s#t1", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"
             class="nuevo5"><img src="images/atras1.png" /></a>
            <?php } // Show if not last page ?></td>
          </tr>
      </table> 
      </div>
           </td>
  </tr>
  </table>
</td></tr></table></form>
<br />
</div>
<!-- InstanceEndEditable -->  
  <!-- end .footer -->
</div>
 <div class="footerAdm">
  <div class="logo1"><div class="img_logo1"></div></div>
  <div class="logo2"><div class="img_logo2"></div></div>
  <div class="logo3"><div class="img_logo3"></div></div>
  <div class="textoPie1">Seycon 3.0 - Diseñado y Desarrollado por:  Jorge G. Eguez Soliz </div>
  <div class="textoPie2">Copyright &copy; Consultora Guez S.R.L</div>
 </div>
</body>
<!-- InstanceEnd --></html>
<?php mysql_free_result($Recordset1);?>