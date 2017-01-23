</main>
<footer>
<div class="footer">
<div class="container center-block">
<?php 
if(!is_array($content)){
 echo @$content;
}else{
echo @$content[0];
}
?>

</div>
<div class="footer">
<div class="container center-block"><p class="copy">Copyright © 2016 - <a href="/privacy-policy">Privacy Policy</a></p>
</div></div>
</div>
</footer> 
<?php
  if(is_array($content)){
  foreach($content as $key => $value){

  echo ($key != 0 ? $value : '' );
  }
}
?>
<!-- inline bootstrap here... -->


<script>

function isCanvasSupported(){
  var elem = document.createElement('canvas');
  return !!(elem.getContext && elem.getContext('2d'));
}

function toArray(obj) {
  var array = [];
 
  for (var i = obj.length >>> 0; i--;) { 
    array[i] = obj[i];
  }
  return array;
}


var SVG = true;

if (typeof SVGRect != "undefined"){}else {

SVG = false;
}

if(SVG == true){
var iconSVG = "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 {{w}} {{h}}'><defs><symbol id='a' viewBox='0 0 90 66' opacity='0.3'><path d='M85 5v56H5V5h80m5-5H0v66h90V0z'/><circle cx='18' cy='20' r='6'/><path d='M56 14L37 39l-8-6-17 23h67z'/></symbol></defs><use xlink:href='#a' width='20%' x='40%'/></svg>"; 
}

var imagereplacements = toArray(document.getElementsByClassName('image-replacement'));


for (i = 0, len = imagereplacements.length;i<len;i++) {		
	
	(function (imagereplacement){
	var clss = imagereplacement.getAttribute("class");
	var img = document.createElement("IMG");
	img.setAttribute("width",imagereplacement.getAttribute("data-width"));
	 img.setAttribute("height",imagereplacement.getAttribute("data-height"));
	img.setAttribute("class",'image-replacer ' + clss);
	
	 if(typeof imagereplacement.getAttribute("data-style") != 'undefined'){
	 	img.setAttribute("style", imagereplacement.getAttribute("data-style"));
	 }else{
	 img.setAttribute("style","");
	 }
	 	
	if(SVG == true){
		img.src = "data:image/svg+xml;charset=utf-8," + encodeURIComponent(iconSVG.replace(/{{w}}/g, imagereplacement.getAttribute("data-width")).replace(/{{h}}/g,imagereplacement.getAttribute("data-height")));
	}
	else if(isCanvasSupported){
		var canvas =  document.createElement("canvas");
		canvas.setAttribute("width",imagereplacement.getAttribute("data-width"));
	 canvas.setAttribute("height",imagereplacement.getAttribute("data-height"));
      var ctx=canvas.getContext("2d");
     
      ctx.fillStyle="#aaa";
      ctx.fillRect(0,0,imagereplacement.getAttribute("data-width"),imagereplacement.getAttribute("data-height"));

      var url = canvas.toDataURL();
		img.src = url;
	}
	 imagereplacement.appendChild(img);
	var image = new Image();
	 image.onload = function () {
  		img.src = this.src; 
        	
        	if(imagereplacement.getAttribute("data-alt") != null){
        		img.setAttribute("alt",imagereplacement.getAttribute("data-alt"));     
        	} else {				
				img.setAttribute("alt","");  
			}                 
	};
	image.src =  imagereplacement.getAttribute("data-src");
	})(imagereplacements[i]);

}
document.addEventListener("DOMContentLoaded", function(event) { 
if(SVG == false){
	
	document.getElementById('main-image').innerHTML = '<img class="img-fluid" src="/images/tas-logo-60.png" width="170" height="60" />';
	document.getElementById('small-logo').innerHTML = '<img class="img-fluid" src="/images/tas-logo-60.png" width="85" height="30" />';
	document.getElementById('fb-logo').innerHTML = '<img class="img-fluid" src="/images/fb-f.png" width="25" height="25" />';
	document.getElementById('mail').innerHTML = '<img class="img-fluid" src="/images/mail.png" width="32" height="25" />';
}
});



 <?php /*
$('.image-replacement').each(function(){
  var $this = $(this),
 clss = $this.attr('class'),
      data = $this.data(), 
      $img = $('<img />').attr({
                width: data.width,
                height: data.height,
                style: data.style,
                     
                src: "data:image/svg+xml;charset=utf-8," + encodeURIComponent(iconSVG.replace(/{{w}}/g,data.width).replace(/{{h}}/g,data.height)),
                        class: 'image-replacer ' + clss, 
                title: 'Click to load' 
              })
              .appendTo($this);
         
                var image = new Image();
image.onload = function () {
    $img.attr({
                    src: this.src, 
                    class: clss, title: '', 
                    });
                    
};
image.src = data.src;
                                 
                 
});     


var imagereplacements = toArray(document.getElementsByClassName('image-replacement'));
if(SVG == true){
for (i = 0, len = imagereplacements.length;i<len;i++) {		

	
	(function (imagereplacement){
	var clss = imagereplacement.getAttribute("class");
	var data = imagereplacement.dataset;
	var img = document.createElement("IMG");
	 img.width = data.width;
	 img.height = data.height;
	 img.setAttribute("class", clss);  
	
	 if(typeof data.style != 'undefined'){
	 	img.setAttribute("style", data.style);
	 }
	 img.src = "data:image/svg+xml;charset=utf-8," + encodeURIComponent(iconSVG.replace(/{{w}}/g,data.width).replace(/{{h}}/g,data.height));
	 img.class='image-replacer ' + clss;	
	 imagereplacement.appendChild(img);
	var image = new Image();
	 image.onload = function () {
  		img.src = this.src; 
        	
        	if(typeof data.alt != 'undefined'){
        		img.alt = data.alt;     
        	}                  
	};
	image.src = data.src;
	})(imagereplacements[i]);
}
}else{



}


 */ ?> 
</script> 
</body>
</html>