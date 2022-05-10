<?php
error_reporting(0);

date_default_timezone_set("Asia/Calcutta");
//error_reporting(E_ALL);
//require('dbconnect.php');
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
$qr_data = "test_qr_data";
$pdf_file="Product-list.pdf";

//$sql = "SELECT `item id`,`PAGE NO/SLNO` FROM `isr`";
//$result = mysqli_query($conn,$sql);
//$count = 0;
$i = 1;
//if (mysqli_num_rows($result))

$pdf_grid="<table width=180 border=1>";

// loop on each row
$count=0;
while($i <= 10)
{
    $i++;
    $pdf_grid .="<tr>";

    #echo "ITEM ID: " . $row["item id"]." "."PAGE NO/SLNO: " .$row["PAGE NO/SLNO"]."<br>";
    //Prepare QR data
    //$qr_data  = $row["item id"]."*****".$row["PAGE NO/SLNO"];
    //Counter for filename

    $number = str_pad($count, 3, '0', STR_PAD_LEFT);
    $filename_png = "QR\\qr_".$number.".png";
    $filename_jpg = "QR\\qr_".$number.".jpg";

        //QRimage::jpg($qr_data, $filename,'L', 2, 0, 100, true, true);
        //rename($filename, 'QR\\'. $filename);
    QRcode::png($qr_data,  $filename_png, 'L', 4, 2);
    //rename( $filename_png, 'QR\\'.  $filename_png);
   // echo $filename_png,$filename_jpg;
    convert_jpg_png($filename_png,$filename_jpg);



   // echo $filename_png,$filename_jpg;

    //imagedestroy('QR\\'.  $filename_png);

        $pdf_grid .="<td><img src='".$filename_jpg."'></td></tr>";

        //$pdf_grid .="<td>".$row['item id']."</td></tr>";
        $count++;
        //Get server path
        //$directory = dir(getcwd());
        }    //echo "0 results";

$pdf_grid .="</table>";
//echo $pdf_grid;


$p = new PDF();
$p->SetLeftMargin(7);
$p->AddPage();
$p->setfont('times','',10);
$p->htmltable($pdf_grid);

$p->output("pdfs/".$pdf_file,'F');


?>


