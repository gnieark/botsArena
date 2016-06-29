document.createSvg = function(tagName) {
    var svgNS = "http://www.w3.org/2000/svg";
    return this.createElementNS(svgNS, tagName);
};

var grid = function(numberPerSide, size, pixelsPerSide, colors) {
    var svg = document.createSvg("svg");
    svg.setAttribute("width", pixelsPerSide);
    svg.setAttribute("height", pixelsPerSide);
    svg.setAttribute("viewBox", [0, 0, numberPerSide * size, numberPerSide * size].join(" "));

    for(var i = 0; i < numberPerSide; i++) {
        for(var j = 0; j < numberPerSide; j++) {
          var color1 = colors[(i+j) % colors.length];
          var color2 = colors[(i+j+1) % colors.length];  
          var g = document.createSvg("g");
          g.setAttribute("transform", ["translate(", i*size, ",", j*size, ")"].join(""));
          var number = numberPerSide * i + j;
          var box = document.createSvg("rect");
          box.setAttribute("width", size);
          box.setAttribute("height", size);
          box.setAttribute("fill", color1);
          box.setAttribute("id", "b" + number); 
          g.appendChild(box);
          var text = document.createSvg("text");
          text.appendChild(document.createTextNode(i * numberPerSide + j));
          text.setAttribute("fill", color2);
          text.setAttribute("font-size", 6);
          text.setAttribute("x", 0);
          text.setAttribute("y", size/2);
          text.setAttribute("id", "t" + number);
          g.appendChild(text);
          svg.appendChild(g);
        }  
    }
    svg.addEventListener(
        "click",
        function(e){
            var id = e.target.id;
            if(id)
                alert(id.substring(1));
        },
        false);
    return svg;
};



function tron(bot1,bot2,xdcheck){
         //empty
        while (document.getElementById('fightResult').firstChild) {
            document.getElementById('fightResult').removeChild(document.getElementById('fightResult').firstChild);
        } 
         document.getElementById('fightResult').appendChild(grid(1000,10,40,["red", "white"]));

	
  
}