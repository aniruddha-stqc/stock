<?php
date_default_timezone_set("Asia/Calcutta");
//error_reporting(E_ALL);
require('dbconnect.php');
require('phpqrcode/qrlib.php');


function addQuotes($str){
    return "'$str'";
}

function insertQRinfo($conn, $values){
    //delete all old rows
    $result = $conn->query("DELETE FROM qrdetails;");
    //delete successful
    if($result){
        $result = $conn->query("ALTER TABLE qrdetails AUTO_INCREMENT=1;");
        //if auto increment reinitialized
        if($result){
            echo "INFO: Table reinitialized successfully <br>";
            $values_ = implode(",",$values);
            $sql = "INSERT INTO qrdetails ( `ITEM_ID`,`PAGE_NO`, `QRPATH`) VALUES" . $values_;
            $stmt = $conn->query($sql);
            if ($stmt  === TRUE) {
                echo "INFO: New records inserted successfully on: ".date("Y-m-d h:i:sa")."<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        else{
            echo "ERROR: Table reinitialization failed <br><br>";
        }
    }
    else{
        echo "ERROR: Table reinitialization failed <br><br>";
    }


}


$sql = "SELECT `item id`,`PAGE NO/SLNO` FROM `isr`";
$result = $conn->query($sql);
$count = 0;
$values = array();
if ($result->num_rows > 0) {
    echo "INFO: Generating ".$result->num_rows." QR codes ...<br>";
    // loop on each row
    while($row = $result->fetch_assoc()) {
        #echo "ITEM ID: " . $row["item id"]." "."PAGE NO/SLNO: " .$row["PAGE NO/SLNO"]."<br>";
        //Prepare QR data
        $qr_data  = $row["item id"]."*****".$row["PAGE NO/SLNO"];
        //Counter for filename
        $count += 1;
        //Padding with prefix zeros
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
        //echo "Generated QR code image in location: ".$directory->path."\\QR\\".$filename."<br>";
        //$path = $directory->path."\\QR\\".$filename;

        $_value = "(". addQuotes($row["item id"]).",".addQuotes($row["PAGE NO/SLNO"]).",".addQuotes($filename).")";
        array_push($values, $_value);
        //check if any QR data is blank and warn the user if blank
        if (empty(trim($row["item id"])) or empty(trim($row["PAGE NO/SLNO"]))){
            echo "WARNING: Blank data in QR codes of: ".$filename."<br>";
        }
    }

    //Insert QR info to db table 'qrdetails'
    insertQRinfo($conn, $values);


} else {
    echo "0 results";
}


