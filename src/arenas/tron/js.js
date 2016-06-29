



function tron(bot1,bot2,xdcheck){
         //empty
        while (document.getElementById('fightResult').firstChild) {
            document.getElementById('fightResult').removeChild(document.getElementById('fightResult').firstChild);
        } 
       
alert("plop");
	// "circle" may be any tag name
	var shape = document.createElementNS("http://www.w3.org/2000/svg", "circle");
	// Set any attributes as desired
	shape.setAttribute("cx", 25);
	shape.setAttribute("cy", 25);
	shape.setAttribute("r",  20);
	shape.setAttribute("fill", "green");
	// Add to a parent node; document.documentElement should be the root svg element.
	// Acquiring a parent element with document.getElementById() would be safest.
	document.getElementById('fightResult').appendChild(shape);
  
}
