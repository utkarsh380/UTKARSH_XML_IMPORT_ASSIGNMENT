<?php
require 'database.php';
$conn = new PDO("mysql:host=localhost;dbname=phpcrud", 'root', '');
if(isset($_POST['buttonImport'])) {
// Create a new DOMDocument 
$doc = new DOMDocument; 
// XSD schema 
$XSD = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
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

// Load the XML 
$st="C:/xampp/htdocs/PROJECT-IMPORT-XML-USING-PHP/.$_FILES[xmlFile][name]";
$doc->loadXML($st); 
  
//echo "all ok";
if ($doc->schemaValidateSource($XSD)) { 
    echo "This document is valid!\n"; 
    
} 

   // echo realpath($_FILES['xmlFile']['tmp_name']);
	copy($_FILES['xmlFile']['tmp_name'],
		'C:/xampp/htdocs/PROJECT-IMPORT-XML-USING-PHP/'.$_FILES['xmlFile']['name']);
	$products = simplexml_load_file('C:/xampp/htdocs/PROJECT-IMPORT-XML-USING-PHP/'.$_FILES['xmlFile']['name']);
	foreach($products as $product){
		$stmt = $conn->prepare('insert into
			prod(id, name, price, quantity)
			values(:id, :name, :price, :quantity)');
		$stmt->bindValue('id', $product->id);
		$stmt->bindValue('name', $product->name);
		$stmt->bindValue('price', $product->price);
		$stmt->bindValue('quantity', $product->quantity);
		$stmt->execute();
	}
}

$stmt = $conn->prepare('select * from prod');
$stmt->execute();
?>

<form method="post" enctype="multipart/form-data">
	XML File <input type="file" name="xmlFile">
	<br>
	<input type="submit" value="Import" name="buttonImport">
</form>
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