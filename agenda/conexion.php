<?
class MySQL{   

            public $conexion;   
			  
            public function MySQL(){   
              if(!isset($this->conexion)){   
			  
              /*  $this->conexion = (mysql_connect("localhost","root","")) or die(mysql_error()."localhosh ERROT");   
                mysql_select_db("bdseycon",$this->conexion) or die(mysql_error());   */
				include ('../bdlocal.php');
				$this->conexion=$bdlocal;
				
              }   
            } 
			
	        public function consulta($consulta){     
				$resultado = mysql_query($consulta,$this->conexion) or die(mysql_error());  
				mysql_query("SET NAMES 'utf8'"); 
				return $resultado;   		 
           }  
		   
		    public function getCampo($campo, $sql){
				$result = $this->consulta($sql);
				$aux_id = mysql_fetch_array($result);
				return $aux_id[$campo];
			}
            
			public function getMaxCampo($campo, $tabla){
				$sql = "select max($campo) as id from $tabla";
				return $this->getCampo('id', $sql);
			}
			
			public function imprimirTabla($sql){
				$result = $this->consulta($sql);
				$numeroCampos = mysql_num_fields($result);
				while ($row = $this->getRow($result)){
		
					   for ($i = 0; $i < $numeroCampos; $i++){
						   echo "\t<td>".$row[$i]."</td>\n";
					   }
   						   //echo "\t<td><img src='icon_edit_s.png'></td>\n";
						   //echo "\t<td><img src='icon_cross.png'></td>\n";
     
				}
			}
			
			public function imprimirAgenda($sql){
				$result = $this->consulta($sql);
				
				while ($row = $this->getRow($result)){		
				           $variables = $row[0]."&abuscar=".$row[0]."&campo=idevento";		   
						   echo "\t<td>".$row[1]."</td>\n";
						   echo "\t<td>".substr( $row[2], 0, 35 )." 
						   <a href='../listar_evento.php?$variables' target='_blank' class='leermas'> Leer mas...</a></td>\n";										
                            return $row[0];
				}
				
			}



			
			public function imprimirCombo($sql){
				$result = $this->consulta($sql);
				while ($row = $this->getRow($result)){
					echo "\t<option value=$row[0]>$row[1]</option>\n";
				}
			}
			
			public function getRow($sql){
				//$res = $this->consulta($sql);
				return mysql_fetch_row($sql);
					
			}
			
			public function getArray($sql){
				$res = $this->consulta($sql);
				return mysql_fetch_array($res);
					
			}
			
			
			public function getCampoArray($campo, $array){
				return $array[$campo];
			}
			
			
			public function getM(){
			  echo "en construccion";
		   }
		   
           }
		   
		   function fechaAMD($fecha){
			       list($d,$m,$a)=explode("/",$fecha);
                   return $a."/".$m."/".$d;
		   }
  
?>