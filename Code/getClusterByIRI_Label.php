<?php

require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$data = array();
$option = $_GET['opt'];
$query = '';

if($option=='iri')
{
    $query = "Select t.IRI, Count(t.IRI) GroupCount from topic t
                    INNER JOIN nanopubs np ON t.npId = np.npId
                WHERE np.dataset = 'nextprot' OR np.dataset = 'wikipathways'
                group by t.IRI";
}
else if($option=='ims')
{
    $query = "SELECT ims.topicIRI, COUNT(ims.topicIRI) 
                    FROM topic_ims_iri_merge ims 
                GROUP BY ims.topicIRI";
}
else if ($option == 'stat')
{
    $query = "SELECT * from statistics";
}
else if ($option == 'equal')
{
    $query = "SELECT * from exact_match_iri_merge";
}
else
{
    $query = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1) as Label,
                COUNT(SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1)) as GroupCount 
            FROM topic t INNER JOIN nanopubs np ON t.npId = np.npId
            Where np.dataset = 'nextprot' OR np.dataset = 'wikipathways' 
            GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1)";    
}

$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
       $data[]=$row;
    }
}

echo json_encode($data);
//====================================================================================
//echo json_encode($obj); // All data in on json file.
?> 