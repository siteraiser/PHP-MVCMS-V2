<?php 

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

function outputCSV($data) {
    $output = fopen("php://output", "w");
	fputcsv($output, array('Email Address', 'First Name', 'Last Name'));
    foreach ($data as $row) {
        fputcsv($output, $row); // here you can change delimiter/enclosure
    }
    fclose($output);
}//array(
  // array("name 1", "age 1", "city 1"),
    //array("name 2", "age 2", "city 2"),
    //array("name 3", "age 3", "city 3"))
outputCSV( $data );
?>