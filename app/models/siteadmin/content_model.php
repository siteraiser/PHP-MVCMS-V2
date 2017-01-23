<?php /*
Copyright © 2016 
	
	This file is part of MVCMS.

    MVCMS is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    MVCMS is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with MVCMS.  If not, see <http://www.gnu.org/licenses/>.
*/	
class content_model extends requestHandler{
	public $userid;
	//for User class
	public function init()
    {
       	$query="SELECT user FROM content WHERE id = :id";
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array(':id'=>$_GET['article'])); 		
		while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
			$this->userid = $row["user"];			
		}
    }
	function getCreatorId(){
		return $this->userid;
	}

	public function insert(){		
		$date = new DateTime('now');
		$timestamp = $date->format('Y-m-d H:i:s');
		$query='INSERT INTO content (
		articlename,
		published,
		menu,
		type,
		menutype,
		content,
		date,
		lastupdate,
		dependencies
		)
		VALUES
		(?,?,?,?,?,?,?,?,?)';			
		
		$array=array(
			$_POST['articlename'],
			isset($_POST['published']) ? 1 : 0,
			$_POST['menu'],
			$_POST['type'],		
			$_POST['menutype'],	
			$_POST['content'],
			$timestamp,
			$timestamp,
			$_POST["dependencies"]
			);				
			
			$stmt=$this->pdo->prepare($query);
			$stmt->execute($array);			
			return $this->pdo->lastInsertId('id');
	}
	public function imgPrep($html){		
	
	
	
	
		$html='<div class="dynamic-content">'.$html.'</div>';//

	$doc = new DOMDocument();
	@$doc->loadHTML($html);

	$doc->removeChild($doc->doctype); 
	$doc->replaceChild($doc->firstChild->firstChild->firstChild, $doc->firstChild);

//$bsarr = array('1200'=>'lg','992'=>'md','768'=>'sm','544'=>'xs');
$bsarr = array('544'=>'xs','768'=>'sm','992'=>'md','1200'=>'lg');



	$selector = new DOMXPath($doc);
	
	
	
	
	
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
		
		
		
		$html = $doc->saveHTML();
	
		$html =substr($html, 29, -7); 
		//fix element closings
		$html =$this->getDOMString($html);
		
		return $html;
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
	
	
	
	
	
	public function update_ajax(){	


		
		
		
		
		
		
		
		
		
		
		
/* NOT BEING USED... */


/**
 * Helper function for drupal_html_to_text().
 *
 * Calls helper function for HTML 4 entity decoding.
 * Per: http://www.lazycat.org/software/html_entity_decode_full.phps
 */
function decode_entities_full($string, $quotes = ENT_COMPAT, $charset = 'ISO-8859-1') {
  return html_entity_decode(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]+);/', 'convert_entity', $string), $quotes, $charset); 
}

/**
 * Helper function for decode_entities_full().
 *
 * This contains the full HTML 4 Recommendation listing of entities, so the default to discard  
 * entities not in the table is generally good. Pass false to the second argument to return 
 * the faulty entity unmodified, if you're ill or something.
 * Per: http://www.lazycat.org/software/html_entity_decode_full.phps
 */
function convert_entity($matches, $destroy = true) {
  static $table = array('quot' => '&#34;','amp' => '&#38;','lt' => '&#60;','gt' => '&#62;','OElig' => '&#338;','oelig' => '&#339;','Scaron' => '&#352;','scaron' => '&#353;','Yuml' => '&#376;','circ' => '&#710;','tilde' => '&#732;','ensp' => '&#8194;','emsp' => '&#8195;','thinsp' => '&#8201;','zwnj' => '&#8204;','zwj' => '&#8205;','lrm' => '&#8206;','rlm' => '&#8207;','ndash' => '&#8211;','mdash' => '&#8212;','lsquo' => '&#8216;','rsquo' => '&#8217;','sbquo' => '&#8218;','ldquo' => '&#8220;','rdquo' => '&#8221;','bdquo' => '&#8222;','dagger' => '&#8224;','Dagger' => '&#8225;','permil' => '&#8240;','lsaquo' => '&#8249;','rsaquo' => '&#8250;','euro' => '&#8364;','fnof' => '&#402;','Alpha' => '&#913;','Beta' => '&#914;','Gamma' => '&#915;','Delta' => '&#916;','Epsilon' => '&#917;','Zeta' => '&#918;','Eta' => '&#919;','Theta' => '&#920;','Iota' => '&#921;','Kappa' => '&#922;','Lambda' => '&#923;','Mu' => '&#924;','Nu' => '&#925;','Xi' => '&#926;','Omicron' => '&#927;','Pi' => '&#928;','Rho' => '&#929;','Sigma' => '&#931;','Tau' => '&#932;','Upsilon' => '&#933;','Phi' => '&#934;','Chi' => '&#935;','Psi' => '&#936;','Omega' => '&#937;','alpha' => '&#945;','beta' => '&#946;','gamma' => '&#947;','delta' => '&#948;','epsilon' => '&#949;','zeta' => '&#950;','eta' => '&#951;','theta' => '&#952;','iota' => '&#953;','kappa' => '&#954;','lambda' => '&#955;','mu' => '&#956;','nu' => '&#957;','xi' => '&#958;','omicron' => '&#959;','pi' => '&#960;','rho' => '&#961;','sigmaf' => '&#962;','sigma' => '&#963;','tau' => '&#964;','upsilon' => '&#965;','phi' => '&#966;','chi' => '&#967;','psi' => '&#968;','omega' => '&#969;','thetasym' => '&#977;','upsih' => '&#978;','piv' => '&#982;','bull' => '&#8226;','hellip' => '&#8230;','prime' => '&#8242;','Prime' => '&#8243;','oline' => '&#8254;','frasl' => '&#8260;','weierp' => '&#8472;','image' => '&#8465;','real' => '&#8476;','trade' => '&#8482;','alefsym' => '&#8501;','larr' => '&#8592;','uarr' => '&#8593;','rarr' => '&#8594;','darr' => '&#8595;','harr' => '&#8596;','crarr' => '&#8629;','lArr' => '&#8656;','uArr' => '&#8657;','rArr' => '&#8658;','dArr' => '&#8659;','hArr' => '&#8660;','forall' => '&#8704;','part' => '&#8706;','exist' => '&#8707;','empty' => '&#8709;','nabla' => '&#8711;','isin' => '&#8712;','notin' => '&#8713;','ni' => '&#8715;','prod' => '&#8719;','sum' => '&#8721;','minus' => '&#8722;','lowast' => '&#8727;','radic' => '&#8730;','prop' => '&#8733;','infin' => '&#8734;','ang' => '&#8736;','and' => '&#8743;','or' => '&#8744;','cap' => '&#8745;','cup' => '&#8746;','int' => '&#8747;','there4' => '&#8756;','sim' => '&#8764;','cong' => '&#8773;','asymp' => '&#8776;','ne' => '&#8800;','equiv' => '&#8801;','le' => '&#8804;','ge' => '&#8805;','sub' => '&#8834;','sup' => '&#8835;','nsub' => '&#8836;','sube' => '&#8838;','supe' => '&#8839;','oplus' => '&#8853;','otimes' => '&#8855;','perp' => '&#8869;','sdot' => '&#8901;','lceil' => '&#8968;','rceil' => '&#8969;','lfloor' => '&#8970;','rfloor' => '&#8971;','lang' => '&#9001;','rang' => '&#9002;','loz' => '&#9674;','spades' => '&#9824;','clubs' => '&#9827;','hearts' => '&#9829;','diams' => '&#9830;','nbsp' => '&#160;','iexcl' => '&#161;','cent' => '&#162;','pound' => '&#163;','curren' => '&#164;','yen' => '&#165;','brvbar' => '&#166;','sect' => '&#167;','uml' => '&#168;','copy' => '&#169;','ordf' => '&#170;','laquo' => '&#171;','not' => '&#172;','shy' => '&#173;','reg' => '&#174;','macr' => '&#175;','deg' => '&#176;','plusmn' => '&#177;','sup2' => '&#178;','sup3' => '&#179;','acute' => '&#180;','micro' => '&#181;','para' => '&#182;','middot' => '&#183;','cedil' => '&#184;','sup1' => '&#185;','ordm' => '&#186;','raquo' => '&#187;','frac14' => '&#188;','frac12' => '&#189;','frac34' => '&#190;','iquest' => '&#191;','Agrave' => '&#192;','Aacute' => '&#193;','Acirc' => '&#194;','Atilde' => '&#195;','Auml' => '&#196;','Aring' => '&#197;','AElig' => '&#198;','Ccedil' => '&#199;','Egrave' => '&#200;','Eacute' => '&#201;','Ecirc' => '&#202;','Euml' => '&#203;','Igrave' => '&#204;','Iacute' => '&#205;','Icirc' => '&#206;','Iuml' => '&#207;','ETH' => '&#208;','Ntilde' => '&#209;','Ograve' => '&#210;','Oacute' => '&#211;','Ocirc' => '&#212;','Otilde' => '&#213;','Ouml' => '&#214;','times' => '&#215;','Oslash' => '&#216;','Ugrave' => '&#217;','Uacute' => '&#218;','Ucirc' => '&#219;','Uuml' => '&#220;','Yacute' => '&#221;','THORN' => '&#222;','szlig' => '&#223;','agrave' => '&#224;','aacute' => '&#225;','acirc' => '&#226;','atilde' => '&#227;','auml' => '&#228;','aring' => '&#229;','aelig' => '&#230;','ccedil' => '&#231;','egrave' => '&#232;','eacute' => '&#233;','ecirc' => '&#234;','euml' => '&#235;','igrave' => '&#236;','iacute' => '&#237;','icirc' => '&#238;','iuml' => '&#239;','eth' => '&#240;','ntilde' => '&#241;','ograve' => '&#242;','oacute' => '&#243;','ocirc' => '&#244;','otilde' => '&#245;','ouml' => '&#246;','divide' => '&#247;','oslash' => '&#248;','ugrave' => '&#249;','uacute' => '&#250;','ucirc' => '&#251;','uuml' => '&#252;','yacute' => '&#253;','thorn' => '&#254;','yuml' => '&#255;'
                       );

  if (isset($table[$matches[1]])) return $table[$matches[1]];
  // else 
  return $destroy ? '' : $matches[0];
}


		
		$date = new DateTime('now');
		$timestamp = $date->format('Y-m-d H:i:s');
	
	
	    $query="SELECT user FROM content WHERE lastupdate = :lastupdate";
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array(':lastupdate'=>$_POST['lastupdate'])); 		
		if (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {	
			$count = 1;
		}
		
		$query='UPDATE content SET 

		content=:content,
		lastupdate=:lastupdate
		
		WHERE id=:id';
		//echo 'what'. $valu = str_replace("®", "&reg;" ,$valu);utf8_decode($_POST['content'])
		$array=array(
			
				':content'=>$this->imgPrep($_POST['content'])
				
			);				

			$array[':lastupdate']=$timestamp;

			$array[':id']=$_POST['id'];
			
			$stmt=$this->pdo->prepare($query);
			$stmt->execute($array);	
			
				
			//Find articles calling this article
			$delete_cache_ids = $this->findLinkedArticles();
			
			//Handle Page Caching
			foreach($delete_cache_ids as $key => $value){					
				$this->deleteCacheByArticleId($value);	
				$this->updateArticleLastModByArticleId($value,$timestamp);	
				$this->updateLastModByArticleId($value,$timestamp);	
			}
			$this->deleteCacheByArticleId($_POST['id']);
			$this->updateLastModByArticleId($_POST['id'],$timestamp);			
			return $delete_cache_ids;
	}
	public function update(){			
		$date = new DateTime('now');
		$timestamp = $date->format('Y-m-d H:i:s');
	
	
	    $query="SELECT user FROM content WHERE lastupdate = :lastupdate";
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array(':lastupdate'=>$_POST['lastupdate'])); 		
		if (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {	
			$count = 1;
		}
		
		$query='UPDATE content SET 
		articlename=:articlename,
		published=:published,
		menu=:menu,
		type=:type,
		menutype=:menutype,
		content=:content,
		date=:date,
		dependencies=:dependencies,		
		lastupdate=:lastupdate
		
		WHERE id=:id';
		
		$array=array(
				':articlename'=>$_POST['articlename'],
				':published'=>isset($_POST['published']) ? 1 : 0,
				':menu'=>$_POST['menu'],
				':type'=>$_POST['type'],		
				':menutype'=>$_POST['menutype'],	
				':content'=>$_POST['content'],
				':date'=>$_POST['date'],
				':dependencies'=>$_POST["dependencies"]
			);				
			if($count == 0){
				$array[':lastupdate']=$_POST['lastupdate'];
			}else{
				$array[':lastupdate']=$timestamp;
				
			}
			$array[':id']=$_POST['id'];
			
			$stmt=$this->pdo->prepare($query);
			$stmt->execute($array);	
			
				
			//Find articles calling this article
			$delete_cache_ids = $this->findLinkedArticles();
			
			//Handle Page Caching
			foreach($delete_cache_ids as $key => $value){					
				$this->deleteCacheByArticleId($value);	
				$this->updateArticleLastModByArticleId($value,$timestamp);	
				$this->updateLastModByArticleId($value,$timestamp);	
			}
			$this->deleteCacheByArticleId($_POST['id']);
			$this->updateLastModByArticleId($_POST['id'],$timestamp);		
				
	}
	function findLinkedArticles(){						
			
	
			$query="SELECT id FROM content WHERE content LIKE '%[article[".$_POST['id']."]]%'";
			$stmt=$this->pdo->prepare($query);
			$stmt->execute(array());
		
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
				$delete_cache_ids[] = $row["id"];			
			}	
			return $delete_cache_ids;			
		}
	
	
	public function getAllById($table,$id){
       	$query="SELECT * FROM content WHERE id = :id";
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array(':id'=>$id)); 		
		if (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {	
			$value = $row["content"];				
			$value = str_replace("&", "&amp;" ,$value);
	        $value = str_replace("<", "&lt;",$value);
			$row['content'] = $value;    				
		}
		
		return $row;
	}
	public function selectDistinct($table,$col){
		//not necesarily being used
		$stmt=$this->pdo->prepare("SELECT DISTINCT $col FROM $table");
		$stmt->execute(array());
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $rows;
	}	
	
	
	
	public function countAll($table){
	
		$stmt=$this->pdo->prepare("SELECT count(*) AS count FROM $table");
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['count'];
	}
	//------
	
	public function getPagesByArticleId($id){
		$sql="SELECT * FROM pages
		
		WHERE FIND_IN_SET(?, pages.articleids) OR pages.articleids=?";	
	
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array($id,$id));
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function delete($id){
		$message='';
			//Join on match or find in set
		$rows = $this->getPagesByArticleId($id);
		if(count($rows)!=0){
				$message='Article removed from the following pages:';
			foreach ($rows as $row){
			
				$assignedviewpositions=unserialize($row['positions']);			
				
				foreach($assignedviewpositions as $key => $position){
					if($position['id']==$id ){
						unset($assignedviewpositions[$key]);
					}
				}
				
				$assignedviewpositions=serialize($assignedviewpositions);

				$articleids=explode(',',$row['articleids']);
				foreach (array_keys($articleids, $id) as $key) {
					unset($articleids[$key]);
				}
				$articleids=implode(',',$articleids);	
	
				$this->update_page($row['id'],$articleids,$assignedviewpositions);
				//Delete this page's cache
				$this->deletePageCache($row['categoryname'],$row['page']);	
				
				$message.=' '.$row['page'];
				
			}
		}
		$message.=$this->delete_article($id);
		

		return $message;
	
	}	
	function delete_article($id){		
			if ($this->pdo->exec("DELETE FROM content WHERE id = $id")) {
				return 'Article id:'.$id.' deleted';
			}
			$this->deleteAppCaches($id);		
		}
		
	function update_page($id,$articleids, $positions){

			$date = new DateTime('now');
			$timestamp = $date->format('Y-m-d H:i:s');
			$query='UPDATE pages SET 		
			articleids=:articleids,
			positions=:positions,
			lastupdate=:lastupdate
			
			WHERE id=:id';
			
			$stmt=$this->pdo->prepare($query);
			$stmt->execute(array(
			':articleids'=>$articleids,	
			':positions'=>$positions,
			':lastupdate'=>$timestamp,
			':id'=>$id));
		}

		
	public function updateArticleLastModByArticleId($id,$lastupdate){

			$query='UPDATE content SET 		
			lastupdate=:lastupdate
			
			WHERE id=:id';
			
			$stmt=$this->pdo->prepare($query);
			$stmt->execute(array(
			':lastupdate'=>$lastupdate,
			':id'=>$id));
	}	
	
	public function update_page_date($id,$lastupdate){

			$query='UPDATE pages SET 		
			lastupdate=:lastupdate
			
			WHERE id=:id';
			
			$stmt=$this->pdo->prepare($query);
			$stmt->execute(array(
			':lastupdate'=>$lastupdate,
			':id'=>$id));
		}		
		
	//Update lastmod
	public function updateLastModByArticleId($id, $lastupdate){
		
		$rows = $this->getPagesByArticleId($id);
		//Delete cache for every page that contains this article
		if(count($rows)!=0){			
			foreach ($rows as $row){
				//echo'id: ' .$row['id'] . ' Date: '.$lastupdate . '<br>';
				$this->update_page_date($row['id'],$lastupdate);
			}			
		}
	}
		
		
		
	//Delete Caching functions
	public function deleteCacheByArticleId($id){
		$rows = $this->getPagesByArticleId($id);
		//Delete cache for every page that contains this article
		if(count($rows)!=0){			
			foreach ($rows as $row){
				$this->deletePageCache($row['categoryname'],$row['page']);
			}			
		}
		$this->deleteAppCaches($id);	
	}
	//Delete pages'	caches on update or delete
	public function deletePageCache($categoryname,$page){
		$path=($categoryname==''?'':$categoryname.'/').$page;
		$break = Explode('/', $path);
		$file = implode('-^-', $break);
		$cachefile = $this->doc_root.'cached/cached-'.$file;
		if (file_exists($cachefile)){
			unlink($cachefile);
		}		
	}	



		//Un-Caching
		
		//Dependent apps cache deletion. "blog" will delete: blog, blog/anycache etc.
	function deleteAppCaches($id){	
		$query="SELECT dependencies FROM content
		WHERE content.id = :id";
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array(':id'=>$id)); 	
		
		$count=$stmt->rowCount($result);
		if ($count > 0) {
			$row=$stmt->fetch(PDO::FETCH_ASSOC);			
			$apps = explode(',',$row['dependencies']);
		}

		$dir = new DirectoryIterator($this->doc_root."/cached");
		foreach ($dir as $fileinfo) {
		if($fileinfo != '.' && $fileinfo != '..')
			$caches[]= pathinfo($fileinfo->getFilename(), PATHINFO_FILENAME);
		}
		
		foreach ($apps as $app) {
		//delete app base cache
			if (file_exists($this->doc_root.'cached/'.$app)){
				unlink($this->doc_root.'cached/'.$app);
			}	
			//delete nested caches
			$compare='cached-'.$app.'-^-';
			$del_length = strlen('cached-'.$app.'-^-');
			foreach($caches as $cachefile){
			
				$teststem=substr($cachefile, 0, $del_length); 
				if($compare == $teststem){
					if (file_exists($this->doc_root.'cached/'.$cachefile)){
						unlink($this->doc_root.'cached/'.$cachefile);
					}		
				}
			}
		}
	
	}
	
}