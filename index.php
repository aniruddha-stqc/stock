<?php

//error_reporting(E_ALL);
require('dbconnect.php');
require('phpqrcode/qrlib.php');


//Prepare QR data
$qr_data  = "HelloWorld123";
//Display in Browser
#QRcode::png($qr_data);
//Save to server path
QRcode::png($qr_data, 'test.png', 'L', 4, 2);
//Get server path
$directory = dir(getcwd());
//Display Server path where saved
echo "Generated QR code image in location: ".$directory->path;





