<?php
// Include the database configuration file
include 'dbConfig.php';
$statusMsg = '';
$conn = new PDO("mysql:host=localhost;dbname=phpcrud", 'root', '');
// File upload path
$targetDir = "uploads/";

    if(isset($_POST["submit"])){

      if(!empty($_FILES["file"]["name"])){
    // Allow certain file formats
            $fileName = basename($_FILES["file"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    //  $allowTypes = array('jpg','png','jpeg','gif','pdf');
            $allowTypes = array('xml');
            
    if(in_array($fileType, $allowTypes)){
    // Upload file to server
    
    if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
            
    $doc = new DOMDocument; 
     // XSD schema 
      
         $XSD ="<?xml version=\"1.0\" encoding=\"utf-8\"?>
                 <xs:schema attributeFormDefault=\"unqualified\" elementFormDefault=\"qualified\" xmlns:xs=\"http://www.w3.org/2001/XMLSchema\">
                   <xs:element name=\"products\">
                     <xs:complexType>
                       <xs:sequence>
                         <xs:element maxOccurs=\"unbounded\" name=\"product\">
                           <xs:complexType>
                             <xs:sequence>
                               <xs:element name=\"id\" type=\"xs:string\" />
                               <xs:element name=\"name\" type=\"xs:string\" />
                               <xs:element name=\"price\" type=\"xs:unsignedShort\" />
                               <xs:element name=\"quantity\" type=\"xs:unsignedByte\" />
                             </xs:sequence>
                           </xs:complexType>
                         </xs:element>
                       </xs:sequence>
                     </xs:complexType>
                   </xs:element>
                 </xs:schema>";
            
        
            $xml = simplexml_load_file('uploads/'.$_FILES['file']['name']);
            // Load the XML 
         
            $doc->loadXML($xml->asXML());
            //echo "all ok";
            if ($doc->schemaValidateSource($XSD)) 
            echo "This document is valid!\n"; 
            
           $products = simplexml_load_file('uploads/'.$_FILES['file']['name']);
            
	         foreach($products as $product){
		       $stmt = $conn->prepare('insert into
			                           product_tb(id, name, price, quantity)
			                           values(:id, :name, :price, :quantity)');
	              	$stmt->bindValue('id', $product->id);
	              	$stmt->bindValue('name', $product->name);
	              	$stmt->bindValue('price', $product->price);
	              	$stmt->bindValue('quantity', $product->quantity);
	              	$stmt->execute();
                }
                  
               $stmt = $conn->prepare('select * from product_tb');
               $stmt->execute();
               
          
        }else
           $statusMsg = "Sorry, there was an error uploading your file.";
 
    }else
        $statusMsg = 'Sorry, only XML files are allowed to upload.';

}else
    $statusMsg = 'Please select a file to upload.';

// Display status message

echo $statusMsg;
}
?>
<br>
<h3>Product List</h3>
<table cellpadding="2" cellspacing="2" border="1">
	<tr>
		<th>Id</th>
		<th>Name</th>
		<th>Price</th>
		<th>Quantity</th>
	</tr>
	<?php while($product = $stmt->fetch(PDO::FETCH_OBJ)) { ?>
	<tr>
		<td><?php echo $product->id; ?></td>
		<td><?php echo $product->name; ?></td>
		<td><?php echo $product->price; ?></td>
		<td><?php echo $product->quantity; ?></td>
	</tr>
	<?php } ?>
</table>


 



