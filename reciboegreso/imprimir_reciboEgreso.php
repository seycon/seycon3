<?php
   session_start();
   if (!isset($_SESSION['softLogeoadmin'])){
       header("Location: ../index.php");	
   }
   ob_start();
   include("../MPDF53/mpdf.php");
   include('../conexion.php');
   include('../aumentaComa.php');
   include('../reportes/literal.php');
   $db = new MySQL();
   $empresa = "select nombrecomercial,imagen from empresa where idempresa = 1";
   
   
 // $idreciboegreso = 2;
 //  $reporte = $_GET['tipo'];
 
 
   $idreciboegreso = $_GET['idrecibo'];
   $sql = "select idreciboegreso,identregado,nombreentregado,fecha,pagado,round(totalegreso,2)as 'totalingreso',firma_digital,codigo,responsable from reciboegreso where idreciboegreso=$idreciboegreso";
   $maestro = mysql_query($sql);
   $maestro = mysql_fetch_array($maestro);

 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Recibo de Egreso</title>
<link rel="stylesheet" type="text/css" href="estilos.css"/>




</head>

<body>

<br />
      <div class="cen" > 
         <table border="0" style="border:0px solid" width="99%">
          <col style="width: 50%" >
          <col style="width: 30%">
          <col style="width: 20%">
          <tr>
            <td width="59%" rowspan="2">
            <!-- <img src="<?php echo "../../".$db->getCampo('imagen',$empresa)?>" height="50" width="355" /> -->
            </td>
            <td colspan="2" align="center" class="session1_titulo">RECIBO DE EGRESO</td>
            

          </tr>
          <tr>
            <td width="25%" class="contorno" align="center"><b>Fecha: </b><?php echo $db->GetFormatofecha($maestro['fecha'],'-');?></td>
            <td width="16%" align="center" class="contorno"><b>Nro: </b><?php echo $idreciboegreso;?></td>
          </tr>
          <tr>
            <td class="contorno"><b>Recibimos de: </b><?php echo $maestro['nombreentregado']?></td>
            <td colspan="2" align="center" class="fondoContorno">            
            ORIGINAL CLIENTE           
            </td>
          </tr>
        </table>

      
        
         
        
        <table width="99%">
                   <col style="width:10%">
                 <col style="width:67%">
                 <col style="width:22%">
                   <tr>
                    <th width="10.5%" class="contorno" >Nro</th>
                    <th width="71.5%" class="contorno">DESCRIPCION</th>
                    <th width="18%" class="contorno">IMPORTE</th>
                   </tr>
                   
                  </table>
          
          <table width="99%" class="contorno" cellspacing="0"  style="position:absolute;left:25px;<?php if($maestro['pagado']==1) echo "background-image:url(../images/pagado.png);";?>">
                 <col style="width:10%">
                 <col style="width:67%">
                 <col style="width:22%">
              
                  

                      <?php
					  $totalTransaccion = 0;
					  $sql = "select descripcion,round(egreso,2)as 'ingreso' from detallereciboegreso where idreciboegreso=$idreciboegreso;";
					  $consultaDetalle = mysql_query($sql);					  			  
					  $contador = 0;
					  while($consulta = mysql_fetch_array($consultaDetalle)){						  
					  $contador++;
					  $totalTransaccion = $totalTransaccion + $consulta['ingreso'];
					  if ($contador==1){
						echo "
					       <tr>
                              <td width='10.5%' style='border-right:1px solid #CCC;font-size:10px' align=center>$contador</td>
                              <td width='71.5%' style='border-right:1px solid #CCC;font-size:10px'>&nbsp;&nbsp;$consulta[descripcion]</td>
                              <td width='18%' style='font-size:10px' align=center>".number_format($consulta['ingreso'],2)."</td>
                          </tr><tr >    	
						";  
					  }
					  else{					  
					  echo "<tr>
					          <td  style='border-right:1px solid #CCC;font-size:10px' align=center>$contador</td>
							  <td  style='border-right:1px solid #CCC;font-size:10px' >&nbsp;&nbsp;$consulta[descripcion]</td>
					          <td  align=center style='font-size:10px'>".number_format($consulta['ingreso'],2)."</td>
					        </tr>";								
					      }
					  
					  }
					  
					  
					  
										  
					  for ($i=$contador;$i<=10;$i++){
						echo "<tr>
						<td style='border-right:1px solid #CCC'>&nbsp;</td>
						<td style='border-right:1px solid #CCC'>&nbsp;</td>
						<td >&nbsp;</td>
						</tr>";  
					  }
					  
					  ?>
                     
                  </tr>

          </table>
        
          <table border="0" style="bottom: 9%; position:absolute; margin-left:4px;" width="99%">
          <col style="width: 66%">
          <col style="width: 14%">
          <col style="width: 20%">
              <?php
			  $totalReciboIngreso = $maestro['totalingreso'];
			  ?>
            <tr>
              <td width="65%" class="tituloTexto"><?php echo strtoupper(NumerosALetras($totalTransaccion)) ?></td>
              <td width="17%" align="right" ><b>TOTAL: </b></td>
              <td width="18%" class="contornoTotal"  align="center"><?php echo number_format($totalTransaccion,2);?></td>
            </tr>
          </table>
          
       
        
        
        <table border="0" style="font-size:10px;" width="99%">
          <col style="width: 31%" >
          <col style="width: 35%">
          <col style="width: 35%">
          <tr height="78" >
            <td width="45%" rowspan="2" align="center" class="contorno">
           
                   
                   <table width="100%" height="102" border="0">
              <tr>
                <td width="15%"><b>Glosa:</b></td>
                 <td width="85%"><?php
				      $mensaje = "select mensajereciboingr from impresion";
				      echo $db->getCampo('mensajereciboingr',$mensaje);
				   ?></td>
                </tr>                  
            </table>
                   
            </td>
            <td width="27%" align="center">
                    <p>
                     <?php
                     if ($_GET['firma'] == 1){
						   $firma = "select firmadigital from usuario where idusuario = 1";//'$_SESSION[id_usuario]'"				   
					?>
                     <img src="<? echo "../../".$db->getCampo('firmadigital',$firma);?>" height="30" width="80"/><br />
                   </p>
                    <?php 
					   echo $_SESSION['nombre_usuario'];
					   } else {
						   echo "";
					   }
					?><br />
                    <br />
                   
            ............................................</td>
            <td width="28%" align="center" valign="bottom" <? if ($_GET['firma'] == 1){echo "valign=bottom";} ?>>
                ............................................</td>
          </tr>
          <tr>
            <td height="14" align="center">Entregue Conforme</td>
            <td align="center">Recibi Conforme</td>
          </tr>
        </table>
        
         <table style="font-size:9px;" class="contorno" width="99%">
          <col style="width: 45%" >
          <col style="width: 16%">
          <col style="width: 20%">
          <col style="width: 20%">
            <tr>
              <td>
              </td>
              <td >
               
              </td>
              <td >
               <strong>Fecha:</strong> <?php echo date("d/m/Y");?>
              </td>
              <td  align="right"><?php echo $maestro['codigo']?></td>
            </tr >
        </table>

<br />

</div>

<!-- <div style="position:absolute;border:1px solid #000;width:95%;height:46%;top:20px;left:20px;"></div> -->

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

 
 
