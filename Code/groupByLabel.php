<?php
require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$searchTerm = trim($_POST['searchTerm']);
$searchBy = $_POST['searchBy'];
$data = array();
$all_nano_count = 0;

if($searchBy=='iri')
{
    $searchTerm = trim($searchTerm,"/");

    $query = "SELECT rdfsLabel, COUNT(IRI) as nano_count, GROUP_CONCAT(DISTINCT dataset SEPARATOR ',') as datasets,
              GROUP_CONCAT(DISTINCT IRI SEPARATOR ',') as iri FROM
              (
                  SELECT * FROM new_topic WHERE IRI = '$searchTerm'
              )AS a
              GROUP BY rdfsLabel
              ORDER BY COUNT(IRI) DESC";
}
else
{
    if(count(explode(' ', $searchTerm))>1)
    {
        $query = "SELECT rdfsLabel, COUNT(IRI) as nano_count, GROUP_CONCAT(DISTINCT dataset SEPARATOR ',') as datasets,
                    GROUP_CONCAT(DISTINCT IRI SEPARATOR ',') as iri FROM
                    (
                        SELECT * FROM new_topic WHERE rdfsLabel LIKE '$searchTerm%' AND IRI NOT LIKE 'http://www.nextprot.org/nanopubs#NX_%-%\_%'
                    )AS a
                    GROUP BY rdfsLabel
                    ORDER BY COUNT(IRI) DESC";
    }
    else
    {
        $query = "SELECT rdfsLabel, COUNT(IRI) as nano_count, GROUP_CONCAT(DISTINCT dataset SEPARATOR ',') as datasets,
                    GROUP_CONCAT(DISTINCT IRI SEPARATOR ',') as iri FROM
                    (
                        SELECT * FROM new_topic WHERE MATCH(rdfsLabel)
                            AGAINST('$searchTerm' IN NATURAL LANGUAGE MODE) AND IRI NOT LIKE 'http://www.nextprot.org/nanopubs#NX_%-%\_%'
                    )AS a
                    GROUP BY rdfsLabel
                    ORDER BY COUNT(IRI) DESC";
    }
}

$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
       $all_nano_count += $row['nano_count'];
       //$data[]=$row;
       $child = array();
       if(count(explode(",",$row['iri']))>0)
       {
           $splitIRI = explode(",",$row['iri']);
           foreach ($splitIRI as $IRI) {
               $query = "Select IRI as iri, COUNT(IRI) as nano_count, dataset
                           from new_topic where IRI = '$IRI'
                          Group by IRI";
               $resultChild = mysqli_query($con, $query);
               while($rowChild = mysqli_fetch_assoc($resultChild))
               {
                 $child[] = $rowChild;
               }
            }
       }

       $data[] = array(
                   'iri' => $row['iri'],
                   'rdfsLabel' => $row['rdfsLabel'],
                   'nano_count' => $row['nano_count'],
                   'datasets' => $row['datasets'],
                   'child' => $child
               );
    }
}

$data = array( 'data' => $data,
                'total_nano_count' => $all_nano_count);

mysqli_close($con);
echo json_encode($data);
?>
