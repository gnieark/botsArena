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
	var shape = document.createElementNS("http://www.w3.org/2000/svg", "circle");
	// Set any attributes as desired
	shape.setAttribute("cx", 25);
	shape.setAttribute("cy", 25);
	shape.setAttribute("r",  20);
	shape.setAttribute("fill", "green");
	// Add to a parent node; document.documentElement should be the root svg element.
	// Acquiring a parent element with document.getElementById() would be safest.
	mapImg.appendChild(shape);
	document.getElementById('fightResult').appendChild(mapImg);
  
}
