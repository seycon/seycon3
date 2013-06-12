var site = 'http://localhost/cakephp/fullmoondrivein2/'
$(document).ready(function(){
    $(".menu-item").click(function(){
        document.location.href= site + $(this).attr('url');
        //var valor = $(this).attr('url');
        //alert(valor);
    });
});

