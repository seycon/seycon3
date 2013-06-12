<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Black Admin v2</title>
		<style type="text/css">/*<![CDATA[*/
			@import "css/login.css";
		/*]]>*/</style>
	</head>
<body>

	<div id="container">
		<h1>Administracion Sistema</h1>
		<div id="box">
			<form action="usuario/Dusuario.php?transaccion=login" method="post">
			<p class="main">
				<label>Username: </label>
				<input name="nick" value="" /> 
				<label>Password: </label>
				<input type="password" name="password" value="">	
			</p>
			<p class="space">
				
				<input type="submit" value="Login" class="login" />
			</p>
			</form>
		</div>
 
	</div>
</body>
</html>