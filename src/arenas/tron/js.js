function createElem(type,attributes){
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}
function addLog(message){
  var divLogs = document.getElementById("logs");
  var p=createElem('p',{});
  p.innerHTML=message;
  divLogs.appendChild(p); 
  divLogs.scrollTop = divLogs.scrollHeight;
  
}
function growTails(newPointsByPlayer){

  var botsColor = ['cyan','darkmagenta','darkred','darkslategrey','deeppink','dodgerblue','goldenrod','grey','indigo','lightgreen','mediumslateblue','midnightblue'];
  //document.getElementById('map');
  for (var botId in newPointsByPlayer){
      
      var tail = newPointsByPlayer[botId]['tail'];
      for(var coordsIx in tail){
	
	coords = tail[coordsIx];	
	//draw the point
	var rect=createElemNS('rect',{'x':coords[0],'y':coords[1],'width':'2','height':'2','style':'fill:' + botsColor[botId] + ';'});
	document.getElementById('map').appendChild(rect);	
      }
  }
}
function createElemNS(type,attributes){
    //same as createElem but with ns for svg file
    var elem=document.createElementNS("http://www.w3.org/2000/svg",type);
    for (var i in attributes)
    {elem.setAttributeNS(null,i,attributes[i]);}
    return elem;
}
function changeSelect(number,botId){
    //show an other selector if bot is chosen
    var next = parseInt(number) + 1;
    if((botId > 0) && (number < 12)){
        if(document.getElementById('selectBot' + next)){
            return;
        }else{
            show_bot_panel(next);
        }
        if(number > 0){
                document.getElementById('fightButton').disabled = false;
        }
    }
}
function show_bot_panel(number){
        //configurePlayers
        var fieldset = createElem('fieldset',{'class':'botFormulaire'});
        var legend = createElem('legend',{});
        legend.innerHTML = 'bot ' + number;
        fieldset.appendChild(legend);
        var p=createElem('p');
        var select = createElem('select',{'id':'selectBot' + number, 'onchange':'changeSelect(' + number + ',this.value);'});
        var option = createElem('option',{'value': '0', 'selected': 'selected','disabled':'disabled'});
        option.innerHTML =  '';
        select.appendChild(option);
        for (var i = 0; i <  botsAvailable.length; i++){
            var option = createElem('option',{'value': botsAvailable[i]['id']});
            option.innerHTML =  botsAvailable[i]['name'];
            select.appendChild(option);
        }
        p.appendChild(select);
        fieldset.appendChild(p);
       
        document.getElementById('configurePlayers').appendChild(fieldset);
}

function applyInitMessage(req,xd_check){
  document.getElementById('fightButton').disabled=true;
  //callback function when init game request
  if(req.readyState  == 4){ 
    if(req.status  == 200) {
      //alert (req.responseText);
      try{ 
	 var ret = JSON.parse(req.responseText);
      }catch(e){
	      addLog('erreur' + req.responseText);
	      return;
      }
      addLog(ret['logs']);
      if(ret['status'] == true){
	growTails(ret['botsPosition']);
	play(ret['gameId'],xd_check);
      }

    }else{
	alert ('error ' + req.status);
	document.getElementById('fightButton').disabled=false;
	return;
    }
  }
}
function play(gameId,xd_check){
  
  	var req = new XMLHttpRequest();	 
	req.onreadystatechange  = function(){
	  if(req.readyState  == 4){ 
	    if(req.status  == 200) {
	     addLog(req.responseText);
	  
	    }else{
	      
	    }
	  }
	};
	req.open("POST", '/tron',  true);
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	req.send('act=play&xd_check=' + xd_check + '&gameId=' + gameId + '&fullLogs=' + document.getElementById("fullLogs").checked);
  
}
function tron(xd_check){
        //empty
        while (document.getElementById('fightResult').firstChild) {
            document.getElementById('fightResult').removeChild(document.getElementById('fightResult').firstChild);
        }
	// draw border;
	var svg = createElemNS('svg',{'id':'map','width':'500','height':'500','viewBox':'0 0 1000 1000'});
	var rect=createElemNS('rect',{'x':'0','y':'0','width':'1000','height':'1000','style':'stroke:#000000; fill:none;'});
	svg.appendChild(rect);
	document.getElementById("fightResult").appendChild(svg);

	var plogs = createElem("p",{'id':'logs'});
	document.getElementById("fightResult").appendChild(plogs);
	//get bot list
	var botsList=[];
	var i=0;
	while(document.getElementById('selectBot' + i)){
	  botsList.push(document.getElementById('selectBot' + i).value);
	    i++;
	}
	
	//ask arena to send bots init messages
	var request = new XMLHttpRequest();	 
	request.onreadystatechange  = function(){applyInitMessage(request,xd_check)};
	request.open("POST", '/tron',  true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.send('act=initGame&xd_check=' + xd_check + '&bots=' + JSON.stringify(botsList) + '&fullLogs=' + document.getElementById("fullLogs").checked);
}
