var canvas_gamingMap = document.getElementById("canvas_gamingMap");

window.addEventListener('resize', resizeCanvas, false);

function resizeCanvas() {
    canvas_gamingMap.width = window.innerWidth;
    canvas_gamingMap.height = window.innerHeight;
    
    /**
     * Your drawings need to be inside this function otherwise they will be reset when 
     * you resize the browser window and the canvas goes will be cleared.
     */
    drawStuff(); 
}
resizeCanvas();

function drawStuff() {
	var context = canvas_gamingMap.getContext("2d");
	for (var x = 0.5; x <= 609; x += 32) {
		context.moveTo(x, 0);
		context.lineTo(x, 608);
	}

	for (var y = 0.5; y <= 609; y += 32) {
		context.moveTo(0, y);
		context.lineTo(608, y);
	}

	context.strokeStyle = "#999";
	context.stroke();
}
//# sourceMappingURL=all.js.map
