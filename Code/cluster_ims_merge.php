<?php
 require_once 'config.php';
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
 ///////////////////////////////////////////
 /// For Exact match
 $query = "SELECT * FROM exact_match_iri_merge";
 $resExact = mysqli_query($con, $query);
 $spanLeft = '';
 $exactArray = array();
 if(mysqli_num_rows($resExact) > 0)
 {    
     while($rowExact = mysqli_fetch_assoc($resExact))
     {
        $exactArray[] = $rowExact;
     }
 }
 //echo json_encode($exactArray);
//  $resMapping = array_filter($exactArray, function($iri) {
//     return $iri['topicIRI'] == 'http://www.nextprot.org/db/search#NX_P35354';
//   });
  
//   foreach ($resMapping as $k => $v) {
//     echo '<br />' . $v['mappingIRI'] . ' ' . $v['total_nano_2'];
//   }
//   var_dump($resMapping);
//   echo count($resMapping);
//   echo $resMapping[0]["mappingIRI"];
//   echo '<br /> <br />';
//   foreach ($resMapping as $k => $v) {
//     echo "\$resMapping[$k] => $v";
//     echo '<br />';
//     echo $v['mappingIRI'] . ' ' . $v['total_nano_2'];
//     echo '<br />';

//  }
// DB table to use
$table = 'ims_merge_cluster';
 
// Table's primary key
$primaryKey = 'c_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'topicIRI', 'dt' => 0, 
           'formatter' => function( $d, $row ) {
            $display_content = '';
            $resolve_url = '';
            
            if(strrpos($d, "www.nextprot")>0)
            {
                $display_content = "nextprot:". explode("#",$d)[1];
                $resolve_url = "https://www.nextprot.org/entry/". explode("#",$d)[1];
            }
            else if(strrpos($d,"identifiers.org")>0){
                $splitIRI = explode("/",$d);
                $display_content = "id-". $splitIRI[count($splitIRI) - 2] . ":" . $splitIRI[count($splitIRI) - 1];
                $resolve_url = $d;
            }
            return '<a href="'.$resolve_url.'" target="_blank">'. $display_content .'</a>'  ;
        }
    ),
    array( 'db' => 'nano_count',  'dt' => 1,
    'formatter' => function( $d, $row ) {
        $totalNano = $row["nano_count"];
        $display_content = '';
        $resolve_url = '';
            if(strrpos($row["topicIRI"], "www.nextprot")>0)
            {
                $display_content = "nextprot:". explode("#",$row["topicIRI"])[1];
                $resolve_url = "https://www.nextprot.org/entry/". explode("#",$row["topicIRI"])[1];
            }
            else if(strrpos($row["topicIRI"],"identifiers.org")>0){
                $splitIRI = explode("/",$row["topicIRI"]);
                $display_content = "id-". $splitIRI[count($splitIRI) - 2] . ":" . $splitIRI[count($splitIRI) - 1];
                $resolve_url = $row["topicIRI"];
            }
            return '<span style="cursor:pointer;text-decoration:underline;color:#007bff" onclick="AJAXCallForNano(\''.$row["topicIRI"].'\', '.$totalNano.', \''.$display_content.'\', \''.$resolve_url.'\')">'. $row["nano_count"] .'</span>';
         }  
    ),
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'ia48',
    'pass' => '7INB446Kle',
    'db'   => 'ia48',
    'host' => 'www.macs.hw.ac.uk'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
//var_dump($columns);
echo json_encode(
    SSP::ims_merge( $_GET, $sql_details, $table, $primaryKey, $columns, $exactArray )
);