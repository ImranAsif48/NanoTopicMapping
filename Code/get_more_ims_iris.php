<?php

require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$c_id = $_GET['c_id'];

$data = array();
$query = "Select ims_iri from new_topic_ims_cluster_iris where c_id='$c_id'";
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
?>

