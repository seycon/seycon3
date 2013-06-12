<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Libreria Grafica WEB</title>

<script>

 var $$ = function(id){
  return document.getElementById(id);	
 }

 var iniWebGL = function(l){
	 gl = null;	 
	 try {
		gl = l.getContext("webgl") || l.getContext("experimental-webgl");
	 }catch(e){}
	 
	 if (!gl)
	 alert("Navegador No soporta webGL");
 }
 
 
var inicio = function(){
  iniWebGL($$("lienzo"));
  
  if (gl){
	 gl.clearColor(0.28,0.65,0.39,1.0); 
	 gl.enable(gl.DEPTH_TEST);
	 gl.depthFunc(gl.LEQUAL);
	 gl.clear(gl.COLOR_BUFFER_BIT|gl.DEPTH_BUFFER_BIT);
  }
}


var getShader = function(gl, id){
  var shaderS, codigo, hijo, shader;
  shaderS = $$(id);	
	
	if (!shaderS){
	  return null;	
	}
	
	codigo = "";
	hijo = shaderS.firstChild;
	while (hijo){
		if (hijo.nodeType == hijo.TEXT_NODE)
		codigo += hijo.textContent;
	  hijo = hijo.nextSibling;
	}
	
	if (shaderS.type == "x-shader/x-fragment"){
	 shader = gl.createShader(gl.FRAGMENT_SHADER);
	}else if (shaderS.type == "x-shader/x-vertex"){
	 shader = gl.createShader(gl.VERTEX_SHADER);	
	}else{
	 return null;	
	}
	
	gl.shaderSorce(shader,codigo);
	gl.compileShader(shader);
	if (!gl.getShaderParameter(shader,gl.COMPILE_STATUS)){
		alert("Problemas al compilar el shader "+gl.getShaderInfoLog(shader));
		return null;
	}
	
	
	return shader;
}

var iniciarShader = function(){
	var fshader = getShader(gl,"shader-fs");
	var vshader = getShader(gl,"shader-vs");
	
	var programa = gl.createProgram();
	gl.attachShader(programa,fshader);
	gl.attachShader(programa,vshader);
	gl.linkProgram(programa);
	
	
	if (gl.getProgramParameter(programa,gl.LINK_STATUS)){
	  alert("Problemas al crear el programa");	
	}
	
	gl.useProgram(programa);
	vertexP = gl.getAttribLocation(programa, "aVertexPosition");
	gl.enableVertexAttribArray(vertexP);
}

var iniciarBuffer = function(){
	b = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER,b);
	
	var vertice = [
	1.0,1.0,1.0,
	-1.0,1.0,0.0,
	1.0,-1.0,0.0,
	-1.0,-1.0,0.0
	];
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertice), gl.STATIC_DRAW);
}


var dibujarScena = function(){
  gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
  perspective = makePerspective(45, 640.0/480, 0,1, 100);	
  
  
  
  gl.bindBuffer(gl.ARRAY_BUFFER, b);
  gl.vertexAttribPointer(vertexP,3,gl.FLOAT,false,0,0);
  
  gl.drawArrays(gl.TRIANGLE_STRIP,0,4);
  
}


function loadIdentity() {
  mvMatrix = Matrix.I(4);
}

function multMatrix(m) {
  mvMatrix = mvMatrix.x(m);
}

function mvTranslate(v) {
  multMatrix(Matrix.Translation($V([v[0], v[1], v[2]])).ensure4x4());
}

function setMatrixUniforms() {
  var pUniform = gl.getUniformLocation(shaderProgram, "uPMatrix");
  gl.uniformMatrix4fv(pUniform, false, new Float32Array(perspectiveMatrix.flatten()));

  var mvUniform = gl.getUniformLocation(shaderProgram, "uMVMatrix");
  gl.uniformMatrix4fv(mvUniform, false, new Float32Array(mvMatrix.flatten()));
}

</script>

</head>

<body onload="inicio()">

<canvas  id='lienzo' width='500' height='500' style="border:1px solid;"></canvas>

<script id='shader-fs' type="x-shader/x-fragment">
 void main(void){
   gl_FragColor	= vec4(1.0,1.0,1.0,1.0); 
 }
</script>

<script id='shader-vs' type="x-shader/x-vertex">
 attribute vec3 posicion;
 uniform mat4 uMVMatrix;
 uniform mat4 uPMatrix;
 
 void main(void){
   gl_Position = uPMatrix * uMVMatrix * vec4(posicion,1.0);	 
 }

</script>

</body>
</html>