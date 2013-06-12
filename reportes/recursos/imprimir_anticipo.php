<?php
session_start();
if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: ../index.php");	
}
ob_start();
include("../../MPDF53/mpdf.php");
include('../../conexion.php');

  $db = new MySQL();
  $codanticipo = $_GET['idanticipo'];

  $sql = "select a.numero,a.idanticipo,day(a.fecha)as 'dia',month(a.fecha)as 'mes',year(a.fecha)as 'anio',
          a.egreso,a.documento,round(a.sueldobasico,2)as 'sueldobasico',round(a.anticipo,2)as 'anticipo',a.glosa,left(concat(t.nombre,' ',t.apellido),30)as 'trabajador',left(p.cuenta,35) as 'cuentacaja',
          c.cargo,date_format(t.fechaingreso,'%d/%m/%Y')as 'ingreso',left(s.nombrecomercial,25)as 'nombrecomercial',left(concat(tu.nombre,' ',tu.apellido),30)as 'usuario'  
          from anticipo a,trabajador t,sucursal s,cargo c,usuario u,trabajador tu,plandecuenta p 
          where a.idtrabajador = t.idtrabajador  
		  and a.idsucursal=s.idsucursal  
		  and a.egreso=p.codigo 
		  and t.idcargo=c.idcargo 
		  and u.idusuario=a.idusuario 
		  and u.idtrabajador=tu.idtrabajador   
		  and idanticipo=$codanticipo;";	 
  $consulta = mysql_query($sql );
  $datosGenerales = mysql_fetch_array($consulta);
  $sql = "select * from empresa";
  $empresa = $db->arrayConsulta($sql);
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Anticipo</title>
<link rel="stylesheet" href="anticipo.css" type="text/css" />
</head>

<body>
<div class="margen"></div>

 

<div class="session1_numTransaccion">
 <table width="100%" border="0">
      <tr><td align="center" height="3"></td></tr> 
     <tr><td class="session1_titulo_num"><?php echo "Nº ".$datosGenerales['idanticipo']; ?></td></tr> 
    </table>
</div>
<div class="session1_logotipo"><?php echo "<img src='../../$empresa[imagen]' width='200' height='70'/>"; ?></div>
<div class="session1_contenedorTitulos">
   <table width="100%" border="0">
     <tr><td align="center" class="session1_titulo1">ANTICIPO</td></tr> 
     <tr> <td align="center" class="titulosNegros">CORRESPONDIENTE AL MES DE <?php echo $db->mes($datosGenerales['mes']);?> DEL <?php echo $datosGenerales['anio'];?></td></tr>
    </table>
</div>

<div class="session1_sucursal">
    <table width="100%" border="0">
      <tr><td align="center" class="session1_tituloSucursal"><?php echo strtoupper($datosGenerales['nombrecomercial']); ?></td></tr>
    </table>
</div>
 
 <div class="session2_datos">
 <table width="100%" border="0">
  <tr>
    <td width="16%" class="session2_subtitulos">Fecha:</td>
    <td width="28%" class="session2_contenido"><?php echo $datosGenerales['dia'];?> de <?php echo $db->mes($datosGenerales['mes']);?> de 
    <?php echo $datosGenerales['anio'];?></td>
    <td width="30%" class="session2_subtitulos">Fecha de Ing.:</td>
    <td  width="16%" class="session2_contenido"><?php echo $datosGenerales['ingreso'];?></td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_subtitulos">Nombre:</td>
    <td class="session2_contenido"><?php echo $datosGenerales['trabajador'];?></td>
    <td class="session2_subtitulos">Cargo:</td>
    <td width="16%" class="session2_contenido"><?php echo $datosGenerales['cargo'];?></td>
    <td width="10%">&nbsp;</td>
  </tr>
  <tr>
    <td class="session2_subtitulos">Caja/Banco:</td>
    <td class="session2_contenido"><?php echo $datosGenerales['cuentacaja'];?></td>
    <td class="session2_subtitulos">Recibo:</td>
    <td width="16%" class="session2_contenido"><?php echo $datosGenerales['documento'];?></td>
    <td width="10%">&nbsp;</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td class="session2_subtitulos1">Glosa:</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td colspan="4">
    <table width="560"  align="left">
      <tr>
        <td height="50" valign="top" class="session2_contenido">
         <?php echo $datosGenerales['glosa'];?>
          </td>
      </tr>
    </table>
    </td>
    </tr>
</table>
<table width="1170" border="0">
  <tr>
    <td  width="882" height="21" class="session2_subtitulos12">Sueldo Basico:</td>
    <td width="146" class="session2_contenido1_1"><?php echo number_format($datosGenerales['sueldobasico'],2);?></td>
    <td width="128">&nbsp;</td>
  </tr>
  <tr>
    <td width="882" height="21" class="session2_subtitulos12">Anticipo:</td>
    <td width="146" class="session2_contenido1"><?php echo number_format($datosGenerales['anticipo'],2);?></td>
    <td width="128">Bs</td>
  </tr>
</table>
 </div>


 <div class="session5_firmas"> 
 <table width="100%" border="0" align="center">
  <tr>
    <td width="156">&nbsp;</td>
    <td width="220" class="titulosNegros" align="center">...........................................................</td>
    <td width="50">&nbsp;</td>
    <td width="220" class="titulosNegros" align="center">..........................................................</td>
    <td width="50">&nbsp;</td>
    <td width="220" class="titulosNegros" align="center">.........................................................</td>
    <td width="315">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" class="titulosNegros2">Interesado</td>
    <td>&nbsp;</td>
    <td align="center" class="titulosNegros2">Caja</td>
    <td>&nbsp;</td>
    <td align="center" class="titulosNegros2">Contabilidad</td>
    <td>&nbsp;</td>
  </tr>
</table> 
 </div>

<div class="session5_pie">   
  <table width="93%" border="0" align="center">
  <tr>
    <td width="120" align="right">Elaborado por:</td>
    <td width="324"><?php echo $datosGenerales['usuario'];?></td>
    <td width="93">&nbsp;</td>
    <td width="189">&nbsp;</td>
    <td width="201">&nbsp;</td>
    <td width="170" >Impreso: <?php echo date("d/m/Y");?></td>
    <td width="130">Hora: <?php echo date("H:i:s");?></td>
  </tr>
  </table>
</div>

</body>
</html>
<?php
$mpdf=new mPDF('utf-8','Letter'); 
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>
