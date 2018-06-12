// Maybe: you could use the code as key so you don't have duplicated code being exec'd
// Might be overkill for now 

var doc_ready = [];

doc_ready.push({code: "$('body').append(\"<div id='container1'></div>\");", 
                ran: false,
                priority: 1
               });

doc_ready.push({code: "$container.css({display: 'none'});", 
                ran: false,
                priority: 3
               });
               
doc_ready.push({code: "var $container = $('#container1');", 
                ran: false,
                priority: 2
               });
  
doc_ready.push({code: "$('body').find('#container').remove();",
                ran: true,
                priority: 5
                });

/*
 * sort this array using priority (numeric):
 *
 * https://stackoverflow.com/questions/939326/execute-javascript-code-stored-as-a-string
 */
doc_ready.sort(function(a,b) {
    return a.priority - b.priority;
});

// sort the array using string
/*
doc_ready.sort(function(a,b) {
    return a.name > b.name;
});
*/

$(document).ready(function(){
 var max  = doc_ready.length;
 var code = "";
 
 for(var idx = 0; idx < max; idx++){
   code = doc_ready[idx];
   
   if (!code.ran){
       $('body').append("<script type='text/javascript'>"+ code +"</script>");
       console.table(code);
       code.ran = true;
   }
 }
});
