<?php

//error_reporting(E_ALL);
require('dbconnect.php');
require('phpqrcode/qrlib.php');



$sql = "SELECT `item id`,`PAGE NO/SLNO` FROM `isr`";
$result = $conn->query($sql);
$count = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        #echo "ITEM ID: " . $row["item id"]." "."PAGE NO/SLNO: " .$row["PAGE NO/SLNO"]."<br>";

        //Prepare QR data
        $qr_data  = $row["item id"]."*****".$row["PAGE NO/SLNO"];
        $count += 1;
        $number = str_pad($count, 3, '0', STR_PAD_LEFT);
        $filename = "qr_".$number.".png";
        //Display in Browser
        #QRcode::png($qr_data);
        //Save to server path
        QRcode::png($qr_data, $filename, 'L', 4, 2);
        rename($filename, 'QR\\'. $filename);
        //Get server path
        $directory = dir(getcwd());
        //Display Server path where saved
        echo "Generated QR code image in location: ".$directory->path."\\QR\\".$filename."<br>";

    }
} else {
    echo "0 results";
}







