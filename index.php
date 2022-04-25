<?php

//error_reporting(E_ALL);
require('dbconnect.php');
require('phpqrcode/qrlib.php');
require('signing.php');


//Invoke the function
$data_to_sign = "HelloWorld123";

$status = true;

if ($status) {
    //echo "QR Text<br><br>";
    #$qr_data = $data_to_sign . "##" . $signature;
    $qr_data = $data_to_sign ;
    //echo '<pre>' . $qr_data . '<pre>';
    #QRcode::png($qr_data);
    QRcode::png($qr_data, 'test.png', 'L', 4, 2);
    $directory = dir(getcwd());
    echo "Generated QR code image in location: ".$directory->path;
    
} else {
    echo "Unable to get digital signature";
}
?>



