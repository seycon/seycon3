<?php
include_once 'elements/procesar_contacto.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Contacto' ?>
        <?php include_once 'elements/head.php' ?> 
        <script type="text/Javascript">
            function contact_show(selected_radio){
                $('.contact_option').hide(600);
                $('#' + selected_radio).show(600);
            }
            
            function subscribe_validation(){
                if($('#email_register').val()==''){
                    alert('El Correo electronico es necesario');
                    return false;   
                }                    
                else{
                    if($('#subscribe_aviso').is(':checked'))
                        return true;
                    else{
                        alert('Debe leer y aceptar el aviso de privacidad.')
                        return false;
                    }
                }
                    
            }
            
            function message_validation(){
                if(($('#message_name').val()=='')||($('#message_email').val()=='')||($('#message_message').val()=='')){
                    alert('Hay campos que son necesarios en blanco')
                    return false;
                }
                else{
                    if($('#message_aviso').is(':checked')){
                        return true;       
                    }
                    else{
                        alert('Debe leer y aceptar el aviso de privacidad.')
                        return false; 
                    }
                }                    
            }
            function suggest_validation(){
                if($('#suggest_name').val()==''){
                    alert('Debe ingresar el nombre de la película')
                    return false;
                }
                else
                    return true;        
            }
        </script>
    </head>
    <body>                
        <div id="wrap">       
            <div id="small-logo">
                <a href="index.php"><img src="image/small-logo.png" alt="Autocinema Coyote" title="Autocinema Coyote"/></a>
            </div>
            <div id="sidebar-left">
                <div id="main-menu">
                    <?php include_once 'elements/main_menu.php'; ?>
                </div>                
            </div>
            <div id="sidebar-right">                
                <div id="content">                   
                    <div class="content-top">
                        <div class="content-title">
                            Contacto
                        </div>
                        <img src="image/content-bg-top-bigger2.png" alt=""/>
                    </div>                    
                    <div  style="letter-spacing: 0.2px;word-spacing: 2px;"id="content-bigger2" class="content-middle">                        
                        <div style="margin-bottom: 10px;">Te pedimos completar los cuadros de información para enviar tu solicitud:</div>
                        <div class="contact_select_options">
                            <p style="float: left;width: 70px;margin-top: 0px;">Deseo:</p>
                            <div id="asdf" style="float: left;z-index: 50">
                                <input name="select_option" onchange="contact_show('subscribe');" class="select_option" value="subscribe" checked="checked" type="radio"/>Suscribirme a la cartelera.<br/>
                                <input name="select_option" onchange="contact_show('message');" class="select_option" value="message"  type="radio"/>Enviar comentarios o dudas.<br/>
                                <input name="select_option" onchange="contact_show('suggest');" class="select_option" value="suggest" type="radio"/>Sugerir una película.<br/>
                            </div>
                            <div class="cleared"></div>
                        </div>
                        <div class="contact_options">
                            <div id="subscribe" class="contact_option">                                
                                Agrega tu correo para recibir la cartelera mensual al igual que promociones, descuentos, invitaciones y mucho más.                                
                                <!-- <form class="contact_form" method="post" action="http://app.icontact.com/icp/signup.php" name="icpsignup" id="icpsignup4363" accept-charset="UTF-8" onsubmit="return verifyRequired4363();" target="_blank" >-->
                                <!--                                <form class="contact_form" method="post" action="http://app.icontact.com/icp/signup.php" name="icpsignup" id="icpsignup4363" accept-charset="UTF-8" onsubmit="return subscribe_validation();" target="_blank" >
                                                                    <input type="hidden" name="redirect" value="http://www.icontact.com/www/signup/thanks.html"/>
                                                                    <input type="hidden" name="errorredirect" value="http://www.icontact.com/www/signup/error.html"/>
                                                                    <input type="hidden" name="listid" value="16634"/>
                                                                    <input type="hidden" name="specialid:16634" value="HDBS"/>
                                                                    <input type="hidden" name="clientid" value="917870"/>
                                                                    <input type="hidden" name="formid" value="4363"/>
                                                                    <input type="hidden" name="reallistid" value="1"/>
                                                                    <input type="hidden" name="doubleopt" value="0"/>
                                                                    <div><label>Correo Electrónico*</label><input type="text" name="fields_email" id="email_register"/></div>
                                                                    <div><label>Nombre y Apellido</label><input type="text" name="fields_fname" id="nombre_register"/></div>
                                                                    <div><label>Edad</label><input type="text" name="fields_lname" id="edad_register"/></div>                                    
                                                                    <div><input type="checkbox" name="aviso_register" id="subscribe_aviso"/><span>He leido y estoy de acuerdo con el <a target="_blank" href="files/aviso_de_privacidad.pdf">Aviso de Privacidad</a></span></div>
                                                                    <div><input type="submit" value="Registrate!" name="subscribe"/></div>
                                                                    <div class="cleared"></div>
                                                                </form>-->
                                <script type="text/javascript" src="http://app.icontact.com/icp/loadsignup.php/form.js?c=917870&l=11184&f=4363"></script>
                            </div>
                            <div id="message" style="display: none;" class="contact_option">
                                Utiliza este formulario para enviarnos tus dudas, comentarios o sugerencias.

                                <form  class="contact_form" action="contacto.php" method="post" onsubmit="return message_validation()">
                                    <div><label>Nombre*</label><input type="text" id="message_name" name="message_name"></div>
                                    <div><label>Correo Electrónico*</label><input id="message_email" type="text" name="message_email"></div>
                                    <div><label>Edad</label><input type="text" name="message_edad"></div>
                                    <div><label>Teléfono</label><input type="text" name="message_phone"></div>
                                    <div><label>Mensaje*</label><textarea style="width: 225px;height: 100px;" id="message_message" name="message_message"></textarea></div>      
                                    <div><input type="checkbox" name="message_aviso" id="message_aviso"/><span>He leido y estoy de acuerdo con el <a target="_blank" href="files/aviso_de_privacidad.pdf">Aviso de Privacidad</a></span></div>
                                    <div><input type="submit" value="Enviar!" name="message"/></div>
                                    <div class="cleared"></div>
                                </form>                                
                            </div>
                            <div id="suggest" style="display: none;" class="contact_option">
                                Sugiérenos tu película                                
                                <form  class="contact_form" action="contacto.php" method="post" onsubmit="return suggest_validation()">
                                    <div style="margin: 0px!important;"><input style="height: 28px;width: 480px!important;" type="text" id="suggest_name" name="suggest_name"></div>
                                    <div><input type="submit" value="Enviar!" name="suggest"/></div>
                                    <div class="cleared"></div>
                                </form>
                            </div>
                        </div>        
                        <div>El equipo Coyote esta muy agradecido por todo el apoyo que ha recibido a lo largo de la realizacion de este proyecto. Esperamos que lo disfruten con su familia, amigos o pareja y que los veamos seguido en el Autocinema.</div>
                        <p><span style="color: #DA3127">Si estas interesado en anunciar tu marca, escríbenos a  : </span>
                            <a style="color:#004D99;"
                               href="mailto:ventas@autocinemacoyote.com">ventas@autocinemacoyote.com</a></p>
                        <div class="cleared"></div><br/>
                    </div>
                    <div class="content-bottom">
                        <img src="image/content-bg-bottom-bigger2.png" alt=""/>                        
                    </div>                    
                </div>                             
                <div class="cleared"></div>
                <div id="sidebar-footer">
                    <div id="sidebar-social">
                        <?php include_once 'elements/social.php'; ?>
                    </div>
                    <div style="margin-top: 39px;" id="main-logo">
                        <a href="index.php"><img src="image/main-logo.png" alt="Pagina Principal" title="Pagina Principal"/></a>
                    </div>
                    <div class="cleared"></div>
                </div>
            </div>            
            <div class="cleared"></div>
        </div>
    </body>
</html>