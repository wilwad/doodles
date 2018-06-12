var doc_ready = [];

doc_ready.push("$('btn'  ).css({background: 'yellow'});");
doc_ready.push("$('body' ).css({background: 'pink'});");
doc_ready.push("$('table').css({background: 'green'});");

$(document).ready(function(){
 var max  = doc_ready.length;
 var code = "";

 for(var idx = 0; idx < max; idx++){
   code = doc_ready[idx];
   $('body').append("<script type='text/javascript'>"+ code +"</script>");
 }
});
