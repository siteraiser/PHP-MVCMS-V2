<div class="row">
        <div class="col s12 m8">
          <div class="card blue-grey darken-1">
            <div class="card-content white-text">
              <h1 class="card-title"><?php echo$article['articlename'];?></h1>
              <div class="flow-text"><?php echo$article['content'];?></div>
            </div>
            <div class="card-action">
              <a href="<?php echo$this->base_url.'blog/'.$article['category'];?>">See <?php echo $article['category'];?> Category</a> Created: <?php echo$article['date'];?>
            </div>
          </div>
        </div>
      </div>
      
      
     </div>
</div>