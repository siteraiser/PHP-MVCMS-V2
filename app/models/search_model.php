<?php /*
Copyright � 2016 
	
	This file is part of PHP-MVCMS.

    PHP-MVCMS is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    PHP-MVCMS is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PHP-MVCMS.  If not, see <http://www.gnu.org/licenses/>.
*/	
class search_model extends requestHandler{

//Stopwords from: http://xpo6.com/download-stop-word-list/
	public $stopwords = array("&", "a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");	
	//Build entire pages reverse word index
	public function updateIndex ($pages,$articles){
		$indexes=array();

		foreach($pages as $id => $array){
			foreach($array['words'] as $word){
			//	echo 'key:'.$id .'-'. $word . '<br>';
				if(!array_key_exists($word,$indexes ) ){
					$indexes[$word]['pages'] = $id;
				}else if(!in_array($id, explode(',',$indexes[$word]['pages'] ))){
					$indexes[$word]['pages'] = $indexes[$word]['pages'] . ',' .$id;
				}				
			}
		}
		
		
		foreach($articles as $id => $array){
			foreach($array['words'] as $word){
			//	echo 'key:'.$id .'-'. $word . '<br>';
				if(!array_key_exists($word,$indexes )){
					$indexes[$word]['articles'] = $id;
				}else if(!in_array($id, explode(',',$indexes[$word]['articles'] ))){
					$indexes[$word]['articles'] = $indexes[$word]['articles'] . ($indexes[$word]['articles']==''?'':',') .$id;
				}				
			}
		}
		
				
	//echo '<pre>',htmlspecialchars(print_r($articles, true)),'</pre>';
	//echo '<pre>',htmlspecialchars(print_r($pages, true)),'</pre>';
	//echo '<pre>',htmlspecialchars(print_r($indexes, true)),'</pre>';

		$this->pdo->query("TRUNCATE TABLE searchindex");	
		$sql="INSERT INTO searchindex (word,pagelist,articlelist) VALUES (?,?,?);";
		foreach($indexes as $word => $array){			
			$stmt=$this->pdo->prepare($sql);
			$stmt->execute(array($word,$array['pages'],$array['articles']));	
		}
	}	

public function getPages($rows){

	$skip=array('header','footer');//'menu',
		foreach($rows as $row)
		{	//echo$row['type'];
			if(isset($row['pageid']) && $row['type'] !='noindex' && !(in_array($row['type'],$skip))){
				$pages[$row['pageid']][]=$row;
 /*if not set*/ $pages[$row['pageid']]["headline"]=$row['headline'];
 
			}
					
		}
		
	//	echo '<pre>',htmlspecialchars(print_r($pages, true)),'</pre>';
		
		
	foreach($pages as $key => $page){	
		$retpages[$key]['words']=$this->getUniques($page["headline"] . ' ' . $this->strip($this->renderPage($page)));
	}
	return $retpages;
}


public function renderPage($results){

$sorted=[];
$artOrder=explode( ',', $results[0]['articleids']);
foreach($artOrder as $id){
	foreach($results as $row){
		if($row['articleid'] == $id){
		$row['articleid'];
			$sorted[] = $row;
		}		
	}
}
//var_dump($sorted);

$articleAssViews=unserialize( $results[0]['positions']);

$used=[];
foreach($sorted as $rowkey => $article){
	foreach($articleAssViews as $key => $page){
		if($article['articleid'] == $page['id']){
			$aggregate='';
			if($page['aggregate'] == 'aggregate'){					
				$aggregate='aggregate';			
			}else if($page['aggregate'] == 'agg-pos'){
				$aggregate='agg-pos';			
			}else{
				$aggregate='single';
			}
				
			if($article['type'] == 'header'){ 
				$loadViews[$rowkey][$page['view']]=array('meta'=>$head['meta'],'title'=>$head['title'],'content'=>$article['content']);	
			}else if($article['type'] == 'menu'){ 
				$loadViews[$rowkey][$page['view']]=array('content'=>$article['content'],'type'=>$aggregate);
			}else{
			$loadViews[$rowkey][$page['view']]['type']=$aggregate;
			$loadViews[$rowkey][$page['view']]['content']=($article['type'] == 'noindex'? '' : $article['content']);
			}
			unset($articleAssViews[$key]);break;
		}	
	}
}


//aggregate
$temp='';$menutemp='';
foreach($loadViews as $key => $value){
	foreach($value as $key2 => $value2){

		if($value2['type'] == 'aggregate' AND $loadViews[$key + 1][$key2]['type'] == 'aggregate' AND key($value) == key($loadViews[$key + 1])){
			$temp.= $value2['content'];			
		}else if($value2['type'] == 'aggregate' AND $loadViews[$key - 1][$key2]['type'] == 'aggregate' AND key($value) == key($loadViews[$key - 1])		
		AND $value2['type'] == 'aggregate' AND ($loadViews[$key + 1][$key2]['type'] !== 'aggregate' OR key($value) !== key($loadViews[$key + 1]))){
		
			$content['content']=$temp.$value2['content'];			
			$pagecontent.=$this->renderView('templates/'.key($value),$content);
			$temp='';$menutemp='';//Done aggregating this view, this occurance
	

		}else if($value2['type'] == 'agg-pos' AND $loadViews[$key + 1][$key2]['type'] == 'agg-pos' AND key($value) == key($loadViews[$key + 1])){
			$temp[]= $value2['content'];

			
		}else if($value2['type'] == 'agg-pos' AND $loadViews[$key - 1][$key2]['type'] == 'agg-pos' AND key($value) == key($loadViews[$key - 1])		
		AND $value2['type'] == 'agg-pos' AND ($loadViews[$key + 1][$key2]['type'] !== 'agg-pos' OR key($value) !== key($loadViews[$key + 1]))){
			$temp[]=$value2['content'];
			$content['content']=$temp;
			
			$pagecontent.=$this->renderView('templates/'.key($value),$content);
			$temp='';$menutemp='';//Done aggregating this view, this occurance
		}else{
			$content['content'] = $value2['content'];	
			$pagecontent.=$this->renderView('templates/'.key($value),$content);	
		}

	}
}




 $this->temphtml = $pagecontent;
$this->getParts();
$pagecontent = $this->temphtml;
unset($this->temphtml);
return  html_entity_decode($pagecontent,ENT_QUOTES);
}
public function get_include_contents2($filename,$data) {
		foreach($data as $key => $value){
			$$key = $value;
		}
		if (is_file('app/views/'.$filename.'.php')) {
			ob_start();
	
			include 'app/views/'.$filename.'.php';
			return ob_get_clean();
		}
		echo $filename . ' is not a valid file!';
		return false;
	}	
	public function renderView($view,$data) {	
		return $this->get_include_contents2($view,$data);		
	}	
	
	//For including articles assigned to articles also in template models and every page that uses includes articles with articles...
	public $temphtml='';	
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
		}
		$this->temphtml = $beginning . $article . $end;
		$this->getParts();	
	}
	public function articleById($id){
		$query="SELECT content,type FROM content WHERE id = ? AND published = 1 LIMIT 1";
		$stmt=$this->pdo->prepare($query);
		$stmt->execute(array($id)); 
		if (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {	
			if($row['type'] != 'noindex' || $skipnoindex == true){
				return $row['content'];				
			}else{
				return '';
			}
			
		}
	}

	public function submitPage($pageID){
		//echo$pageID;
		//echo'<hr>id-->'.	$pageID;
		$sql="SELECT *,pages.id AS pageid, content.id AS articleid FROM content
		JOIN pages ON (pages.articleids=content.id AND pages.published = 1 AND content.published = 1 AND pages.id = ?
		) OR ( FIND_IN_SET(content.id, pages.articleids) AND pages.published = 1 AND content.published = 1 AND pages.id = ?)";

		$sql.=" ORDER BY content.id ASC;";
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array($pageID,$pageID));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	

		$page = $this->getPages($rows);
		$words = $page[$pageID]['words'];


		if(count($words) == 0){
			$this->deletePage($pageID);
		}

		
		
		
		$in  = str_repeat('?,', count($words) - 1) . '?';	

		//	echo $word;
		$sql="SELECT word,pagelist FROM searchindex WHERE word IN ($in)";//AND NOT FIND_IN_SET(?, pagelist)
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute($words);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//	echo '<br>'.$word . '==='. count($rows);
		
//Find and insert any current words not found in index
		foreach($rows as $row){
			$wordArray["word"][] = $row['word'];
			$wordArray["pagelist"][] = $row['pagelist'];
		//	$wordArray["articlelist"][] = $row['pagelist'];			
		}	
		
		foreach($words as $word){
			if(!in_array($word,$wordArray['word'])){
				$notFound[] =$word;			
			}
		}
		$sql="INSERT INTO searchindex (word,pagelist) VALUES (?,?);";
		foreach($notFound as $word){			
			$stmt=$this->pdo->prepare($sql);
			$stmt->execute(array($word,$pageID));	
		}
	

//Get all words which don't already have this page id
		$sql="SELECT word,pagelist FROM searchindex WHERE NOT FIND_IN_SET(?, pagelist) AND word IN ($in)";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array_merge(array($pageID),$words));
		$rows2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
 
	

			$sql="UPDATE searchindex SET word=?,pagelist=? WHERE word=?;";
			foreach($rows2 as $row){						
				$stmt=$this->pdo->prepare($sql);
				$stmt->execute(array($row['word'],$row['pagelist'].($row['pagelist'] !=''?',':'').$pageID,$row['word']));	
				//echo 'should be hjere!!!'.$row['pagelist'].','.$pageID;
			}			
		
		// Delete words that are this page id and are not in current content
		$sql="DELETE FROM searchindex WHERE pagelist = ? AND word NOT IN ($in) AND articlelist IS NULL";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array_merge(array($pageID),$words));
	//	echo '<br>deleted from search during submit'.$stmt->rowCount();
	
		
		/* Find and remove pageid from remaining in list */
		$sql="SELECT word,pagelist FROM searchindex WHERE FIND_IN_SET(?, pagelist) AND word NOT IN ($in)";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array_merge(array($pageID),$words));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);			
		

			$sql="UPDATE searchindex SET word=?,pagelist=? WHERE word=?;";
			foreach($rows as $row){		
				$good=[];
				$ids=explode(',',$row['pagelist']);
				foreach($ids as $val){
					if($val != $pageID){
					$good[] = $val;
					}					
				}
				
				$stmt=$this->pdo->prepare($sql);
				$stmt->execute(array($row['word'],implode(',',$good),$row['word']));	
			}			
		
/*
		echo 'Found Word array<pre>';
		var_dump($wordArray);
		echo'</pre>';	
		
		echo 'Not Found<pre>';
		var_dump($notFound);
		echo'</pre>';	
*/
	}
	//shoud be done before actual removal
	public function deletePage($pageID){
				
	
	
		//delete any words without any other remaining ids
		$sql="DELETE FROM searchindex WHERE pagelist = ? AND articlelist IS NULL";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array($pageID));
	//	echo '<hr>deleted from search...'.$stmt->rowCount();
			
		
		/* Find and remove pageid from remaining in list */
		$sql="SELECT word,pagelist FROM searchindex WHERE FIND_IN_SET(?, pagelist)";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array($pageID));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		
		
		
	
			
			$sql="UPDATE searchindex SET word=?,pagelist=? WHERE word=?;";
			foreach($rows as $row){		
				$good=[];			
				$ids=explode(',',$row['pagelist']);
				foreach($ids as $val){					
					if($val != $pageID){
						$good[] = $val;
					}					
				}
				
				$stmt=$this->pdo->prepare($sql);
				$stmt->execute(array($row['word'],implode(',',$good),$row['word']));	
			}			
			
	}	

	
	
	
	
	
	
	
	
	
	
	public function submitArticle($articleID){
		//echo $articleID;
		//Get articles
		$sql="SELECT * FROM content WHERE id = ? AND published = 1 ";  //(type != 'header' AND type != 'footer' AND type != 'default' AND type != 'menu' AND type != 'html' AND type != 'auto p false' AND type != 'noindex' AND type != '' AND type IS NOT NULL)";//type = article
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array($articleID));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($rows as $row)
		{	
			
			$content=$this->strip( html_entity_decode($row['content'],ENT_QUOTES));
			if( $content != ''){	
				if($row['type'] !='noindex'){					
					$content = $this->getUniques( $row['articlename'].' '. $row['description'].' '.$content);
				}else{
					$content = '';
				}
				if(isset($articles[$row['id']])){
					foreach($articles as $word){
						$articles[$row['id']]['words'][] = $word;
					}
				}else{
					$articles[$row['id']]['words'] =  $content;
					//$pages[$row['id']]['type'] = 'page';
				}
			}			
		}
		
		
		
		
		$words = $articles[$articleID]['words'];
		
//	echo'word count'.count($words);			
		if(count($words) == 0){
			$this->deleteArticle($articleID);
		}

		
		
		
		
		$in  = str_repeat('?,', count($words) - 1) . '?';	

		//	echo $word;
		$sql="SELECT word,articlelist FROM searchindex WHERE word IN ($in)";//AND NOT FIND_IN_SET(?, pagelist)
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute($words);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//	echo '<br>'.$word . '==='. count($rows);
		
//Find and insert any current words not found in index
		foreach($rows as $row){
			$wordArray["word"][] = $row['word'];
			$wordArray["articlelist"][] = $row['articlelist'];
		//	$wordArray["articlelist"][] = $row['pagelist'];			
		}	
		
		foreach($words as $word){
			if(!in_array($word,$wordArray['word'])){
				$notFound[] =$word;			
			}
		}
		$sql="INSERT INTO searchindex (word,articlelist) VALUES (?,?);";
		foreach($notFound as $word){			
			$stmt=$this->pdo->prepare($sql);
			$stmt->execute(array($word,$articleID));	
		}
	

//Get all words which don't already have this page id
		$sql="SELECT word,articlelist FROM searchindex WHERE NOT FIND_IN_SET(?, articlelist) AND word IN ($in)";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array_merge(array($articleID),$words));
		$rows2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		
		foreach($rows2 as $row){			
			
			$sql="UPDATE searchindex SET word=?,articlelist=? WHERE word=?;";
			foreach($rows as $row){						
				$stmt=$this->pdo->prepare($sql);
				$stmt->execute(array($row['word'],$row['articlelist'].($row['articlelist'] !=''?',':'').$articleID,$row['word']));	
			}			
		}
		// Delete words that are this page id and are not in current content
		$sql="DELETE FROM searchindex WHERE articlelist = ? AND word NOT IN ($in) AND pagelist IS NULL";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array_merge(array($articleID),$words));
		//echo '<hr>deleted from search during submit'.$stmt->rowCount();
	
		
		/* Find and remove articleID from remaining in list */
		$sql="SELECT word,articlelist FROM searchindex WHERE FIND_IN_SET(?, articlelist) AND word NOT IN ($in)";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array_merge(array($articleID),$words));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);			
		
		
	
			
			$sql="UPDATE searchindex SET word=?,articlelist=? WHERE word=?;";
			foreach($rows as $row){		
				$good=[];			
				$ids=explode(',',$row['articlelist']);
				foreach($ids as $val){
					if($val != $articleID){
						$good[] = $val;
					}					
				}
				
				$stmt=$this->pdo->prepare($sql);
				$stmt->execute(array($row['word'],implode(',',$good),$row['word']));	
			}			
		
		
		
		
		/*
			echo 'Found Word array<pre>';
		var_dump($wordArray);
		echo'</pre>';	
		*/
		
		
		
		
		
			
	}
	
	
	
	
	//shoud be done before actual removal
	public function deleteArticle($articleID){
				
				
	
	
		//delete any words without any other remaining ids
		$sql="DELETE FROM searchindex WHERE articlelist = ? AND pagelist IS NULL";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array($articleID));
	//	echo '<hr>deleted from search...'.$stmt->rowCount();
			
		
		/* Find and remove pageid from remaining in list */
		$sql="SELECT word,articlelist FROM searchindex WHERE FIND_IN_SET(?, articlelist)";//
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array($articleID));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		
		
		

			$sql="UPDATE searchindex SET word=?,articlelist=? WHERE word=?;";
			foreach($rows as $row){		
				$good=[];			
				$ids=explode(',',$row['articlelist']);
				foreach($ids as $val){
					if($val != $articleID){
						$good[] = $val;
					}					
				}
				
				$stmt=$this->pdo->prepare($sql);
				$stmt->execute(array($row['word'],implode(',',$good),$row['word']));	
			}			
		

		
	}	

	
	
	
	
	
	
	
	
	
	
	public function updateSearch(){//Site search update execution starts here, use this function in your mvc application to include articles from the content table.
	
	$sql="SELECT *,pages.id AS pageid, content.id AS articleid FROM content
		JOIN pages ON (pages.articleids=content.id AND pages.published = 1 AND content.published = 1
		) OR ( FIND_IN_SET(content.id, pages.articleids) AND pages.published = 1 AND content.published = 1)";

		$sql.=" ORDER BY content.id ASC;";
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array());
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	

		$pages = $this->getPages($rows);
			
		//echo '<pre>',htmlspecialchars(print_r($pages, true)),'</pre>';
		/* or Unrendered results...
		$skip_headline=array('header','menu','footer');	
		foreach($rows as $row)
		{	
		
			$content=$this->strip($row['content']);
			if( $content != ''){	
				if($row['type'] !='noindex'){					
					$content = $this->getUniques( str_replace("-", " ", (in_array($row['type'],$skip_headline)?:$row['headline'])).' '. $content);
				}else{
					$content = '';
				}
				if(isset($pages[$row['pageid']])){
					foreach($content as $word){
						$pages[$row['pageid']]['words'][] = $word;
					}
				}else{
					$pages[$row['pageid']]['words'] =  $content;
				}
			}			
		}*/
		//Get articles
		$sql="SELECT * FROM content WHERE (type != 'header' AND type != 'footer' AND type != 'default' AND type != 'menu' AND type != 'html' AND type != 'auto p false' AND type != 'noindex' AND type != '' AND type IS NOT NULL)";//type = article
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array());
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($rows as $row)
		{	
			
			$content=$this->strip( html_entity_decode($row['content'],ENT_QUOTES));
			if( $content != ''){	
				if($row['type'] !='noindex'){					
					$content = $this->getUniques( $row['articlename'].' '. $row['description'].' '.$content);
				}else{
					$content = '';
				}
				if(isset($articles[$row['id']])){
					foreach($articles as $word){
						$articles[$row['id']]['words'][] = $word;
					}
				}else{
					$articles[$row['id']]['words'] =  $content;
					//$pages[$row['id']]['type'] = 'page';
				}
			}			
		}
			
		
		$this->updateIndex ($pages,$articles);
		//echo '<pre>',htmlspecialchars(print_r($pages, true)),'</pre>';
		//echo '<pre>',htmlspecialchars(print_r($articles, true)),'</pre>';
	}

	public function getUniques($content){
	
		$words = explode(' ',$content);
		$uniques=array();
		foreach($words as $word){
		$word = $this->wordStem(strtolower($word));
			if(!in_array( $word,$uniques ) && !in_array( $word,$this->stopwords ) && $word!=''){
				$uniques[] = $word;
			} 
		}
		//echo '<pre>',htmlspecialchars(print_r($uniques, true)),'</pre>';
		return $uniques;
	}

	function strip($html){

	//	$html = preg_replace('~<\s*\bspan\b[^>]*>edit<\s*\/\s*span\s*>~is', '', $html);
	//$html = preg_replace('~<\s*\bspan\b[^>]*>sorting<\s*\/\s*span\s*>~is', '', $html);remove edit buttons for bs4 grid editor
	$html = str_replace(array('<span class="editcontent2">edit</span>','<span class="editcontent2">Edit</span>', '<span class="editcontent">sorting</span>', '<span class="editcontent">edit</span>'), '', $html);	

		
//$html = str_replace(array('&', '-', '|'), ' ', $html);	
	
		$html = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $html);//remove scripts
		$html = preg_replace('~<\s*\bstyle\b[^>]*>(.*?)<\s*\/\s*style\s*>~is', '', $html);
		$html = preg_replace('~<\s*\bnav\b[^>]*>(.*?)<\s*\/\s*nav\s*>~is', '', $html);
	
		$html = preg_replace('#<br\s*/?>#i', "\n", $html);
		$html = preg_replace('#<p\s*/?>#i', "\n", $html);$html = preg_replace('#</p>#i', "\n", $html);
		$html = preg_replace('#<h1\s*/?>#i', "\n", $html);$html = preg_replace('#</h1>#i', "\n", $html);
		$html = preg_replace('#<h2\s*/?>#i', "\n", $html);$html = preg_replace('#</h2>#i', "\n", $html);
		$html = preg_replace('#<h3\s*/?>#i', "\n", $html);$html = preg_replace('#</h3>#i', "\n", $html);
		$html = preg_replace('#<div\s*/?>#i', "\n", $html);$html = preg_replace('#</div>#i', "\n", $html);
		
		$content1=strip_tags($html);
				
		$order = array("\r\n", "\n", "\r","&nbsp;");
		$replace = ' ';

		// Processes \r\n's first so they aren't converted twice.
		$content1 = str_replace($order, $replace, $content1);$content1 = preg_replace('/\s\s+/', ' ', $content1);
		$content1 = preg_replace("/[^ \w]+/", " ", $content1);
		$content1 = strtolower($content1);
		
		$search = array('/(\s)+/s');		// shorten multiple whitespace sequences
		$replace = array('\\1');
 		
		$content1 = preg_replace($search, $replace, $content1);
		$content1 = rtrim($content1, ' ');
			//	echo $content1;
		return $content1;
	}

	public function wordStem($word) {
	#PHP implementation of the Porter Stemming Algorithm
	#Written by Iain Argent for Complinet Ltd., 17/2/00
	#Translated from the PERL version at http://www.muscat.com/~martin/p.txt
	#Version 1.1 (Includes British English endings)
	#--Reduces words to their base stem for search engines and indexing
		$step2list=array(
		'ational'=>'ate', 'tional'=>'tion', 'enci'=>'ence', 'anci'=>'ance', 'izer'=>'ize',
		'iser'=>'ise', 'bli'=>'ble',
		'alli'=>'al', 'entli'=>'ent', 'eli'=>'e', 'ousli'=>'ous', 'ization'=>'ize',
		'isation'=>'ise', 'ation'=>'ate',
		'ator'=>'ate', 'alism'=>'al', 'iveness'=>'ive', 'fulness'=>'ful', 'ousness'=>'ous',
						'aliti'=>'al',
		'iviti'=>'ive', 'biliti'=>'ble', 'logi'=>'log'
		);

		$step3list=array(
		'icate'=>'ic', 'ative'=>'', 'alize'=>'al', 'alise'=>'al', 'iciti'=>'ic', 'ical'=>'ic',
		'ful'=>'', 'ness'=>''
		);

		$c = "[^aeiou]"; # consonant
		$v = "[aeiouy]"; # vowel
		$C = "${c}[^aeiouy]*"; # consonant sequence
		$V = "${v}[aeiou]*"; # vowel sequence

		$mgr0 = "^(${C})?${V}${C}"; # [C]VC... is m>0
		$meq1 = "^(${C})?${V}${C}(${V})?" . '$'; # [C]VC[V] is m=1
		$mgr1 = "^(${C})?${V}${C}${V}${C}"; # [C]VCVC... is m>1
		$_v = "^(${C})?${v}"; # vowel in stem

		if (strlen($word)<3) return $word;

				$word=preg_replace("/^y/", "Y", $word);

		#Step 1a
				$word=preg_replace("/(ss|i)es$/", "\\1", $word);        # sses-> ss, ies->es
				$word=preg_replace("/([^s])s$/", "\\1", $word);         #        ss->ss but s->null

		#Step 1b
				if (preg_match("/eed$/", $word)) {
						$stem=preg_replace("/eed$/", "", $word);
						if (preg_match('/'.$mgr0.'/', $stem)) {
								$word=preg_replace("/.$/", "", $word);
						}
				}
				elseif (preg_match("/(ed|ing)$/", $word)) {
						$stem=preg_replace("/(ed|ing)$/", "", $word);
						if (preg_match("/$_v/", $stem)) {
								$word=$stem;

								if (preg_match("/(at|bl|iz|is)$/", $word)) {
										$word=preg_replace("/(at|bl|iz|is)$/", "\\1e", $word);
								}

								elseif (preg_match("/([^aeiouylsz])\\1$/", $word)) {
										$word=preg_replace("/.$/", "", $word);
								}

								elseif (preg_match("/^${C}${v}[^aeiouwxy]$/", $word)) {
										$word.="e";
								}
						}
				}

		#Step 1c (weird rule)
				if (preg_match("/y$/", $word)) {
						$stem=preg_replace("/y$/", "", $word);
						if (preg_match("/$_v/", $stem))
								$word=$stem."i";
				}

		#Step 2
				if
		(preg_match("/(ational|tional|enci|anci|izer|iser|bli|alli|entli|eli|ousli|ization|isation|ation|ator|alism|iveness|fulness|ousness|aliti|iviti|biliti|logi)$/",
		$word, $matches)) {
				
		$stem=preg_replace("/
		(ational|tional|enci|anci|izer|iser|bli|alli|entli|eli|ousli|ization|isation|ation|ator|alism|iveness|fulness|ousness|aliti|iviti|biliti|logi)$/",
		"", $word);
						$suffix=$matches[1];
						if (preg_match("/$mgr0/", $stem)) {
								$word=$stem.$step2list[$suffix];
						}
				}

		#Step 3
				if (preg_match("/(icate|ative|alize|alise|iciti|ical|ful|ness)$/", $word, $matches)) {
						$stem=preg_replace("/(icate|ative|alize|alise|iciti|ical|ful|ness)$/", "", $word);
						$suffix=$matches[1];
						if (preg_match("/$mgr0/", $stem)) {
								$word=$stem.$step3list[$suffix];
						}
				}

		#Step 4
				if
		(preg_match("/(al|ance|ence|er|ic|able|ible|ant|ement|ment|ent|ou|ism|ate|iti|ous|ive|ize|ise)$/",
		$word, $matches)) {
				
		$stem=preg_replace("/(al|ance|ence|er|ic|able|ible|ant|ement|ment|ent|ou|ism|ate|iti|ous|ive|ize|ise)$/",
		"", $word);
						$suffix=$matches[1];
						if (preg_match("/$mgr1/", $stem)) {
								$word=$stem;
						}
				}
				elseif (preg_match("/(s|t)ion$/", $word)) {
						$stem=preg_replace("/(s|t)ion$/", "\\1", $word);
						if (preg_match("/$mgr1/", $stem)) $word=$stem;
				}

		#Step 5
				if (preg_match("/e$/", $word, $matches)) {
						$stem=preg_replace("/e$/", "", $word);
						if (preg_match("/$mgr1/", $stem) |
								(preg_match("/$meq1/", $stem) &
								~preg_match("/^${C}${v}[^aeiouwxy]$/", $stem))) {
								$word=$stem;
						}
				}
				if (preg_match("/ll$/", $word) & preg_match("/$mgr1/", $word)) $word=preg_replace("/.$/", "",
		$word);

		# and turn initial Y back to y
				preg_replace("/^Y/", "y", $word);

				return $word;	
	}

	public function getLevenshtein($input,$words){
		//similar_text() might work too.
		// array of words to check against
		//$words  = array('apple','pineapple','banana','orange',						'radish','carrot','pea','bean','potato','comput','lactat');

		// no shortest distance found, yet
		$shortest = -1;

		// loop through words to find the closest
		foreach ($words as $word) {

			// calculate the distance between the input word,
			// and the current word
			$lev = levenshtein($input, $word);

			// check for an exact match
			if ($lev == 0) {

				// closest word is this one (exact match)
				$closest = $word;
				$shortest = 0;

				// break out of the loop; we've found an exact match
				break;
			}

			// if this distance is less than the next found shortest
			// distance, OR if a next shortest word has not yet been found
			if ($lev <= $shortest || $shortest < 0) {
				// set the closest match, and shortest distance
				$closest  = $word;
				$shortest = $lev;
			}
		}

		//echo "Input word: $input\n";
		if ($shortest == 0) {//0 is an exact match
		//	echo "perfect match found: $closest\n";
			return array('closest'=>$closest,'shortest'=>$shortest);
		} 
		if (strlen($input) < 4 && strlen($input) > 1 && $shortest < 2) {//0 is an exact match
			//echo "match found 4: $closest\n";
			return array('closest'=>$closest,'shortest'=>$shortest);
		} 
		if (strlen($input) > 3 && $shortest < 3) {//0 is an exact match
			//echo "match found 4: $closest\n";
			return array('closest'=>$closest,'shortest'=>$shortest);
		} 
		if(strlen($input) > 6 && $shortest < 5){
		//echo "match found 6: $closest\n";
			return array('closest'=>$closest,'shortest'=>$shortest);
		}
		
		
	}
	
	//Process Search Input
	public function search($input=''){

		if($input != ''){
		


		$sql="SELECT word FROM searchindex";
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
		foreach($rows as $row){
			$wordArray[] = $row['word'];
		}			
		unset($rows);

		$searchArray = explode(' ',trim($input));
		
		foreach($searchArray as $word){
			if(!in_array($word,$this->stopwords)){
				//$stemmed[] = $this->wordStem($word);
				$sql="SELECT * FROM searchindex WHERE word =?";
				$stmt=$this->pdo->prepare($sql);
				$levens=$this->getLevenshtein($this->wordStem(strtolower($word)),$wordArray);
				$stmt->execute(array($levens['closest']));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if(!empty($row['pagelist'])){
					$matchedids[$row['word']]['page']=array('set'=>$row['pagelist'],'score'=>$levens['shortest']);	//maybe use levinshteins exact match, or not, for weighting	
				}
				if(!empty($row['articlelist'])){
					$matchedids[$row['word']]['article']=array('set'=>$row['articlelist'],'score'=>$levens['shortest']);	//maybe use levinshteins exact match, or not, for weighting	
				}
				$levens='';
			}		
		}		
		unset($wordArray);
		
				//echo '<pre>',htmlspecialchars(print_r($matchedids, true)),'</pre>';
		//$results[]= ['id'=>$id,'type'=>$type,'score'=>$score];
		$uniqueids=array();
		foreach($matchedids as $word => $results){
		unset($score);
			$pageids = explode(',',$results['page']['set']);
			$articleids = explode(',',$results['article']['set']);
			if($results['page']['score'] === 0){
				$score['page']= 1;
			}else if($results['page']['score'] < 2 ){
				$score['page'] = .9;
			}else{
				$score['page'] = .7;
			}
			if($results['article']['score'] === 0){
				$score['article']= 1;
			}else if($results['article']['score'] < 2 ){
				$score['article'] = .9;
			}else{
				$score['article'] = .7;
			}	
		
			
			
			foreach($pageids as $id){
				if ($id !=''){
					if(!array_key_exists($id,$uniqueids['page'])){
						$uniqueids['page'][$id]=$score['page'];
					}else{
						$uniqueids['page'][$id]+=$score['page'];
					}			
				}
			}		

			foreach($articleids as $id){
				if ($id !=''){
					if(!array_key_exists($id,$uniqueids['article'])){
						$uniqueids['article'][$id]=$score['article'];
					}else{
						$uniqueids['article'][$id]+=$score['article'];
					}
				}
			}
		
		}
		
		$records=[];
		foreach($uniqueids as $type => $value){
			foreach($value as $id => $score){
			$records[]=['id'=>$id,'type'=>$type,'score'=>$score];
			}
		
		}


		//Re adjust with various regex matches
		$records = $this->reRankPages($records,$searchArray);

		
		
		function sortByOrder($a, $b) {
			return $a['score'] < $b['score'];
		}
		usort($records, 'sortByOrder');
		
			
		 // echo '<pre>',htmlspecialchars(print_r($records, true)),'</pre>';
		
		return $records;
			
		}
	}
		
	
	
	public function reRankPages($records,$searchArray){
			
		//Separate article and page records
		$pages=[];
		$articles=[];
		foreach($records as $key => $record){
			if( $record['type']=='page'){
				$pages[] = $record['id'];
			}else if( $record['type']=='article'){
				$articles[] = $record['id'];
			}			
		}
		
		
		
		//Get Pages contents
		$in  = str_repeat('?,', count($pages) - 1) . '?';
		$sql = "SELECT *,pages.id AS pageid, content.id AS articleid FROM content
		JOIN pages ON (pages.articleids=content.id AND pages.published = 1 AND content.published = 1
		) OR ( FIND_IN_SET(content.id, pages.articleids) AND pages.published = 1 AND content.published = 1)
		 WHERE pages.id IN ($in)";
		$sql.=" ORDER BY content.id ASC;";
		
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute($pages);	
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			

		unset($pages);
		
		$skip=array('header','footer');//'menu',
		foreach($rows as $row)
		{	//echo$row['type'];
			if(isset($row['pageid']) && $row['type'] !='noindex' && !(in_array($row['type'],$skip))){
				$pages[$row['pageid']][]=$row;
 /*if not set*/ $pages[$row['pageid']]["headline"]=$row['headline']; 
			}					
		}		
		
		foreach($pages as $key => $page){				
			$retpages[$key]['words']=$page["headline"] . ' ' . $this->strip($this->renderPage($page));
		}
		
		$pages = $retpages;			

		
		
		
		
		//Get Articles contents
		$in  = str_repeat('?,', count($articles) - 1) . '?';
		$sql="SELECT * FROM content WHERE (type != 'header' AND type != 'footer' AND type != 'default' AND type != 'menu' AND type != 'html' AND type != 'auto p false' AND type != 'noindex' AND type != '' AND type IS NOT NULL) AND id IN ($in)";//type = article
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute($articles);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		
		foreach($rows as $row)
		{	
			
			$content=$this->strip( html_entity_decode($row['content'],ENT_QUOTES));
			if( $content != ''){	
				if($row['type'] !='noindex'){					
					$content = $row['articlename'].' '. $row['description'].' '.$content;
				}else{
					$content = '';
				}
				$retarticles[$row['id']]['words'] =  $content;
								
			}			
		}
		
		$articles = $retarticles;
		
		
		
		//Re-Rank pages
	 	$records = $this->doRank($records,$pages,$searchArray,'page');
		
		//Re-Rank articles	
		$records = $this->doRank($records,$articles,$searchArray,'article');

		return $records; 
	}		

	public function doRank($records,$pages,$searchArray,$type){
			
		//runs regex and scoring on articles and pages. 
		foreach($searchArray as $word){			
			//Singular
			$new = $this->makeSingular($word);
			if($new){
				$singular[] =$new;					
			}				
			//Plural	 
			if(substr($word,strlen($word)-2,strlen($word)) == 'ss'){
				$plural[] =  $word.'es';
			}else{
				$plural[] =  $word.'s';
			}   
		}	
		foreach($records as $record => $value){		
				if($type==$value['type']){
				
			$pagecontent = $pages[$value['id']]['words'];
			$bodycount = strlen($pagecontent);
				
			
			if(count($searchArray)>1){
				$total = $this->getMatches($pagecontent,array(implode(' ',$searchArray)));	
			
				if($total>0){ 	
					$records[$record]['score'] +=  (($total/$bodycount) * 100 ) + ($total/2);
				}
			}
			
			unset($total);
			$total = $this->getMatches($pagecontent,$searchArray);	
			if($total>0){ 
				//if($total > 1 && $bodycount<500){$total =2;}
				$records[$record]['score'] +=  (($total/$bodycount) * 100 ) + ($total/8);
			}
			//	echo '<pre>',htmlspecialchars(print_r($results['matched'], true)),'</pre>';
			
			unset($total);

			
			$total = $this->getMatches($pagecontent,$singular);	
			if($total>0){ 
				$records[$record]['score'] +=  (($total/$bodycount) * 100 )/8;
			}
			
			
			unset($total);

					
		
			$total = $this->getMatches($pagecontent,$plural);	
			if(($weight=$total)>0){ 
				$records[$record]['score'] +=  (($total/$bodycount) * 100 )/8;
			}
			}
			//
		
		}		// echo '<pre>',htmlspecialchars(print_r($records, true)),'</pre>';
		return $records;
}



	
	public function getMatches($content,$searchArray){
		

		foreach($searchArray as $value){
			$regexs[] = array('regx' => "/\b$value\b/i", "term"=>$value);
		}


		$match=false;
		foreach($regexs as $key => $value){
			if ( @preg_match_all($value['regx'], $content, $matches, PREG_OFFSET_CAPTURE)){
				$match = true;
				$total  += (count($matches[0]) );//* strlen($value['term'])
				//break;
			}	
			
		}
		
		if($match == true){	
			return $total;
		}else{
			return false;
		}
	}

	function makeSingular($word){
		$singular='';
		if(    substr($word,strlen($word)-4,strlen($word)) == 'sses'){
			$singular = substr($word,0,-2);
		} elseif(    substr($word,strlen($word)-4,strlen($word)) == 'ches'){
			$singular = substr($word,0,-2);
		} elseif(    substr($word,strlen($word)-3,strlen($word)) == 'xes'){
			$singular = substr($word,0,-2);
		}elseif(    substr($word,strlen($word)-1,strlen($word)) == 's'){
			$singular = substr($word,0,-1);
		}
		if($singular != ''){
			return $singular;
		}else{
			return false;
		}		
	}    

	function stripHTML($html){
	//	$html = preg_replace('~<\s*\bspan\b[^>]*>edit<\s*\/\s*span\s*>~is', '', $html);
	//$html = preg_replace('~<\s*\bspan\b[^>]*>sorting<\s*\/\s*span\s*>~is', '', $html);remove edit buttons for bs4 grid editor

		$html = str_replace(array('<span class="editcontent2">edit</span>','<span class="editcontent2">Edit</span>', '<span class="editcontent">sorting</span>', '<span class="editcontent">edit</span>'), '', $html);	

		
		
		$html = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $html);//remove scripts
		$html = preg_replace('~<\s*\bstyle\b[^>]*>(.*?)<\s*\/\s*style\s*>~is', '', $html);
		$html = preg_replace('~<\s*\bnav\b[^>]*>(.*?)<\s*\/\s*nav\s*>~is', '', $html);
		$html = preg_replace('#<br\s*/?>#i', "\n", $html);
		$html = preg_replace('#<p\s*/?>#i', "\n", $html);$html = preg_replace('#</p>#i', "\n", $html);
		$html = preg_replace('#<h1\s*/?>#i', "\n", $html);$html = preg_replace('#</h1>#i', "\n", $html);
		$html = preg_replace('#<h2\s*/?>#i', "\n", $html);$html = preg_replace('#</h2>#i', "\n", $html);
		$html = preg_replace('#<h3\s*/?>#i', "\n", $html);$html = preg_replace('#</h3>#i', "\n", $html);
		$html = preg_replace('#<div\s*/?>#i', "\n", $html);$html = preg_replace('#</div>#i', "\n", $html);
		
		$content1=strip_tags($html);	
		$order = array("\r\n", "\n", "\r","&nbsp;");
		$replace = ' ';

		// Processes \r\n's first so they aren't converted twice.
		$content1 = str_replace($order, $replace, $content1);

				
		return $content1;
	}
}