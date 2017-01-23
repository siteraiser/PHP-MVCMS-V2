<?php
/*	Copyright © 2016 
	
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


class dynamicPages extends requestHandler{

public $userLoggedIn = false;	
public $pageID = '';	
public $sortable = 0;	
public $pagetype = "default";
//Determine if logged in	
private function editable($id,$content){
	
	if($this->userLoggedIn ){	///have to be an admin to get this far, && canEdit might be of use || $user->canEditArticle($this->content_model))
		if($id == 2){
			return "<div class='editable'><a href='".$this->base_url."siteadmin/pages/edit?record=".$this->pageID."'>Display element - Edit Page</a>$content</div>";	
		}else{
	//			return  '<div class="editable row connectedSortable" ><div  data-colxs="4" data-colsm="4" data-colmd="4" data-collg="4" data-colxl="4" class="col-xs-12"><div  id="div-0" class="content">'."<a href='".$this->base_url.'siteadmin/content/edit?article='.$id."'>Edit Article</a>$content".'<span class="editcontent">Edit</span> <span class="add">+</span> <span class="remove">-</span><span class="delete">X</span></div></div></div>';	
			return "<div class='editable'><a href='".$this->base_url.'siteadmin/content/edit?article='.$id."'>Edit Article</a>$content</div>";	
		}
	}else{
		return $content;
	}	
}

public function index($path){
	$editable_meta = "";
	$user=(isset($_SESSION[SESSION_PREFIX]['user'])) ? $_SESSION[SESSION_PREFIX]['user'] : null;
		
	if($user && $user->isAdmin()){
		$this->userLoggedIn = true;
		$editable_meta = "<script src='/ckeditor/ckeditor.js' type='text/javascript'></script>  <link href='/assets/css/arrange.css' rel='stylesheet' type='text/css'> <link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'> <script defer src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
  <script  defer src='/assets/js/arrange.js'></script>
  ";
  /*
 <style> 

.row.flexy > div[class*='col-'] {
  display: flex;
  flex:1 0 auto;
}

.card {
  width:100%;
} </style>

*/
	}





$this->loadModel('siteadmin/dynamicpages_model');
$results=$this->dynamicpages_model->getPage($path);
if($results != false && !is_array($results)){
	$this->loadcontroller=$results;
	return;
}else if($results == false){
	return;
}
$this->isCMS=1;



//Let system know page output wants to be cached
if($results[0]['cache'] == 1 && !$this->userLoggedIn){ 
	$this->cache = true;
}
if($results[0]['minify'] == 1 && !$this->userLoggedIn){
	$this->minify = true;
}

//If there is a matched controllerless page, continue...
//set page header for article type "header" contents
$head['meta']=$results[0]['meta'];
$head['title']=$results[0]['headline'];

//load template model
$templatemodel=$results[0]['template'].'_model';
$this->loadModel('templates/'.$templatemodel);

//sort
$sorted=[];
$artOrder=explode( ',', $results[0]['articleids']);
foreach($artOrder as $id){
	foreach($results as $row){
		if($row['articleid'] == $id){
			$sorted[] = $row;
		}		
	}
}
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
				
			if($article['contenttype'] == 'header'){ 
				$this->pageID = $article['id'];
				$loadViews[$rowkey][$page['view']]=array('meta'=>$head['meta'] . $editable_meta,'title'=>$head['title'],'content'=>$this->editable($article['articleid'],$article['content']));	
			}else if($article['contenttype'] == 'menu'){ 
				$loadViews[$rowkey][$page['view']]=array('menu'=>$article['menu'],'menutype'=>$article['menutype'],'content'=>$this->editable($article['articleid'],$article['content']),'type'=>$aggregate);
			}else{
				$loadViews[$rowkey][$page['view']]['type']=$aggregate;
				$cont = $article['content'];
				if($article['contenttype'] == 'html'){ 
					$pagetype = 'html';
					if($this->userLoggedIn){ ///...	
						$cont = $this->$templatemodel->wrap($article['articleid'],$article['content']);
					}
				}
				
				$loadViews[$rowkey][$page['view']]['content']= $this->editable($article['articleid'],$cont);
				
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
			if(isset($value2['menu'])){ $menutemp.=  $this->$templatemodel->getMenu($value2['menu'],$value2['menutype']); }
			
		}else if($value2['type'] == 'aggregate' AND $loadViews[$key - 1][$key2]['type'] == 'aggregate' AND key($value) == key($loadViews[$key - 1])		
		AND $value2['type'] == 'aggregate' AND ($loadViews[$key + 1][$key2]['type'] !== 'aggregate' OR key($value) !== key($loadViews[$key + 1]))){
		
			$content['content']=$this->$templatemodel->respImgs($temp.$value2['content']);
			if(isset($value2['meta'])){	$content['meta'] = $value2['meta'];	}
			if(isset($value2['title'])){ $content['title'] = $value2['title']; }
			
			if(isset($value2['menu']) || $menutemp !='' ){$content['menu'] = $menutemp.$this->$templatemodel->getMenu($value2['menu'],$value2['menutype']); }//getMenu returns null if no menu is assigned			
			
			$this->addView('templates/'.key($value),$content);
			$temp='';$menutemp='';//Done aggregating this view, this occurance
	

		}else if($value2['type'] == 'agg-pos' AND $loadViews[$key + 1][$key2]['type'] == 'agg-pos' AND key($value) == key($loadViews[$key + 1])){
			$temp[]= $this->$templatemodel->respImgs($value2['content']);
			if(isset($value2['menu'])){ $menutemp[]=  $this->$templatemodel->getMenu($value2['menu'],$value2['menutype']); }
			
		}else if($value2['type'] == 'agg-pos' AND $loadViews[$key - 1][$key2]['type'] == 'agg-pos' AND key($value) == key($loadViews[$key - 1])		
		AND $value2['type'] == 'agg-pos' AND ($loadViews[$key + 1][$key2]['type'] !== 'agg-pos' OR key($value) !== key($loadViews[$key + 1]))){
			$temp[]=$this->$templatemodel->respImgs($value2['content']);
			$content['content']=$temp;
			if(isset($value2['meta'])){	$content['meta'] = $value2['meta'];	}
			if(isset($value2['title'])){ $content['title'] = $value2['title']; }
			
			if(isset($value2['menu']) || $menutemp !='' ){
				$menutemp[]=$this->$templatemodel->getMenu($value2['menu'],$value2['menutype']);
				$content['menu'] = $menutemp; }//getMenu returns null if no menu is assigned			
			
			$this->addView('templates/'.key($value),$content);
			$temp='';$menutemp='';//Done aggregating this view, this occurance
		}else{
			$content['content'] = $this->$templatemodel->respImgs($value2['content']);	
			if(isset($value2['meta'])){	$content['meta'] = $value2['meta'];	}
			if(isset($value2['title'])){ $content['title'] = $value2['title']; }
			
			if(isset($value2['menu'])){ $content['menu'] = $this->$templatemodel->getMenu($value2['menu'],$value2['menutype']); }else{$content['menu'] = '';}
			$this->addView('templates/'.key($value),$content);	
		}

	}
}
$imgs='';
foreach($this->$templatemodel->image_list as $value){
	$imgs.='<meta property="og:image" content="'.$value['src'].'"/><meta property="og:image:width" content="'.$value['w'].'"/><meta property="og:image:height" content="'.$value['h'].'"/>';
	
}




$this->output = str_replace("OPENGRAPHIMAGES","$imgs",$this->output);

//echo'<pre>';
//var_dump($this->$templatemodel->image_list);
//echo'</pre>';
}
}
