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
function connectFour(bot1,bot2,xd_check, newGame){
    if (newGame === undefined){
     newGame = true;   
    }
  document.getElementById('fightResult').innerHTML = '';
  //create grid
  
  var table=createElem('table',{'class':'battleGrid'});
  for (var i=6; i > -1; i--){
    var tr=createElem('tr');
    for (var j=0;j<7; j++){
        var td=createElem('td',{'id': 'td' + j + '-' + i});
        tr.appendChild (td);
    }
    
    table.appendChild(tr);
  }
  document.getElementById('fightResult').appendChild(table);
  
  var xhr = Ajx(); 
  xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
      if(xhr.status  == 200) {
          //for debug
          document.getElementById('logs').innerHTML += xhr.responseText + '<br/>';
        try{
            var reponse = JSON.parse(xhr.responseText);  
        }catch(e){
	      document.getElementById('logs').innerHTML += 'erreur' +xhr.responseText;
	      return;
	  }  
        
        //fill the grid

        if( reponse['strikeX'] > -1){
            //alert ('td' + reponse['strikeX'] + '-' + reponse['strikeY']);
	   document.getElementById('td' + reponse['strikeX'] + '-' + reponse['strikeY']).innerHTML = reponse['strikeSymbol'];
	}
	if(reponse['continue'] == 1){
            connectFour(bot1,bot2,xd_check, false);
        }
        
        
      }else{
	  alert ('error ' + xhr.status);
	  return;
      }
    }};
    xhr.open("POST", '/connectFour',  true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    if(newGame){
        var act='newFight';
    }else{
        var act='fight';
    }
    xhr.send('act=' + act + '&bot1=' + bot1 + '&bot2=' + bot2 + '&xd_check=' + xd_check);
}
