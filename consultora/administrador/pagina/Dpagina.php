<?php
include("../conexion.php");
include("../aumentaComa.php");

class class_pagina{
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
        $dominio    = $this->filtro($_POST['dominio']);
	$titulo     = $this->filtro($_POST['titulo']);
        $subtitulo     = $this->filtro($_POST['subtitulo']);
        $imagen1    = $this->filtro($_POST['imagen1']);
        $imagen2    = $this->filtro($_POST['imagen2']);
        $imagen3    = $this->filtro($_POST['imagen3']);
        $pie        = ($_POST['pie']);
        $plantilla  = $this->filtro($_POST['plantilla']);;
        $estado= '1';
        /*----------cambiamos nombre---------------------*/
        $id=$db->getNextID('idpagina','pagina');
        $origen1 ="../uploads/$imagen1";
        $destino1="../archivos/pagina{$id}img1.jpg";
        $imagen1="pagina{$id}img1.jpg";
        copy($origen1, $destino1);
        $origen2 ="../uploads/$imagen2";
        $destino2="../archivos/pagina{$id}img2.jpg";
        $imagen2="pagina{$id}img2.jpg";
        copy($origen2, $destino2);
        $origen3 ="../uploads/$imagen3";
        $destino3="../archivos/pagina{$id}img3.jpg";
        $imagen3="pagina{$id}img3.jpg";
        copy($origen3, $destino3);
        /*-----------------------------------------------*/
	$sql = "insert into pagina  values (
	0,'$dominio','$titulo','$subtitulo','$imagen1','$imagen2','$imagen3','$pie','$plantilla','100.5','$estado')";
	$db->consulta($sql);
        header( 'Location: ../listar_pagina.php');
	 exit();		
    }
    public function modificar(){
        $db = new MySQL();
        $idpagina = $this->filtro($_POST['idpagina']);
        $dominio  = $this->filtro($_POST['dominio']);
	$titulo   = $this->filtro($_POST['titulo']);
        $subtitulo= $this->filtro($_POST['subtitulo']);
        $imagen1  = $this->filtro($_POST['imagen1']);
        $imagen2  = $this->filtro($_POST['imagen2']);
        $imagen3  = $this->filtro($_POST['imagen3']);
        $pie      = ($_POST['pie']);
        $plantilla= $this->filtro($_POST['plantilla']);;

        /*----------cambiamos nombre---------------------*/
        $id=$idpagina;
        $origen1 ="../uploads/$imagen1";
        $destino1="../archivos/pagina{$id}img1.jpg";
        $imagen1="pagina{$id}img1.jpg";
        copy($origen1, $destino1);
        $origen2 ="../uploads/$imagen2";
        $destino2="../archivos/pagina{$id}img2.jpg";
        $imagen2="pagina{$id}img2.jpg";
        copy($origen2, $destino2);
        $origen3 ="../uploads/$imagen3";
        $destino3="../archivos/pagina{$id}img3.jpg";
        $imagen3="pagina{$id}img3.jpg";
        copy($origen3, $destino3);
        /*-----------------------------------------------*/

	$sql = "update pagina set dominio='$dominio',titulo='$titulo',subtitulo='$subtitulo',imagen1='$imagen1',imagen2='$imagen2',imagen3='$imagen3'
                                 ,pie='$pie',plantilla='$plantilla'
                where idpagina=$idpagina";
	$db->consulta($sql);
        header( 'Location: ../listar_pagina.php');
         exit();
    }
    public function eliminar(){
        $db = new MySQL();
        $idpagina = $this->filtro($_GET['idpagina']);
	$sql = "update pagina set estado='2' where idpagina=$idpagina";
	$db->consulta($sql);
        header( 'Location: ../listar_pagina.php');
         exit();
    }
    
    function filtro($cadena){
        return htmlspecialchars(strip_tags($cadena),ENT_QUOTES);
    }

}
    $transaccion = (isset($_GET['transaccion']))?$_GET['transaccion']:'';
    $objeto= new class_pagina();
    $objeto->consultar($transaccion);
?>