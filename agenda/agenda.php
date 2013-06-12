<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agenda</title>

<link rel="stylesheet" href="../css/jquery-ui-1.8.13.custom.css" type="text/css"/>
<link rel="stylesheet" href="../css/estilos.css" type="text/css"/>

<script src="../js/jquery-1.5.1.min.js"></script>
<script src="../js/jquery-ui-1.8.13.custom.min.js"></script>
<script src="../js/ui/jquery.ui.core.js"></script>
<script src="../js/ui/jquery.ui.widget.js"></script>
<script src="../js/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="../js/jquery.validate.js"></script>
<script src="agendaAjax.js"></script>


<script>

$(document).ready(function()
{
	
	var events = ['29-09-2011', '2011-09-18', '2011-09-28', '2011-09-08'];
	$("#pick").datepicker({
		   onSelect: function(textoFecha, objDatepicker){
			            verEventoDia('ver_evento.php',textoFecha, 'derecha');
	                 },
   beforeShowDay: function(date) {                             
      var current  = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();                                              
      return jQuery.inArray(current, events) == -1
             ? [true, '']
             : [true, 'css-class-to-highlight', 'tool-tip-text'];                                              
   },

	autoSize: true,
	buttonImage: "css/images/calendar.gif",
	buttonImageOnly: true,
	dateFormat: 'yy-mm-dd'
	});

});	
</script>




<style type="text/css">


    .col{
		background-color:#F00;
	}

   .centro{
	   position:relative;
	   margin:0 auto;
	   width:960px;
	   height:700px;
	   background:url(Agenda.jpg);
   }
   
   .izquierda{
	  margin-left:54px;  
	  margin-top:105px;
	  position:relative;
	  float:left;
	  width:400px;
	  height:500px;
     
   }
   
     .derecha{
	  margin-left:54px;  
	  margin-top:105px;
	  position:relative;
	  float:left;
	  width:400px;
	  height:500px;
     
   }
   
   div.ui-datepicker{
	   font-size:22px;
   }
   
   

.leermas {
	font-weight: bold;
	color: #900;
	font-size: 10px;
	text-decoration:none;
}

     
.hora {
	position:relative;
	margin-top:75px;
	text-align:right;	
	width:890px;
	height:36px;

}

    #overlay{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000;
        z-index:1001;
		opacity:.75;
        -moz-opacity: 0.75;
        filter: alpha(opacity=75);
		visibility:hidden;
    }
	
	 #modal {
        position: absolute;
        top: 10%;
        left: 20%;
        width: 1200px;
  
       /* background: #CCC;*/
		color: #333;
        z-index:1002;
		visibility:hidden;


    }

</style>



</head>

<body onload="cargarFechaActual();mueveReloj();">

  <div id="overlay" class="overlay1"></div> 
<!-- fin base semi-transparente --> 
 
<!-- ventana modal -->  
	<div id="modal" class="modal1"></div>


   <div class="centro">


	<div class="hora">  
         <input type="text" id="reloj" size="10" style="font-size:16px;"> 
    </div>

       <div id="izquierda" class="izquierda">
            
            <div id="fech">
                <table width="390px" height="79" style="border:0.5px solid #CCC; text-align:center" cellspacing="0" bgcolor="#ECFFFF">
                    <tr>
                       <td height="60"  id="numFecha" style="font-size:84px; font-weight:bolder"></td>
                      <td height="60"  id="numFecha" style="font-size:84px"></td>
                    </tr>
                    <tr>
                        <td height="13" align="center"  id="textDia" style="font-size:20px;font-weight:bolder"></td>
                        <td id="mesA" align="right" style="font-size:20px;font-weight:bolder"></td>
                     </tr>
                </table>
            </div>
            
           
       
         <div id="pick">
           </div>
           
       </div>
       
       
       
       <div  id="derecha" class="derecha">
       <?
	      include('ver_evento.php');
	   ?>
       </div>
       
       
       
          
   </div>
   
   
   
   
   
	
</body>
</html>