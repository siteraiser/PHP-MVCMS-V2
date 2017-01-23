<?php echo'<?xml version="1.0" encoding="UTF-8" ?>' ?>
<?php /* $lastMod =  date("Y-m-d")."T".date("H:i:s")."+00:00";*/?>

<urlset       xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

 <?php /*  Uncomment to add homepage, for use when the home page is a controller 
	<url>
        <loc><?php echo $this->base_url;?></loc>
        <priority>1.0</priority>
    </url>
	*/
?>
    <url>
        <loc><?php echo $this->base_url;?>search</loc>
		<priority>0.60</priority>
    </url>         
    <?php foreach($urlslist as $url) { ?>
    <url>
        <loc><?php echo $this->base_url.$url['link']?></loc>
        <priority><?php echo $url['priority']?></priority>
		<?php if(isset($url['changefreq'])){?>
		<changefreq><?php echo $url['changefreq']?></changefreq>
		<?php } ?>
	
		<?php if(isset($url['lastmod'])){?>
		<lastmod><?php echo $url['lastmod']?></lastmod>
		<?php } ?>
    </url>
    <?php } ?>
 
</urlset>