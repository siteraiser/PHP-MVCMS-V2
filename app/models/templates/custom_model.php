<?php 

class custom_model extends requestHandler{
	public $temphtml='';	public $image_list='';	
	public function getParts(){
		$parts=[];
		$parts = explode('[article[',$this->temphtml,2);
		if(count($parts)===1){
			return;
		}
		$beginning = $parts[0];
		$parts = explode(']]',$parts[1],2);
		$id = $parts[0];
		$end = $parts[1];
		if($id!=0){
			$article = $this->articleById($id);
			
			if($this->editing()){
				$beginning.="<div contenteditable='false' data-id='".$id."' class='editablel2'><a class='edit-it' href='".$this->base_url.'siteadmin/content/edit?article='.$id."'>Edit Article</a><span class='delete-n'>Delete N</span>";
				$end ='</div>'.$end;
			}		
		
		}

		
		$this->temphtml = $beginning . $article . $end;
		$this->getParts();	
	}
	
	public function wrap($id,$content){ // maybe conditionally include / require this
	return 'Tools: <span class="tooltoggle">On</span>

<div id="scrnsize">&nbsp;</div>
<button class="save" data-article="'.$id.'" data-id="'.$this->sortable.'">Save</button>

<div class=" container-fluid bootup" id="sortableInputID'.$this->sortable.'">'.
$content . '</div>

<hr />
<h4>Draggable content blocks</h4>
&nbsp;

<div class="draggable">
<div class="container-fluid content">Edit me and my wonderful content!</div>
<span class="editcontent2">Edit</span></div>

<div class="draggable">
<div class="card">
<div class="card-block content">Drag me to my editing, lorem ipsum yada yada!</div>
<span class="editcontent2">Edit</span></div>
</div>
&nbsp;

<div class="draggable">
<div class="container-fluid">
<div class="row">
<div class="width-50-perc">
<div class="content">
<h2>width-50-perc</h2>
Drag me to my target2, lorem ipsum yada yada! lorem ipsum yada yada!</div>
<span class="editcontent2">Edit</span></div>

<div class="width-50-perc">
<div class="content">
<h2>width-50-perc</h2>
Drag me to my target2, lorem ipsum yada yada! lorem ipsum yada yada!</div>
<span class="editcontent2">Edit</span></div>
</div>
</div>
</div>
&nbsp;

<div class="draggable">
<div class="jumbotron content">Drag me to my editing, lorem ipsum yada yada!</div>
<span class="editcontent2">Edit</span></div>



<div class="draggable">
<div class="card content">
<p style="text-align:center"><picture><img alt="Nordic Hot Tub" src="/userfiles/1/image/spas/5340162.png" style=" " class="img-fluid center-block "></picture></p>

<div class="card-block" style="text-align: justify;"><strong><span style="font-size:18px">Nordic Hot Tubs</span></strong> Since 1995, Nordic has led the industry in providing high quality, therapeutic hot tubs. A Nordic Hot Tub is the perfect balance of hydrotherapy at a cost-effective price. Nordic provides is the perfect balance of active and passive muscle and joint relief. Please inquire about the different sizes and configurations. We offer installation! <a href="http://www.nordichottubs.com/">WEBSITE</a>
</div>

</div><span class="editcontent2">Edit</span>

</div>





<button class="copyhtml" id="InputID'.$this->sortable.'">Copy HTML</button><button class="addhtml" id="InputID'.$this->sortable.'">Add HTML</button><textarea id="loadInputID'.$this->sortable.'"></textarea><div id="outbox'.$this->sortable++.'"></div> ';
}
	
	
	
	
	public function articleById($id){
		$query="SELECT content FROM content WHERE id = ? AND published = 1 LIMIT 1";
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array($id)); 
		if (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {	
			return $row['content'];
		}
	}
		
	function getDOMString($text) {
	  if (!$text) return null;
	  $text = strtr($text,  array('></source>' => '>'  ));
	  return $text;
	  
	  /* 
	  2016-10-02 12:24:30
	  
	  '></area>' => ' />',
		'></base>' => ' />',
		'></basefont>' => ' />',
		'></br>' => ' />',
		'></col>' => ' />',
		'></frame>' => ' />',
		'></hr>' => ' />',
		'></img>' => ' />',
		'></input>' => ' />',
		'></isindex>' => ' />',
		'></link>' => ' />',
		'></meta>' => ' />',
		'></param>' => ' />',
		'default:' => '',
		// sometimes, you have to decode entities too...
		'&quot;' => '&#34;',
		'&amp;' =>  '&#38;',
		'&apos;' => '&#39;',
		'&lt;' =>   '&#60;',
		'&gt;' =>   '&#62;',
		'&nbsp;' => '&#160;',
		'&copy;' => '&#169;',
		'&laquo;' => '&#171;',
		'&reg;' =>   '&#174;',
		'&raquo;' => '&#187;',
		'&trade;' => '&#8482;' 
		*/
	  
	}
	public function respImgs($html){
		
	if(strlen($html)!=0){
		
		
	$this->temphtml = $html;

	$this->getParts();
		
	$html = $this->temphtml;
	
	unset($this->temphtml);
		
		
		
		
	$html='<div class="dynamic-content">'.$html.'</div>';//

	$doc = new DOMDocument();
	@$doc->loadHTML($html);

	$doc->removeChild($doc->doctype); 
	$doc->replaceChild($doc->firstChild->firstChild->firstChild, $doc->firstChild);

//$bsarr = array('1200'=>'lg','992'=>'md','768'=>'sm','544'=>'xs');
$bsarr = array('544'=>'xs','768'=>'sm','992'=>'md','1200'=>'lg');



	$selector = new DOMXPath($doc);
	if(!$this->editing() && $this->pagetype='html'){
	/*	
		$spans = $doc->getElementsByTagName('span');	
		$total=$spans->length;
		for ($i = 0; $i < $total - 1; $i++) {
		$spanclass = $spans->item($i)->getAttribute('class');
			if( in_array($spanclass,array('left','right','add','remove','delete','editcontent','editcontent2'))){
				$spans->item($i)->parentNode->removeChild($spans->item($i)); 
			}
		}
		*/
		
		
		
		/*
		

		foreach($selector->query('//span[contains(attribute::class, "left")]') as $e ) {
			if($e->nodeValue === '<'){
			$e->parentNode->removeChild($e);
			}
		}
		foreach($selector->query('//span[contains(attribute::class, "right")]') as $e ) {
			if($e->nodeValue === '>'){
				$e->parentNode->removeChild($e);
			}
		}
		
		
		
		
		foreach($selector->query('//span[contains(attribute::class, "add")]') as $e ) {
			$e->parentNode->removeChild($e);
		}
		foreach($selector->query('//span[contains(attribute::class, "remove")]') as $e ) {
			$e->parentNode->removeChild($e);
		}		
		foreach($selector->query('//span[contains(attribute::class, "delete")]') as $e ) {
			$e->parentNode->removeChild($e);
		}
		*/
		foreach($selector->query('//span[contains(attribute::class, "editcontent")]') as $e ) {
			$e->parentNode->removeChild($e);
		}
		foreach($selector->query('//span[contains(attribute::class, "editcontent2")]') as $e ) {
			$e->parentNode->removeChild($e);
		}		
		
		
		foreach($selector->query('//div[contains(attribute::contenteditable, "false")]') as $e ) {
			$e->removeAttribute('contenteditable');
		}	
		foreach($selector->query('//div[contains(attribute::contenteditable, "true")]') as $e ) {
			$e->removeAttribute('contenteditable');
		}
	
		foreach($selector->query('//div[contains(attribute::class, "connectedSortable")]') as $e ) {
			  if ($e->hasChildNodes()) 
				{
					foreach( $e->getElementsByTagName('div') as $div) 
					{                
						$div->removeAttribute('data-colxs');	
						$div->removeAttribute('data-colsm');	
						$div->removeAttribute('data-colmd');	
						$div->removeAttribute('data-collg');	
						$div->removeAttribute('data-colxl');		
					
						$div->removeAttribute('data-shiftmode');	
						$div->removeAttribute('data-shiftxs');	
						$div->removeAttribute('data-shiftsm');	
						$div->removeAttribute('data-shiftmd');	
						$div->removeAttribute('data-shiftlg');	
						$div->removeAttribute('data-shiftxl');		
						if($div->getAttribute('style') == '' || $div->getAttribute('style') == ' '){
							$div->removeAttribute('style');				
						}else{
							$div->setAttribute('style',preg_replace("/(?:^|\s)position: relative; left: 0px; top: 0px;(?:\s|$)/msi", " ", $div->getAttribute('style')));
						}		

					if ($div->hasChildNodes()) 
					{

						foreach( $div->getElementsByTagName('div') as $div2) 
						{                
							$div2->setAttribute('class',preg_replace("/(?:^|\s)connectedSortable(?:\s|$)/msi", " ", $div2->getAttribute('class')));
							$div2->setAttribute('class',preg_replace("/(?:^|\s)ui-sortable(?:\s|$)/msi", " ", $div2->getAttribute('class')));
							$div2->setAttribute('class',preg_replace("/(?:^|\s)ui-sortable-handle(?:\s|$)/msi", " ", $div2->getAttribute('class')));
						
						}
							
					}				
					
				}
			}
			$e->removeAttribute('style');
			$e->setAttribute('class',preg_replace("/(?:^|\s)connectedSortable(?:\s|$)/msi", " ", $e->getAttribute('class')));
			$e->setAttribute('class',preg_replace("/(?:^|\s)ui-sortable(?:\s|$)/msi", " ", $e->getAttribute('class')));
			$e->setAttribute('class',preg_replace("/(?:^|\s)ui-sortable-handle(?:\s|$)/msi", " ", $e->getAttribute('class')));
			
			foreach($selector->query('//div[contains(attribute::class, "sortable-row")]') as $e ) {
			  if ($e->hasChildNodes()) 
				{					
					if ($e->childNodes->length == 1 & $e->childNodes[0]->nodeType == XML_TEXT_NODE) {// && ( $e->childNodes[0]->nodeValue == '&nbsp;' | $e->childNodes[0]->nodeValue == '&nbsp;')								
						$e->parentNode->removeChild($e);
					}							
				}else{					
					$e->parentNode->removeChild($e);
				}
			}	
		}
/*			foreach($selector->query('//div[contains(attribute::class, "sortable-row")]') as $e ) {
			$e->removeAttribute('class');
		}
		foreach($selector->query('//div[contains(attribute::class, "ui-sortable")]') as $e ) {
			$e->removeAttribute('class');
		}
		foreach($selector->query('//div[contains(attribute::class, "ui-sortable-handle")]') as $e ) {
			$e->removeAttribute('class');
		}	
*/	

		

	}

/*
		if( $this->pagetype='html'){
										
			$pics = $doc->getElementsByTagName('picture');    

			$total=$pics->length;

			//Create unique names / references to use which won't be overwritten each iteration.

			for ($i = 0; $i < $total; $i++) {

				$name = 'pic'.$i;

				$$name  = $pics->item($i);

			}
					
				
			for ($i = 0; $i < $total; $i++) {

				$name = 'pic'.$i;

				$srcs=false;				
				$parsed='';
				$src='';$srcset='';$oneshallpass = false;$moresizes=false;
                if ($$name->hasChildNodes() )
				{ 
					 if ($$name->getElementsByTagName('source')->length > 0 )
				{
					foreach( $$name->getElementsByTagName('source') as $source) 
					{             


						$type =  $source->getAttribute('type');
						if($type != 'image/svg+xml'){
							$srcs=true;
							$srcset=$source->getAttribute('srcset');
											
					
							$origsrc = end(explode('-',$srcset));
							$origsrc = explode('.',$origsrc);
						
							if(!in_array($origsrc[0],['xs','sm','md','lg','xl'])){
								$parsed = parse_url($srcset);
							
								$filename = pathinfo($parsed['path'], PATHINFO_FILENAME); 
								$ext = pathinfo($parsed['path'], PATHINFO_EXTENSION);
								$dir = pathinfo($parsed['path'], PATHINFO_DIRNAME);
								$dir = ltrim($dir, '/');
							
								$origsrc=$this->base_url.$dir.'/'.$filename.'.' . $ext;
								
							}
						}else{
							$oneshallpass = true;
							break;
							
						}
					}
				}else{$srcs=false;$srcset='';}
					
					 if ($$name->getElementsByTagName('img')->length > 0 )
				{
					
					foreach( $$name->getElementsByTagName('img') as $img2) 
					{

						if(	$img2->getAttribute('data-predefined') == 1){
							break;
						}
						if(!$oneshallpass){
							
							$img2->setAttribute('data-predefined','1');
							
						
						$moresizes=false;$size='';
						$src= $img2->getAttribute('src');		
							
						$parsed = parse_url($src);
							
						$filename = pathinfo($parsed['path'], PATHINFO_FILENAME); 
						$ext = pathinfo($parsed['path'], PATHINFO_EXTENSION);
						$dir = pathinfo($parsed['path'], PATHINFO_DIRNAME);
						$dir = ltrim($dir, '/');
			
						foreach($bsarr as $imgwidth => $label){
							if($srcs == false){
										
							 $uri = $this->base_url.$dir.'/'.$filename .'-'. $label. '.' . $ext;
											
								
							}else{
								
								// $checksrc = explode('.', $origsrc, -1);
									
								$uri = $this->base_url.$origsrc;
							}
				
							$size = @getimagesize($uri);
							if($size[0] > 0){	
							
								//echo$img2->getAttribute('alt');	
								$moresizes=true;
						
							}						
						
						}
						
						$imgfname='';
						if(strpos($filename, 'xs') || strpos($filename, 'sm')||strpos($filename, 'md')||strpos($filename, 'lg') ||strpos($filename, 'xl')){
							$imgfname  = implode('-', explode('-', $filename, -1));
						}
						if($imgfname == ''){
							$imgfname = $filename;
						}
						
						//New Image placed
						if($srcs == true && $moresizes == true && $srcset != '/'.$dir.'/'.$imgfname . '.' . $ext){
							$img2->setAttribute('new-image-placed','1');
							$img2->setAttribute('src', $srcset);
						
							$$name->parentNode->replaceChild($img2,$$name);						
						
						}else if($srcs == false && $moresizes == true ){
						
						$$name->parentNode->replaceChild($img2,$$name);
						//No more sizes and no sources or more sizes and sources that matched - good to go!	
						}else if((($srcs == false && $moresizes == false) ||(($srcs == true && $moresizes == true)  && $srcset == '/'.$dir.'/'.$imgfname . '.' . $ext)) || $oneshallpass == true){//and srcs match maybe
							if(is_object($img2)){
								
							
								$$name->parentNode->replaceChild($img2,$$name);
							}
							
						}else{
								//var_dump($this->base_url.$dir.'/'.$imgfname  .'.' . $ext);
								
								
							$img2->setAttribute('src',$this->base_url.$dir.'/'.$imgfname .'.' . $ext);
							$$name->parentNode->replaceChild($img2,$$name);//echo$srcset
						}
						}else{$img2->setAttribute('data-predefined','1');}
					}	
				}else{//no img tags present
					$$name->parentNode->removeChild($$name);
				}
				
								
				}else{//no tags present
					$$name->parentNode->removeChild($$name);
				}
			}				
		}
		*/
			
	  foreach ($doc->getElementsByTagName('picture') as $picture)
        {	
        
            if ($picture->hasChildNodes()) 
            {
				if ($picture->getElementsByTagName('img')->length > 0 )
				{
					foreach( $picture->getElementsByTagName('img') as $img2) 
					{        
				
						$img2->setAttribute('data-predefined','1');
					}
				}
			}else{	$picture->parentNode->removeChild($picture);}
		}
		
		
		
		
		
		
  if($doc->getElementsByTagName('p')->length > 0 )
    {

        foreach ($doc->getElementsByTagName('p') as $node)
        {	
        
            if ($node->hasChildNodes()) 
            {
           	 $style = $node->getAttribute('style');
                if($doc->getElementsByTagName('img')->length > 0  && preg_match("/text-align:center/i", $style)||$doc->getElementsByTagName('img')->length > 0  && preg_match("/text-align: center/i", $style))
                  {
                  foreach( $node->getElementsByTagName('img') as $img) 
                   {
					   
					   
					   
					  
					   	$predefined = $img->getAttribute('data-predefined');	
	
					if(isset($predefined) && $predefined == '1'){		
						
						continue 1;
						
					}
                   	$imgstyle = $img->getAttribute('style');
                   	//if(preg_match("/text-align: center;/i", $imgstyle )){
					$pattern = "/margin:\s*(\d*)\s*(px|%);*/" ;
					$imgstyle = preg_replace($pattern, "margin: $1$2 auto;", $imgstyle);
					
					$img->setAttribute('style',$imgstyle);
						   
					$img->setAttribute('class','center-block '.$classes);
				
				
			//new 	$img->setAttribute('class','d-block m-x-auto '.$classes);
				
			//}
			
                   }
				   
				   
				   

                }
            }
           
         }
    }




	$imgs = $doc->getElementsByTagName('img');	
	$total=$imgs->length;
for ($i = 0; $i < $total; $i++) {
$name = 'img'.$i;
$$name  = $imgs->item($i);
}
	
for ($i = 0; $i < $total; $i++) {
$name = 'img'.$i;

$classes = $$name->getAttribute('class');
	

		$cleansrc=$src= $$name->getAttribute('src');	
	//$srcset = $img->getAttribute('srcset');
		$parsed = parse_url($src);
		
	//	VAR_DUMP($parsed);
		if (empty($parsed['scheme'])) {
			$src = $this->base_url.substr($src,1);
		}
		
		

		$size = getimagesize($src);					
		$width=$size['0'];
		$height=$size['1'];
	foreach($this->image_list as $key => $value){
				
				foreach($value as $key2 => $sourcesrc){
					if($key2 == 'src'){
						$chkarr[] = $sourcesrc;
				}
			}
			
		}

	if($width > 199 && $height > 199 && !in_array($src,$chkarr)){
	$this->image_list[] = array("src"=>$src,"w"=>$width,"h"=>$height);
	
	}
	$predefined = $$name->getAttribute('data-predefined');	
	
	if(isset($predefined) && $predefined == '1'){		
		$$name->removeAttribute('data-predefined');
		continue 1;
		
	}
		$style = $$name->getAttribute('style');
		
		$stylesArray = explode(';',  str_replace(' ', '', $style ));
		foreach ($stylesArray as $key => $value){
			
			if(substr($value,0,6) == "width:"){

				$w = end(explode(':', $value));
				$px = substr($w,-2,strlen($w));
				if($px == "px"){
					$width = substr($w,0,-2);
				}
			}else if(substr($value,0,7) == "height:"){
			
				$h = end(explode(':', $value));
				$px = substr($h,-2,strlen($h));
				if($px == "px"){
					$height = substr($h,0,-2);					
				}
			}
			
		}
		
		$pattern = "/height:\s*\d*\s*(px|%);*/" ;
		$style = preg_replace($pattern,"", $style) ;
		$pattern = "/width:\s*\d*\s*(px|%);*/" ;
		$style = preg_replace($pattern,"", $style) ;
		
				
		$$name->setAttribute('style',$style);
	//	$img->setAttribute('width',$width);
	//	$img->setAttribute('height',$height);			
		//$img->setAttribute('s',$height);			
		
		$alt = $$name->getAttribute('alt');
		$classes = $$name->getAttribute('class');
		if(!preg_match("/img-fluid/i", $classes)){
			$$name->setAttribute('class','img-fluid '.$classes);
		}
		
		
		$picture=$doc->createElement('picture');
		$comment1 = $doc->createComment( '[if IE 9]><video style="display: none;"><![endif]' );
		$comment2 = $doc->createComment( '[if IE 9]></video><![endif]' );		
		
		$filename = pathinfo($parsed['path'], PATHINFO_FILENAME); 
		$ext = pathinfo($parsed['path'], PATHINFO_EXTENSION);
		$dir = pathinfo($parsed['path'], PATHINFO_DIRNAME);
	//	echo $dir.'/'.$filename . '-min.' . $ext . '<hr>';
		$dir = ltrim($dir, '/');
		
		$closed = true;
		$smallest = $cleansrc;	
		foreach($bsarr as $imgwidth => $label){
			if(@getimagesize( $this->base_url.$dir.'/'.$filename . '-' . $label . '.' . $ext)){
			
				if($closed){
					$smallest =	$dir.'/'.$filename . '-'. $label . '.' . $ext;
					$picture->appendChild($comment1);
					$closed = false;
					
					//$source=$doc->createElement('source');
				//	$source->setAttribute('srcset', $cleansrc); //. ' 600w'
					//$picture->appendChild($source);
				}
				if($imgwidth != '0'){
					$source=$doc->createElement('source');
					$source->setAttribute('media',"(max-width: {$imgwidth}px)");	
					$source->setAttribute('srcset', $dir.'/'.$filename . '-'. $label . '.' . $ext); //. ' 600w'
					$picture->appendChild($source);
				}
			
			//	$template = $doc->createDocumentFragment();
			//	$template->appendXML($this->getDOMString($source));
			
			
			
				
				
				//$source2=$doc->createElement('source');
			//	$source2->setAttribute('srcset', $dir.'/'.$filename . '.' . $ext);
			
				//$picture->appendChild($source2);						
			//	$picture->appendChild($comment2);			
			}	
			
		}		
		if(!$closed){
							
			$source=$doc->createElement('source');
				$source->setAttribute('srcset', $cleansrc); //. ' 600w'
				
				$picture->appendChild($source);
			
			
		$picture->appendChild($comment2);
	}
	
	//	$$name->setAttribute('srcset',$cleansrc);	
	//	$$name->removeAttribute('src');	


$$name->setAttribute('src',$smallest);//not for noscript version!		
		$$name->parentNode->insertBefore($picture,$$name);
		$picture->appendChild($$name->cloneNode(true));
		
		
		
		
		
	//	$$name->setAttribute('src',$cleansrc);
	//	$$name->removeAttribute('srcset');
		
	//	$noscript=$doc->createElement('noscript');				
	//	$noscript->appendChild($$name->cloneNode(true));
		
	//	$picture->appendChild($noscript->cloneNode(true));	

		
	
		
		

		
		


	
		
		$$name->parentNode->replaceChild($picture,$$name);
		

		
		/*

		
		
		$span=$doc->createElement('span');
		if($style!=''){
		$span->setAttribute('data-style',$style);
		}
		$span->setAttribute('data-width',$width);
		$span->setAttribute('data-height',$height);
		$span->setAttribute('class','img-fluid image-replacement '.$classes);	
		$span->setAttribute('data-alt',$alt);		
		$span->setAttribute('data-src',$cleansrc);
		$span->setAttribute('data-srcset',$srcset);		
		$img->parentNode->replaceChild($span,$img);
		$span->appendChild($noscript);
	*/
		
	}
	
	

			//fix videos
		$frames = $doc->getElementsByTagName('iframe');	
	

		foreach ($frames AS $frame) {
			
			
				if( !preg_match("/embed-responsive-item/i",$frame->getAttribute('class')) ){
					$d=$doc->createElement('div');
				$frame->setAttribute('class','embed-responsive-item');
				$width = $frame->getAttribute('width'); 
				$height = $frame->getAttribute('height'); 
				$ratio = ($width / $height);
				$ratio = round($ratio, 2);
			$d->setAttribute('class',($ratio < 1.4 ?'embed-responsive embed-responsive-4by3':'embed-responsive embed-responsive-16by9')); 
			$frame->parentNode->replaceChild($d,$frame);
			$d->appendChild($frame);
				}
		}
	
	

	
	
	
	
	
	
		
	$html = $doc->saveHTML();
	
	$html =substr($html, 29, -7); 
	//fix element closings
	$html =$this->getDOMString($html);
	
	return $html;

	}
	}


	public function getMenuByName($name){
       	$query="SELECT * FROM menus WHERE name = :name";
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array(':name'=>$name));						
		if (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {	
			return $row;
		}				
	}	
	
	public $testurl='';
	public $urls=[];
	public $checklist='';
	public $out='';
	//For drop menu
	public $i=0;

	public function getMenu($menu,$type){
		$this->out='';
		//$phpArray=json_decode('[{"url":"/","name":"home"},{"url":"/testpage","name":"products <br>and services","children":[{"url":"/products-and-services/design","name":"design","children":[{"url":"/products-and-services/design/graphics","name":"graphics"},{"url":"/products-and-services/design/web","name":"web"}]},{"url":"/products-and-services/seo","name":"seo"}]}]');
		$res = $this->getMenuByName($menu);
		$phpArray = unserialize($res['data']);

		$phpArray=$this->labelObjects($phpArray);
	
		$this->testurl=$this->path;//'/testpage';
		$this->testurl=ltrim($this->testurl,'/');
		$testurls=explode('/',$this->testurl);

		
		$this->checked($phpArray);

		$urlcount = count($testurls);
		$i=0;$urlcheck='';
		while($i < ($urlcount)){
			$urlcheck.='/'.$testurls[$i];
			foreach($this->urls as $id => $url){
				if($urlcheck == '/home'){$urlcheck = '/';}
				if(str_replace("-", " ", strtolower($url))==str_replace("-", " ", strtolower($urlcheck))){
					$this->checklist[]=$url;		
				}
			}
		$i++;
		}
		
		
		
	//Determine if editable div is required
	$user=(isset($_SESSION[SESSION_PREFIX]['user'])) ? $_SESSION[SESSION_PREFIX]['user'] : null;
		
	if($user && $user->isAdmin()){
		$userLoggedIn = true;
	}
		
	if($type=='sidemenu'){
			$this->sidemenu($phpArray);
			return $this->editable($res['id'],"<ol class='tree'>".$this->out."</ol>");	
			
		}else
		if($type=='dropmenu'){
			$this->dropmenu($phpArray);
		return $this->editable($res['id'],"<ul id='nav' class=''>".$this->out."</ul>");	
			
		}
	}
	
	public function labelObjects($objects){
		foreach($objects as $object){
			$object->skip = 1;			
		}
		return $objects;
	}
	function checked($objects){
		foreach($objects as $object){
			$this->urls[] = $object->url;
			if(isset($object->children)){            
				$this->checked($object->children);            
			}
		}
	}
	function checkedurl($url,$str='checked'){
		if(is_array($this->checklist)){
			if(in_array($url,$this->checklist)){
				return $str;
			}
		}
		return'';
	}		
	public function sidemenu($objects)
	{		
		foreach($objects as $object){
		
			$href="";$href_close="";
			if($object->url != ''){
				$href="<a href='".$object->url."' ".($object->url == '/'.$this->testurl? 'class="select"':'').">";
				$href_close='</a>';
			}	
			if(isset($object->children)){
				$this->out.= "<li><label for='".$object->name."'>$href".strip_tags($object->name,'')."$href_close</label> <input type='checkbox' ".$this->checkedurl($object->url)." id='".$object->name."' />";
			}else{
				$linsert = '';$label='';$label_close='';
				if(!$object->skip){
					$linsert = " class='file'";
				}else{
					$label="<label for='".$object->name."'>";
					$label_close="</label><input type='checkbox' id='".$object->name."' />";					
				}
				
				$this->out.= "<li".$linsert.">".$label.$href.strip_tags($object->name,'')."$href_close$label_close";	
			}
			if(isset($object->children)){
				 $this->out.= '<ol>';
				$this->sidemenu($object->children);
				$this->out.= '</ol>';
			}
			$this->out.= '</li>';
		}
	}
	

	public function dropmenu($objects)
	{

		foreach($objects as $object){

			$href='';
			if($object->url != ''){
				$href='<a class="nav-link'.$this->checkedurl($object->url, ' active').(isset($object->children) && $object->skip?' r-pad':'').'" href="'.$object->url.'">'.$object->name.'</a>'.(isset($object->children) && $object->skip?'<img alt="" height="16" class="downarrow" src="/images/down-arrow.png"/>':'').'';
			}else{
				$href='<span>'.$object->name.'</span>';
			}	
			$this->out.= '<li class="nav-item">'.$href.(isset($object->children) && !$object->skip?'<img alt="" height="16" class="downarrow" src="/images/down-arrow.png"/><img alt="" height="16" class="arrow" src="/images/side-arrow.png"/>':'');

			if(isset($object->children)){
				$this->out.= '<ul>';
				$this->dropmenu($object->children);
				$this->out.= '</ul>';
			}			
			$this->out.= '</li>';
		}
	}

private function editable($id,$content){
	
	if($this->editing()){	///have to be an admin to get this far, && canEdit might be of use || $user->canEditArticle($this->content_model))
			return "<div class='editable'><a href='".$this->base_url.'siteadmin/menueditor?menuid='.$id."'>Edit Menu</a>$content</div>";	
	}else{
		return $content;
	}	
}

function editing(){
	$user=(isset($_SESSION[SESSION_PREFIX]['user'])) ? $_SESSION[SESSION_PREFIX]['user'] : null;
	if($user && $user->isAdmin()){
		return true;
	}else{
		return false;
	}
}


	
}/*	
		echo '<pre>';
		echo htmlspecialchars(print_r($testurls, true));
		echo '</pre><hr>';

		echo '<pre>';
		echo htmlspecialchars(print_r($urls, true));
		echo '</pre><hr>';	
				
		echo '<pre>';
		echo htmlspecialchars(print_r($checklist, true));
		echo '</pre><hr>';
		*/