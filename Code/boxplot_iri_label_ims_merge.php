<?php

require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$arrayIRI = array();
$arrayLabel = array();
$arrayIMS = array();
$arrayMerge = array();
$query = '';

$query = "Select nano_count from topic_iri_cluster order by nano_count desc";

$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
       $arrayIRI[]=intval($row["nano_count"]);
    }
}
$query = "Select nano_count from label_cluster order by nano_count desc";

$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
       $arrayLabel[]=intval($row["nano_count"]);
    }
}

$query = "Select null_count from null_label_cluster";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
       $arrayLabel[]=intval($row["null_count"]);
    }
}


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
       $arrayMerge[]=intval($row["nano_count"]);
    }
}

# The experimental results:
$results = array(
    'Topic_IRI' =>
                $arrayIRI,
    'rdfsLabel' =>
                $arrayLabel,
    'IMS' =>
                $arrayIMS,
    'IMS_Merge' =>
                $arrayMerge,
);

echo json_encode($results);
//====================================================================================
//echo json_encode($obj); // All data in on json file.
?> 