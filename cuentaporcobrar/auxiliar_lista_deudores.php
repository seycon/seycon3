<?php
include_once("../conexion.php");
$mysql = new MySQL();
$lista = "";
switch ($_POST["deudor"]) {
    case "cliente":
        $consulta = "select * from cliente where estado=1";
        $res = $mysql->consulta($consulta);
        while ($row = mysql_fetch_array($res)) {
            $lista = $lista . "<option value='" . $row['idcliente'] . "'>" . $row['nombre'] . "</option>";
        }

        break;
    case "trabajador":
        $consulta = "select * from trabajador where estado=1";
        $res = $mysql->consulta($consulta);
        while ($row = mysql_fetch_array($res)) {
            $lista = $lista . "<option value='" . $row['idtrabajador'] . "'>" . $row['nombre'] . "</option>";
        }
        break;
    default:
        $consulta = "select * from proveedor where estado=1";
        $res = $mysql->consulta($consulta);
        while ($row = mysql_fetch_array($res)) {
            $lista = $lista . "<option value='" . $row['idproveedor'] . "'>" . $row['nombre'] . "</option>";
        }
        break;
}

echo $lista;
?>
 