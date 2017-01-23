<div class="nav-bar"><input type="checkbox" class="hider" id="hider" value="1"><label for="hider" id="hider-label">&nbsp;</label>
	<div class="container">
	<nav class="">

  <div class="clearfix" id="exCollapsingNavbar2">
 <a class="navbar-logo" href="/">
 	<picture>

  <source srcset="/images/tas-logo-60.svg" type="image/svg+xml">

  <img class="img-fluid" src="/images/tas-logo-60.png" alt="TAS Hearth and Patio logo" width="170" height="60">

</picture>
</a>
    
  <?php if(isset($menu))echo$menu;?>
 
  
  <form class="search-form" name="search" method="get" action="/search">
    <input class="search-form-control" name="search" type="text" placeholder="Search"><button class="search-btn btn btn-danger-outline" type="submit">Search</button>
  </form>
  </div>
</nav>
</div>
</div>
</header>
<main>
<?php /*








<div class="container-fluid">
	<div class="row">
	<nav class="navbar navbar-light bg-faded">
  <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2">
   MENU&#9776;
  </button><input type="checkbox" class="hider" id="hider" value="1"><label for="hider" id="search_ha">&nbsp;</label>
  <div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
 <a class="navbar-brand" href="/">
 <span id="main-image" class="img-fluid image-replacement" data-src="/images/tas-logo-60.svg" data-width="170" data-height="60">
  <noscript><img class="img-fluid" src="/images/tas-logo-60.png" width="170" height="60" /></noscript>
</span>
</a>
    
  <?php if(isset($menu))echo$menu;?>
 
  
  <form class="form-inline pull-xs-right" name="search" method="get" action="/search2">
    <input class="form-control" name="search" type="text" placeholder="Search">
    <button class="btn btn-danger-outline" type="submit">Search</button>
  </form>
  </div>
</nav>
</div>
</div>




 <!--
    <li class="nav-item active">
      <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Features</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Pricing</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">About</a>
    </li> -->



<div class="navWrap" style="height:50px">

<nav id="myNavbar" class="navbar navbar-default" role="navigation">

	<div class="container innerNav">

		<div class="navbar-header">

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">

                <span class="sr-only">Toggle navigation</span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>

            </button>

            <a class="navbar-brand" href="<?php echo $this->base_url; ?>"><img style="margin-top:-10px;width:135px;height:45px;" alt="CGSUNY" src="/images/hder.svg"></a>

        </div>

 

        <div class="collapse navbar-collapse" id="navbarCollapse">

            <ul class="nav navbar-nav vcenter">
            <?php  if(isset($menu))echo$menu;

				<li <?php echo(current_url() == base_url() ? 'class="active"' : '')?>><a href="<?php echo base_url(); ?>">Home</a>	</li>	

				<li <?php echo(current_url() == base_url().'read-review' ? 'class="active"' : '')?>><a href="<?php echo base_url(); ?>read-review/">Read Review</a></li>

				<li <?php echo(current_url() == base_url().'write-reviews' ? 'class="active"' : '')?>><a href="<?php echo base_url(); ?>write-reviews/">Write a Review</a></li>

				<li <?php echo(current_url() == base_url().'about-us' ? 'class="active"' : '')?>><a href="<?php echo base_url(); ?>about-us/">About Us</a></li>			

				<li <?php echo(current_url() == base_url().'login' ? 'class="active"' : '')?>><a href="<?php echo base_url(); ?>login/">Dealer Login</a></li>
				

			</ul>

			<div class="search-box col-sm-1 col-md-2 pull-right">

				<form class="" role="search" method="get" action="<?php echo $this->base_url; ?>search">

					<div class="search-bar-grp input-group">

						<input type="text" class="search-bar-inpt form-control" placeholder="Search" name="q">

						<div class="input-group-btn">

							<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>

						</div>

					</div>

				</form>

			</div>              

        </div>

	</div> 

</nav>

</div> 	*/?>
