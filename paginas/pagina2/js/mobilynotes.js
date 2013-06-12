/* ==========================================================
 * MobilyNotes
 * date: 17.12.2010
 * author: Marcin Dziewulski
 * web: http://www.mobily.pl or http://playground.mobily.pl
 * email hello@mobily.pl
 * Free to use under the MIT license.
 * ========================================================== */
(function($){$.fn.mobilynotes=function(options){var defaults={init:"rotate",positionMultiplier:5,title:null,showList:true,autoplay:true,interval:4000};var sets=$.extend({},defaults,options);return this.each(function(){var $t=$(this),note=$t.find(".note"),size=note.length,autoplay;var notes={init:function(){notes.css();if(sets.showList){notes.list()}if(sets.autoplay){notes.autoplay()}notes.show()},random:function(l,u){return Math.floor((Math.random()*(u-l+1))+l)},css:function(){var zindex=note.length;note.each(function(i){var $t=$(this);switch(sets.init){case"plain":var x=notes.random(-(sets.positionMultiplier),sets.positionMultiplier),y=notes.random(-(sets.positionMultiplier),sets.positionMultiplier);$t.css({top:y+"px",left:x+"px",zIndex:zindex--});break;case"rotate":var rotate=notes.random(-(sets.positionMultiplier),sets.positionMultiplier),degrees="rotate("+rotate+"deg)";$t.css({"-webkit-transform":degrees,"-moz-transform":degrees,"-o-transform":degrees,filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation="+rotate+")",zIndex:zindex--})}$t.attr("note",i)})},zindex:function(){var arr=new Array();note.each(function(i){arr[i]=$(this).css("z-index")});var z=Math.max.apply(Math,arr);return z},list:function(){$t.after($("<ul />").addClass("listNotes"));var ul=$t.find(".listNotes"),title=new Array();if(sets.title!=null){note.each(function(i){title[i]=$(this).find(sets.title).text()})}else{title[0]="Bad selector!"}for(x in title){$t.next(".listNotes").append($("<li />").append($("<a />").attr({href:"#",rel:x}).text(title[x])))}},autoplay:function(){var i=1,autoplay=setInterval(function(){i==size?i=0:"";var div=note.eq(i),w=div.width(),left=div.css("left");notes.animate(div,w,left);i++},sets.interval)},show:function(){$t.next(".listNotes").find("a").click(function(){var $t=$(this),nr=$t.attr("rel"),div=note.filter(function(){return $(this).attr("note")==nr}),left=div.css("left"),w=div.width(),h=div.height();clearInterval(autoplay);notes.animate(div,w,left);return false})},animate:function(selector,width,position){var z=notes.zindex();selector.animate({left:width+"px"},function(){selector.css({zIndex:z+1}).animate({left:position})})}};notes.init()})}}(jQuery));