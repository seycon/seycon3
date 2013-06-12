<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php $title = 'Autocinema Coyote &middot; Donde Estamos' ?>
        <?php include_once 'elements/head.php' ?>         
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script>
            $(document).ready(function(){
                initialize();
            })
            var stockholm = new google.maps.LatLng(19.385058,-99.249701);
            var parliament = new google.maps.LatLng(19.385058,-99.249701);
            var marker;
            var map;

            function initialize() {
                var mapOptions = {
                    zoom: 16,                    
                    mapTypeId: google.maps.MapTypeId.HYBRID,
                    center: stockholm
                };

                map = new google.maps.Map(document.getElementById('map_canvas'),
                mapOptions);

                marker = new google.maps.Marker({
                    map:map,
                    draggable:true,
                    title: 'Autocinema Coyote',
                    animation: google.maps.Animation.DROP,
                    position: parliament
                });
                google.maps.event.addListener(marker, 'click', toggleBounce);
            }

            function toggleBounce() {

                if (marker.getAnimation() != null) {
                    marker.setAnimation(null);
                } else {
                    marker.setAnimation(google.maps.Animation.BOUNCE);
                }
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
                <div style="width: 540px;" id="content">                    
                    <div class="content-top">
                        <div class="content-title">
                            Dónde Estamos&nbsp;<span style="font-size:15px;color:#0055A6;">  (1525 Carretera Federal México - Toluca. Palo Alto, Cuajimalpa de Morelos.)</span>
                        </div>
                        <img src="image/content-bg-top-bigger2.png" alt=""/>
<!--                        <img  id="mapa-cine" style="position: absolute;margin-top: -13px;margin-left: 20px;z-index: 50;"  src="image/mapa.png" alt="Click para agrandar la imagen" title="Click para agrandar la imagen"/>
                        <img  id="zoom" style="position: absolute;margin-top: -3px;margin-left: 30px;z-index: 60;cursor: pointer;"  src="image/zoom.png" alt="Click para agrandar la imagen" title="Click para agrandar la imagen"/>-->
                    </div>                    
                    <div id="content-bigger2" style="height: 550px!important;" class="content-middle" style="padding: 0px 20px;width: 526px;">                        
                        <div id="map_canvas" style="position: absolute!important;margin-top: -14px;margin-left: -10px;width:576px;height: 582px;"></div>
                    </div>
                    <div class="content-bottom">
                        <img src="image/content-bg-bottom-bigger2.png" alt=""/>                        
                    </div>                    
                    <div style="padding:10px;;border:15px #427fc0 solid;border-radius:15px;width:570px;margin-top:10px;">
                        <p style="margin-top: 0px;">
                            <span class="red" style="font-size: 19px;">¿Dónde está el nuevo Autocinema Coyote?</span><br/>
                            A 500 metros de donde se unen Constituyentes y Reforma. La dirección exacta es #1525 Carretera Federal México - Toluca. La entrada
                            es sobre la carretera México - Toluca junto al Centro Comercial Lilas (osea, el Autocinema queda justo frente al edificio del 
                            Pantalón, pero dando a la carretera, y se entra por la carretera). Está en la lateral derecha de la carretera. junto a la MAP.
                        </p>
                        <p>
                            <span class="red">Dos indicaciones IMPORTANTES para llegar:</span><br/>
                            1)&nbsp;&nbsp; Si vienen sobre Constituyentes y desembocan en la carretera, recuerden seguirse del lado IZQUIERDA del camino, es decir, no se suban 
                            por la subida que está a la DERECHA (ya que esta los terminará llevando a Santa Fe)<br/>
                            2)&nbsp;&nbsp; Al iniciar la carretera, pasando el condominio Reserva Bezares, es importante salirse a la lateral a mano 
                            derecha, y pasando la MAP, ahí está el Autocinema!
                            -Referencias importantes para llegar son el Centro Comercial Lilas, La MAP y el Edificio del Pantalón.

                        </p>
                        <div style="font-size: 15px;">
                            <span class="red">Estamos a tan solo:</span><br/>
                            <span class="red">1 </span>minuto del edificio del Pantalón y el Centro Comercial Arcos Bosques<br/>
                            <span class="red">1 </span>minuto del Centro Comercial Plaza Lilas<br/>
                            <span class="red">1 </span>minuto del cruce de Constituyentes y Reforma<br/>
                            <span class="red">10 </span>minuto de Santa Fe<br/>
                            <span class="red">25 </span>minuto del cruce de Periférico y Constituyentes<br/>
                            <span class="red">25 </span>minuto del cruce de Periférico y Reforma<br/>
                            <span class="red">40 </span>minuto del sur de la ciudad (viniendo por los puentes de Av. Del Poeta y cruzando por Santa Fe)
                        </div>
                        <p class="red" style="margin-bottom: 0px;font-size: 18px;">"No olvides entrar a la lateral de la Carretera México Toluca".</p>
                    </div>
                    <div class="cleared"></div>
                    <div style="width: 620px;margin: 10px 0px;">
                        <div style="float: left;width: 310px;">
                            <img  id="zoom" style="position: absolute;margin-top: 4px;margin-left: 4px;z-index: 60;cursor: pointer;"  src="image/zoom.png" alt="Click para agrandar la imagen" title="Click para agrandar la imagen"/>
                            <img style="width: 300px;" src="image/mapa.png" alt="Mapa Autocinema" title="Mapa Autocinema"/>
                        </div>
                        <div style="float: left;text-align: center;">
                            <!--<img width="310" height="302" src="image/entrada.jpg" title="Entrada Autocinema Coyote" alt="Entrada Autocinema Coyote"/>-->
                            <!--<iframe width="560" height="315" src="http://www.youtube.com/embed/Th6-pMs6WGc" frameborder="0" allowfullscreen></iframe>-->
                            <iframe width="310" height="302" src="http://www.youtube.com/embed/Th6-pMs6WGc" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div class="cleared"></div>
                    </div>                    
                    <div style="padding:10px;border:15px #427fc0 solid;border-radius:15px;width:570px;margin-top:10px;">
                        <p style="margin-top: 0px"><span class="red">DESDE EL SUR (OPCIÓN A – SANTA FE):</span><br/>
                            - Llegar a Las Aguilas y tomar los puentes de Av. Del Poeta hacia Santa Fe<br/>
                            - Al llegar a Santa Fe, tomar la carretera hacia Reforma y al llegar a Reforma, dar vuelta en U y entrar a la carretera México – Toluca (pegarse a la izquierda en la Y)<br/>
                            - En el primer minuto de la carretera, pasando la Plaza Lilas, pegarse a la derecha y entrar a la lateral donde se encuentra la MAP y justo al lado, el Autocinema Coyote.
                        </p>
                        <p><span class="red">DESDE EL SUR (OPCIÓN B – PERIFÉRICO Y CONSTITUYENTES)</span><br/>
                            - Tomar periférico hacia el norte y salirse en Constituyentes – Parque Lira<br/>
                            - Tomar Constituyentes hacia Santa Fe y al llegar a donde se juntan Constituyentes y Reforma, tomar hacia la izquierda para entrar a la carretera México Toluca y en el primer minuto de la carretera, pasando a la Plaza Lilas, pegarse a la derecha y entrar a la lateral donde se encuentra la MAP y justo al lado, el Autocinema Coyote.
                        </p>
                        <p><span class="red">DESDE EL NORTE</span><br/>
                            - Tomar periférico hacia Reforma y salirse en Reforma y avanzar sobre Reforma hacia las lomas todo derecha.<br/>
                            - Al terminar Reforma, seguirse derecho para entrar a la carretera México – Toluca (pegarse a la izquierda en la Y)<br/>
                            - En el primer minuto de la carretera, pasando la Plaza Lilas, pegarse a la derecha y entrar a la lateral donde se encuentra la MAP y justo al lado, el Autocinema Coyote.
                        </p>
                        <p><span class="red">DESDE EL CENTRO (O DESDE COL. CONDESA, COL. ROMA O COL. JUAREZ)</span><br/>
                            - Tomar Reforma hacia las lomas todo derecho y al terminar Reforma, seguirse derecho para entrar a la carretera México – Toluca (pegarse a la izquierda en la Y) y en el primer minuto de la carretera, pasando la Plaza Lilas, pegarse a la derecha y entrar a la lateral donde se encuentra la MAP y justo al lado, el Autocinema Coyote.
                        </p>
                        <p style="margin-bottom: 0px;"><span class="red">DESDE EL PONIENTE (BOSQUES DE LAS LOMAS, TECAMACHALCO, INTERLOMAS, ETC.)</span><br/>
                            - Dirigirse hacia Bosques de las Lomas y ya en Bosques de las Lomas, dirigirse hacia la Plaza Comercial Lilas.<br/>
                            - Al estar frente a la Plaza Comercial Lilas, tomar la lateral de la carretera y a unos cuantos metros se encuentra la MAP y justo al lado, el Autocinema Coyote.
                        </p>
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
        <div style="display: none;width: 99%;" id="main-popup" >            
            <div id="capa" style="height: 1020px;">&nbsp;</div>
            <div style="position:absolute;width: 685px;top: 880px" id="main-popup-content">
                <img id="close" src="image/fancy_close.png" alt="Cerrar" title="Cerrar"/>
                <div style="width: 700px" id="main-popup-content-content"> 
                    <img  style="width: 685px;" src="image/mapa2.jpg" alt="Mapa del Autocinema" />
                </div>
            </div>
        </div>       
        <script type="text/Javascript">
            $(document).ready(function() {                   
                margen = ($(window).width()-700)/2;
                $("#main-popup-content").css('left',margen + 'px');
                $("#main-popup").click(function(){
                    $(this).css('display','none');
                });
                $("#close").click(function(){
                    $("#main-popup").css('display','none');
                });        
                $("#mapa-cine").click(function(){                               
                    $("#main-popup").css('display','block');                    
                });
                $("#zoom").click(function(){ 
                    wheight = $("#wrap").height()+40;                    
                    $("#capa").css('height',wheight+'px');
                    $("#main-popup").css('display','block');                    
                });
            });
        </script>
    </body>
</html>