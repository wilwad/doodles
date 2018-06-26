/* 
* I am recording how long it takes for a PHP page to load when:
*  the nav menu displayed is pulled from $_SESSION (cached) &
*  when nav menu is created from scratch each time (freshly baked)
*
* then averaging the result
*/
var data  = {
		   'freshly baked': {pass1: 0.011120, pass2: 0.011107, pass3: 0.011264, avg: 0}, 
		   'cached': {pass1:0.010999, pass2: 0.011082, pass3: 0.011179, avg: 0}
		  };

for(var row in data){
   console.log(row, data[row].pass1);
   data[row].avg = (data[row].pass1 + data[row].pass2 + data[row].pass3) / 3;
}

console.table(data);
