<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php 
	session_start();
	include("conexion.php");
    $db = new MySQL();	

    if (!isset($_SESSION['softLogeoadmin'])) {
	    header("Location: index.php");	
	}
	$estructura = $_SESSION['estructura'];
	if (!$db->tieneAccesoFile($estructura['Recursos'],'Monitoreo','nuevo_monitor.php')){
	     header("Location: cerrar.php");	
    } 

    function abrirSession($cliente, $guardias)
	{
		return "<div class='conttipocliente_5'>
		<div class='tipocliente_5'>Cliente: <span style='color:#CCC'>$cliente</span></div>
		<div class='nrotrabajadores_5'>Guardias: $guardias</div>
		";
	}

	function cerrar()
	{
		return "</div>";	
	}
	
	function abrirFila()
	{
		return "<div class='contenedorPersonal'>";
	}
	
	function cardex($codigo, $src, $nombre)
	{
		return " <div class='cardex' onclick='getDatosPersonal($codigo);'>  
				  <div class='franjacardex'>
				     <div class='nropersonal'>Nº $codigo</div>
				  </div>
				  <div class='fotopersonal'>
				    <img src='$src' width='90' height='80' />
				  </div>
				  <div class='textopersonal'>N.</div>
				  <div class='namepersonal'>$nombre</div>
				</div> ";	
	}
	
	
	function cargarDatos($db, $sql)
	{
		$cadena = "";
		$nroCliente = "";  
		$cantPersonal = 0;
		$dato = $db->consulta($sql);	
		$i = 0;
		if ($db->getnumRow($sql) > 0) {
			while ($data = mysql_fetch_array($dato)) {
				$src = $data['foto']; 
				if ($data ['foto'] == "") {
					$src = "files/modelo_sombraSeycon.png";	
				}
				$cliente = strtoupper($data['cliente']);
				$nombre = ucfirst(strtolower($data['nombre'])); 	
				if ($nroCliente != $data['idcliente']) {
				   if ($i > 0) {
					  $cadena = $cadena . cerrar() . cerrar();  
				   }
				   $cadena = $cadena . abrirSession($cliente,$data['nroguardias']) . abrirFila();
				   $nroCliente = $data['idcliente'];
				   $cantPersonal = 0;
				}
				if ($cantPersonal == 7 && $nroCliente == $data['idcliente']) {
				   $cadena = $cadena . cerrar() . abrirFila(); 
				   $cantPersonal = 0;
				}
				
				$cadena = $cadena . cardex($data['idtrabajador'], $src, $nombre);
				$cantPersonal++;	  
				$i++;
			}
			$cadena = $cadena . cerrar() . cerrar();
		}
		echo  $cadena;	
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Seycon 3.0</title>
<link rel="stylesheet" href="monitor/monitor.css" type="text/css"/>
<link href="autocompletar/estilo1.css" rel="stylesheet" type="text/css" />
<script async="async" src="monitor/monitor.js"></script>
<script async="async" src="monitor/tablero.js"></script>
<script async="async" src="autocompletar/funciones.js"></script>
</head>
<body>

  <div class="contenedor_1">
      <div class="camera"></div>   
      <div class="header1">
         <div id="panel" class="contenedor_5">                         
           <?php
		   		$sql = "
				   select t.idtrabajador,left(concat(t.nombre,' ',t.apellido),15)as 'nombre',
				   t.fotoprincipal as 'foto',left(c.nombre,40)as 'cliente',c.idcliente,c.nroguardias    
				   from historialpersonal h,trabajador t,cliente c  
				   where h.tipo='cliente' and h.titulo='asignar' 				   
				   and c.idcliente=h.descripcion 
				   and h.estado=1 and h.idtrabajador=t.idtrabajador 
				   order by c.idcliente,t.nombre;  ";
				   cargarDatos($db, $sql);
		   ?>              
         </div>    
         <div id="panel_asistencia" class="contenedor_8" style="display:none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="4%" height="50" class="titulo6"></td>
                <td width="6%" class="titulo6_2">Nº</td>
                <td width="28%" class="titulo6_2">Trabajador</td>
                <td width="12%" class="titulo6_2">Fecha</td>
                <td width="28%" class="titulo6_2">Cliente</td>
                <td width="11%" class="titulo6_2">Ingreso</td>
                <td width="11%" class="titulo6_3">Salida</td>
                <td width="11%" style="display:none">Idtrabajador</td>
                <td width="11%" style="display:none;">Idcliente</td>
              </tr>
              <tbody id="cuerpo_asistencia"> 

             </tbody>
            </table>

         </div>
         
      </div>
      <div class="header2">
        <form id="formBusqueda" name="formBusqueda" >
          <div class="tituloGeneral">MONITOR SECURITY</div>          
          <div class="contenedor_4" id="opcion_monitoreo">
            <div class="opcliente">
            <input type="radio" id="busqueda" name="busqueda" checked="checked"
              value="cliente" onclick="restaurarCampos('cliente')"/></div>
            <div class="textocliente">Cli.</div>
            <div class="ccliente">             
            <input type="text"  name="tipocliente" id="tipocliente" class="bordecomplete"
             autocomplete="off" onclick="this.select();" onkeyup="tipoClienteBusqueda(event);"/><br />
            <div id="tipoclienteResult" class="divresultado" style="width:190px;"></div>
            <input type="hidden" id="idtipocliente" name="idtipocliente" value="" />             
            </div>            
            <div class="loadercliente"><div id="autoL4" class="autoLoading"></div></div>
            
            <div class="optrabajador">
            <input type="radio" id="busqueda" name="busqueda" value="trabajador" 
            onclick="restaurarCampos('trabajador')"/></div>
            <div class="textotrabajador">Trab.</div>
             <div class="ctrabajador">             
            <input type="text"  name="tipotrabajador" id="tipotrabajador"
             class="bordecomplete" autocomplete="off" 
             onclick="this.select();" onkeyup="tipoTrabajadorBusqueda(event);"/><br /> 
            <div id="tipotrabajadorResult" class="divresultado" style="width:190px;"></div>
            <input type="hidden" id="idtipotrabajador" name="idtipotrabajador" value="" />            
            </div>
            <div class="loadertrabajador"><div id="autoL3" class="autoLoading"></div></div>            
            
            <div class="opparametro">
            <input type="radio" id="busqueda" name="busqueda" value="parametro" 
            onclick="restaurarCampos('parametro')"/></div>
            <div class="textoparametro">P.</div>
            <div class="cparametro">             
              <select id="parametro" class="bordeselector">
                  <option value="<">Faltantes</option>
                  <option value="=">Completos</option>
                  <option value=">">Demas</option>
              </select>
            </div>
            <input type="button" class="botonNegro" value="Buscar" onclick="getBusqueda()"/>            
          </div>
          
          
          <div class="contenedor_4" id="opcion_asistencia" style="display:none;">
              <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                <tr>
                  <td width="13%" class="textooption">Trabajador</td>
                  <td width="25%">
                  <input type="text"  name="trabajadorhorario" id="trabajadorhorario"
                   class="bordecomplete" autocomplete="off" 
                   onclick="this.select();" onkeyup="trabajadorHorarioBusqueda(event);"/><br /> 
                  <div id="trabajadorhorarioResult" class="divresultado" style="width:190px;"></div>
                  <input type="hidden" id="idtrabajadorhorario" name="idtrabajadorhorario" value="" />            
            
                  </td>
                  <td width="5%"><div id="autoL6" class="autoLoading"></div></td>
                  <td width="5%" class="textooption">Mes</td>
                  <td width="13%">
                  <select id="mes" class="bordeselector" style="position:relative;width:110px;">
                    <?php
					   $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio"
					   , "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
					   for ($i = 1; $i <= 12; $i++) {
						   echo "<option value='$i'>".$meses[$i -1]."</option>";
					   }
					?>                  
                  </select>
              </td>
                  <td width="10%" class="textooption">Año</td>
                  <td width="17%">
                  <select id="anio" class="bordeselector" style="position:relative;">
                    <?php
					   for ($i = 2010; $i <= 2025; $i++) {
						   echo "<option value='$i'>$i</option>";
					   }
					?>
                  </select></td>
                  <td width="12%"><input type="button" class="button" value="Buscar" onclick="getHorario()"/></td>
                </tr>
              </table>

          </div>
          
          
          </form>
      </div>     
      
      <div class="contenedor_2">      
        <div class="interior_cont2"></div>
        <div class="contenido_cont2">
            <div class="contenido_fron">
               <div class="contenedor_3">                   
                    <select id="tipo" class="option_3" onchange="consultarPersonal('%')">
                        <option value="sinasignar">Sin Asignar</option>
                         <option value="notrabajo">No Trabajo</option>
                    </select>
                </div>
            </div> 
        
            <div class="contenido_sup">
            
             <ul id="strabajador">
               <?php		   
                   $sql = "select t.idtrabajador,left(concat(t.nombre,' ',t.apellido),30)as 'nombre' 
					  from trabajador t where t.idtrabajador not in(
					  select h.idtrabajador
					   from historialpersonal h 
					  where h.tipo='cliente' and h.titulo='asignar' 
					   and h.estado=1  
					  ) 
					  and t.idtrabajador not in(					  
						select idtrabajador from historialpersonal where 
						tipo='falta' and fecha=current_date and estado=1
					  )					  
					  and t.control='Monitorizado' and t.estado=1 order by t.nombre limit 14;";
                   $producto = $db->consulta($sql);
                   while ($dato = mysql_fetch_array($producto)) {
					   $codigo = $dato['idtrabajador'];
                       $item = "<li class='listatrabajador' onclick='getDatosPersonal($codigo);'
                        >".ucfirst(strtolower($dato['nombre']))."</li>";		  
                       echo $item;	 
                   }
               ?>
             </ul>             
            
            </div>
            <div class="contenido_inf">               
                  <div class="contenedorSearch">
                      <div class="borde1Listar"> </div>                      
                      <div class="iconSearch"></div>
                      <input type="text"  name="abuscar" id="abuscar" class="borde2Listar" autocomplete="off"  
                       onkeyup="consultarPersonal(this.value)" />      
                  </div>            
            </div>
        </div>
      </div>  
      <div class="union_1" style="top:110px;"></div>
      <div class="union_1" style="top:90px;"></div>
      <div class="union_1" style="top:400px;"></div>
      <div class="union_1" style="top:380px;"></div>
      
      <div class="contenedor_6">
          <div class="interior_cont6"></div>
          <div class="contenido_cont6">
             <div class="contenido_from6">Menú de Opciones</div>
             <div class="contenido_inf6">
              <table width="60%" border="0" align="center">
                <tr>
                  <td width="19%" align="right">
                  <input type="radio" id="opcion" name="opcion" checked="checked" onclick="setOption('monitoreo')"/></td>
                  <td width="81%" class="texto6">Monitoreo</td>
                </tr>
                <tr>
                  <td align="right">
                  <input type="radio" id="opcion" name="opcion" onclick="setOption('asistencia')"/>
                  </td>
                  <td class="texto6">Asistencia</td>
                </tr>
              </table>
             </div>
          </div>
      </div>
      <div class="union_1" style="top:580px;"></div>
      <div class="union_1" style="top:560px;"></div>
      


  <div id="overlay" class="overlays"></div>    
  <div id="gif" class="gifLoader"></div>
  <div id="overlay2" class="overlays"></div>


 <!-- Ventana Advertencia -->
  <div id="modal_mensajes" class="contenedorMsgBox">
  <div id="u1_msg" class="union1_msg" style="top:80px"></div>
  <div id="u2_msg" class="union1_msg" style="top:60px"></div>

  <div class="modal_interiorMsgBox"></div>
  <div class="modalContenidoMsgBox">
      <div class="cabeceraMsgBox">        
        <div id="modal_tituloCabecera" class="modal_titleMsgBox">ADVERTENCIA</div>
        <div class="modal_cerrarMsgBox">
         <img src="iconos/borrar2.gif" width="12" height="12" title="Cerrar"
          style="cursor:pointer" onclick="closeMensaje()"></div>
      </div>
      <div class="contenidoMsgBox">
        <div class="modal_ico1MsgBox"><img src="iconos/alerta.png" width="28" height="28"></div>
        <div class="modal_datosMsgBox" id="modal_contenido">Debe Seleccionar un Almacén de Origen.</div>
        <div class="modal_boton1MsgBox">
        <input type="button" value="Aceptar" class="botonNegroMsgBox" onclick="closeMensaje()"/>
        </div>
      </div>
  </div>
  </div>

 <!-- Ventana Horario -->
  <div id="modal_horario" class="contenedorHorario">
  <div class="modal_interiorHorario"></div>
  <div class="modalContenidoHorario">
      <div class="cabeceraHorario">        
        <div id="modal_tituloCabecera" class="modal_titleMsgBox">HORARIO</div>
        <div class="modal_cerrarMsgBox">
         <img src="iconos/borrar2.gif" width="12" height="12" title="Cerrar [Esc]"
          style="cursor:pointer" onclick="closeHorario()"></div>
      </div>
      <div class="contenidoHorario">
      
        <table width="100%" border="0">
          <tr>
            <td width="38%">&nbsp;</td>
            <td width="13%" align="center" class="texto6">Hora</td>
            <td width="14%" align="center" class="texto6">Min.</td>
            <td width="35%" class="texto6">&nbsp;&nbsp;Seg.</td>
          </tr>
          <tr>
            <td height="5"></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td align="right" class="texto6">INGRESO:</td>
            <td colspan="3">
            <select id="hora_i" name="hora_i">
              <?php
				 for ($i = 0; $i <= 24; $i++) {
					 echo "<option value='$i'>$i</option>";
				 }
			  ?>              
            </select>
            :
            <select id="minuto_i" name="minuto_i">
              <?php
				 for ($i = 0; $i <= 59; $i++) {
					 echo "<option value='$i'>$i</option>";
				 }
			  ?>   
            </select>
            :
            <select id="segundo_i" name="segundo_i">
              <?php
				 for ($i = 0; $i <= 59; $i++) {
					 echo "<option value='$i'>$i</option>";
				 }
			  ?>
            </select>
            </td>
          </tr>
          <tr>
            <td align="right" class="texto6">SALIDA:</td>
            <td colspan="3">
            <select id="hora_s" name="hora_s">
              <?php
				 for ($i = 0; $i <= 24; $i++) {
					 echo "<option value='$i'>$i</option>";
				 }
			  ?>
            </select>
            :
            <select id="minuto_s" name="minuto_s">
              <?php
				 for ($i = 0; $i <= 59; $i++) {
					 echo "<option value='$i'>$i</option>";
				 }
			  ?>
            </select>
            :
            <select id="segundo_s" name="segundo_s">
              <?php
				 for ($i = 0; $i <= 59; $i++) {
					 echo "<option value='$i'>$i</option>";
				 }
			  ?>
            </select>

            </td>
          </tr>
        </table>      
        <div class="modal_botonHorario">
        <input type="button" value="Guardar [F2]" class="botonNegroMsgBox" onclick="closeMensaje()"/>
        </div>
      </div>
  </div>
  </div>


  <!--  Sub Ventana Credencial -->
  <div id="ventana_credencial" class="contenedor_credencial">
      <div class="borde1_credencial"></div>
      <div class="cerrar_credencial">
        <img src="iconos/borrar2.gif" width="15" height="15" alt="Cerrar" title="Cerrar [Esc]"
         style="cursor:pointer" onclick="closeCredencial()">
      </div>
      <div class="nro_credencial" id="cp_nrotrabajador"></div>
      <div class="interno_credencial">
        <div class="franja1_credencial"></div>        
        <img src="files/modelo_sombraSeycon.png" class="foto_credencial" id="imagen_credencial" width="100" height="100" />
  
        <div class="textocp1" style="top:30px;left:150px;"> Nombre.</div>
        <div class="datocp1" style="top:30px;" id="cp_nombre"></div>
        
        <div class="textocp1" style="top:50px;left:150px;"> Dirección.</div>
        <div class="datocp1" style="top:50px;" id="cp_direccion"></div>
        
        <div class="textocp1" style="top:70px;left:150px;"> Telefono.</div>
        <div class="datocp1" style="top:70px;" id="cp_telefono"></div>
        
        <div class="textocp1" style="top:90px;left:150px;"> Celular.</div>
        <div class="datocp1" style="top:90px;" id="cp_celular"></div>
        
        <div class="textocp1" style="top:110px;left:150px;"> Telf. Cony.</div>
        <div class="datocp1" style="top:110px;" id="cp_conyugue"></div>
        
        
          <div class="tabs_credencial">
          <ul  class="menujs">
              <li id="tabs1" class="listajs" onclick="viewMenu('tabs-1')"
               style="background-color:#000;color:#fff;">Cambiar Cliente</li>
              <li id="tabs2" class="listajs" onclick="viewMenu('tabs-2')">Asistencia</li>
              <li id="tabs3" class="listajs" onclick="viewMenu('tabs-3')">Antecendentes</li>
              <li id="tabs4" class="listajs" onclick="viewMenu('tabs-4')">No Trabajo</li>
              <li id="tabs5" class="listajs" onclick="viewMenu('tabs-5')">Historial</li>
          </ul>
          <!-- Cliente -->
          <div id='tabs-1' style="display:block; height:300px;">
              <table width="100%" border="0">
              <tr>
                <td width="30%">&nbsp;</td>
                <td width="47%" colspan="2"><input type="hidden" id="idtrabajador" name="idtrabajador" /></td>
                <td width="23%">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td colspan="2">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="right" class="titulotexto">Cliente:</td>
                <td>
                <input type="text"  name="cliente" id="cliente" onkeyup="tipoBusqueda(event);"
                 autocomplete="off" value="-- Sin Asignar --" onclick="this.select();"/>
                <div id="clienteResult" class="divresultado"></div>
                <input type="hidden" id="idcliente" name="idcliente" value="" />
                </td>
                <td><div id="autoL2" class="autoLoading"></div></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="right" class="titulotexto" valign="top">Motivo<span class="rojo">*</span>:</td>
                <td colspan="2">
                <textarea name="motivo" id="motivo" rows="5" cols="30"></textarea>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td colspan="2">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
            <input type="button" class="boton_guardar" value="Guardar [F2]" onclick="asignarCliente()" />
            
          </div>
          
          <!-- Asistencia -->
          <div id='tabs-2' style="display:none; height:300px;">
           <form id='formdatos' name='formdatos' >
              <table width="100%" border="0">
              <tr>
                <td width="57%">&nbsp;</td>
                <td width="20%">&nbsp;</td>
                <td width="23%">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="right" class="titulotexto">Registrar Ingreso:</td>
                <td><input type="radio" name="asistencia" id="asistencia" value="ingreso" checked="checked"/></td>
                <td>&nbsp;</td>
              </tr>            
              <tr>
                <td align="right" class="titulotexto">Registrar Salida:</td>
                <td><input type="radio"  name="asistencia" id="asistencia" value="salida" /></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
            <input type="button" class="boton_guardar" value="Guardar [F2]" onclick="registrarAsistencia()"/>            
            </form>
          </div>
          
                         
          <!-- Antecedentes -->
          <div id='tabs-3' style="display:none; height:300px;">
              <table width="100%" border="0">
              <tr>
                <td width="30%">&nbsp;</td>
                <td width="47%">&nbsp;</td>
                <td width="23%">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="right" class="titulotexto">Titulo:</td>
                <td>
                <select id="titulo_ant" name="titulo_ant">
                   <option value="conducta">Conducta</option>
                   <option value="vestimenta">Vestimenta</option>
                </select>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="right" class="titulotexto" valign="top">Detalle<span class="rojo">*</span>:</td>
                <td>
                <textarea name="detalle_ant" id="detalle_ant" rows="5" cols="30"></textarea>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
            <input type="button" class="boton_guardar" value="Guardar [F2]" onclick="registrarAntecedente()"/>            
          </div>
          
          <!-- Dias no Trabajados -->
          <div id='tabs-4' style="display:none; height:300px;">
              <table width="100%" border="0">
              <tr>
                <td width="30%">&nbsp;</td>
                <td width="47%">&nbsp;</td>
                <td width="23%">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="right" class="titulotexto">Titulo:</td>
                <td>
                <select id="titulo_falta" name="titulo_falta">
                   <option value="notrabajo">No Trabajo</option>
                   <option value="abandono">Abandono</option>
                </select>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="right" class="titulotexto" valign="top">Detalle<span class="rojo">*</span>:</td>
                <td>
                <textarea name="detalle_falta" id="detalle_falta" rows="5" cols="30"></textarea>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
            <input type="button" class="boton_guardar" value="Guardar [F2]" onclick="registrarFalta()"/>            
          </div>
          
          <!-- Historial -->
          <div id='tabs-5' style="display:none; height:300px;">         
             <br />
             <table width="95%" border="0" cellpadding="0" cellspacing="0" class="tablahistorial" align="center">
              <tr class="listaHistorial">
               <th width="40" align="center" class="celdah_1">Nº</th>
               <th width="147" class="celdah_1" align="center">Fecha</th>
               <th width="360" class="celdah_1" >Cliente</th>
               <th width="137" class="celdah_1" align="center">Entrada</th>     
               <th width="137" class="celdah_2" align="center">Salida</th> 
              </tr>                      
              <tbody id="listaHistorial"></tbody>
            </table>           
          </div>        
      </div>
  </div>

  </div>
</div>

</body>
</html>