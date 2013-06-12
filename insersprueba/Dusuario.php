<?php
    session_start(); 
    include("../conexion.php");
    $db = new MySQL();
    $tipo = $_GET["tipo"];
 
    if ($tipo == "lista") {
	    $personal = $_GET['personal']; 
        if ($personal == "fijo") {
	        $sql = "select idtrabajador,concat(nombre,' ',apellido)as 'nombre' from trabajador where estado=1 order by nombre,apellido;"; 
        } else {
	        $sql = "select idpersonalapoyo,concat(nombre,' ',apellido)as 'nombre' from personalapoyo order by nombre,apellido;"; 
        }
        echo "<option value=''>-- Seleccione --</option>";
        echo $db->imprimirCombo($sql);	
        exit();	 
    }
 
 
    if ($tipo == "trabajador") {
        $sucursal = $_GET['sucursal'];
        $sql = "select idtrabajador,concat(nombre,' ',apellido)as 'nombre' from trabajador where estado=1 "
		    ." and idsucursal=$sucursal order by nombre,apellido;"; 
        echo "<option value=''>-- Seleccione --</option>";
        echo $db->imprimirCombo($sql);	
        exit();	 
    }
 
    if ($tipo == "validarUsuario") {	
        $cantidad = 0;
        if ($_GET['idtransaccion'] != "") {   
	        $condicion = " and idusuario!=$_GET[idtransaccion] "; 
	    } else {
		    $condicion = "";
	    }	 
	    $sql = "select * from usuariorestaurante where login='$_GET[usuario]' $condicion;"; 
        $cantidad = $db->getnumRow($sql);
	    echo $cantidad;
    }
 
    if ($tipo == "usuarios") {
        $personal = $_GET['personal']; 
        if ($personal == "fijo") {
	        $sql = "select  u.idusuario,concat(t.nombre,' ',t.apellido)as 'nombre' from trabajador t,usuariorestaurante u 
	        where u.estado=1 and u.idtrabajador=t.idtrabajador and u.tipo='fijo' order by nombre,apellido;"; 
        } else {
	        $sql = "select u.idusuario,concat(p.nombre,' ',p.apellido)as 'nombre' from personalapoyo p,usuariorestaurante u
	        where u.estado=1 and u.idtrabajador=p.idpersonalapoyo and u.tipo='apoyo' order by nombre,apellido;"; 
        }
        echo $db->imprimirCombo($sql);	
        exit();	  
    }
 
    if ($tipo == "listaUsuarios") {
	    $i = 0;	
	    $cadena = "";
	    for($k = 1; $k <= 2; $k++) {
		    if ($k == 1) {
			    $sql = "select u.idusuario,u.tipo,left(concat(t.nombre,' ',t.apellido),15)as 'nombre' 
                      from usuariorestaurante u,trabajador t,cargo c where t.idtrabajador=u.idtrabajador 
                      and t.idcargo=c.idcargo and u.tipo='fijo' and u.estado=1 and u.idsucursal=$_GET[idsucursal];";	 
			  } else {
			    $sql = "select u.idusuario,u.tipo,left(concat(t.nombre,' ',t.apellido),15)as 'nombre'  
                       from usuariorestaurante u,personalapoyo t where t.idpersonalapoyo=u.idtrabajador 
                       and u.tipo='apoyo' and u.estado=1 and u.idsucursal=$_GET[idsucursal] ;";	  
			  }		
		
		 $trabajadores = $db->consulta($sql);
		 while ($dato = mysql_fetch_array($trabajadores)) {
		     $cadena = $cadena."<td width='16%'>
		     <div id='opcionMesero' onclick='jumpAtencionTrabajador($dato[idusuario])'><div id='textoMesero'>$dato[nombre]</div></div>
		     </td>";	
		     $i++;
		     if ($i == 6) {
		         $i = 0;
		         echo "<tr>$cadena<tr>";
		         $cadena = "";	 
		     }
		 }
		
	   }
		
		if ($i < 6) {
		    for ($j = $i; $j <= 6; $j++) {
		  	    $cadena = $cadena."<td width='16%'></td>";
		    }
		    echo $cadena;
		}
		exit();
 }
 
 
?> 