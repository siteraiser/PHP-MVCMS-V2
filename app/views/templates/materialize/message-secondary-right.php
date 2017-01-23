<!-- #secondary -->
<div id="secondary" class="widget-area col s12 m4 l3 offset-l1" role="complementary">
	<?php echo $content;?>
<br>
<?php 

if ((filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && $_POST['email1']=='') && ($_POST['email'] !='' && $_POST['message'] !='' && $_POST['name'] !='')) {
 $to = "carl@siteraiser.com"; 
 $subject = "Message from Get Pit"; 
 $email = 'contact@siteraiser.com'; 
 $message = 'Sent from:'.$_POST['email'] .' '. $_POST['name'] . PHP_EOL .$_POST['message']; 
 $headers = "From: $email"; 
 $sent = mail($to, $subject, $message, $headers) ;  //success message is in other file
} ?>

<div class="container">
<?php if($sent){?>
Someone will respond to your message soon, thanks for your feedback!
<?php
}else{

if(($_POST['email'] =='' OR $_POST['message'] =='' OR $_POST['name'] =='') && !empty($_POST) ){
echo'<span style="color:#f00;" >Please complete the form.</span>';
}
?>
<form method="post">
<input type="email" name="email1" class="spoofemail">
Enter Your Email:<input type="email" name="email" value="<?php echo @$_POST['email']?>"><br>
Name:<input type="text" name="name" value="<?php echo@$_POST['name']?>"><br>
Message:<textarea rows="10" name="message"><?php echo@$_POST['message']?></textarea>
<div class="center-align">
  <button class="btn waves-effect waves-light light-blue" type="submit" name="action">Submit
    <i class="material-icons right">send</i>
  </button>
  </div>
  </form>
<?php } ?>


</div>
</div>