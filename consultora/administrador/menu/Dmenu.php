<?php
include("../conexion.php");
include("../aumentaComa.php");

class class_menu{
    public function consultar($transaccion){
        switch ($transaccion){
            case 'insertar':$this->guardar();
                break;
            case 'modificar':$this->modificar();
                Break;
            case 'eliminar':$this->eliminar();
                Break;            
        }
    }
    
    public function guardar(){
        $db = new MySQL();
        $idpagina    = $this->filtro($_POST['idpagina']);
	$titulo     = $this->filtro($_POST['titulo']);       
        $estado= '1';        
	$sql = "insert into menu  values (
	0,'$idpagina','$titulo','$estado')";
	$db->consulta($sql);
        header( 'Location: ../listar_menu.php');
	 exit();		
    }
    public function modificar(){
        $db = new MySQL();
        $idmenu   = $this->filtro($_POST['idmenu']);
        $idpagina = $this->filtro($_POST['idpagina']);
        $titulo   = $this->filtro($_POST['titulo']);        
	$sql = "update menu set idpagina='$idpagina',texto='$titulo'
                where idmenu=$idmenu";
	$db->consulta($sql);
        header( 'Location: ../listar_menu.php');
         exit();
    }
    public function eliminar(){
        $db = new MySQL();
        $idmenu = $this->filtro($_GET['idmenu']);
	$sql = "update menu set estado='2' where idpagina=$idmenu";
	$db->consulta($sql);
        header( 'Location: ../listar_menu.php');
         exit();
    }
    
    function filtro($cadena){
        return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);
    }

}
    $transaccion = (isset($_GET['transaccion']))?$_GET['transaccion']:'';
    $objeto= new class_menu();
    $objeto->consultar($transaccion);
?>