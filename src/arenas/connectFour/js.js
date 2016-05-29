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
    
    if (newGame){
        //empty
        while (document.getElementById('fightResult').firstChild) {
            document.getElementById('fightResult').removeChild(document.getElementById('fightResult').firstChild);
        }
        //create grid
        var table=createElem('table',{'class':'battleGrid'});
        for (var i=6; i > -1; i--){
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
        //for test
          alert(xhr.responseText);
          
        try{
            var reponse = JSON.parse(xhr.responseText);  
        }catch(e){
	      document.getElementById('logs').innerHTML += 'erreur' +xhr.responseText;
	      return;
        }
        //log
         document.getElementById('logs').innerHTML += reponse['log'] + '<br/>';
        //fill the grid
        if( reponse['strikeX'] > -1){
	   document.getElementById('td' + reponse['strikeX'] + '_' + reponse['strikeY']).innerHTML = reponse['strikeSymbol'];
	}
	
	if(reponse['cellsWin'] === undefined){
	  
	}else{
	    var cellsWin = JSON.parse(reponse['cellsWin']);
	    for(var i in cellsWin){
	      document.getElementById('td' + cellsWin[i][0] + '_' + cellsWin[i][1]).setAttribute('class','winCase');
	    }
	    
	}
	
	
	//if game isn't finished, continue
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
