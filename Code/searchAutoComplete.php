<?php
require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$search = $_GET['term'];
$option = $_GET['option'];
$data = array();
$query = '';
if($option == 'proteinName')
{
    $query = "SELECT DISTINCT t.rdfsLabel as label FROM topic t 
            WHERE t.rdfsLabel like '%$search%'
            LIMIT 10";
}
else if($option == 'proteinId')
{
    $query = "SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(t.IRI, '#', 2), '#', -1) AS label
               FROM topic t 
                WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(t.IRI, '#', 2), '#', -1) like '%$search%'
                LIMIT 10";
}
else if($option == 'gene')
{
    $query = "SELECT LEFT(SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 2), ' ( ', -1), 
                length(SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 2), ' ( ', -1)) - 1) as label 
                FROM topic t
                WHERE LEFT(SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 2), ' ( ', -1), 
                    length(SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 2), ' ( ', -1)) - 1) like '%$search%'
                LIMIT 10";
}
else if($option == 'iri')
{
    $query = "SELECT  t.IRI  as label FROM topic t
                WHERE t.IRI LIKE '%$search%'
                LIMIT 10";
}
else if($option == 'label')
{
    $query = "SELECT  t.rdfsLabel  as label FROM topic t
                WHERE t.rdfsLabel LIKE '%$search%'
                LIMIT 10";
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

echo json_encode($data);
?>