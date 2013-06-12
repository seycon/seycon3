<?php

	class Dtrabajador
    {   
	
		public $db;   
				  
		public function Dtrabajador($basedatos)
		{   
			$this->db = $basedatos;	
		}
		
		public function insertarTrabajador()
		{
		  $foto1 = $_FILES['foto1']['name'];
		  $foto2 = $_FILES['foto2']['name'];
		  $urlfoto1 = "";
		  $urlfoto2 = "";
		  $diastrabajo = "$_POST[lunes]-$_POST[martes]-$_POST[miercoles]-$_POST[jueves]"
		          ."-$_POST[viernes]-$_POST[sabado]-$_POST[domingo]";
		  $horario = $this->setHorario();
		  if ($foto1 != '') {
			$ahora = time();  
			$urlfoto1 =  "files/$ahora".$foto1;
			copy($_FILES['foto1']['tmp_name'], $urlfoto1);
		  }

		  if ($foto2 != '') {
			$ahora = time();  
			$urlfoto2 =  "files/$ahora".$foto2;
			copy($_FILES['foto2']['tmp_name'], $urlfoto2);
		  }
	  
		  $fechaingreso =  $this->filtro($this->db->GetFormatofecha($_POST['fechaingreso'],'/'));
		  $fechafinalizacion = $this->filtro($this->db->GetFormatofecha($_POST['fechafinalizacion'],'/'));
		  $fechanacimiento = $this->filtro($this->db->GetFormatofecha($_POST['fechanacimiento'],'/'));	
		  $fechamoto = $this->filtro($this->db->GetFormatofecha($_POST['fechamoto'],'/'));
		  $fechaauto = $this->filtro($this->db->GetFormatofecha($_POST['fechaauto'],'/'));	
		  $fechaseguro = $this->filtro($this->db->GetFormatofecha($_POST['fechaseguro'],'/'));
		  $fechaafp = $this->filtro($this->db->GetFormatofecha($_POST['fechaafp'],'/'));
		  $sql = "INSERT INTO trabajador
		  (nombre,apellido,carnetidentidad,origenci,fechanacimiento,sexo,direccion
		  ,telefono,celular,nacionalidad,ciudad,numerolibreta,unidad
		  ,vivienda,descripcionvivienda,idcargo,seccion
		  ,emailpersonal,emailcorporativo,estadocivil,fotoprincipal
		  ,fotosecundaria,hijos,fechaingreso,sueldobasico,modalidadcontrato
		  ,fechafinalizacion,diastrabajo,tipohorario,horariodetrabajo
		  ,puntualidad,formadepago,nombrebanco,transporte,bonoproduccion
		  ,asistencia,idsucursal,numerocuenta,seguromedico,afp
		  ,fechaseguro,fechaafp,observacion
		  ,licenciamoto,licenciaauto,fechamoto,fechaauto
		  ,categoriaauto,control,idusuario,estado
           ) VALUES ('".$this->filtro($_POST['nombre'])
		  ."','".$this->filtro($_POST['apellido'])."','".$this->filtro($_POST['carnetidentidad'])
		  ."','".$this->filtro($_POST['origenci'])."','$fechanacimiento','".$this->filtro($_POST['sexo'])."','"
		  .$this->filtro($_POST['direccion'])."','".$this->filtro($_POST['telefono'])
		  ."','".$this->filtro($_POST['celular'])
		  ."','".$this->filtro($_POST['nacionalidad'])."','".$this->filtro($_POST['ciudad'])
		  ."','".$this->filtro($_POST['libreta'])."','".$this->filtro($_POST['unidad'])
		  ."','".$this->filtro($_POST['vivencia'])."','".$this->filtro($_POST['descripcionvivienda'])
		  ."','".$this->filtro($_POST['idcargo'])
		  ."','".$this->filtro($_POST['departamento'])."','".$this->filtro($_POST['email'])
		  ."','".$this->filtro($_POST['emailcorporativo'])."','".$this->filtro($_POST['estadocivil'])
		  ."','$urlfoto1','$urlfoto2','".$_POST['hijos']
		  ."','$fechaingreso','".$this->filtro($_POST['sueldobasico'])
		  ."','".$this->filtro($_POST['modalidadcontrato'])
		  ."','$fechafinalizacion','".$diastrabajo
		  ."','".$this->filtro($_POST['contratopara'])
		  ."','$horario','".$this->filtro($_POST['puntualidad'])
          ."','".$this->filtro($_POST['formadepago'])
		  ."','".$this->filtro($_POST['nombrebanco'])."','".$this->filtro($_POST['transporte'])
		  ."','".$this->filtro($_POST['bonoproduccion'])
		  ."','".$this->filtro($_POST['asistencia'])."','".$this->filtro($_POST['idsucursal'])
		  ."','".$this->filtro($_POST['numerocuenta'])
		  ."','".$this->filtro($_POST['seguromedico'])
		  ."','".$this->filtro($_POST['afp'])
		  ."','".$fechaseguro
		  ."','".$fechaafp 
		  ."','".$this->filtro($_POST['observaciones'])
		  ."','".$this->filtro($_POST['moto'])
		  ."','".$this->filtro($_POST['auto'])
		  ."','$fechamoto','$fechaauto','".$this->filtro($_POST['categoriaauto'])
		  ."','$_POST[control]',$_SESSION[id_usuario],1);";	
		  $this->db->consulta($sql);
		  $trabajador = $this->db->getMaxCampo('idtrabajador', 'trabajador');
		  $this->insertarFamiliares($trabajador);
		  $this->insertarHijos($trabajador);	
		  $this->insertarSucursalTrabajo($trabajador);
		  $this->insertarCajero($trabajador);
		  $this->insertarEducacion($trabajador);
		  $this->insertarIdiomas($trabajador);    
		  $this->insertarHabilidad($trabajador);
		  $this->insertarGarante($trabajador);
		  $this->insertarConyugue($trabajador);
		  $this->insertarHabitos($trabajador);
		  $this->insertarVendedor($trabajador);
		}
		
		
		public function insertarFamiliares($idtrabajador) {
		  $datos =  json_decode(stripcslashes($_POST['datosFamilia'])); 
		  $sql = "delete from familiares where idtrabajador=$idtrabajador";
		  $this->db->consulta($sql); 			
			for ($i = 0; $i < count($datos); $i++) {
				$fila = $datos[$i];
				$parentesco = $fila[0];
				$nombre = $fila[1];				
				$fecha = $this->db->GetFormatofecha($fila[2], "/");
				$direccion = $fila[3];
				$telefono = $fila[4];
				$sql = "insert into familiares(parentesco,nombre,fechanacimiento
				,direccion,telefono,idtrabajador)
				values ('$parentesco','$nombre','$fecha','$direccion','$telefono','$idtrabajador');";				
				$this->db->consulta($sql);
			}	
		}
		
		
		public function insertarHijos($idtrabajador) {
		  $datos =  json_decode(stripcslashes($_POST['datosHijos'])); 
		  $sql = "delete from hijos where idtrabajador=$idtrabajador";
		  $this->db->consulta($sql); 			
			for ($i = 0; $i < count($datos); $i++) {
				$fila = $datos[$i];
				$dependencia = $fila[0];
				$nombre = $fila[1];				
				$genero = $fila[2];
				$fecha = $this->db->GetFormatofecha($fila[3], "/");				
				$sql = "insert into hijos(tipodependencia,nombre,genero,fechanacimiento,idtrabajador)
				values ('$dependencia','$nombre','$genero','$fecha','$idtrabajador');";				
				$this->db->consulta($sql);
			}	
		}
		
		
		public function filtro($cadena)
		{
            return htmlspecialchars(strip_tags($cadena));
        }
		
		
		public function setHorario()
		{
		    switch($_POST['contratopara']) {
				case "Administracion":
				    $horario = "$_POST[am1]:$_POST[am1_minutos]-$_POST[am2]:$_POST[am2_minutos]/"
					    ."$_POST[pm1]:$_POST[pm1_minutos]-$_POST[pm2]:$_POST[pm2_minutos]";
				break;
				case "Ciudad":
				    $horario = "$_POST[ingreso1]/$_POST[sale1]";
				break;
				case "Campo":
				    $horario = "$_POST[dias1]/$_POST[dias2]";
				break;
			}
			return $horario;
		}
		
		
		public function getHorario($horario) {
			$datos = explode("/", $horario);
			$horainicio = explode("-", $datos[0]);
			$horafinal = explode("-", $datos[1]);
			$am1 = explode(":", $horainicio[0]);
			$am2 = explode(":", $horainicio[1]);
			$pm1 = explode(":", $horafinal[0]);
			$pm2 = explode(":", $horafinal[1]);
			return array($am1[0], $am1[1], $am2[0], $am2[1], $pm1[0], $pm1[1], $pm2[0], $pm2[1]);					
		}
	
	    public function insertarSucursalTrabajo($idtrabajador)
		{
		    if (isset($_POST['sucursalAsignada']) && $_POST['sucursalAsignada'] != "") {
				$sql = "delete from sucursaltrabajador where idtrabajador=$idtrabajador";
				$this->db->consulta($sql);
			    $sucursalesA = explode(',',$_POST['sucursalAsignada']);
				for ($j = 0; $j < count($sucursalesA); $j++) {
				    $sql = "insert into sucursaltrabajador values($idtrabajador, $sucursalesA[$j])";
				    $this->db->consulta($sql);	
				}
			}	
		}
		
		public function insertarVendedor($idtrabajador)
		{
		      if ($_POST['vende_active_check'] == 'vendedor') {	
			     $sql = "delete from vendedor where idtrabajador=$idtrabajador";
				 $this->db->consulta($sql);
				 $sql = "delete from rutatrabajo where idtrabajador=$idtrabajador";
				 $this->db->consulta($sql);
				 
			     if ($_POST['comisioncobros'] != "" || $_POST['comisionventas'] != "") { 
					  $sql = "INSERT INTO vendedor(idtrabajador,comisioncobros,comisionventas) 
					   VALUES ('$idtrabajador','".$this->filtro($_POST['comisioncobros'])
					   ."','".$this->filtro($_POST['comisionventas'])."');";
					  $this->db->consulta($sql);
				 }
				 if (isset($_POST['rutasAsignada']) && $_POST['rutasAsignada'] != "") {
					  $rutasA = explode(',',$_POST['rutasAsignada']);
					  for ($j = 0; $j < count($rutasA); $j++) {
						$sql = "insert into rutatrabajo(idtrabajador,idruta,estado)
						 values($idtrabajador, $rutasA[$j], 1)";
						$this->db->consulta($sql);	
					  }
				 }
			  }	
		}
			
		
		public function insertarCajero($idtrabajador)
		{
		    if ($_POST['cajero_check'] == 'cajero') {
				if ($_POST['textocaja1'] != "" || $_POST['textocaja2'] != "" 
				   || $_POST['textocaja3'] != ""  || $_POST['textocaja4'] != "" 
				    || $_POST['textocaja5'] != "" || $_POST['textocaja6'] != "" 
	                 ||  $_POST['textobanco1'] != "" || $_POST['textobanco2'] != ""
				      || $_POST['textobanco3'] != ""  ||  $_POST['textobanco4'] != "" 
				       || $_POST['textobanco5'] != "" || $_POST['textobanco6'] != "") {
				$sql = "delete from cajero where idtrabajador=$idtrabajador";
				$this->db->consulta($sql);
				$sql = "insert into cajero (idcajero,idtrabajador,textocaja1,textocaja2,textocaja3
					,textocaja4,textocaja5,textocaja6,textobanco1,textobanco2
					,textobanco3 ,textobanco4,textobanco5,textobanco6,cuentacaja1,cuentacaja2,
					cuentacaja3,cuentacaja4,cuentacaja5,cuentacaja6,cuentabanco1,cuentabanco2
					,cuentabanco3,cuentabanco4,cuentabanco5,cuentabanco6)
					values(null,$idtrabajador,'".$this->filtro($_POST['textocaja1'])
					."','".$this->filtro($_POST['textocaja2'])."','"
					.$this->filtro($_POST['textocaja3'])."','".$this->filtro($_POST['textocaja4'])
					."','".$this->filtro($_POST['textocaja5'])."','"
					.$this->filtro($_POST['textocaja6'])."','"
					.$this->filtro($_POST['textobanco1'])."','".$this->filtro($_POST['textobanco2'])
					."','".$this->filtro($_POST['textobanco3'])."','"
					.$this->filtro($_POST['textobanco4'])."','".$this->filtro($_POST['textobanco5'])
					."','".$this->filtro($_POST['textobanco6'])."','"
					.$this->filtro($_POST['cuentacaja1'])."','".$this->filtro($_POST['cuentacaja2'])."','"
					.$this->filtro($_POST['cuentacaja3'])."','".$this->filtro($_POST['cuentacaja4'])."','"
					.$this->filtro($_POST['cuentacaja5'])."','".$this->filtro($_POST['cuentacaja6'])
					."','".$this->filtro($_POST['cuentabanco1'])."','".$this->filtro($_POST['cuentabanco2'])
					."','".$this->filtro($_POST['cuentabanco3'])
					."','".$this->filtro($_POST['cuentabanco4'])."','".$this->filtro($_POST['cuentabanco5'])
					."','".$this->filtro($_POST['cuentabanco6'])."');";	
				 $this->db->consulta($sql);
		       }
			}	
			
		}
		
		public function modificarTrabajador()
		{			
		  $foto1 = $_FILES['foto1']['name'];
		  $foto2 = $_FILES['foto2']['name'];
		  $urlfoto1 = "";
		  $urlfoto2 = "";
		  $diastrabajo = "$_POST[lunes]-$_POST[martes]-$_POST[miercoles]-$_POST[jueves]"
		          ."-$_POST[viernes]-$_POST[sabado]-$_POST[domingo]";
		  $horario = $this->setHorario();
		  $archivofoto1 = "";
		  $archivofoto2 = "";
		  if ($foto1 != '') {
		      $ahora = time();  
			  $urlfoto1 =  "files/$ahora".$foto1;
			  copy($_FILES['foto1']['tmp_name'], $urlfoto1);
			  $archivofoto1 = " fotoprincipal='$urlfoto1',";
		  }
		  if ($foto2 != '') {
			  $ahora = time();  
			  $urlfoto2 =  "files/$ahora".$foto2;
			  copy($_FILES['foto2']['tmp_name'], $urlfoto2);
			  $archivofoto2 = " fotosecundaria='$urlfoto2',";
		  }
		  $fechaingreso =  $this->filtro($this->db->GetFormatofecha($_POST['fechaingreso'],'/'));
		  $fechafinalizacion = $this->filtro($this->db->GetFormatofecha($_POST['fechafinalizacion'],'/'));
		  $fechanacimiento = $this->filtro($this->db->GetFormatofecha($_POST['fechanacimiento'],'/'));	
		  $fechamoto = $this->filtro($this->db->GetFormatofecha($_POST['fechamoto'],'/'));
		  $fechaauto = $this->filtro($this->db->GetFormatofecha($_POST['fechaauto'],'/'));
		  $fechaseguro = $this->filtro($this->db->GetFormatofecha($_POST['fechaseguro'],'/'));
		  $fechaafp = $this->filtro($this->db->GetFormatofecha($_POST['fechaafp'],'/'));
		  $trabajador = $_POST['idtrabajador'];	
		  $sql = "update trabajador set nombre='".$this->filtro($_POST['nombre'])
		  ."',apellido='".$this->filtro($_POST['apellido'])
		  ."',carnetidentidad='".$this->filtro($_POST['carnetidentidad'])
		  ."',origenci='".$this->filtro($_POST['origenci'])."',fechanacimiento='$fechanacimiento',sexo='"
		  .$this->filtro($_POST['sexo'])."',direccion='"
		  .$this->filtro($_POST['direccion'])."',telefono='".$this->filtro($_POST['telefono'])
		  ."',celular='".$this->filtro($_POST['celular'])
		  ."',nacionalidad='".$this->filtro($_POST['nacionalidad'])."',ciudad='".$this->filtro($_POST['ciudad'])
		  ."',numerolibreta='".$this->filtro($_POST['libreta'])."',unidad='".$this->filtro($_POST['unidad'])
		  ."',vivienda='".$this->filtro($_POST['vivencia'])
		  ."',descripcionvivienda='".$this->filtro($_POST['descripcionvivienda'])
		  ."',idcargo='".$this->filtro($_POST['idcargo'])
		  ."',seccion='".$this->filtro($_POST['departamento'])
		  ."',emailpersonal='".$this->filtro($_POST['email'])
		  ."',emailcorporativo='".$this->filtro($_POST['emailcorporativo'])
		  ."',estadocivil='".$this->filtro($_POST['estadocivil'])
		  ."',$archivofoto1 $archivofoto2 hijos='".$_POST['hijos']
		  ."',fechaingreso='$fechaingreso',sueldobasico='".$this->filtro($_POST['sueldobasico'])
		  ."',modalidadcontrato='".$this->filtro($_POST['modalidadcontrato'])
		  ."',fechafinalizacion='$fechafinalizacion',diastrabajo='".$diastrabajo
		  ."',tipohorario='".$this->filtro($_POST['contratopara'])
		  ."',horariodetrabajo='$horario',puntualidad='".$this->filtro($_POST['puntualidad'])
          ."',formadepago='".$this->filtro($_POST['formadepago'])
		  ."',nombrebanco='".$this->filtro($_POST['nombrebanco'])."',transporte='".$this->filtro($_POST['transporte'])
		  ."',bonoproduccion='".$this->filtro($_POST['bonoproduccion'])
		  ."',asistencia='".$this->filtro($_POST['asistencia'])."',idsucursal='".$this->filtro($_POST['idsucursal'])
		  ."',numerocuenta='".$this->filtro($_POST['numerocuenta'])
		  ."',seguromedico='".$this->filtro($_POST['seguromedico'])
		  ."',afp='".$this->filtro($_POST['afp'])
		  ."',fechaseguro='".$fechaseguro
		  ."',fechaafp='".$fechaafp
		  ."',observacion='".$this->filtro($_POST['observaciones'])
		  ."',licenciamoto='".$this->filtro($_POST['moto'])
		  ."',licenciaauto='".$this->filtro($_POST['auto'])
		  ."',fechamoto='$fechamoto',fechaauto='$fechaauto',categoriaauto='".$this->filtro($_POST['categoriaauto'])
		  ."',control='$_POST[control]',idusuario=$_SESSION[id_usuario] where idtrabajador=$trabajador;";	
		  $this->db->consulta($sql);
		  $this->insertarFamiliares($trabajador);
		  $this->insertarHijos($trabajador);	
		  $this->insertarSucursalTrabajo($trabajador);
		  $this->insertarCajero($trabajador);
		  $this->insertarEducacion($trabajador);
		  $this->insertarIdiomas($trabajador);    
		  $this->insertarHabilidad($trabajador);
		  $this->insertarGarante($trabajador);
		  $this->insertarConyugue($trabajador);
		  $this->insertarHabitos($trabajador);			
		  $this->insertarVendedor($trabajador);
		}
		
		
		public function insertarEducacion($idtrabajador) 
		{
			if ($_POST['lugarprimaria'] != "" || $_POST['anioprimaria'] != "" || $_POST['aprovacionprimaria'] != ""
			 || $_POST['lugarsecundaria'] != "" || $_POST['aniosecundaria'] != "" || $_POST['aprovacionsecundaria'] != ""
			  || $_POST['lugaruniversitaria'] != "" || $_POST['aniouniversitaria'] != "" 
			   || $_POST['aprovacionuniversitaria'] != "") {
			    $sql = "delete from niveleducacion where idtrabajador=$idtrabajador";
			    $this->db->consulta($sql);
		        $sql = "insert into niveleducacion
				(lugarprimaria,anioprimaria,nivelprimaria,lugarsecundaria
				,aniosecundaria,nivelsecundaria,lugaruniversitaria
				,aniouniversitaria,niveluniversitaria,idtrabajador)
			    values('$_POST[lugarprimaria]', '$_POST[anioprimaria]', '$_POST[aprovacionprimaria]'
				, '$_POST[lugarsecundaria]', '$_POST[aniosecundaria]', '$_POST[aprovacionsecundaria]'
				, '$_POST[lugaruniversitaria]', '$_POST[aniouniversitaria]'
				, '$_POST[aprovacionuniversitaria]','$idtrabajador')";
			    $this->db->consulta($sql);	
			} 
		}
		
		public function insertarIdiomas($idtrabajador)
		{
			if ($_POST['descripcionidioma1'] != "" || $_POST['nivelidioma1'] != "" 
			   || $_POST['descripcionidioma2'] != "" || $_POST['nivelidioma2'] != "" 
			    || $_POST['descripcionidioma3'] != "" || $_POST['nivelidioma3'] != "") {
			    $sql = "delete from idiomas where idtrabajador=$idtrabajador";
			    $this->db->consulta($sql);
		        $sql = "insert into idiomas	(idioma1,nivel1,idioma2,nivel2
				,idioma3,nivel3,idtrabajador)
			    values('$_POST[descripcionidioma1]', '$_POST[nivelidioma1]', '$_POST[descripcionidioma2]'
				, '$_POST[nivelidioma2]', '$_POST[descripcionidioma3]', '$_POST[nivelidioma3]'
				, '$idtrabajador')";
			    $this->db->consulta($sql);	
			}
		}
		
		public function insertarHabilidad($idtrabajador)
		{
			if ($_POST['descripcionhabilidad1'] != "" || $_POST['nivelhabilidad1'] != "" 
			   || $_POST['descripcionhabilidad2'] != "" || $_POST['nivelhabilidad2'] != "" 
			    || $_POST['descripcionhabilidad3'] != "" || $_POST['nivelhabilidad3'] != "") {
			    $sql = "delete from habilidad where idtrabajador=$idtrabajador";
			    $this->db->consulta($sql);
		        $sql = "insert into habilidad(habilidad1,nivel1,habilidad2,nivel2,habilidad3,nivel3,idtrabajador)
			    values('$_POST[descripcionhabilidad1]', '$_POST[nivelhabilidad1]', '$_POST[descripcionhabilidad2]'
				, '$_POST[nivelhabilidad2]', '$_POST[descripcionhabilidad3]', '$_POST[nivelhabilidad3]'
				, '$idtrabajador')";
			    $this->db->consulta($sql);	
			}
		}
				
		public function insertarGarante($idtrabajador)
		{			
			if ($_POST['nombregarante'] != "" || $_POST['apellidogarante'] != "" 
			   || $_POST['direcciongarante'] != "" || $_POST['telefonogarante'] != "" 
			    || $_POST['nacionalidadgarante'] != "" || $_POST['profesiongarante'] != "" 
				 || $_POST['ingresogarante'] != "" || $_POST['conyuguegarante'] != "" 
				  || $_POST['ingresoconyugue'] != "" 
			       || $_POST['parentescogarante'] != "" || $_POST['tiempogarante'] != "") {
				$sql = "delete from garante where idtrabajador=$idtrabajador";
				$this->db->consulta($sql);			
				$sql = "insert into garante(
				nombre,apellido,direccion,telefono,parentesco,
				 tiempo,vivienda,estadocivil,nacionalidad,profesion,
				   ingresomensual,nombreconyugue,ingresoconyugue,idtrabajador)
				values('$_POST[nombregarante]','$_POST[apellidogarante]','$_POST[direcciongarante]'
				,'$_POST[telefonogarante]','$_POST[parentescogarante]', '$_POST[tiempogarante]'
				, '$_POST[viviendagarante]', '$_POST[estadocivilgarante]', '$_POST[nacionalidadgarante]'
				, '$_POST[profesiongarante]', '$_POST[ingresogarante]', '$_POST[conyuguegarante]'
				, '$_POST[ingresoconyugue]','$idtrabajador')";
				$this->db->consulta($sql);	
		    }
			
		}
		
		public function insertarConyugue($idtrabajador)
		{
			if (($_POST['nombreconyugue'] != "" || $_POST['nacimientoconyugue'] != "" 
			   || $_POST['empresatrabajo'] != "" || $_POST['celularconyugue'] != "" 
			    || $_POST['direccionconyugue'] != "") && $_POST['estadocivil'] == "Casado") {
				$sql = "delete from conyugue where idtrabajador=$idtrabajador";
				$this->db->consulta($sql);		
				$fecha =  $this->filtro($this->db->GetFormatofecha($_POST['fechaconyugue'],'/'));	
				$sql = "insert into conyugue(nombre,lugarnacimiento,empresa,situacion
				,celular,fechanacimiento,direccion,idtrabajador)
				values('$_POST[nombreconyugue]','$_POST[nacimientoconyugue]','$_POST[empresatrabajo]'
				,'$_POST[situacioncivil]','$_POST[celularconyugue]', '$fecha'
				, '$_POST[direccionconyugue]','$idtrabajador')";
				$this->db->consulta($sql);	
		    }
			
		}
		
		public function insertarHabitos($idtrabajador)
		{			    
			$sql = "delete from habitos where idtrabajador=$idtrabajador";
			$this->db->consulta($sql);	
			$sql = "insert into habitos(alcohol,fumar,medicamento,drogas,mariguana,
					descripcionmedicamentos,descripciondroga,descripcionalcohol,
					descripcionfuma,salud,enfermedad,sida,accidente,descripcionenfermedad,
					descripcionaccidente,idtrabajador)values
			       ('$_POST[salcohol]','$_POST[sfuma]', '$_POST[smedicamento]', '$_POST[sdroga]',
			        '$_POST[smariguana]','$_POST[cualmedicamento]','$_POST[cualdroga]',
					'$_POST[cualalcohol]','$_POST[cualfuma]','$_POST[salud1]',
					'$_POST[salud2]','$_POST[salud3]','$_POST[salud4]'
					,'$_POST[cualenfermedad]','$_POST[cualaccidente]',$idtrabajador);";
			$this->db->consulta($sql);   	
			
		}
		
		
	}


	
?>