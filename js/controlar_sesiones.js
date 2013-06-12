// JavaScript Document
function ajaxx() {
	  if (window.XMLHttpRequest) {
		  return new XMLHttpRequest();
	  } else if (window.ActiveXObject) {
		  return new ActiveXObject("Microsoft.XMLHTTP");
	  }
  }
  

  
  
  function $v(id){
	  return document.getElementById(id);
  }


    function login(){
		$v('modal').style.visibility = 'visible';
		$v('overlay').style.visibility = 'visible';
	     peticion = ajaxx();   
	     peticion.open('GET', 'login.php?url_ver='+document.URL, true); 
	     peticion.onreadystatechange = function() { 	
		   if (peticion.readyState == 4) {
			   $v('modal').innerHTML = peticion.responseText;
			   $v('modal').scrollTop = 200;
			   $v('modal').scrollLeft = 5000;
			   $v('tabla_login').style.backgroundColor = '#f6a828';
			   $v('tabla_login').className = 'tabla_login';
		   } 
	    } 
	    peticion.send(null); 
    }	
	
	

      function eventos(){
	  if (window.document.addEventListener) {
		window.document.addEventListener("onmousemove", mequedo, false);
		window.document.addEventListener("keydown", mequedo, false);
      } else {

		window.document.attachEvent("onmousemove", mequedo, false);
		window.document.attachEvent("onkeydown", mequedo, false);
      }
	  }

	  
	  
	  
	  function mequedo(){
	    clearTimeout(meLargo);
	    largarse();
      }
	  
     
	 function largarse(){
	   meLargo = setTimeout("login()", 5000); //1200000
     }
	 