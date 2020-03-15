<?php
require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$search = $_POST['search'];
$data = array();

$query = "SELECT CONCAT('https://www.nextprot.org/entry/', SUBSTRING_INDEX(SUBSTRING_INDEX(t.IRI, '#', 2), '#', -1)) AS IRI,
             COUNT(t.IRI) as NanoCount, t.IRI as OrignalIRI, np.dataset,
             t.rdfsLabel as fullName, t.rdfsDescription as description FROM topic t 
            INNER JOIN nanopubs np on t.npId = np.npId 
            WHERE t.rdfsLabel is NOT null and t.rdfsLabel like '%$search%' 
            GROUP BY t.rdfsLabel
            ORDER BY COUNT(t.rdfsLabel) DESC";
            
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