<?php

require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$topic = '';

if(isset($_GET['iri']))
{
    $iri = $_GET['iri'];
    if(count(explode(',', $iri))>1)
    {
        $splitIRIs = explode(',', $iri);
        $IRIs = '';
        foreach ($splitIRIs as $i) {
            $IRIs .= "'$i',";
        }

        $IRIs = trim($IRIs,",");
        $topic = "IRI IN ($IRIs)";
    }
    else{
        $topic = "IRI = '$iri'";
    }
    
}
else if(isset($_GET['lbl']))
{
    $label = $_GET['lbl'];
    $topic = "rdfsLabel like '$label' AND IRI NOT LIKE 'http://www.nextprot.org/nanopubs#NX_%-%\_%'";
}

$data = array();
//$server = 'http://server.nanopubs.lod.labs.vu.nl/';
//$server = 'http://130.60.24.146:7880/';
$server = 'https://openphacts.cs.man.ac.uk/nanopub/server/';
//$server = 'http://server.np.dumontierlab.com/';
//$server = 'http://server.np.scify.org/';
//$server = 'https://server.nanopubs.knows.idlab.ugent.be/';
//$server = 'http://rdf.disgenet.org/nanopub-server/';

//SUBSTRING(np_claim, 1, 10) as np_claim

$query = "Select CONCAT('$server', nphash) as trustyURI, npHash, createdOn, np_claim, dataset, pubDate 
            from new_topic where $topic";

#$query = "Select CONCAT('$server', nphash) as trustyURI, npHash, createdOn, np_claim, dataset 
#            from new_topic where newtopicId >= 7268284 and newtopicId <= 7268284"; SUBSTRING(np_claim, 1, 5) as np_claim

mysqli_set_charset($con,"utf8");
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
//echo $data;
?>

