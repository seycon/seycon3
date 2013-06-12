<?php

class LibroDiario{
	var $db;	
	
	public function LibroDiario($conectar){
		include ($conectar);
		$this->db = new MySQL();
	}
	
	
	private function cuentaCaja($idVendedor){
	 return ",(select cuentacaja from vendedor where idvendedor='$idVendedor')";	
	}
	
	public function insertarCaja(){   
	 $n = func_num_args();
	 $p = func_get_args();
	 $sql = "insert into librodiario values(null";
	 
	 for ($i=0;$i<$n;$i++){
	   $sql.=($i==4)? $this->cuentaCaja($p[$i]): ",'$p[$i]'";  
	 }
	 
	 $sql.=",1);";
	 
	 $this->db->consulta($sql); 	
	}
	
	private function cuentaXcobrar($cliente){
	return ",(select cuentaporcobrar from cliente where idcliente='$cliente')";	
	}
	
	
	public function insertarCuentaXCobrar(){   
	 $n = func_num_args();
	 $p = func_get_args();
	 $sql = "insert into librodiario values(null";
	 
	 for ($i=0;$i<$n;$i++){
	   $sql.=($i==4)? $this->cuentaXcobrar($p[$i]): ",'$p[$i]'";  
	 }
	 
	 $sql.=",1);";
	 
	 $this->db->consulta($sql); 	
	}
	
	
	private function cuentarecargo($cliente){
	return ",(select recargo from configuracionventa)";	
	}
	
	
	
	
	
	private function cuentaServicios($servicio){
	 return ",(select cuentaingreso from servicio where idservicio='$servicio')";
    }
	
	public function insertarCuentaServicios(){   
	 $n = func_num_args();
	 $p = func_get_args();
	 $sql = "insert into librodiario values(null";
	 
	 for ($i=0;$i<$n;$i++){
	   $sql.=($i==4)? $this->cuentaServicios($p[$i]): ",'$p[$i]'";  
	 }
	 
	 $sql.=",1);";
	 
	 $this->db->consulta($sql); 	
	}
	
	
	private function cuentaProducto($producto){
	 return ",(select cuentaingreso from producto where idproducto='$producto')";
    }
	
	public function insertarCuentaProductos(){   
	 $n = func_num_args();
	 $p = func_get_args();
	 $sql = "insert into librodiario values(null";
	 
	 for ($i=0;$i<$n;$i++){
	   $sql.=($i==4)? $this->cuentaProducto($p[$i]): ",'$p[$i]'";  
	 }
	 
	 $sql.=",1);";
	 
	 $this->db->consulta($sql); 	
	}
	
	
	public function insertarCuentasLD(){   
	 $n = func_num_args();
	 $p = func_get_args();
	 $sql = "insert into librodiario values(null";
	 
	 for ($i=0;$i<$n;$i++)
	   $sql.=",'$p[$i]'";  
	 
	 
	 $sql.=",1);";
	 
	 $this->db->consulta($sql); 	
	}
	
	 public function consulta(){
		  $p = func_get_args();
		 return $this->db->consulta($p[0]);
	 }
	
	
	 public function getCampo($campo, $sql){
		  return $this->db->getCampo($campo, $sql);
	}
            
	public function getMaxCampo($campo, $tabla){
		 return $this->db->getMaxCampo($campo, $tabla);
	}
	
	
	
}

//$objeto = new LibroDiario();
//$res = $objeto->insertarCaja('Ingreso','fecha',1,'Venta Servicios #$idnotaventa/cliente //$_GET[nombre_c]/*','1','500',0,'null','null','transaccioon','tipoCambio','ufv');
//
//
//echo $res;

?>