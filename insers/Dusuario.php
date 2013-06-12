<?php
    session_start(); 
    include("../conexion.php");
    $db = new MySQL();
    $tipo = $_GET["tipo"];
	
    if ($tipo == "lista") {
	    $personal = $_GET['personal']; 
        if ($personal == "fijo") {
	        $sql = "select idtrabajador,concat(nombre,' ',apellido)as 'nombre' from trabajador where 
			estado=1 order by nombre,apellido;"; 
        } else {
	        $sql = "select idpersonalapoyo,concat(nombre,' ',apellido)as 'nombre' from personalapoyo 
			order by nombre,apellido;"; 
        }
        echo "<option value=''>-- Seleccione --</option>";
        echo $db->imprimirCombo($sql);	
        exit();	 
    }
 
    if ($tipo == "bajaUsuario") {
		$sql = "select * from atencion where estado='atencion' and idusuariorestaurante=$_GET[usuario] ";
		$cantidad = $db->getnumRow($sql);
		echo $cantidad;
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
	    $sql = "select * from usuariorestaurante where login='$_GET[usuario]' $condicion and estado=1;"; 
        $cantidad = $db->getnumRow($sql);
	    echo $cantidad."---";
		
		$cantidad = 0;
		if ($_GET['idtrabajador'] != "" && $_GET['idtransaccion'] == "") {
		    $sql = "select * from usuariorestaurante where idtrabajador=$_GET[idtrabajador] and tipo='$_GET[tipoT]' 
		    and estado=1";
		    $cantidad = $db->getnumRow($sql);
	        echo $cantidad."---";
		} else {
		    echo "0"."---";	
		}
		$cantidad = 0;
		if ($_GET['idsucursal'] != "" && $_GET['idtransaccion'] != "") {
			$sql = "select * from usuariorestaurante where tipo='$_GET[tipoT]' and idtrabajador=$_GET[idtrabajador] and estado=1;";
			$datoUsuario = $db->arrayConsulta($sql);
			if ($datoUsuario['idsucursal'] != $_GET['idsucursal']) {			
				$sql = "select a.* from atencion a,usuariorestaurante u 
				where a.estado='atencion' and a.idusuariorestaurante=u.idusuario and u.tipo='$_GET[tipoT]' 
				and u.estado=1 and u.idtrabajador=$_GET[idtrabajador]"; 
				$cantidad = $db->getnumRow($sql);
				echo $cantidad."---";	
			} else {
			    echo "0"."---";	
			}
			
		} else {
			echo "0"."---";	
		}
		
		
		if ($_GET['idtrabajador'] != "") {
		   $sql = "select round(sum(a.efectivo),2)as 'debe' from atencion a,usuariorestaurante u 
            where a.idusuariorestaurante=u.idusuario and a.estado='cobrado' and u.tipo='$_GET[tipoT]'
			 and u.idtrabajador=$_GET[idtrabajador]
             and credito=0 and socio=0 group by u.idtrabajador; ";
	       $debe = $db->arrayConsulta($sql);
		   $sql = "select round(sum(acumulado),2)as 'haber',round(sum(nulo),4)as 'nulo',round(sum(cortesia),4)as 'cortesia' 
			,round(sum(credito),4)as 'credito' from entregadinero where idtrabajador=$_GET[idtrabajador] 
				and tipo='$_GET[tipoT]' and estado=1 group by idtrabajador;";
		   $haber = $db->arrayConsulta($sql);		
		   $total = $debe['debe'] - $haber['haber'];
		   echo $total."---";
		}
		
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
			    $sql = "select u.idusuario,u.tipo,left(concat(t.nombre,' ',t.apellido),14)as 'nombre','F' as 'tipo',(
				       select count(*) from atencion where estado='atencion' and idusuariorestaurante=u.idusuario
				       )as 'total'  
                      from usuariorestaurante u,trabajador t,cargo c where t.idtrabajador=u.idtrabajador 
                      and t.idcargo=c.idcargo and u.tipo='fijo' and u.estado=1 and u.idsucursal=$_GET[idsucursal];";	 
			  } else {
			    $sql = "select u.idusuario,u.tipo,left(concat(t.nombre,' ',t.apellido),14)as 'nombre','A' as 'tipo',(
				       select count(*) from atencion where estado='atencion' and idusuariorestaurante=u.idusuario
				       )as 'total'   
                       from usuariorestaurante u,personalapoyo t where t.idpersonalapoyo=u.idtrabajador 
                       and u.tipo='apoyo' and u.estado=1 and u.idsucursal=$_GET[idsucursal] ;";	  
			  }		
		
		 $trabajadores = $db->consulta($sql);
		 while ($dato = mysql_fetch_array($trabajadores)) {
			 if ($dato['total'] > 0){
			     $clase = "opcionMesero1";	 
			 } else {
				 $clase = "opcionMesero";
			 }
			 
			 
			 
		     $cadena = $cadena."<td width='16%'>
		     <div id='$clase' onclick='jumpAtencionTrabajador($dato[idusuario])'>
			 <div class='imagenMesero'></div>
			 <div id='textoMesero'>$dato[tipo]-$dato[nombre]</div>
			 </div>
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