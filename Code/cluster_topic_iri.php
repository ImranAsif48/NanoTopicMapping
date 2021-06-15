<?php

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

// DB table to use
$table = 'new_topic_iri_cluster';

// Table's primary key
$primaryKey = 'topicIRI';

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
                if(count(explode("#",$d))>1)
                {
                    $display_content = "nextprot:". explode("#",$d)[1];
                    $resolve_url = "https://www.nextprot.org/entry/". explode("#",$d)[1];
                }
                else{
                    $splitIRI = explode("/",$d);
                    $display_content = "nextprot:". $splitIRI[count($splitIRI )-1];
                    $resolve_url = $d;
                }
            }
            else if(strrpos($d, "linkedlifedata.com")>0)
            {
                $splitIRI = explode("/",$d);
                $display_content = "linkedlifedata:". $splitIRI[count($splitIRI )-1];
                $resolve_url = $d;
            }
            else if(strrpos($d,"identifiers.org")>0){
                $splitIRI = explode("/",$d);
                $display_content = "id-". $splitIRI[count($splitIRI) - 2] . ":" . $splitIRI[count($splitIRI) - 1];
                $resolve_url = $d;
            }
                    return 'Group (<a href="'.$resolve_url.'" target="_blank">'. $display_content .'</a>)' ;
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

                if(count(explode("#",$row["topicIRI"]))>1)
                {
                    $display_content = "nextprot:". explode("#",$row["topicIRI"])[1];
                    $resolve_url = "https://www.nextprot.org/entry/". explode("#",$row["topicIRI"])[1];
                }
                else{
                    $splitIRI = explode("/",$row["topicIRI"]);
                    $display_content = "nextprot:". $splitIRI[count($splitIRI )-1];
                    $resolve_url = $d;
                }
            }
            else if(strrpos($row["topicIRI"], "linkedlifedata.com")>0)
            {
                $splitIRI = explode("/",$row["topicIRI"]);
                $display_content = "linkedlifedata:". $splitIRI[count($splitIRI )-1];
                $resolve_url = $d;
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
    //'host' => 'www.macs.hw.ac.uk'
    'host' => 'mysql-server-1'
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
