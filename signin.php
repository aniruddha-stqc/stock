<?php
// Include database connection
require('dbconnect.php');
$response = new \stdClass();
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
        $sql = "Select * from users where EMAIL='$email' and PASSWORD='$password' limit 1";
        //Execute the query to the MySQL data base
        $result = mysqli_query($conn, $sql);
        //If exactly one row is fetched
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $mem_name = $row["MEMBER_NAME"];
            $reg_email = $row["EMAIL"];
            //$approval_status = $row["status"];
            //$password_md5 = $row["password"];
            //$speciality_h_image[] = $row["speciality_h_img"];
            $response->status = "0";
            $response->message = "Credentials OK";
            $response->mem_name = $mem_name;
            $response->email = $reg_email;

        } else {
            $response->status = "2";
            $response->message = "Incorrect credentials";
        }

    }

} else {
    $response->status = "3";
    $response->message = "POST parameters expected";
}

echo json_encode($response);
?>