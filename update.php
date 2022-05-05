<?php
// Include database connection
date_default_timezone_set("Asia/Calcutta");
//error_reporting(E_ALL);
require('dbconnect.php');
//Initialize JSON response
$response = new \stdClass();
//Check if POST parameters are set
if (isset($_POST['ITEM_ID']) && isset($_POST['PAGE_NO'])) {
    //receive the post parameters of email and password
    $item_id = $_POST['ITEM_ID'];
    $page_no = $_POST['PAGE_NO'];
    //Check if credentials are blank
    if (empty($item_id) || empty($page_no)) {

        $response->status = "1";
        $response->message = "Incorrect request";

    } else {

        //check if the data matches entry in database
        $sql = "SELECT * FROM QRDETAILS WHERE ITEM_ID='$item_id' AND PAGE_NO = '$page_no'";
        //Execute the query to the MySQL data base
        $result = mysqli_query($conn, $sql);
        //If exactly one row is fetched
        if ($result->num_rows == 1) {
            $response->status = "8";
            $response->message = "Found";
            }
        else {
            $response->status = "5";
            $response->message = "Not Found";
        }
    }

} else {
    $response->status = "3";
    $response->message = "POST parameters expected";

}

echo json_encode($response);
?>