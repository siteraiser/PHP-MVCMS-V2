<?php 

ini_set('upload_max_filesize', '20M');

ini_set('post_max_size', '20M');

ini_set('max_input_time', 300);

ini_set('max_execution_time', 300);

include_once('../system/config.inc.php');
include_once(BASE_URI.'/applications/blog/classes/User.php');
if(!isset($_SESSION)) {
     session_start();
} 

require('../'.DB);
$pdo=get_dbconn();
$user=(isset($_SESSION[SESSION_PREFIX]['user'])) ? $_SESSION[SESSION_PREFIX]['user'] : null;

 if(  isset($_POST['headline-text'])){ 
 
 $link = str_replace(" ", "-",strtolower('/blog/'.$_POST['category-select'].'/'.$_POST['headline-text']));
 $query="INSERT INTO blog (`type`,`user` ,`link` ,`headline`,`description`,`content`,`Category` ) VALUES ( ? , ?, ?, ?, ?,?, ? )";
 $stmt=$pdo->prepare($query);
 $stmt->execute(array($_POST['type-select'],$user->getId(),$link,$_POST['headline-text'],$_POST['description-text'],$_POST['editor1'],$_POST['category-select']));
 
$_GET['article']=$pdo->lastInsertId();
$insert_id = $pdo->lastInsertId();

 if ($_FILES['PostArticleImage']['name'] != '') {
            $filename=$_FILES['PostArticleImage']['name'];  //you can change this with your filename
            $allowed= 'png,jpg,gif,jpeg,img,PNG,JPG,JPEG,GIF';
            $extension_allowed= explode(',', $allowed); 
            $file_extension= pathinfo($filename, PATHINFO_EXTENSION);

                if(!array_search($file_extension, $extension_allowed))
                {
                    die('You are not allowed to use this file extension.');
                } else {
                       
                     
			$uploadfile = BASE_URI.'/images/banners/'.$filename;//Full path of file to be uploaded
	$th_uploadfile = BASE_URI.'/images/banners/th_'.$filename;//Full path of file to be uploaded
			//Move temp file to destination path
			if(move_uploaded_file($_FILES['PostArticleImage']['tmp_name'],$uploadfile))
			{copy($uploadfile, $th_uploadfile);
	                	 $update_photo = "UPDATE `blog` SET `image`=? WHERE `id`=?";
               			 $stmt=$pdo->prepare($update_photo);
				$stmt->execute(array($filename,$insert_id));
	                }
                
                }
}
//add original date
$query="SELECT lastUpdate FROM blog WHERE id = :id";
	$stmt=$pdo->prepare($query);
	$stmt->execute(array(':id'=>$_GET['article'])); 		
		
	if (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
			
		$query="UPDATE blog SET date=:date WHERE id = :id";
		 $stmt=$pdo->prepare($query);
		$stmt->execute(array(':date'=>$row['lastUpdate'],':id'=>$_GET['article'])); 	
	}


 }else{







$clearCache=0;
 if( isset($_POST['editor1'])){ 
  $clearCache=1;



 $query="UPDATE blog SET content=:content WHERE id = :id AND user = :user";
 $stmt=$pdo->prepare($query);
$stmt->execute(array(':content'=>$_POST['editor1'],':id'=>$_GET['article'], ':user'=>$user->getId())); 	
 
 
 
 }
 
 
 }
 class blog{
	
	public $page = '';
	public $content = '';
	public $output ='';
	public $link='';
	function output($pdo,$user){
	
	
		//$query="SELECT * FROM blog WHERE Category LIKE :category ORDER BY date DESC;";
		$query="SELECT * FROM blog WHERE id = :id";
			$stmt=$pdo->prepare($query);
			$stmt->execute(array(':id'=>$_GET['article'])); 		
		
		$i=0; $count=$stmt->rowCount($result);
		if ($count > 0) {
					
			while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
				echo$row["id"];				
			$this->link=$row["link"];	
				$value = $row["content"];
				if($user && ($user->getId()==$row["user"])){
					 $value = str_replace("&", "&amp;" ,$value);
	                       		 $value = str_replace("<", "&lt;",$value);
	                       		$this->output =  $value;
                        	}else{
                        	
                        	die();
                        	}
			}
		}

	}
	
}
$blog = new blog();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Replace Textarea by Code - CKEditor Sample</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
	<script src="sample.js" type="text/javascript"></script>
	<link href="sample.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<h1>
		FileManager in CKEditor Sample
	</h1>
	<!-- This <div> holds alert messages to be display in the sample page. -->
	<div id="alerts">
		<noscript>
			<p>
				<strong>CKEditor requires JavaScript to run</strong>. In a browser with no JavaScript
				support, like yours, you should still see the contents (HTML data) and you should
				be able to edit it normally, without a rich editor interface.
			</p>
		</noscript>
	</div><?php
	
	echo'<form action="demo.php?article=';
	$blog->output($pdo,$user); echo'" method="post">';?>
		<p>
			
			<textarea id="editor1" name="editor1" ><?php echo$blog->output; ?></textarea>
			<script type="text/javascript">
			//<![CDATA[

				// This call can be placed at any point after the
				// <textarea>, or inside a <head><script> in a
				// window.onload event handler.

				// Replace the <textarea id="editor"> with an CKEditor
				// instance, using default configurations.
				CKEDITOR.replace( 'editor1',
                { width: 750,   resize_minWidth: 150,
                    filebrowserBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Connector=http://www.siteraiser.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
                    filebrowserImageBrowseUrl : 'js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=http://www.siteraiser.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
                    filebrowserFlashBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=http://www.siteraiser.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
					filebrowserUploadUrl  :'http://www.siteraiser.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/upload.php?Type=File',
					filebrowserImageUploadUrl : 'http://www.siteraiser.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/upload.php?Type=Image',
					filebrowserFlashUploadUrl : 'http://www.siteraiser.com/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/upload.php?Type=Flash'
				});

			//]]>
			</script>
		</p>
		
	</form>
	<div id="footer">
	<?php
	echo'<a href="/editimages/editimages.php?article=';
	$blog->output($pdo,$user); echo'">edit / replace images</a>';
	echo"<a href=\"{$blog->link}\">Go to {$blog->link}</a> ";?>
	<a href="/filemanager_in_ckeditor/newblogpost.php">new post</a>;
		<hr />
		<p>
			CKEditor - The text editor for Internet - <a href="http://ckeditor.com/">http://ckeditor.com</a>
		</p>
		<p id="copy">
			Copyright &copy; 2003-2010, <a href="http://cksource.com/">CKSource</a> - Frederico
			Knabben. All rights reserved.
		</p>
	</div>
	
<?php
if($clearCache==1){//on page save
	
	$break = Explode('/', $blog->link);
	array_shift($break);
	//var_dump($break);
	$break2 = $break;
	//
	
	$file = implode('-:-', $break);
	$cachefile = BASE_URI.'/cached/cached-'.$file;
	unlink($cachefile);
	
	$break2 = array_pop($break2);	
	
	$file2 = '-:-'.$break[1];
	$cachefile2 = BASE_URI.'/cached/cached-blog'.$file2;
	unlink($cachefile2);
	
	
	$path = BASE_URI.'/cached/';
if ($handle = opendir($path)) {

    while (false !== ($file = readdir($handle))) { 
         //echo  $fileN = filename($path . $file);
     
        //24 hours in a day * 3600 seconds per hour
        if(stristr($file, 'cached-blog'.$file2) == TRUE && is_file($path . $file))
        {
           unlink($path . $file);echo$path . $file.' unlinked';
        }

    }

    closedir($handle); 
}
	
	
	
	unlink(BASE_URI.'/cached/cached-blog');
}?>
</body>
</html>
