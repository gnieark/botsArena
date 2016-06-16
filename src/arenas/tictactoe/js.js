function Ajx(){
    var request = false;
	try {request = new ActiveXObject('Msxml2.XMLHTTP');}
	catch (err2) {
		try {request = new ActiveXObject('Microsoft.XMLHTTP');}
		catch (err3) {
			try { request = new XMLHttpRequest();}
			catch (err1) {
				request = false;
			}
		}
	}
    return request;
}
function tictactoe(bot1,bot2,xd_check,fullLogs){
  document.getElementById('fightResult').innerHTML = '<p>Please wait...</p>';
  var xhr = Ajx(); 
  xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
      if(xhr.status  == 200) {
	document.getElementById('fightResult').innerHTML = xhr.responseText;				
      }
    }};
    xhr.open("POST", '/tictactoe',  true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('act=fight&bot1=' + bot1 + '&bot2=' + bot2 + '&fullLogs=' + fullLogs + '&xd_check=' + xd_check);
}
