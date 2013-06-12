<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Image_Barcode Class Test</title>
  
  <script>
  function generar(){
	  var valor=document.getElementById('dato').value;	  
	  document.getElementById('imagen').src="docs/barcode_img.php?num="+valor+"&type=Code39&imgtype=png";	  
  }
  </script>
 
</head>
<body style="background-image: url(#FFFFFF);">




<div class="test">
<h2>Ean13:</h2>
<img id="imagen" src="docs/barcode_img.php?num=456&type=Code39&imgtype=png">
</div>


<input type="text" id='dato' onKeyUp="generar()" />
<input type="button" onClick="generar()"/>
</body>
</html>
