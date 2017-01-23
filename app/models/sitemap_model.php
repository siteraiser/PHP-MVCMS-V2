<?php /*
	  MVCMS - Copyright &copy; 2016 - Carl Turechek
  	
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
class sitemap_model extends requestHandler{

	public function getLastupdate($lastmod){	
		$datetime = new DateTime($lastmod);
		return $datetime->format('Y-m-d\TH:i:sP');	
	}

	public function getURLS(){
		$links = array();
		//add blog articles, categories and blog page
		$stmt=$this->pdo->prepare("SELECT link,lastupdate FROM content WHERE (type = 'blog' OR type = 'code') AND published = '1'");
		$stmt->execute(array());
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$articleCount = count($rows);

		foreach($rows as $row)
		{
			$links[] = array('link'=>$row['link'], 'priority' => '0.8','lastmod' =>  $this->getLastupdate($row['lastupdate']));
		}
			
		
		$stmt=$this->pdo->prepare("SELECT DISTINCT category FROM content WHERE (type = 'blog' OR type = 'code') AND (category != '' AND category IS NOT NULL) AND published = '1'");
		$stmt->execute(array());
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach($rows as $row)
		{	
		/* check for the latest article in each category */	
			$stmt=$this->pdo->prepare("SELECT lastupdate FROM content WHERE (type = 'blog' OR type = 'code') AND (category = ? AND category IS NOT NULL) AND published = '1' ORDER BY lastupdate DESC LIMIT 1");
			$stmt->execute(array($row['category']));
			$lastupdate = $stmt->fetch(PDO::FETCH_ASSOC);
			$links[] = array('link'=>'blog/'.$this->urlSlug($row['category']), 'priority' => '0.7' , 'lastmod' =>  $this->getLastupdate($lastupdate['lastupdate']) );
		}	
		if($articleCount !=0){
			$stmt=$this->pdo->prepare("SELECT lastupdate FROM content WHERE (type = 'blog' OR type = 'code') AND (category != '' AND category IS NOT NULL) AND published = '1' ORDER BY lastupdate DESC LIMIT 1");
			$stmt->execute(array($row['category']));
			$lastupdate = $stmt->fetch(PDO::FETCH_ASSOC);
			$links[] = array('link'=>'blog', 'priority' => '0.9', 'lastmod' =>  $this->getLastupdate($lastupdate['lastupdate']));
		}
		unset($lastupdate);
		//Get Pages	
		$sql="SELECT *, pages.lastupdate AS pagelupdate FROM pages
		JOIN content ON (pages.articleids=content.id AND pages.published = 1
		) OR ( FIND_IN_SET(content.id, pages.articleids) AND pages.published)";
		$sql.=" GROUP BY pages.page ORDER BY pages.priority DESC";
		$stmt=$this->pdo->prepare($sql);
		$stmt->execute(array());
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//	echo '<pre>',htmlspecialchars(print_r($rows, true)),'</pre>';
		foreach($rows as $row)
		{	
			if($row['priority']!=0){
				if(!empty($row['link'])){
					$link=$row['link'] . '/' . $row['page'];
				}else{
					$link=(!empty($row['categoryname'])? $row['categoryname'] . '/':'');
					$link.=($row['page'] !='home' ? $row['page']:'');
				}	
			
		
				$links[] = array('link'=>$link, 'priority' => $row['priority'], 'lastmod' =>  $this->getLastupdate($row['pagelupdate']));
			}
		}
		
		return $links;
	}

}