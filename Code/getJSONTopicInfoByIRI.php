<?php

require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$data = array();
$option = $_GET['opt'];
$query = '';

if($option=='all')
{
    $query = "Select t.IRI, Count(t.IRI) GroupCount from topic t 
                group by t.IRI";
}
else
{
    $query = "Select t.IRI, Count(t.IRI) GroupCount from topic t 
                 group by t.IRI
                 having (count(t.IRI) >= 2) 
                 order by count(t.IRI) desc";    
}

$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
       $data[]=$row;
    }
}

mysqli_close($con);
echo json_encode($data);
//====================================================================================
//echo json_encode($obj); // All data in on json file.
?> 