 <div class="section">
    <div class="row container">
    
    <p class="navbar-brand">
	<a href="/"><img alt="PHP-MVCMS logo" onerror="this.onerror=null;this.src='/images/php-mvcms.png'" src="/images/php-mvcms2.svg" /></a></p>
<h2 class="flow-text">
PHP-MVCMS Blog</h2>
<p class="grey-text text-darken-3 lighten-3">
	The lastest CMS news can be found here.</p>

	<div class="m-container nav custom-drop">	
		<nav id="site-navigation" class="main-navigation custom-drop" role="navigation">	<a href="#" data-activates="mobile-nav" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
		<div id="mobile-nav" class="menu side-nav">
		<?php if(isset($sidemenu))echo$sidemenu;?>
		<!--
			<ul>
				<li class="mobile-header"><p><a href="#">Sass</a></p></li>
				<li class="mobile-header"><p><a href="#">Sass</a></p></li>
				<li class="mobile-header"><p><a href="#">Sass</a></p></li>
			</ul>-->
			<div class="clear"></div>
		</div>
		
		
		<div class="hide-on-med-and-down">
				<?php if(isset($dropmenu))echo$dropmenu;?>
			<!--<ul id="top-bar">
				<li class="menu-item"><a href="#">Sass</a></li>
				<li class="menu-item"><a href="#">Components</a></li>
				<li class="menu-item"><a href="#">JavaScript</a></li>
			</ul>
			-->
		</div>
		<div class="nav-wrapper right valign-wrapper">
      <form action="/search" method="get">
        <div class="input-field valign">
          <input name="search" id="search" type="search" required>
          <label for="search"><i class="material-icons">search</i></label>
         <!-- <i class="material-icons">close</i> -->
        </div>
      </form>
    </div>		</nav><!-- #site-navigation -->		

	<div class="clear"></div>
</div>


	