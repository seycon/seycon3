// JavaScript Document
function inicializar()
{
$(document).ready(function()
	{
		$("#fecha").datepicker({
			showOn: "button",
			buttonImage: "css/images/calendar.gif",
			buttonImageOnly: true
		});
		$("#formValidado").validate({
			rules: {
         		textoRequerido: {
            		required: true
         		},
         		enteroRequerido: {
            		required: true,
            		number: true
         		},
				entero: {
            		number: true
         		},
				emailRequerido: {
					required: true,
					email: true
				},
				email: {
					email: true
				},
				webRequerido: {
					required: true,
					url: true
				},
				web: {
					url: true
				},
				decimalRequerido: {
					required: true,
					number: true
				},
				decimal: {
					number: true,
				}
      		},
			messages: {
         		textoRequerido: {
            		required: "* Debe llenar este campo"
         		},
         		enteroRequerido: {
            		required: "* Debe llenar este campo",
            		number: "* Debe llenar un numero valido"
         		},
				entero: {
            		number: "* Debe llenar un numero valido"
         		},
				emailRequerido: {
					required: "* Debe llenar el email",
					email: "* Debe llenar un email valido"
				},
				email: {
					email: "* Debe llenar un email valido"
				},
				webRequerido: {
					required: "* Debe llenar la url.",
					url: "* Url no valida"
				},
				web: {
					url: "* Url no valida"
				},
				decimalRequerido: {
					required: "* Debe ingresar el costo",
					number: "* Debe ser un valor valido"
				},
				decimal: {
					number: "* Debe ser un valor valido"
				}
      		} 
		});
	});
}