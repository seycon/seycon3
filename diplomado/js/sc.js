// JavaScript Document



function wd(){
	var wid = ($(".tdmain").css("left")) + "px";
	$("body").css("position","absolute");
	$("body").css("left",wid);
}
/*window.onresize = function(){
	wd();
}*/
$(document).ready(function(){

//var vat = $(".tdmain").height() - volSel;


//$(".separator").height(vat);

$("#logoaccess").click(function(){
	document.location.href="../";
});


$("#terms_mail").click(function(){
	if($("#terms_mail").is(":checked")){
		$("input[name=Submit]").removeAttr("disabled");
	}else{
		$("input[name=Submit]").attr("disabled","disabled");
	}
});

$("#terms_cart").click(function(){
	if($("#terms_cart").is(":checked")){
		$("input[name=subForm]").removeAttr("disabled");
	}else{
		$("input[name=subForm]").attr("disabled","disabled");
	}
});


$(".menu div").click(function(){
	if($(this).attr("url") =="#"){
		$(".textAlert").html("").html("Pronto m&aacute;s informaci&oacute;n");
		$(".textAlert").css("top",$(this).offset().top+10);
		$(".textAlert").css("left",$(this).offset().left+$(this).width()-100);
		$(".textAlert").fadeIn(150);
	}
	if($(this).attr("url") =="#!"){
		return null;
	}else{
		window.location = $(this).attr("url");
			
	}
});

$(".menu div").mouseleave(function(){
	$(".textAlert").fadeOut(150);
});



$(".send").click(function(){
		var iform =	$(this).attr("fid");	
		
	var tName = $("#"+iform+" #fld").attr("value");	
	if(tName != ""){
		
		//$("#"+iform+" .confirmation").html("Enviando...");
		
		$.post("http://www.northcreative.com.mx/autocinemacoyote/home/contacto/send.php", {textFld: tName, optionForm:iform},function (data){
			//	if(data == "true"){
					 $("#"+iform+" .confirmation").html("¡Enviado!");
					 $("#"+iform+" #fld").attr("value"," ");
			//	}else{
			//		 $("#"+iform+" .confirmation").html("¡Intenta de nuevo!");
			//	}
		 });	
		
							 $("#"+iform+" .confirmation").html("¡Enviado!");
					 $("#"+iform+" #fld").attr("value"," ");

		
	}else{
		alert("¡Ups! No escribiste ninguna información, por favor completa el campo requerido.");
	}
});

$("#subForm").click(function(){
		var textFields = 0;
							 
	$("#comments input[name!='subForm']").each(function(){
		if($(this).val() == ""){
			$(this).css("border","#F00 2px solid");
		}else{
			$(this).css("border","#F00 1px dashed");
			textFields++;
		}
	});
	
	var txt = $("#comments textarea").val();
	
	if(txt == ""){
			$("#comments textarea").css("border","#F00 2px solid");
	}else{
			$("#comments textarea").css("border","#F00 1px dashed");
			textFields++;
	}
	
	
	if(textFields>=5){
		
		cName    = $("#comments #nombreC").val();
		cMail    = $("#comments #correoC").val();
		cAge     = $("#comments #edadC").val();
		cPhone   = $("#comments #telefonoC").val();
		cMessage = $("#comments #mensajeC").val();
		
		$.post("http://www.northcreative.com.mx/autocinemacoyote/home/contacto/send.php", {optionForm:"comments", nombreC:cName, correoC:cMail, edadC:cAge, telefonoC:cPhone, mensajeC:cMessage},function (data){
			/*	if(data == "true"){
					 $("#questionBox .confirmation").html("¡Enviado!");
					 $("#comments input[name!='subForm']").each(function(){
						$(this).val("");
					 });
					 $("#comments textarea").val("");
					 
				}else{
					 $("#questionBox .confirmation").html("¡Intenta de nuevo!");
				}*/
		 });
		
							 $("#questionBox .confirmation").html("¡Enviado!");
					 $("#comments input[name!='subForm']").each(function(){
						$(this).val("");
					 });
					 $("#comments textarea").val("");

		
	}else{
		alert("Por favor llena todos los campos resaltados.")
	}
							 
});



$(".imagePopup").click(function(){
	//	wd();
		$("body").css("overflow","hidden");
		
		app = Number($(this).attr("isx"));
		
		$("#popUp"+app).fadeIn(150,function(){
			$(this).click(function(){
				$(this).fadeOut(150,function(){
					$("body").css("overflow","auto");
				});
			});
		});
		event.preventDefault();
});


$("#closeButton").click(function(){
		$("#popUp").fadeOut(150,function(){
			$("body").css("overflow","auto");
	//		$("body").css("position"," ");
		});
		preventDefault();
});

$(".menuVenta").click(function(){
	_url = $(this).attr("url");
	
	if(_url =="#"){
		$(".textAlert").css("top",$(this).offset().top+10);
			$(".textAlert").css("left",$(this).offset().left+$(this).width()-10);
		$(".textAlert").fadeIn(150);
	}
	if(_url=="#!"){
		return null;
	}else{
		window.location = _url;
			
	}

	
});

	$("input:radio").click(function(){
		var crm = "#"+$(this).val();
		$(".box").slideUp();
		$(crm).slideDown();
	});

var icpForm4363 = document.getElementById('icpsignup4363');
if (document.location.protocol === "https:")	icpForm4363.action = "https://app.icontact.com/icp/signup.php";
						   
});

function verifyRequired4363() {  
	if ($("#email_register").val() == "") {
		$("#email_register").focus();
		alert("El campo de correo electrónico es necesario.");
		return false;
	}
		return true;
}