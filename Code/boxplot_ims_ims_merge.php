<?php

require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$arrayIMS = array();
$arrayIMSMerge = array();
$query = '';

$query = "Select nano_count from ims_cluster order by nano_count desc";

$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
       $arrayIMS[]=intval($row["nano_count"]);
    }
}
$query = "Select nano_count from ims_merge_cluster order by nano_count desc";

$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
       $arrayIMSMerge[]=intval($row["nano_count"]);
    }
}

# The experimental results:
$results = array(
    'IMS' =>
                $arrayIMS,
    'IMS_Merge' =>
                $arrayIMSMerge,
);

echo json_encode($results);
//====================================================================================
//echo json_encode($obj); // All data in on json file.
?> 