<?php
//index.php
include 'functions.php';
?>
<?=template_header('Home')?>
<!DOCTYPE html>
<html>
 <head>
  <title>Import XML Data</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />-->
  <link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
 </head>
 <body>
 
  <br />
  <div class="container">
   <div class="row">
   
    <br />
    <div class="col-md-9" style="margin:0 auto; float:none;">
     <span id="message"></span>
     <form method="post" id="import_form" enctype="multipart/form-data">
      <div class="form-group">
       <label>Select XML File</label>
       <input type="file" name="file" id="file" />
      </div>
      <br />
      <div class="form-group">
       <input type="submit" name="submit" id="submit" class="btn btn-info" value="Import" />
      </div>
     </form>
    </div>
   </div>
  </div>
 
 </body>
</html>
<script>
$(document).ready(function(){
 $('#import_form').on('submit', function(event){
  event.preventDefault();

  $.ajax({
   url:"import.php",
   method:"POST",
   data: new FormData(this),
   contentType:false,
   cache:false,
   processData:false,
   beforeSend:function(){
    $('#submit').attr('disabled','disabled'),
    $('#submit').val('Importing...');
   },
   success:function(data)
   {
    $('#message').html(data);
    $('#import_form')[0].reset();
    $('#submit').attr('disabled', false);
    $('#submit').val('Import');
   }
  })

  setInterval(function(){
   $('#message').html('');
  }, 5000);

 });
});
</script>
<?=template_footer()?>