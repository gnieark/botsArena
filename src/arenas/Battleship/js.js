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
function createElem(type,attributes)
{
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}
function fight(xd_check,fullLogs,gameId){
    var xhr = Ajx(); 
        xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
        if(xhr.status  == 200) {
	  try{
	      var strike = JSON.parse(xhr.responseText);
	  }catch(e){
	      document.getElementById('logs').innerHTML += 'erreur' +xhr.responseText;
	      return;
	  }
	  if( strike['target'] !== ''){
	    var coords = strike['target'].split(",");
	    document.getElementById( 'bot' + strike['opponent'] + '-' + coords[1] + '-' +  coords[0]).innerHTML = "X";
	  }
	  var p=createElem("p");
	  p.innerHTML=strike['log'];
	  document.getElementById('logs').appendChild(p);
	  document.getElementById("logs").scrollTop=document.getElementById("logs").scrollHeight;
	  if( strike['continue'] == 1){
	    fight(xd_check);
	  }
            
        }
				
    }};
    xhr.open("POST", '/Battleship',  true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('act=fight&xd_check=' + xd_check + '&fullLogs=' + fullLogs);

}

function battleship(bot1,bot2,gridWidth,gridHeight,nbShip1,nbShip2,nbShip3,nbShip4,nbShip5,nbShip6,xd_check,fullLogs){
  
  var shipsArea= parseInt(nbShip1) + 2 * parseInt(nbShip2) + 3 * parseInt(nbShip3) + 4 * parseInt(nbShip4) + 5 * parseInt(nbShip5) + 6 * parseInt(nbShip6);
  if(shipsArea > parseInt(gridWidth * gridHeight / 2) ){
      alert("Map is too small. Sum of ships areas must be under 50% of the map." + shipsArea + " ");
      return;
  }
  var ships= [0,parseInt(nbShip1),parseInt(nbShip2),parseInt(nbShip3),parseInt(nbShip4),parseInt(nbShip5),parseInt(nbShip6)];
  var longestShip=0;
  for(var i = 6; i > 0; i--){
    if(ships[i] > 0){
     longestShip=ships[i];
     break;
    }
  }
  if((longestShip==0)
    ||((longestShip > gridWidth) && (longestShip > gridHeight))
  ){
      alert ("Map is too small. Grow it or reduce ships");
      return;
  }
   
  
  var  bot1IdName = bot1.split("-");
  var  bot2IdName = bot2.split("-");
  document.getElementById('fightResult').innerHTML = '';
  //dessiner les deux grilles
  var tableAdv=createElem("table",{"id":"tbl1","class":"battleshipGrid"});
  var tableMe=createElem("table",{"id":"tbl2","class":"battleshipGrid"});
  //ligne de titre 
  var trTitre1=createElem("tr");
  var trTitre2=createElem("tr");
  var tdTitre1=createElem("th",{"colspan":gridWidth});
  var tdTitre2=createElem("th",{"colspan":gridWidth});
  tdTitre1.innerHTML = bot1IdName[1];
  tdTitre2.innerHTML = bot2IdName[1];
  trTitre1.appendChild(tdTitre1);
  tableAdv.appendChild(trTitre1);
  trTitre2.appendChild(tdTitre2);
  tableMe.appendChild(trTitre2);

  for (var i=0; i < gridHeight ; i++){
	  var trAdv=createElem("tr");
	  var trMe=createElem("tr");
	  for (var j=0; j < gridWidth ; j++){
		  var tdAdv=createElem("td",{"id":"bot1-" + i +"-" + j,"class": "empty"});
		  var tdMe=createElem("td",{"id":"bot2-" + i +"-" + j,"class": "empty"});
		  trAdv.appendChild(tdAdv);
		  trMe.appendChild(tdMe);
	  }
	  tableAdv.appendChild(trAdv);
	  tableMe.appendChild(trMe);
  }
  document.getElementById('fightResult').appendChild(tableAdv);
  document.getElementById('fightResult').appendChild(tableMe);
  var divLogs=createElem("div",{"id":"logs"});
  document.getElementById('fightResult').appendChild(divLogs);



  var xhr = Ajx(); 
  xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
    if(xhr.status  == 200) {
        //debug
        //alert(xhr.responseText);
        try{
            var grids = JSON.parse(xhr.responseText);
        }catch(e){
            document.getElementById('logs').innerHTML = xhr.responseText;
            return;
        }
        
        for( var player=1; player <= 2 ; player ++){
            for (var y=0; y < grids[player].length ; y++){
                    for (var x=0; x < grids[player][y].length ; x++){
                        if (grids[player][y][x] == 1){
                            document.getElementById( 'bot' + player + '-' + y + '-' + x).className="shipOn";
                        }
                    }
            }
        }
        var p=createElem("p");
        p.innerHTML='players placed theirs ships';
        document.getElementById('logs').appendChild(p);
        fight(xd_check,fullLogs);
    }
				
  }};
  xhr.open("POST", '/Battleship',  true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send(
  'act=initGame&bot1=' + bot1IdName[0] 
  + '&bot2=' + bot2IdName[0]
  + '&gridWidth=' + gridWidth 
  + '&gridHeight=' + gridHeight 
  + '&nbShip1=' + nbShip1 
  + '&nbShip2=' + nbShip2 
  + '&nbShip3=' + nbShip3 
  + '&nbShip4=' + nbShip4 
  + '&nbShip5=' + nbShip5
  + '&nbShip6=' + nbShip6 
  + '&xd_check=' + xd_check
  + '&fullLogs=' + fullLogs
  );

}