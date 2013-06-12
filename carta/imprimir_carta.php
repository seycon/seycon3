<?php
  session_start();
   if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: ../index.php");	
   }
  ob_start();
   include("../MPDF53/mpdf.php");
   include('../conexion.php');
   $db = new MySQL();
         
   $idcarta = $_GET['idcarta'];
   $sql = "select destinado,para,day(fecha)as 'dia',month(fecha)as 'mes',year(fecha)as 'anio',
    left(referencia,50)as 'referencia',contenido,left(nombrefirma,35)as 'nombrefirma',left(cargo,35)as 'cargo'  
    from carta where idcarta=$idcarta;";
   $carta = $db->arrayConsulta($sql);
   
   
   function insertarFecha($ciudad,$dia,$mes,$anio,$db){
	  $mes = strtolower($db->mes($mes));
	  $mes = ucfirst($mes); 
	  echo "<table width='100%' border='0'>
      <tr>
        <td width='68%'>&nbsp;</td>
        <td width='32%' class='texto'>$ciudad, $dia de $mes de $anio</td>
      </tr>
      </table>";   
   }
   
   function insertarDirigido($dirigido){
	  echo "<table width='100%' border='0'>
         <tr><td class='texto'>Señor:</td></tr>
         <tr><td height='70' valign='top' class='texto'>$dirigido</td>
         </tr>
      </table>"; 
   }
   
   function insertarPresente(){
	  echo "<table width='100%' border='0'>
       <tr>
        <td class='texto'><u>Presente.-</u></td>
       </tr>
      </table>"; 
   }
   
   function insertarReferencia($contenido){
	echo "<table width='100%' border='0' >
      <tr>
       <td class='session2_textoTitulo'>Ref.: <u>$contenido</u></td>
      </tr>
      </table>";   
   }
   
   function insertarDatos($datos){
	echo " <table width='100%' border='0'>
      <tr><td height='450' valign='top' class='session2_datos_contenido'>$datos</td></tr>
    </table>";   	   
   }
   
   function insertarDatosFirma($representante,$cargo){
	echo "<table width='100%' border='0'>
     <tr>
       <td width='30%'>&nbsp;</td>
       <td width='40%' class='session3_datosFirma'>$representante <br> $cargo</td>
       <td width='30%'>&nbsp;</td>
     </tr>
    </table>";   
   }
   
?>   

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="carta.css" rel="stylesheet" type="text/css" />
<title>Imprimir Carta</title>
</head>

<body>

  <div class="session1_fecha"><?php insertarFecha('Santa Cruz',$carta['dia'],$carta['mes'],$carta['anio'],$db);?></div>
  <div class="session1_dirigido"><?php insertarDirigido($carta['para'])?></div>
  <div class="session1_presente"><?php insertarPresente();?></div>
  <div class="session2_titulo"><?php insertarReferencia($carta['referencia']);?> </div>
  <div class="session2_datos"><?php insertarDatos($carta['contenido']); ?></div>
  <div class="session3_firma"><?php insertarDatosFirma($carta['nombrefirma'],$carta['cargo']);?></div>
  
</body>
</html>

<?php
$mpdf = new mPDF('utf-8','Letter'); 
$content = ob_get_clean();
$mpdf->SetHTMLHeader($header);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
?>