function createElem(type,attributes){
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttribute(i,attributes[i]);}
    return elem;
}
function createElemNs(type,attributes){
    var elem=document.createElement(type);
    for (var i in attributes)
    {elem.setAttributeNs(i,attributes[i]);}
    return elem;
}


function tron(bot1,bot2,xdcheck){
	
        var svg = document.getElementById("map");
	var svgDoc = svg.contentDocument;
	var rect=createElemNs('rect',{'x':'10','y':'10','width':'50','height':'80','style':'stroke:#000000; fill:none;'});
	
	svgDoc.appendChild(rect);
  
}
