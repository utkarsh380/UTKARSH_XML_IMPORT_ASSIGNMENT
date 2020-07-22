<?php
include 'functions.php';
// Your PHP code here.
if(isset($_GET['action'])) {
	$contacts = simplexml_load_file('contact.xml');
	$id = $_GET['id'];
	$index = 0;
	$i = 0;
	foreach($contacts->contact as $contact){
		if($contact['id']==$id){
			$index = $i;
			break;
		}
		$i++;
	}
	unset($contacts->contact[$index]);
	file_put_contents('contact.xml', $contacts->asXML());
}
$contacts = simplexml_load_file('contact.xml');
//echo 'Number of contacts: '.count($contacts);
//echo '<br>List contact Information';
// Home Page template below.

?>

<?=template_header('Home')?>

<div class="content">
	<h2>Home</h2>
</div>
<br>

<!--<a href='create.php'> 
        <button class="btn"> 
            ADD CONTACTS 
        </button> 
    </a>--> 
<br>


<?=template_footer()?>