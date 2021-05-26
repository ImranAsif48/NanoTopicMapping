<?php
require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$searchTerm = trim($_POST['searchTerm']);
$searchBy = $_POST['searchBy'];
$data = array();
$all_nano_count = 0;
$query = "";

if($searchBy=='iri')
{
    $searchTerm = trim($searchTerm,"/");
    $query = "SELECT IRI, rdfsLabel, dataset, npHash FROM new_topic WHERE IRI = '$searchTerm'";
}
else
{
    if(count(explode(' ', $searchTerm))>1)
    {
        $query = "SELECT IRI, rdfsLabel, dataset, npHash FROM new_topic
                    WHERE rdfsLabel LIKE '$searchTerm%' AND IRI NOT LIKE 'http://www.nextprot.org/nanopubs#NX_%-%\_%'";
    }
    else
    {
        $query = "SELECT IRI, rdfsLabel, dataset, npHash FROM new_topic WHERE MATCH(rdfsLabel) 
                            AGAINST('$searchTerm' IN NATURAL LANGUAGE MODE) AND IRI NOT LIKE 'http://www.nextprot.org/nanopubs#NX_%-%\_%'";
    }
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
//echo json_encode($query);
?>