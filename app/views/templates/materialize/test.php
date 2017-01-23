<?php
		$this->loadModel('pagination_model');
		//Define pagination vars 
		/* Material Design */
		$pageconfig['pagination_open']='<ul class="pagination">';
		$pageconfig['pagination_close']='</ul>';
		$pageconfig['open']='<li class="waves-effect">';
		$pageconfig['close']='</li>';
		$pageconfig['active_open']='<li class="active"><a>';
		$pageconfig['active_close']='</a></li>';
		$pageconfig['next_open']='<li class="waves-effect">';
		$pageconfig['next_close']='</li>';
		$pageconfig['prev_open']='<li class="waves-effect">';
		$pageconfig['prev_close']='</li>';
		$pageconfig['disabled_left']='<li class="disabled"><a><i class="material-icons">chevron_left</i></a></li>';	
		$pageconfig['disabled_right']='<li class="disabled"><a><i class="material-icons">chevron_right</i></a></li>';		
		$pageconfig['prev_chevron_left']='<i class="material-icons">chevron_left</i>';
		$pageconfig['next_chevron_right']='<i class="material-icons">chevron_right</i>';		
		$pageconfig['first_open']="<li>";
		$pageconfig['first_close']="</li>";	
		$pageconfig['last_open']="<li>";
		$pageconfig['last_close']="</li>";	
					
		//Get current page assignments		
		$pageconfig['query']="SELECT * FROM content WHERE (type='blog' OR type='code') AND published = 1 ORDER BY date DESC";
		$pageconfig['query_array'];
		$pageconfig['link_url']= $link;
		$pageconfig['link_params']=''; 
		$pageconfig['num_results']=2;//default set to 10
		$pageconfig['num_links'] = 3;//ahead and behind
		$pageconfig['get_var']='page'; 
		$pageconfig['prevnext']=true;
		$pageconfig['firstlast']=true;
				/*Paginate!*/
		$this->pagination_model->paginate($pageconfig);
		if($this->pagination_model->total_records == 0){
			return;
		}
		$results=$this->pagination_model->results;
		$currentpage=$this->pagination_model->page_links;
		$totalrecords=$this->pagination_model->total_records;
		
		?>
		
<div class="row"><?php 	

	foreach($results as $record){
?>
	
        <div class="col s12 m8 l6">
          <div class="card blue-grey darken-1">
            <div class="card-content white-text">
              <span class="card-title"><?php echo$record['articlename'];?></span>
              <p><?php echo$record['description'];?></p>
            </div>
            <div class="card-action">
              <a href="<?php echo$this->base_url.$record['link'];?>">Read Article</a>
             <!-- <a href="#">This is a link</a> -->
            </div>
          </div>
        </div>
     
<?php 
	}
?> </div>
<?php
	echo $currentpage;
	
	/*
		echo '<pre>';
		print_r($content);
		echo  '</pre>';
		echo '<pre>';
		print_r($article);
		echo  '</pre>';
		echo '<pre>';
		print_r($articles);
		echo  '</pre>';
		
		*/
		?>				
<br>

</div>
</div>
