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
function addLog(message){
  var p=createElem('p',{});
  p.innerHTML=message;
  document.getElementById('logs').appendChild(p); 
}
function createElem(type,attributes){
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}
function connectFour(bot1,bot2,xd_check, gameId, newGame){
    if (newGame === undefined){
     newGame = true;
     
    }
    
    if (newGame){
        gameId=0;
        //don't touch the button while game is not ended
        document.getElementById('fightButton').disabled=true;
        
        //empty
        while (document.getElementById('fightResult').firstChild) {
            document.getElementById('fightResult').removeChild(document.getElementById('fightResult').firstChild);
        }
        //create grid
        var table=createElem('table',{'class':'battleGrid'});
        for (var i=5; i > -1; i--){
            var tr=createElem('tr');
            for (var j=0;j<7; j++){
                var td=createElem('td',{'id': 'td' + j + '_' + i});
                tr.appendChild (td);
            }
            
            table.appendChild(tr);
        }
        document.getElementById('fightResult').appendChild(table);
        var divLogs=createElem("div",{"id":"logs"});
        document.getElementById('fightResult').appendChild(divLogs);
  }
  //send request  
  var xhr = Ajx(); 
  xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
      if(xhr.status  == 200) {
        try{
            var reponse = JSON.parse(xhr.responseText);  
        }catch(e){
	      addLog('erreur' +xhr.responseText);
	      return;
        }
        //log
         addLog(reponse['log']);
         
        //fill the grid
        if( reponse['strikeX'] > -1){
	   document.getElementById('td' + reponse['strikeX'] + '_' + reponse['strikeY']).innerHTML = reponse['strikeSymbol'];
	}
	
	//colorise cells that win
	if(reponse['cellsWin'] === undefined){
	  
	}else{
	    var cellsWin = JSON.parse(reponse['cellsWin']);
	    for(var i in cellsWin){
	      document.getElementById('td' + cellsWin[i][0] + '_' + cellsWin[i][1]).setAttribute('class','winCase');
	    }
	    
	}
	
	//if game isn't finished, continue
	if(reponse['continue'] == 1){
            connectFour(bot1,bot2,xd_check,reponse['gameId'], false);
        }else{
             document.getElementById('fightButton').disabled=false;
        }
      }else if(xhr.status  == 512){
          //just forget
          return;
      }else{
	  alert ('error ' + xhr.status);
          document.getElementById('fightButton').disabled=false;
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
    xhr.send('act=' + act + '&bot1=' + bot1 + '&bot2=' + bot2 + '&xd_check=' + xd_check + '&gameId=' + gameId + '&fullLogs=' + document.getElementById("fullLogs").checked);
}
