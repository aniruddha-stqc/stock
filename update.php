<?php
// Include database connection
date_default_timezone_set("Asia/Calcutta");
//error_reporting(E_ALL);
require('dbconnect.php');

//Check if POST parameters are set
if (isset($_POST['email']) && isset($_POST['password'])) {
    //receive the post parameters of email and password
    $email = $_POST['email'];
    $password = $_POST['password'];
    //Check if credentials are blank
    if (empty($email) || empty($password)) {
        $response->status = "1";
        $response->message = "email or password is mandatory";
        echo json_encode($response);
    } else {
        //$password_md5 = md5($password);
        //check if the credential matches entry in database
        $sql = "Select * from qrdetails where item_id='$email' limit 1";
        //Execute the query to the MySQL data base
        $result = mysqli_query($conn, $sql);
        //If exactly one row is fetched
        if ($result->num_rows == 1) {

            $response->status = "8";
            $response->message = "Found";

            while ($row = $result->fetch_assoc()) {
                $mem_name = $row["mem_name"];
                $reg_email = $row["email"];
                $approval_status = $row["status"];
                $password_md5 = $row["password"];
                //$speciality_h_image[] = $row["speciality_h_img"];
            }

            if ($approval_status == "A") {

                if ($password_md5 == md5($password)) {
                    $response->status = "0";
                    $response->message = "Credentials OK";
                    $response->mem_name = $mem_name;
                    $response->email = $reg_email;

                } else {
                    $response->status = "2";
                    $response->message = "Incorrect credentials";

                }

            } else {
                if ($approval_status == "P") {
                    $response->status = "4";
                    $response->message = "Registration pending";

                }
            }

        } else {
            $response->status = "5";
            $response->message = "Not registered";

        }
    }
} else {
    $response->status = "3";
    $response->message = "POST parameters expected";

}

echo json_encode($response);
?>