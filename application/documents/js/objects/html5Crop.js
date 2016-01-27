function CanvasState(canvas) 
{
	this.canvas = canvas;
 	this.width = canvas.width;
  	this.height = canvas.height;
  	this.ctx = canvas.getContext('2d');
	
	var srcImageObj = document.getElementById('srcIMG')
	var canvasDest = document.getElementById("canvasDestination");
	var contextDest = canvasDest.getContext("2d");
	
	var cropButton = document.getElementById('cropBTN')
	
	cropButton.addEventListener('click', function(e) {
	
		var ant = document.getElementById('testAnt');
		var left = ant.style.left;
		var top =	ant.style.top;
		var Width = ant.style.width;
		var Height = ant.style.height;
	
		var sourceX = left.substring(0,left.length-2)-5;
		var sourceY = top.substring(0,top.length-2);
		var sourceWidth = Width.substring(0,Width.length-2);
		var sourceHeight = Height.substring(0,Height.length-2);
		var destWidth = sourceWidth;
		var destHeight = sourceHeight;
		var destX = canvasDest.width / 2 - destWidth / 2;
		var destY = canvasDest.height / 2 - destHeight / 2;
		//Clear before draw
		contextDest.clearRect(0, 0, canvasDest.width, canvasDest.height);
		//Drawing the cropped Image
		contextDest.drawImage(srcImageObj, sourceX, sourceY, sourceWidth, sourceHeight, destX, destY, destWidth, destHeight);
	});
		
	// This complicates things a little but but fixes mouse co-ordinate problems
	// when there's a border or padding. See getMouse for more detail
	var stylePaddingLeft, stylePaddingTop, styleBorderLeft, styleBorderTop;
	if (document.defaultView && document.defaultView.getComputedStyle) 
	{
		this.stylePaddingLeft = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingLeft'], 10)      || 0;
		this.stylePaddingTop  = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingTop'], 10)       || 0;
		this.styleBorderLeft  = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderLeftWidth'], 10)  || 0;
		this.styleBorderTop   = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderTopWidth'], 10)   || 0;
	}
	// Some pages have fixed-position bars (like the stumbleupon bar) at the top or left of the page
	// They will mess up mouse coordinates and this fixes that
	var html = document.body.parentNode;
	this.htmlTop = html.offsetTop;
	this.htmlLeft = html.offsetLeft;

	this.dragging = false;
	this.mx = 0;
	this.my = 0;
	// This is an example of a closure!
	// Right here "this" means the CanvasState. But we are making events on the Canvas itself,
	// and when the events are fired on the canvas the variable "this" is going to mean the canvas!
	// Since we still want to use this particular CanvasState in the events we have to save a reference to it.
	// This is our reference!
	var myState = this;
	//fixes a problem where double clicking causes text to get selected on the canvas
	canvas.addEventListener('selectstart', function(e) { e.preventDefault(); return false; }, false);
	// Up, down, and move are for dragging
	canvas.addEventListener('mousedown', function(e) {
	var mouse = myState.getMouse(e);
		this.mx = mouse.x;
		this.my = mouse.y;
	
	var ant = document.getElementById('testAnt');
		ant.style.left = (this.mx+10)+"px";
		ant.style.top = (this.my)+"px";
		ant.style.width = (0)+"px";
		ant.style.height = (0)+"px";

	 	myState.dragging = true;

  }, true);
  
  canvas.addEventListener('mousemove', function(e) {
  	
    if (myState.dragging)
	{
	
      var mouse = myState.getMouse(e);
	 	  myState.clear(e);
	  
	  var width =  mouse.x - this.mx;
	  var height =  mouse.y - this.my;

	  var ant = document.getElementById('testAnt');
		  ant.style.left = (this.mx+10)+"px";
		  ant.style.top = (this.my)+"px";
		  ant.style.width = (width-6)+"px";
		  ant.style.height = (height-6)+"px";	   
    }
  }, true);
  canvas.addEventListener('mouseup', function(e) {
  		  myState.dragging = false;
  }, true);
  // double click for making new shapes
  canvas.addEventListener('dblclick', function(e) {
 		  myState.clear(e);
  }, true);
}

CanvasState.prototype.clear = function() {
  		 this.ctx.clearRect(0, 0, this.width, this.height);
}

CanvasState.prototype.getCanvasHandle = function(e) {
		return this.ctx;
}

// Creates an object with x and y defined, set to the mouse position relative to the state's canvas
// If you wanna be super-correct this can be tricky, we have to worry about padding and borders
CanvasState.prototype.getMouse = function(e) {
	var element = this.canvas, offsetX = 0, offsetY = 0, mx, my;
	
	// Compute the total offset
	if (element.offsetParent !== undefined) {
		do {
		  offsetX += element.offsetLeft;
		  offsetY += element.offsetTop;
		} while ((element = element.offsetParent));
	}
	
	// Add padding and border style widths to offset
	// Also add the <html> offsets in case there's a position:fixed bar
	offsetX += this.stylePaddingLeft + this.styleBorderLeft + this.htmlLeft;
	offsetY += this.stylePaddingTop + this.styleBorderTop + this.htmlTop;
	
	mx = e.pageX - offsetX;
	my = e.pageY - offsetY;
	// We return a simple javascript object (a hash) with x and y defined
	return {x: mx, y: my};
}

function init() {
	 var myCanvas = new CanvasState(document.getElementById('canvas1'));
}
