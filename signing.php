<?php

//Function definition
function get_digital_signature($p_keystore_filepath, $p_keystore_password, $p_data_to_sign, &$p_signature) {
    $keystore_filepath = $p_keystore_filepath;
    //file_get_contents — Reads entire file into a string
    //Parameters: keystore_path = location of keystore file in p12 format
    $cert_store = file_get_contents($keystore_filepath);
    if (empty($cert_store)) {
        echo "Error: Unable to read the p12 file<br><br>";
        return false;
    } else {
        $keystore_password = $p_keystore_password;
        // openssl_pkcs12_read — Parse a PKCS#12 Certificate Store into an array
        //Parameters:
        //$cert_store = The certificate store contents, not its file name
        //$cert_info = On success, this will hold the Certificate Store Data.
        //$keystore_password = Encryption password for unlocking the PKCS#12 file.
        if (openssl_pkcs12_read($cert_store, $cert_info, $keystore_password)) {

            //openssl_pkey_export — Gets an exportable PEM representation of a key into a string
            //Parameters:
            //$cert_info = Private key component
            //$private_pem = Private key in PEM exportable format
            if (openssl_pkey_export($cert_info[pkey], $private_pem)) {
                // fetch private key from exportable representation file and ready it
                //Parameters:
                //$private_pem = Private key in exportable format
                //Returns a positive key resource identifier on success, or FALSE on error.
                $pkeyid = openssl_pkey_get_private($private_pem);
                if ($pkeyid) {
                    // Data to be digitally signed
                    $data_to_sign = $p_data_to_sign;
                    // openssl_sign — Generate digital signature
                    //Parameters:
                    //$data = The string of data you wish to sign
                    //$signature = If the call was successful the signature is returned in
                    //$pkeyid = a PEM formatted private key identifier
                    if (openssl_sign($data_to_sign, $signature, $pkeyid)) {
                        //Convert to base64 to get human readable form
                        //echo base64_encode($signature);
                        $p_signature = base64_encode($signature);
                        //echo $p_signature;
                        //echo "Success: Digital Signature generated successfully<br><br>";
                        return true;
                    } else {
                        echo "Error: Unable to generate digital signature<br><br>";
                        return false;
                    }

                    // free the private key from memory
                    //Parameters: private key identifier
                    openssl_free_key($pkeyid);
                } else {
                    echo "Error: Unable to get resource id for private key<br><br>";
                    return false;
                }
            } else {
                echo "Error: Unable to fetch the private key<br><br>";
                return false;
            }
        } else {
            echo "Error: Unable to unlock the key store<br><br>";
            return false;
        }
    }
}
?>