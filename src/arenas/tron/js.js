function createElem(type,attributes){
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}
function createElemNS(type,attributes){
    //same as createElem but with ns for svg file
    var elem=document.createElementNS("http://www.w3.org/2000/svg",type);
    for (var i in attributes)
    {elem.setAttributeNS(null,i,attributes[i]);}
    return elem;
}

function applyInitMessage(req){
  //callback function when init game request
  if(req.readyState  == 4){ 
    if(req.status  == 200) {
      alert (req.responseTxt);

    }else{
	alert ('error ' + xhr.status);
	document.getElementById('fightButton').disabled=false;
	return;
    }
  }
}
function tron(bot1,bot2,xd_check){
        //empty
        while (document.getElementById('fightResult').firstChild) {
            document.getElementById('fightResult').removeChild(document.getElementById('fightResult').firstChild);
        }
	// draw border;
	var svg = createElemNS('svg',{'id':'map','width':'500','height':'500','viewBox':'0 0 1000 1000'});
	var rect=createElemNS('rect',{'x':'0','y':'0','width':'1000','height':'1000','style':'stroke:#000000; fill:none;'});
	svg.appendChild(rect);
	
	document.getElementById("fightResult").appendChild(svg);
		
	//ask arena to send bots init messages
	var request = new XMLHttpRequest();	 
	request.onreadystatechange  = function(){applyInitMessage(request)};
	request.open("POST", '/tron',  true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.send('act=initGame&bot1=' + bot1 + '&bot2=' + bot2 + '&xd_check=' + xd_check + '&fullLogs=' + document.getElementById("fullLogs").checked);
}
