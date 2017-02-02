<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/* Include PHPExcel Library */

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "tax_info";

$id = $_GET['id'];

try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $conn->prepare("SELECT * FROM student_tax WHERE iss_no=$id");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($result as $row) {
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$ssno = $row['ssno'];
		$address = $row['address'];
		$city = $row['city'];
		$state = $row['state'];
		$zip = $row['zipcd'];
		$amount = $row['field_02'];
		$remain = $row['field_05'];
		$paid = $row['paid'];
	}

}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';



$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load("template.xls");
$objPHPExcel->setActiveSheetIndex(0);

/* Insert */

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 14, $first_name . " " . $last_name);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 10, $ssno);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 18, $address);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 21, $city . ", " . $state . " " . $zip);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 4, "$" . $amount);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 15, "$" . $remain);



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('_template.xls');

$myCommand = "export HOME=/tmp && libreoffice --headless --convert-to pdf _template.xls";

shell_exec ($myCommand);


header('Content-Type: application/pdf');
header('Content-Disposition: attachment;filename="01simple.pdf"');
header('Cache-Control: max-age=0');

sleep(3);

$str = file_get_contents(dirname(__FILE__) . '/_template.pdf');
echo $str;

exit;
?>
