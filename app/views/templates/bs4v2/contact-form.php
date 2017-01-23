<!-- #secondary -->
<div>
	<?php echo $content;?>
<br>
<?php 

if ((filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && $_POST['email1']=='') && ($_POST['email'] !='' && $_POST['message'] !='' && $_POST['name'] !='')) {
 $to = "dawn.tashearth@gmail.com"; 
 $subject = "Message from Example Website"; 
 $email = 'contact@example.com'; 
 $message = 'Sent from: '.$_POST['email'] .' '. $_POST['name'] . PHP_EOL .$_POST['message']; 
 $headers = "From: $email"; 
 $sent = mail($to, $subject, $message, $headers) ;  //success message is in other file
} ?>

<div class="container">

<h2 style="font-family: 'Montserrat';"><strong>Contact Us</strong></h2>
<?php if($sent){?>
Thank you for contacting us!
<?php
}else{

if(($_POST['email'] =='' OR $_POST['message'] =='' OR $_POST['name'] =='') && !empty($_POST) ){
echo'<span style="color:#f00;" >Please complete the form.</span>';
}
?>
<form method="post">
<input style="display:none;" type="email" name="email1" class="spoofemail">

<fieldset class="form-group  <?php echo( !empty($_POST) ? ($_POST['name'] == ''?'has-warning':'has-success') : '');?>">
 <label for="name">Name<span style="color:#f00;">*</span></label><br />
<input class="form-control <?php echo( !empty($_POST) ? ($_POST['name'] ==''?' form-control-warning':'form-control-success') : '');?>" id="name" type="text" name="name" value="<?php echo@$_POST['name']?>">
</fieldset>

<fieldset class="form-group  <?php echo( !empty($_POST) ? ($_POST['email'] ==''?'has-warning':'has-success') : '');?>">
 <label for="email">Email<span style="color:#f00;">*</span></label><br />
<input class="form-control <?php echo( !empty($_POST) ? ($_POST['email'] ==''?' form-control-warning':'form-control-success') : '');?>" id="email" type="email" name="email" value="<?php echo @$_POST['email']?>">
</fieldset>

<fieldset class="form-group  <?php echo( !empty($_POST) ? ($_POST['message'] ==''?'has-warning':'has-success') : '');?>">
 <label for="message">Message<span style="color:#f00;">*</span></label><br />
<textarea class="form-control  <?php echo( !empty($_POST) ? ($_POST['message'] ==''?' form-control-warning':'form-control-success') : '');?>" id="message" rows="10" name="message"><?php echo@$_POST['message']?></textarea>
</fieldset>

<div class="center-block">
  <button class="btn" style="font-family: 'Montserrat';color:#fff;background-color:#B22222;" type="submit" name="action"><strong>SUBMIT</strong>
  
  </button>
  </div>
  </form>
<?php } ?>


</div>
</div>
<br />


<script defer src="/assets/js/contact-validate.js" type="text/javascript"></script>

