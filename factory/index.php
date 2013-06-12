<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Factory</title>
        <link rel="stylesheet" href="estilo_index.css" type="text/css"/>
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
        <script type="text/javascript"  src="evento.js"></script>
</head>

<body>
    <div class="centro" >
        <img src="images/pantalla-cf.jpg" width="600px" height="100%" "/>
                <div class="form_login">
                    <form method="post" action="iniciar_session.php">
                    <table>
                        <tr></tr>
                        <td align="right"><label>Usuario: </label></td>
                        <td><input type="text" name="usuario" id="usuario" onkeyup="enter_entrar(event)"/></td>
                        </tr>
                        <tr>
                            <td align="right"><label>Contraseña:</label></td>
                            <td><input type="password" name="dato" id="dato" onkeyup="enter_entrar(event)"/></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="button" id="btn_entrar" value="Entrar" onclick="entrar();" /></td>
                        </tr>           
                    </table>
                </form>
                    <div id="aviso"  style="text-align: center;"></div>
            </div>
             
    </div>
</body>
</html>