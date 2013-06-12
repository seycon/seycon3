<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script src="funcionesUtiles.js" type="text/javascript" ></script>
</head>

<body>

<p>
  <select name="rodrigo" id="rodrigo"  >
    <option value="asdf">asdf</option>
    <option value="2222">asdf</option>
    <option value="3434" selected="selected">2323</option>
  </select>
</p>

<select name="farell" id="farell" >
  <optgroup>
    <option value="1" >santa Cruz</option>
    <option value="2">La Paz</option>
    <option value="3" selected="selected">cochabanba</option>
</optgroup>
</select> 

<script>
seleccionarCombo("farell","2");
</script>

</body>
</html>