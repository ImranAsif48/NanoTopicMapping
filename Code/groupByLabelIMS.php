<?php
require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$searchTerm = trim($_POST['searchTerm']);
$searchBy = $_POST['searchBy'];
$cid_array = array();
$data = array();
$all_nano_count = 0;
$query = "";

if($searchBy=='iri')
{
    $searchTerm = trim($searchTerm,"/");
    if(strrpos($searchTerm, "www.nextprot.org/entry")>0)
    {
        $splitIRI = explode("/",$searchTerm);
        $searchTerm = 'http://www.nextprot.org/db/search#' . $splitIRI[count($splitIRI )-1];
    }
    //else if(strpos($searchTerm, 'https') == true){
    $searchTerm = str_replace('https', 'http', $searchTerm); 
    //}

    $query = "SELECT DISTINCT c_id FROM new_topic_ims_cluster_iris
                WHERE ims_iri = '$searchTerm' 
                GROUP BY ims_iri";
}
else
{
    if(count(explode(' ', $searchTerm))>1)
    {
        $query = "SELECT DISTINCT GROUP_CONCAT(DISTINCT c_id SEPARATOR ',') as c_id FROM new_topic_ims_cluster_iris
                    WHERE rdfsLabel LIKE '$searchTerm%' AND nano_count IS NOT null
                    GROUP BY rdfsLabel";
    }
    else
    {
        $query = "SELECT DISTINCT GROUP_CONCAT(DISTINCT c_id SEPARATOR ',') as c_id FROM new_topic_ims_cluster_iris 
                    WHERE MATCH(rdfsLabel) AGAINST('$searchTerm' IN NATURAL LANGUAGE MODE) AND nano_count IS NOT null
                    GROUP BY rdfsLabel";
    }
}


$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
        $cid_array[]=$row; 
    }

    ////////////////////////////////////////////////
    // Get similar lables under specified cluster
    $cId_array = array();
    foreach( $cid_array as $item ){
        
        if(count(explode(",",$item["c_id"]))>0)
        {
            $flagIn = false;
            foreach (explode(",",$item["c_id"]) as $value) {
                if(in_array($value, $cId_array))
                {
                    $flagIn = true;
                    break;
                }
                else{
                    array_push($cId_array,$value);
                }
            }

            if($flagIn==true)
            continue;
        }
        else{
            if(in_array($item["c_id"], $cId_array))
            {
            continue;
            }
            else{
                array_push($cId_array,$item["c_id"]);
            }
        }

        $cID = $item["c_id"];//explode(",",$item["c_id"])[0];
        $parentLabel = '';
        $parentIRI = '';
        $parentNano = 0;
        $totalNano = 0;
        $parentDataset = '';
        $IRIs = '';
        $child = array();
        $flagParent = true;
        
        $query = "SELECT DISTINCT ims_iri, nano_count, rdfsLabel, datasets FROM new_topic_ims_cluster_iris 
                    WHERE c_id IN ($cID) AND nano_count IS NOT NULL 
                    ORDER BY nano_count DESC";

        $result = mysqli_query($con, $query);

        if(mysqli_num_rows($result) > 0)
        {    
            while($row = mysqli_fetch_assoc($result))
            {
                if($flagParent == true)
                {
                    $parentIRI = $row['ims_iri'];
                    $parentLabel = $row['rdfsLabel'];
                    $parentNano = $row['nano_count'];
                    $parentDataset = $row['datasets'];
                    $flagParent = false;
                }
                else{
                    $child[]=$row;
                }

                $totalNano += $row['nano_count']; 
                $all_nano_count += $row['nano_count']; 
                $IRIs .= $row['ims_iri'] . ',';
            }
        }
        
        ///////////////////////////////////////////////////
        //// bind data now
        $IRIs = trim($IRIs,",");
        $data[] = array(
                    'iri' => $parentIRI,
                    'rdfsLabel' => $parentLabel,
                    'nano_count' => $parentNano,
                    'datasets' => $parentDataset,
                    'child' => $child,
                    'IRIs' => $IRIs,
                    'total_nano' => $totalNano
                );
    }

    array_multisort(array_column($data, 'total_nano'), SORT_DESC, $data);
}
else
{
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
                            SELECT * FROM new_topic WHERE rdfsLabel LIKE '%$searchTerm%' AND IRI NOT LIKE 'http://www.nextprot.org/nanopubs#NX_%-%\_%'
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
    $child = array();
    
    while($row = mysqli_fetch_assoc($result))
    {
        $data[] = array(
            'iri' => $row['IRI'],
            'rdfsLabel' => $row['rdfsLabel'],
            'nano_count' => $row['nano_count'],
            'datasets' => $row['datasets'],
            'child' => $child,
        );

        $all_nano_count += $row['nano_count']; 
    }
}

$data = array( 'data' => $data,
                'total_nano_count' => $all_nano_count);
////////////////////////////////////////////////
mysqli_close($con);
echo json_encode($data);
?>