<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "tax_info";

echo "<table style='border: solid 1px black;'>";

class TableRows extends RecursiveIteratorIterator { 
    function __construct($it) { 
        parent::__construct($it, self::LEAVES_ONLY); 
    }

    function current() {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() { 
        echo "<tr>"; 
    } 

    function endChildren() { 
        echo "</tr>" . "\n";
    } 
} 

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $conn->prepare("SELECT * FROM student_tax");
	$stmt->execute();
	//$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($result as $row) {
		$id = $row['iss_no'];
		echo "<tr>";
		echo "<td style='width:150px;border:1px solid black;'>" .  "<a href = 'download.php?id=$id'>$id</a>" . "</td>";
		echo "<td style='width:150px;border:1px solid black;'>" .  $row['first_name'] . "</td>";
		echo "<td style='width:150px;border:1px solid black;'>" .  $row['last_name'] . "</td>";
		echo "</tr>";
		
	}

}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
echo "</table>";
?>
