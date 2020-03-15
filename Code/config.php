<?php

// Database information
    $server = 'www.macs.hw.ac.uk';
    $username = 'ia48';
    $password = '7INB446Kle';
    $dbName = 'ia48';
    
    $con = mysqli_connect($server, $username, $password, $dbName);
    if(!$con)
    {
        die('The connection is not built...');
    }
    
?>
