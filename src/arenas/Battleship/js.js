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

function battleship(bot1,bot2,gridWidth,gridHeight,nbShip1,nbShip2,nbShip3,nbShip4,nbShip5,nbShip6,xd_check){
    
  document.getElementById('fightResult').innerHTML = '';
  //dessiner les deux grilles
  tableAdv=createElem("table",{"id":"tblAdv","className":"battleshipGrid"});
  tableMe=createElem("table",{"id":"tblAdv","className":"battleshipGrid"});
  
  for (i=0; i < gridHeight ; i++){
   trAdv=createElem("tr");
   trMe=createElem("tr");
   for (j=0; j < gridwidth ; i++){
     tdAdv=createElem("td",{"id":"adv" + i +"-" + j,"className": "empty"});
     tdMe=createElem("td",{"id":"me" + i +"-" + j,"className": "empty"});
     trAdv.appendChild(tdAdv);
     trMe.appendChild(tdMe);
   }
   tableAdv.appendChild(trAdv);
   tableMe.appendChild(trMe);
  }
  document.getElementById('fightResult').appendChild(tableAdv);
  document.getElementById('fightResult').appendChild(tableMe);
  
 /* 
  
  var xhr = Ajx(); 
  xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
      if(xhr.status  == 200) {
	document.getElementById('fightResult').innerHTML = xhr.responseText;				
      }
    }};
    xhr.open("POST", '/Battleship',  true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send('act=fight&bot1=' + bot1 + '&bot2=' + bot2 + '&xd_check=' + xd_check);
    */
}