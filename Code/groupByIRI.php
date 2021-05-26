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
    $query = "SELECT IRI, COUNT(IRI) as nano_count, GROUP_CONCAT(DISTINCT dataset SEPARATOR ',') as datasets, rdfsLabel FROM
                (
                    SELECT * FROM new_topic WHERE IRI = '$searchTerm'
                )AS a
                GROUP BY IRI
                ORDER BY COUNT(IRI) DESC";
}
else
{
    if(count(explode(' ', $searchTerm))>1)
    {
        $query = "SELECT IRI, COUNT(IRI) as nano_count, GROUP_CONCAT(DISTINCT dataset SEPARATOR ',') as datasets, rdfsLabel FROM
                    (
                        SELECT * FROM new_topic WHERE rdfsLabel LIKE '$searchTerm%' AND IRI NOT LIKE 'http://www.nextprot.org/nanopubs#NX_%-%\_%'
                    )AS a
                    GROUP BY IRI
                    ORDER BY COUNT(IRI) DESC";
    }
    else
    {
        $query = "SELECT IRI, COUNT(IRI) as nano_count, GROUP_CONCAT(DISTINCT dataset SEPARATOR ',') as datasets, rdfsLabel FROM
                    (
                        SELECT * FROM new_topic WHERE MATCH(rdfsLabel) 
                            AGAINST('$searchTerm' IN NATURAL LANGUAGE MODE) AND IRI NOT LIKE 'http://www.nextprot.org/nanopubs#NX_%-%\_%'
                    )AS a
                    GROUP BY IRI
                    ORDER BY COUNT(IRI) DESC";
    }
}

$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
       $all_nano_count += $row['nano_count']; 
       $data[]=$row;
    }
}

$data = array( 'data' => $data,
                'total_nano_count' => $all_nano_count);
                
mysqli_close($con);
echo json_encode($data);
?>