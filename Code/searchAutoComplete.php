<?php
require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$searchTerm = str_replace("'","\\'",$_GET['term']);;
$option = $_GET['option'];
$searchBy = $_GET['search'];
$data = array();
$query = '';

if($searchBy == 'iri')
{
    if($option == 'iri')
    {
        $query = "SELECT DISTINCT topicIRI as label FROM new_topic_iri_cluster
                    WHERE topicIRI LIKE '$searchTerm%'
                    LIMIT 10";
    }
    else if($option == 'label')
    {
        $query = "SELECT DISTINCT iri as label FROM new_topic_label_cluster
                    WHERE iri LIKE '$searchTerm%'
                    LIMIT 10";
    }
    else if($option == 'label_ims')
    {
        $query = "SELECT DISTINCT ims_iri as label FROM new_topic_ims_cluster_iris
                    WHERE ims_iri LIKE '$searchTerm%'
                    LIMIT 10";
    }
}
else{
    if($option == 'iri')
    {
        $query = "SELECT DISTINCT rdfsLabel as label FROM new_topic_iri_cluster
                    WHERE rdfsLabel LIKE '$searchTerm%'
                    LIMIT 10";
    }
    else if($option == 'label')
    {
        $query = "SELECT DISTINCT rdfsLabel as label FROM new_topic_label_cluster
                    WHERE rdfsLabel LIKE '$searchTerm%'
                    LIMIT 10";
    }
    else if($option == 'label_ims')
    {
        $query = "SELECT DISTINCT rdfsLabel as label FROM new_topic_ims_cluster_iris
                    WHERE rdfsLabel LIKE '$searchTerm%'
                    LIMIT 10";
    }
}
//and np.dataset = 'nextprot'
////////////////////////////////////////////////////////////////
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