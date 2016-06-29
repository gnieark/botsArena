function createElem(type,attributes){
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}



function tron(bot1,bot2,xdcheck){
         //empty
        while (document.getElementById('fightResult').firstChild) {
            document.getElementById('fightResult').removeChild(document.getElementById('fightResult').firstChild);
        } 
        
        var mapImg = createElem('svg',{'alt' : 'map','width':'200','height':'200'});
	// "circle" may be any tag name
	mapImg.innerHTML = '<line x1="50" y1="50" x2="150" y2="150" stroke="black" stroke-width="2" /><line x1="100" y1="50" x2="200" y2="150" stroke="red" stroke-width="10" /><line x1="150" y1="50" x2="250" y2="150" stroke="blue" stroke-width="5" stroke-dasharray="5,3,2" />';

	document.getElementById('fightResult').appendChild(mapImg);
  
}
