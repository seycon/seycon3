<?php

	class Dcliente
    {   
	
		public $db;   
				  
		public function Dcliente($basedatos)
		{   
			$this->db = $basedatos;	
		}
		
		public function insertarCliente()
		{
		  $fechacontacto = $this->db->GetFormatofecha($_POST['fechacontacto'],"/");	
		  $fechapropietario = $this->db->GetFormatofecha($_POST['fechapropietario'],"/");	
		  $fechaaniversario = $this->db->GetFormatofecha($_POST['fechaaniversario'],"/");
			  
		  $sql = "INSERT INTO cliente
			(nombre,nombrenit,telefono,nit,ciudad,ruta,nombrecontacto
			,celular,emailcontacto,fechacontacto,emailcorporativocontacto
			,skypecontacto,nombrepropietario,celularpropietario,
			emailpropietario,fechapropietario,emailcorporativopropietario,
			skypepropietario,modalidad,idusuario,estado
			,fechaaniversario,direccionoficina,idsucursal,registro,recargo,nroguardias
			) VALUES ('".$this->filtro($_POST['nombre'])."','"
			 .$this->filtro($_POST['nombrenit'])."','".$this->filtro($_POST['telefono'])
			 ."','".$this->filtro($_POST['nit'])."','".$this->filtro($_POST['ciudad'])
			 ."','".$this->filtro($_POST['ruta'])."','".$this->filtro($_POST['nombrecontacto'])."','"
			 .$this->filtro($_POST['celularcontacto'])."','".$this->filtro($_POST['emailcontacto'])
			 ."','".$fechacontacto."','".$this->filtro($_POST['emailcorporativocontacto'])
			 ."','".$this->filtro($_POST['skypecontacto'])
			 ."','".$this->filtro($_POST['nombrepropietario'])."','".$this->filtro($_POST['celularpropietario'])
			 ."','".$this->filtro($_POST['emailpropietario'])."','".$fechapropietario
			 ."','".$this->filtro($_POST['emailcorporativopropietario'])
			 ."','".$this->filtro($_POST['skypepropietario'])
			 ."','".$this->filtro($_POST['modalidad'])."',$_SESSION[id_usuario],'1','$fechaaniversario','"
			 .$this->filtro($_POST['direccionoficina'])."','$_POST[idsucursal]'
			 ,'$_POST[registro]','$_POST[recargo]','$_POST[nroguardias]');";
		   $this->db->consulta($sql);  
		   $cliente = $this->db->getMaxCampo('idcliente', 'cliente');
		   $this->insertarContrato($cliente);
		}
		
		public function filtro($cadena)
		{
            return htmlspecialchars(strip_tags($cadena));
        }
				
		public function modificarCliente()
		{			
		  $fechacontacto = $this->db->GetFormatofecha($_POST['fechacontacto'],"/");	
		  $fechapropietario = $this->db->GetFormatofecha($_POST['fechapropietario'],"/");	
		  $fechaaniversario = $this->db->GetFormatofecha($_POST['fechaaniversario'],"/");
		  $cliente = $_POST['idcliente'];		  
		  $sql = "update cliente set nombre='".$this->filtro($_POST['nombre'])."',nombrenit='"
			 .$this->filtro($_POST['nombrenit'])."',telefono='".$this->filtro($_POST['telefono'])
			 ."',nit='".$this->filtro($_POST['nit'])."',ciudad='".$this->filtro($_POST['ciudad'])
			 ."',ruta='".$this->filtro($_POST['ruta'])."',nombrecontacto='".$this->filtro($_POST['nombrecontacto'])
			 ."',celular='".$this->filtro($_POST['celularcontacto'])
			 ."',emailcontacto='".$this->filtro($_POST['emailcontacto'])
			 ."',fechaaniversario='$fechaaniversario',fechacontacto='".$fechacontacto
			 ."',emailcorporativocontacto='".$this->filtro($_POST['emailcorporativocontacto'])
			 ."',skypecontacto='".$this->filtro($_POST['skypecontacto'])
			 ."',idsucursal='".$this->filtro($_POST['idsucursal'])
			 ."',registro='".$this->filtro($_POST['registro'])
			 ."',nroguardias='".$this->filtro($_POST['nroguardias'])
			 ."',nombrepropietario='".$this->filtro($_POST['nombrepropietario'])
			 ."',celularpropietario='".$this->filtro($_POST['celularpropietario'])
			 ."',emailpropietario='".$this->filtro($_POST['emailpropietario'])."',fechapropietario='".$fechapropietario
			 ."',emailcorporativopropietario='".$this->filtro($_POST['emailcorporativopropietario'])
			 ."',skypepropietario='".$this->filtro($_POST['skypepropietario'])
			 ."',direccionoficina='".$this->filtro($_POST['direccionoficina'])
			 ."',recargo='".$this->filtro($_POST['recargo'])
			 ."',modalidad='".$this->filtro($_POST['modalidad'])."',idusuario='$_SESSION[id_usuario]' where 
			 idcliente=$cliente;";
		   $this->db->consulta($sql);  
		   $this->insertarContrato($cliente); 
		   
		}
		
		
		public function insertarContrato($idcliente)
		{
		    $datos =  json_decode(stripcslashes($_POST['datosContrato'])); 
		    $sql = "delete from contratocliente where idcliente=$idcliente";
		    $this->db->consulta($sql); 			
			for ($i = 0; $i < count($datos); $i++) {
				$fila = $datos[$i];
				$fechainicio = $this->db->GetFormatofecha($fila[0], "/");
				$fechafinal = $this->db->GetFormatofecha($fila[1], "/");
				$idservicio = $fila[2];	
				$precio = $fila[3];		
				$cantidad = $fila[4];							
				$sql = "insert into contratocliente(fechainicio,fechafinal,idservicio
				,precio,cantidad,idcliente)
				values ('$fechainicio','$fechafinal','$idservicio'
				,'$precio','$cantidad','$idcliente');";				
				$this->db->consulta($sql);
			}	
		}	
				
		
	}
	
?>