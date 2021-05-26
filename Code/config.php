<?php

// Database information
    //$server = 'www.macs.hw.ac.uk'; // Not working.
    $server = 'mysql-server-1';
    $username = 'ia48';
    $password = '7INB446Kle';
    $dbName = 'ia48';
    
    $con = mysqli_connect($server, $username, $password, $dbName);
    if(!$con)
    {
        die('The connection is not built...');
    }
    
?>
