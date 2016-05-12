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
function createElem(type,attributes){
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}
function connectFour(bot1,bot2,xd_check){
  document.getElementById('fightResult').innerHTML = '';
  //create grid
  
  var table=createElem('table',{'className':'tabledoc'});
  for (var i=0; i<6; i++){
    var tr=createElem('tr');
    for (var j=0;j<7: j++){
        var td=createElem('td',{'id': 'td' + i + '-' + j});
        tr.appendChild (td);
    }
    
    table.appendChild(tr);
  }
  document.getElementById('fightResult').appendChild(table);
  
  var xhr = Ajx(); 
  xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
      if(xhr.status  == 200) {
        try{
            var reponse = JSON.parse(xhr.responseText);  
        }catch(e){
	      document.getElementById('logs').innerHTML += 'erreur' +xhr.responseText;
	      return;
	  }  
        alert (reponse['continue']);
        
        
        
        
      }else{
	  alert ('error ' + xhr.status);
	  return;
      }
    }};
    xhr.open("POST", '/connectFour',  true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('act=newFight&bot1=' + bot1 + '&bot2=' + bot2 + '&xd_check=' + xd_check);
}
