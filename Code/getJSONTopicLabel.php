<?php
require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$data = array();

// $query = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1) as Label,
//                  COUNT(SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1)) as GroupCount 
//             FROM topic t INNER JOIN nanopubs np on t.npId = np.npId 
//             GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1)
//             HAVING (COUNT(SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1)) >= 2) ";
$query = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1) as Label,
                 COUNT(SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1)) as GroupCount 
            FROM topic t
            GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1)
            HAVING (COUNT(SUBSTRING_INDEX(SUBSTRING_INDEX(t.rdfsLabel, ' ( ', 1), ' ( ', -1)) >= 2) ";


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