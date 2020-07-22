<?php
$connection=mysqli_connect("localhost","root","","phpcrud");
$xml =simplexml_load_file("contact.xml") or die("Error: Cannot create object");
$id=14;
foreach($xml->children() as $rowdata)
{
   $id++;
   $name=$rowdata->name;
   $email=$rowdata->email;
   $phone=$rowdata->phone;
   $title=$rowdata->title;
   $created= $rowdata->created;
    
   $query="INSERT INTO contacts(id,name,email,phone,title,created) VALUES('".$id."','".$name."','".$email."','".$phone."','".$title."','".$created."')";
   echo "success";
   $result = mysqli_query($connection,$query);
}
?>