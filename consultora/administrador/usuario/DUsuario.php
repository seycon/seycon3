<?php
include("../conexion.php");
include("../aumentaComa.php");

class class_usuario{
    public function consultar($transaccion){
        switch ($transaccion){
            case 'insertar':$this->guardar();
                break;
            case 'modificar':$this->modificar();
                Break;
            case 'eliminar':$this->eliminar();
                Break;
            case 'login' :$this->login();
                Break;
            case 'buscar':$this->buscar();
                Break;
        }
    }
    
    public function guardar(){
        $db = new MySQL();
	$nombre = $this->filtro($_POST['nombre']);
        $nick = $this->filtro($_POST['nick']);
        $password = $this->filtro($_POST['password']);
        $fecha= date("Y-m-d");
        $permiso= $this->filtro($_POST['permiso']);;
        $estado= '1';
        
	$sql = "insert into usuario  values (
	0,'$nombre','$nick','$password','$fecha','$permiso','$estado')";
	$db->consulta($sql);
        header( 'Location: ../listar_usuario.php');
	 exit();		
    }
    public function modificar(){
        $db = new MySQL();
        $idusuario = $this->filtro($_POST['idusuario']);
        $nombre = $this->filtro($_POST['nombre']);
        $nick = $this->filtro($_POST['nick']);
        $password = $this->filtro($_POST['password']);
        $permiso= $this->filtro($_POST['permiso']);;

	$sql = "update usuario set nombre='$nombre',nick='$nick',password='$password',permiso='$permiso' where idusuario=$idusuario";
	$db->consulta($sql);
        header( 'Location: ../listar_usuario.php');
         exit();
    }
    public function eliminar(){
        $db = new MySQL();
        $idusuario = $this->filtro($_GET['idusuario']);
	$sql = "update usuario set estado='2' where idusuario=$idusuario";
	$db->consulta($sql);
        header( 'Location: ../listar_usuario.php');
         exit();
    }
    public function buscar($nick,$password=NULL){
        $db = new MySQL();
        $cond=($password==NULL)?'':" AND password='$password'";
        $sql = "select * from usuario where nick='$nick' $cond";
	$tc = $db->getnumRow($sql);
        return $tc>=1;
    }
    public function login(){
        $nick   =$this->filtro($_POST['nick']);
        $pasword=$this->filtro($_POST['password']);
        if(!$this->buscar($nick,$pasword)){
            header( 'Location: ../index.php');
        }else{
            header( 'Location: ../main.php');
        }
    }
    function filtro($cadena){
        return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);
    }

}
    $transaccion = (isset($_GET['transaccion']))?$_GET['transaccion']:'';
    $usuario= new class_usuario();
    $usuario->consultar($transaccion);
?>