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
                if($('#subscribe_email').val()=='')
                    return false;
                else
                    return true;
            }
            function message_validation(){
                if(($('#message_name').val()=='')||($('#message_email').val()==''))
                    return false;
                else
                    return true;
            }
            function suggest_validation(){
                if($('#suggest_name').val()=='')
                    return false;
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
                    <div class="content-title">
                        Contacto
                    </div>
                    <div class="content-top">
                        <img src="image/content-bg-top-bigger.png" alt=""/>
                    </div>                    
                    <div  style="letter-spacing: 0.2px;word-spacing: 2px;"id="content-bigger" class="content-middle">
                        <div>En Autocinema Coyote nosotros valoramos tu opinion, comentarios y sugerencias.<br/>
                        </div>        
                        <p>Te pedimos completar los cuadros de información para enviar tu solicitud:</p>
                        <div class="contact_select_options">
                            <p style="float: left;width: 70px;margin-top: 0px;">Deseo:</p>
                            <div id="asdf" style="float: left;z-index: 50">
                                <input name="select_option" onchange="contact_show('subscribe');" class="select_option" value="subscribe" checked="checked" type="radio"/>Suscribirme a la cartelera mensual.<br/>
                                <input name="select_option" onchange="contact_show('message');" class="select_option" value="message"  type="radio"/>Enviar comentarios o dudas.<br/>
                                <input name="select_option" onchange="contact_show('suggest');" class="select_option" value="suggest" type="radio"/>Sugerir una película.<br/>
                            </div>
                            <div class="cleared"></div>
                        </div>
                        <div class="contact_options">
                            <div id="subscribe" class="contact_option">
                                SUBSCRIPCION:
                                Agrega tu correo para recibir la cartelera mensual al igual que promociones, descuentos, invitaciones y mucho más.
                                <!-- Begin MailChimp Signup Form             
                                <form onsubmit="return subscribe_validation();" action="http://fullmoondrivein.us5.list-manage.com/subscribe/post?u=a31fb104d79bdde31d5a0e276&amp;id=72ed99a3eb" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="contact_form" target="_blank">
                                    <div class="input text required">
                                        <label for="mce-EMAIL">Correo Electrónico : </label>
                                        <input type="text" maxlength="180" type="email" value="" name="EMAIL" id="mce-EMAIL" >
                                    </div>
                                    <div class="submit">
                                        <input type="submit" value="Registrate!" name="subscribe" id="mc-embedded-subscribe" >
                                    </div>
                                </form>
                                End mc_embed_signup-->                
                                <form action="contacto.php" method="post">
                                    <div><label>Correo Electrónico*</label><input type="text" name="subscribe_email" id="subscribe_email"/></div>
                                    <div><label>Nombre y Apellido</label><input type="text" name="subscribe_nombre" id="subscribe_nombre"/></div>
                                    <div><label>Edad</label><input type="text" name="subscribe_edad" id="subscribe_edad"/></div>
                                    <div><input type="checkbox" name="subscribe_aviso" id="subscribe_aviso"/><label>He leido y estoy de acuerdo con el <a target="_blank" href="files/aviso_de_privacidad.pdf">Aviso de Privacidad</a></label></div>
                                    <div class="cleared"></div>
                                </form>
                            </div>
                            <div id="message" style="display: none;" class="contact_option">
                                Utiliza este formulario para enviarnos tus dudas, comentarios o sugerencias.                                
                                <form action="contacto.php" method="post" onsubmit="return message_validation();">
                                    <div><label>Nombre*</label><input type="text" name="name" id="message_name"></div>
                                    <div><label>Correo Electrónico*</label><input type="text" name="email" id="message_email"></div>
                                    <div><label>Teléfono</label><input type="text" name="phone" id="message_phone"></div>
                                    <div><label>Mensaje</label><input type="text" name="message" id="message_message"></div>                                    
                                    <div><input type="submit" name="message_form" value="Enviar!"/>
                                </form>                                    
                                <p>* Estos Campos son necesarios.</p>
                            </div>
                            <div id="suggest" style="display: none;" class="contact_option">
                                Sugiérenos tu película
                                <?php
                                //echo $this->Form->create('Recommended', array('url' => array('controller' => 'recommendeds', 'action' => 'add_suggest'),
                                //  'class' => 'contact_form', 'onsubmit' => 'return suggest_validation();'));
                                //echo $this->Form->input('name', array('label' => '', 'id' => 'suggest_name'));
                                //echo $this->Form->end(__('Enviar!'));
                                ?>
                            </div>
                        </div>        
                        <p>El equipo Coyote esta muy agradecido por todo el apoyo que ha recibido a lo largo de la realizacion de este proyecto. Esperamos que lo disfruten con su familia, amigos o pareja y que los veamos seguido en el Autocinema.</p>
                        <p><span style="color: #DA3127">Si estas interesado en anunciar tu marca, escríbenos a  : </span>
                            <a style="color:#004D99;"
                               href="mailto:ventas@autocinemacoyote.com">ventas@autocinemacoyote.com</a></p>
                        <div class="cleared"></div><br/>
                    </div>
                    <div class="content-bottom">
                        <img src="image/content-bg-bottom-bigger.png" alt=""/>                        
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