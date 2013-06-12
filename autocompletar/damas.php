<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Juego de Damas</title>


<script>

  var $$ = function(id){
	return document.getElementById(id);  
  }


//---Clase tablero
  var Ntablero = function(fil,col){
	 var fil = new Number(fil);
	 var col = new Number(col); 
	 var tabla = new Array(fil); 
	 newMatriz(); 	  
	 cargarPiezas();
	 var turno = 2; 
	 var filS;
	 var colS;
	  
	  this.getNumFilas = function(){
		return fil;   
	  }
	  
	  this.getNumCol = function(){
		return col;  
	  }
	  
	  this.getData = function(fila,columna){
		 return tabla[fila-1][columna-1];
	  }
	  
	  this.setData = function(fila,columna,value){
		tabla[fila-1][columna-1] = value;  
	  }	  
	  
	  function limpiarPaso(){
		for (var i=0;i<fil;i++){
		  for (var j=0;j<col;j++){
			  if (tabla[i][j] == 4){
			   tabla[i][j] = 1;
			  }
		  }
		}
	  }
	  
	  this.setPaso = function(fila,columna){
		 if (tabla[fila-1][columna-1] == 2){
			 turno = 2;
			 filS = fila;
			 colS = columna;
			 limpiarPaso();
			 if ((columna+1)<=col && (fila+1)<=fil){
				 if (tabla[fila][columna] == 1){
					 tabla[fila][columna] = 4;
				 }else{
					if ((columna+2)<=col && (fila+2)<=fil && tabla[fila][columna]!=2){
						if (tabla[fila+1][columna+1] == 1){
							tabla[fila+1][columna+1] = 4;
						}
					}
				 }
			 }
			 if ((columna-1)<=col && (fila+1)<=fil){
				 if (tabla[fila][columna-2] == 1){
					 tabla[fila][columna-2] = 4;
				 }else{
					if ((columna-2)<=col && (fila+2)<=fil && tabla[fila][columna-2]!=2){ 
					 if (tabla[fila+1][columna-3] == 1)
					  tabla[fila+1][columna-3] = 4;
					}
				 }
			 }
			 
		 }
		 if (tabla[fila-1][columna-1] == 3){
			 turno = 3;
			 filS = fila;
			 colS = columna;
			 limpiarPaso(); 
			 if ((columna+1)<=col && (fila-1)<=fil){
				 if (tabla[fila-2][columna] == 1){
					 tabla[fila-2][columna] = 4;
				 }else{
					if ((columna+2)<=col && (fila-2)<=fil && tabla[fila-2][columna]!=3){
						if (tabla[fila-3][columna+1] == 1)
					     tabla[fila-3][columna+1] = 4;
						
					}
				 }
			 }
			 if ((columna-1)<=col && (fila-1)<=fil){
				 if (tabla[fila-2][columna-2] == 1){
					 tabla[fila-2][columna-2] = 4;
				 }else{
					if ((columna-2)<=col && (fila-2)<=fil && tabla[fila-2][columna-2]!=3){
					 if (tabla[fila-3][columna-3] == 1)
					 tabla[fila-3][columna-3] = 4;
					}
				 }
			 }
		 }
		 
		 if (tabla[fila-1][columna-1] == 4){
			limpiarPaso();  
			switch(turno){
			  case 2:
			    realizarPasoFB(fila,columna);
			  break;
			  case 3:
			    realizarPasoFN(fila,columna);
			  break;	
			}
		 }
	  }
	  
	  
	  function realizarPasoFB(fila,columna){
		  var fsel = fila;
		  var csel = columna;
		  tabla[fsel-1][csel-1] = 2;
		  if (columna > colS){
		   while (fsel != filS){
			fsel--;
			csel--;  
			tabla[fsel-1][csel-1] = 1;
		   }
		  }
		  if (columna < colS){
		   while (fsel != filS){
			fsel--;
			csel++;  
			tabla[fsel-1][csel-1] = 1;
		   }
		  }
	  }
	  
	  function realizarPasoFN(fila,columna){
		  var fsel = fila;
		  var csel = columna;
		  tabla[fsel-1][csel-1] = 3;
		  if (columna > colS){
		   while (fsel != filS){
			fsel++;
			csel--;  
			tabla[fsel-1][csel-1] = 1;
		   }
		  }
		  if (columna < colS){
		   while (fsel != filS){
			fsel++;
			csel++;  
			tabla[fsel-1][csel-1] = 1;
		   }
		  }
	  }
	  
	  
	  function cargarPiezas(){
		 for (var k=1;k<=2;k++){ 
		 if (k == 1){
			var ini = 0;
			var fin = 3;
			var ele = 2;  
		 }else{
			var ini = 5;
			var fin = 8;
			var ele = 3;   
		 }		  
		 for (var i=ini;i<fin;i++){
	     dato = (i%2==0) ? 1 : 0; 
			  for (var j=0;j<col;j++){
				  if (dato == 1)
				  tabla[i][j] = ele;
				  dato = 3 - (2 + dato);
			  }		
	     }
		}
	  }
	  	  
	  
	 function newMatriz(){	
	  var dato;
	   for (var i=0;i<fil;i++){
	     dato = (i%2==0) ? 1 : 0; 
		 tablaCol = []; 
		  for (var j=0;j<col;j++){
		   tablaCol[j] = dato; 
		   dato = 3 - (2 + dato);
		  }
		 tabla[i] = tablaCol;
	   }		 
	 }
  }
 
 
//----Clase Panel
  var Npanel = function(tablero,p){
	var lienzo = p;
	var matriz = tablero;
	var images = {blanco: new Image(),negro: new Image(),fichaB: new Image(),fichaN: new Image(),paso: new Image()};
	cargarImagenes();
	
	
	this.setData = function(fila,columna,value){
		matriz.setData(fila,columna,value);
	}
	
	this.getData = function(fila,columna){
		 return matriz.getData(fila,columna);
	}
	
	this.setPaso = function(fila,columna){
		matriz.setPaso(fila,columna);
	}
	
	 this.paint = function(){
	 x = 0;
	 y = 0;
	   for (var j=0;j<matriz.getNumFilas();j++){
		posX = x ;
		for (var i=0;i<matriz.getNumCol();i++){
			posY = y ;
			var elemento = matriz.getData((j+1),(i+1));
			var img = getImagen(elemento);
			lienzo.drawImage(img,posY,posX,60,60);
			y = y + 61;
		}
		x = x + 61;
		y = 0;
	   }
	}
	
	
	function getImagen(num){	 
	 switch(num){
	   case 1:
	    return images.negro;
	   break; 	 
	   case 0:
	    return images.blanco;
	   break;
	   case 2:
	    return images.fichaB;
	   break;
	   case 3:
	    return images.fichaN;
	   break;
	   case 4:
	    return images.paso;
	   break;
	 }
	}
	
	 function cargarImagenes(){
	   images.blanco.src = "img/blanca.png";
	   images.negro.src  = "img/negra.png";
	   images.fichaB.src = "img/ficha2.png";
	   images.fichaN.src = "img/ficha1.png";
	   images.paso.src = "img/paso.png";
	 }	  
  } 
 
//-- Clase Eventos 
  var Neventos = function(npanel){	  
  var panel = npanel;
	  this.clickPanel = function(e){
		  colX = parseInt((e.clientX - 320)/60)+1;
		  filX = parseInt((e.clientY - 40)/60)+1;
		  panel.setPaso(filX,colX);
		  panel.paint();
	  }
  }
 
  window.onload = function(){
	 var lienzo = $$("lienzo").getContext("2d");
	 var tabla = new Ntablero(8,8);
	 var p = new Npanel(tabla,lienzo);
	 var e = new Neventos(p);
	 p.paint();
	 $$("lienzo").addEventListener("click",e.clickPanel,false);
  }

</script>
</head>

<body>

<canvas id="lienzo" width='487' height='485' style="position:absolute;left:320px;top:40px;border:4px solid;">
</canvas>



</body>
</html>