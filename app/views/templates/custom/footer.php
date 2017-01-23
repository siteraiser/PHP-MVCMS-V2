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
<div class="container center-block"><p class="copy">Copyright © <?php echo date("Y"); ?> - <a href="/privacy-policy">Privacy Policy</a></p>
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
(function(){function m(a,b){document.addEventListener?a.addEventListener("scroll",b,!1):a.attachEvent("scroll",b)}function n(a){document.body?a():document.addEventListener?document.addEventListener("DOMContentLoaded",function c(){document.removeEventListener("DOMContentLoaded",c);a()}):document.attachEvent("onreadystatechange",function l(){if("interactive"==document.readyState||"complete"==document.readyState)document.detachEvent("onreadystatechange",l),a()})};function t(a){this.a=document.createElement("div");this.a.setAttribute("aria-hidden","true");this.a.appendChild(document.createTextNode(a));this.b=document.createElement("span");this.c=document.createElement("span");this.h=document.createElement("span");this.f=document.createElement("span");this.g=-1;this.b.style.cssText="max-width:none;display:inline-block;position:absolute;height:100%;width:100%;overflow:scroll;font-size:16px;";this.c.style.cssText="max-width:none;display:inline-block;position:absolute;height:100%;width:100%;overflow:scroll;font-size:16px;";
this.f.style.cssText="max-width:none;display:inline-block;position:absolute;height:100%;width:100%;overflow:scroll;font-size:16px;";this.h.style.cssText="display:inline-block;width:200%;height:200%;font-size:16px;max-width:none;";this.b.appendChild(this.h);this.c.appendChild(this.f);this.a.appendChild(this.b);this.a.appendChild(this.c)}
function x(a,b){a.a.style.cssText="max-width:none;min-width:20px;min-height:20px;display:inline-block;overflow:hidden;position:absolute;width:auto;margin:0;padding:0;top:-999px;left:-999px;white-space:nowrap;font:"+b+";"}function y(a){var b=a.a.offsetWidth,c=b+100;a.f.style.width=c+"px";a.c.scrollLeft=c;a.b.scrollLeft=a.b.scrollWidth+100;return a.g!==b?(a.g=b,!0):!1}function z(a,b){function c(){var a=l;y(a)&&a.a.parentNode&&b(a.g)}var l=a;m(a.b,c);m(a.c,c);y(a)};function A(a,b){var c=b||{};this.family=a;this.style=c.style||"normal";this.weight=c.weight||"normal";this.stretch=c.stretch||"normal"}var B=null,C=null,E=null,F=null;function I(){if(null===E){var a=document.createElement("div");try{a.style.font="condensed 100px sans-serif"}catch(b){}E=""!==a.style.font}return E}function J(a,b){return[a.style,a.weight,I()?a.stretch:"","100px",b].join(" ")}
A.prototype.load=function(a,b){var c=this,l=a||"BESbswy",r=0,D=b||3E3,G=(new Date).getTime();return new Promise(function(a,b){var e;null===F&&(F=!!document.fonts);if(e=F)null===C&&(C=/OS X.*Version\/10\..*Safari/.test(navigator.userAgent)&&/Apple/.test(navigator.vendor)),e=!C;if(e){e=new Promise(function(a,b){function f(){(new Date).getTime()-G>=D?b():document.fonts.load(J(c,'"'+c.family+'"'),l).then(function(c){1<=c.length?a():setTimeout(f,25)},function(){b()})}f()});var K=new Promise(function(a,
c){r=setTimeout(c,D)});Promise.race([K,e]).then(function(){clearTimeout(r);a(c)},function(){b(c)})}else n(function(){function e(){var b;if(b=-1!=g&&-1!=h||-1!=g&&-1!=k||-1!=h&&-1!=k)(b=g!=h&&g!=k&&h!=k)||(null===B&&(b=/AppleWebKit\/([0-9]+)(?:\.([0-9]+))/.exec(window.navigator.userAgent),B=!!b&&(536>parseInt(b[1],10)||536===parseInt(b[1],10)&&11>=parseInt(b[2],10))),b=B&&(g==u&&h==u&&k==u||g==v&&h==v&&k==v||g==w&&h==w&&k==w)),b=!b;b&&(d.parentNode&&d.parentNode.removeChild(d),clearTimeout(r),a(c))}
function H(){if((new Date).getTime()-G>=D)d.parentNode&&d.parentNode.removeChild(d),b(c);else{var a=document.hidden;if(!0===a||void 0===a)g=f.a.offsetWidth,h=p.a.offsetWidth,k=q.a.offsetWidth,e();r=setTimeout(H,50)}}var f=new t(l),p=new t(l),q=new t(l),g=-1,h=-1,k=-1,u=-1,v=-1,w=-1,d=document.createElement("div");d.dir="ltr";x(f,J(c,"sans-serif"));x(p,J(c,"serif"));x(q,J(c,"monospace"));d.appendChild(f.a);d.appendChild(p.a);d.appendChild(q.a);document.body.appendChild(d);u=f.a.offsetWidth;v=p.a.offsetWidth;
w=q.a.offsetWidth;H();z(f,function(a){g=a;e()});x(f,J(c,'"'+c.family+'",sans-serif'));z(p,function(a){h=a;e()});x(p,J(c,'"'+c.family+'",serif'));z(q,function(a){k=a;e()});x(q,J(c,'"'+c.family+'",monospace'))})})};"undefined"!==typeof module?module.exports=A:(window.FontFaceObserver=A,window.FontFaceObserver.prototype.load=A.prototype.load);}());
	
	
	
var font = new FontFaceObserver('montserrat', {
  weight: 400
});
var font2 = new FontFaceObserver('merriweather', {
  weight: 400
});


font.load().then(function () {
  console.log('Font is available');
}, function () {
  console.log('Font is not available');
});
font2.load().then(function () {
  console.log('Font2 is available');
}, function () {
  console.log('Font2 is not available');
});	
	</script>
<style>
/*body{
	font-family:arial, sans-serif;
	}

.content p{
	font-family:montserrat, sans-serif;
	}
.content h1,h2,h3,h4,h5{
	font-family:merriweather, sans-serif;
}

.content a{
	font-family:merriweather bold, sans-serif;
}
*/
</style>

 <?php /*
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

?>

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



</script>  
*/ ?> 
</body>
</html>