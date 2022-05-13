<?php
date_default_timezone_set("Asia/Calcutta");
//error_reporting(E_ALL);
require('dbconnect.php');
require('phpqrcode/qrlib.php');



define('FPDF_FONTPATH','pdftable/font/');
require('pdftable/lib/pdftable.inc.php');

class PDF extends PDFTable{

    function __construct($orientation='P',$unit='mm',$format='A4'){
        PDFTable::PDFTable($orientation,$unit,$format);
        $this->AliasNbPages();
    }

}

function convert_jpg_png($filename_png,$filename_jpg){
    //echo $filename_png."*****".$filename_jpg;

    $image = imagecreatefrompng($filename_png);
    $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
    imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
    imagealphablending($bg, TRUE);
    //echo "1";
    imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
    imagedestroy($image);
    $quality = 50; // 0 = worst / smaller file, 100 = better / bigger file
    // echo "2";
    imagejpeg($bg, $filename_jpg , $quality);
    // echo "3";
    imagedestroy($bg);
    unlink($filename_png);
}

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


$sql = "SELECT ITEM_ID, PAGE_NO FROM ISR";
$result = $conn->query($sql);
$count = 0;
$values = array();
if ($result->num_rows > 0) {
    echo "INFO: Generating ".$result->num_rows." QR codes ...<br>";
    // loop on each row
    while($row = $result->fetch_assoc()) {
        #echo "ITEM ID: " . $row["item id"]." "."PAGE NO/SLNO: " .$row["PAGE NO/SLNO"]."<br>";
        //Prepare QR data
        $qr_data  = $row["ITEM_ID"]."*****".$row["PAGE_NO"];
        //Counter for filename
        $count += 1;
        //Padding with prefix zeros
        $number = str_pad($count, 3, '0', STR_PAD_LEFT);
        $filename_png = "QR\\qr_".$number.".png";
        $filename_jpg = "QR\\qr_".$number.".jpg";
        //Display in Browser
        #QRcode::png($qr_data);
        //Save to server path
        QRcode::png($qr_data, $filename_png, 'L', 4, 2);
        rename($filename_png, 'QR\\'. $filename_png);
        //Get server path
        $directory = dir(getcwd());
        //Display Server path where saved
        //echo "Generated QR code image in location: ".$directory->path."\\QR\\".$filename."<br>";
        //$path = $directory->path."\\QR\\".$filename;

        /*$_value = "(". addQuotes($row["ITEM_ID"]).",".addQuotes($row["PAGE_NO"]).",".addQuotes($filename).")";
        array_push($values, $_value);
        //check if any QR data is blank and warn the user if blank
        if (empty(trim($row["ITEM_ID"])) or empty(trim($row["PAGE_NO"]))){
            echo "WARNING: Blank data in QR codes of: ".$filename."<br>";

        }*/
    }

    //Insert QR info to db table 'qrdetails'
    //insertQRinfo($conn, $values);


} else {
    echo "0 results";
}


