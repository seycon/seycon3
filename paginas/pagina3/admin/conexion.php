<?php

/**
* La Presente clase nos permite realizar
* Conexion con la Base de Datos
*/

class MySQL
{   

    public $conexion;   
			  
    public function MySQL()
	{   
        include ('bdlocal.php');
		$this->conexion = $bdlocal;				
	}
	   
	   
	public function iniciarTransaccion()
	{
		mysql_query("BEGIN");
	}
	   
	public function ejecutarTransaccion()
	{
		mysql_query("COMMIT"); 
	}
	
	public function abortarTransaccion()
	{
	    mysql_query("ROLLBACK");
	}
	
					
    public function consulta($consulta)
	 {     
		 $resultado = mysql_query($consulta,$this->conexion) or die(mysql_error());  
		 mysql_query("SET NAMES 'utf8'"); 
		 return $resultado;   		 
	 }  
		
	 public function getnumRow($consulta)
	 {
		 $resultado = mysql_query($consulta,$this->conexion) or die(mysql_error());  
		 return mysql_num_rows($resultado);   
	 }
		 
		 
	 public function getDatosArray($consulta, $nrocampos)
	 {
		 $sql = $this->consulta($consulta);
		 $result = array();
		 while($data = mysql_fetch_array($sql)) {
			$campos = array();
			for ($i=0;$i<$nrocampos;$i++){
			  $campos[$i] = $data[$i];	
			}
			array_push($result,$campos);
		  }
		  return $result;			   
	 }
		 
		 
	 public function imprimirComboGrupoArray($datosG, $textoOptG, $textoOpt)
	 {
		 $grupo = "";
		  for ($i=0; $i < count($datosG); $i++) {
			$fila = $datosG[$i];
			  if ( $fila[0] != $grupo){
				if ($grupo != "")
				 echo "</optgroup>";	
				 echo "<optgroup label='$textoOptG".ucfirst($fila[0])."'>"; 
				 $grupo = $fila[0];
			  }	
			  $num = func_num_args();
			  $nivel = "[N-".$fila[3]."]";					
			  if ($num > 3) {
			   $select = func_get_arg(3);
				  if ($select == $fila[1])
				   echo "<option value='$fila[1]' selected='selected'>$textoOpt".ucfirst($fila[2])."  $nivel</option>\n";
				  else
				   echo "<option value='$fila[1]'>$textoOpt".ucfirst($fila[2])."  $nivel</option>\n";
			  } else {								
			  echo "<option value='$fila[1]'>$textoOpt".ucfirst($fila[2])."  $nivel</option>\n";
			  }
		  }
		  if (count($datosG) > 0)
		  echo "</optgroup>";
	  }
		 		 
	   /**
	   * Obtine el mes determinado
	   */
	   public function mes($dato)
	   {
		 $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");		 		   return $meses[$dato-1];
	   } 
		 
	   /**
	   *Permite obtener un conjunto de options con los departamentos
	   */
	   public function getDepartamentos($opcionSeleccionar)
	   {
		  $departamento = array("Beni","Chuquisaca","Cochabamba","La Paz","Oruro","Pando","Potosí","Santa Cruz","Tarija"); 
		  for ($i = 0; $i < count($departamento); $i++) {
			 if ($opcionSeleccionar == $departamento[$i])				  
			  echo "<option value='$departamento[$i]' selected='selected'>$departamento[$i]</option>\n"; 
			 else
			  echo "<option value='$departamento[$i]'>$departamento[$i]</option>\n"; 
		  }
	   }
		 
		 public function arrayConsulta($sql)
		 {
			 $result = $this->consulta($sql);
			 $res = mysql_fetch_array($result);
			 return $res;	
		 }		 
			
		  public function getCampo($campo, $sql)
		  {
			  $result = $this->consulta($sql);
			  $aux_id = mysql_fetch_array($result);
			  return $aux_id[$campo];
		  }
		  
		  public function getCampos($sql)
		  {
			  $result = $this->consulta($sql);
			  return mysql_fetch_row($result);
		  }
		  
		  public function getMaxCampo($campo, $tabla)
		  {
			  $sql = "select max($campo) as 'id' from $tabla";
			  return $this->getCampo('id', $sql);
		  }
		  
		  public function getNextID($campo, $tabla)
		  {
			   $sql = "select max($campo) as id from $tabla";
			   $id = $this->getCampo('id',$sql);	
			   return ($id == '') ? 1 : $id + 1;
		  }		  
		  
		  public function imprimirTabla($sql, $numeroCampos)
		  {
			  $result = $this->consulta($sql);
			  while ($row = $this->getRow($result)){
				  echo "<tr>\n";
					 for ($i = 0; $i < $numeroCampos; $i++){
				  
						 echo "\t<td>".$row[$i]."</td>\n";
					 }
				  echo "</tr>\n";
			  }
		  }		  
		  
		 
		  public function GetFormatofecha($fecha, $delimitador)
		  {
			   $date = explode($delimitador,$fecha);
			   return  $fecha == "" ? "" : $date[2]."/".$date[1]."/".$date[0];
		  }		  
		  
		  public function imprimirCombo($sql)
		  {
			  $result = $this->consulta($sql);
			  while ($row = $this->getRow($result)){
				  $num = func_num_args();
				  if ($num > 1){
				  $select = func_get_arg(1);	
				   if ($select == $row[0])
					echo "<option value='$row[0]' selected='selected'>$row[1]</option>\n";
				   else
					echo "<option value='$row[0]'>$row[1]</option>\n";
				  }else{
					echo "<option value='$row[0]'>$row[1]</option>\n";	
				  }
			  }
		  }
		  
		  public function imprimirComboGrupo($sql, $textoOptG, $textoOpt)
		  {
			  $grupo = "";
			  $result = $this->consulta($sql);
			  while ($row = $this->getRow($result)) {
				  if ( $row[0] != $grupo) {
					if ($grupo != "")
					 echo "</optgroup>";	
					 echo "<optgroup label='$textoOptG".ucfirst($row[0])."'>"; 
					 $grupo = $row[0];
				  }	
				  $num = func_num_args();
				  if ($num > 3){
				   $select = func_get_arg(3);
					  if ($select == $row[1])
					   echo "<option value='$row[1]' selected='selected'>$textoOpt".ucfirst($row[2])."</option>\n";
					  else
					   echo "<option value='$row[1]'>$textoOpt".ucfirst($row[2])."</option>\n";
				  }else{								
				  echo "<option value='$row[1]'>$textoOpt".ucfirst($row[2])."</option>\n";
				  }
			  }
			  if ($this->getnumRow($sql) > 0)
			  echo "</optgroup>";
		  }
		  
		  public function getRow($res)
		  {
			  return mysql_fetch_row($res);					
		  }
		  
		  public function getArray($res)
		  {
			  return mysql_fetch_array($res);					
		  }		  
		  
		  public function arrayNombreCampos($sql)
		  {
			  $res = $this->consulta($sql);
			  $numCampos = mysql_num_fields($res);
			  $i=0;
			  while ($i < $numCampos) {
				$nombreCampo = mysql_field_name($res,$a);
				$array[$nombreCampo] = $nombreCampo; 
				$i++; 
			  }
			  return $array;
		  }
		  
		  public function tieneAccesoFile($estructura, $casoUso, $file)
		  {
		   if ($estructura != ""){	
			for ($i = 0; $i <= count($estructura); $i++) {
			  if ($estructura[$i]['Menu'] == $casoUso) {
			   $subMenu = $estructura[$i]['Submenu'];
				 for ($j=0;$j<=count($subMenu);$j++) {
				   if ($subMenu[$j]['Enlace'] == $file) {
					  return true; 
				   }
				 }
				 return false;
			  }
			}
		   }
			return false;
		  }						
		  
		  
		  public function privilegiosFile($estructura, $casoUso, $file, $file2)
		  {
		   $result = array('Modificar'=>'No','Eliminar'=>'No','Acceso'=>'No','File'=>'No');	
		   if ($estructura != ""){	
			for ($i=0;$i<=count($estructura);$i++){
			  if ($estructura[$i]['Menu'] == $casoUso){
			   $result['Modificar'] = $estructura[$i]['Modificar'];	
			   $result['Eliminar'] = $estructura[$i]['Eliminar'];
			   $subMenu = $estructura[$i]['Submenu'];
				 for ($j=0;$j<=count($subMenu);$j++) {
				   if ($subMenu[$j]['Enlace'] == $file) {
					  $result['Acceso'] = 'Si';
				   }
				   if ($subMenu[$j]['Enlace'] == $file2) {
					  $result['File'] = 'Si';
				   }
				 }
				 return $result;
			  }
			}
		   }
			return $result;
		  }
		  
		  public function getCampoArray($campo, $array)
		  {
			  return $array[$campo];
		  }
		  
	
		 	  
		  
} 
		 
		
		   


  
?>