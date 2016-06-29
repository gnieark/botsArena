function createElem(type,attributes){
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}



function tron(bot1,bot2,xdcheck){
         //empty

        
        var mapImg = createElem('svg',{'alt' : 'map','width':'200','height':'200'});
	// "circle" may be any tag name
	mapImg.innerHTML = '    <rect id="rect1" x="10" y="10" width="50" height="80" style="stroke:#000000; fill:none;"/>';
	document.getElementById('mainArticle').appendChild(mapImg);
  
}
