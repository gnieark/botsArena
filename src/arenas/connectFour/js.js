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
function connectFour(bot1,bot2,xd_check){
  document.getElementById('fightResult').innerHTML = '<p>Please wait...</p>';
  var xhr = Ajx(); 
  xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
      if(xhr.status  == 200) {
	document.getElementById('fightResult').innerHTML = xhr.responseText;				
      }else{
	  alert ('error ' + xhr.status);
	  break;
      }
    }};
    xhr.open("POST", '/connectFour',  true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('act=newFight&bot1=' + bot1 + '&bot2=' + bot2 + '&xd_check=' + xd_check);
}
