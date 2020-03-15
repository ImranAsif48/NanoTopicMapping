<?php
require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$interaction_count = 0;
$complex_count = 0;
$gene_count = 0;
$molecule_count = 0;
$metabolome_count = 0;
$genome_count = 0;
$microRNAs_count = 0;
$protein_count = 0;
$diseas_count = 0;
$drugs_count = 0;
$biological_count = 0;

$query = "SELECT * FROM topic t";

$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
       //$data[]=$row;
       if(strpos($row['IRI'], 'Interaction') == true)
       {
            $interaction_count++;
       }
       if(strpos($row['IRI'], 'Complex') == true)
       {
            $complex_count++;
       }
       if(strpos($row['IRI'], 'gene') == true || strpos($row['IRI'], 'ensembl') == true)
       {
            $gene_count++;
       }
       if(strpos($row['IRI'], 'chebi') == true)
       {
            $molecule_count++;
       }
       if(strpos($row['IRI'], 'hmdb') == true)
       {
            $metabolome_count++;
       }
       if(strpos($row['IRI'], 'rgd') == true)
       {
            $genome_count++;
       }
       if(strpos($row['IRI'], 'mirbase') == true)
       {
            $microRNAs_count++;
       }
       if(strpos($row['IRI'], 'uniprot') == true || strpos($row['IRI'], 'nextprot') == true)
       {
            $protein_count++;
       }
       if(strpos($row['IRI'], 'bel2nanopub') == true)
       {
            $biological_count++;
       }
       if(strpos($row['IRI'], 'rdf.disgenet') == true)
       {
            $diseas_count++;
       }
       if(strpos($row['IRI'], 'LIDDI_') == true)
       {
            $drugs_count++;
       }
    }

//////////////////////////////////////////////////////////////
$data = array();
class jsonData{
        public $name = '';
        public $count = 0;
}
$obj = new jsonData;
$obj->name = "WikiPathways Interaction";
$obj->count = $interaction_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "WikiPathways Complex";
$obj->count = $complex_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "Gene";
$obj->count = $gene_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "Molecule";
$obj->count = $molecule_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "Metabolome";
$obj->count = $metabolome_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "Genome";
$obj->count = $genome_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "microRNAs";
$obj->count = $microRNAs_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "Protein";
$obj->count = $protein_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "Biological";
$obj->count = $biological_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "Gene Disease Association (GDAs)";
$obj->count = $diseas_count;
array_push($data, $obj);

$obj = new jsonData;
$obj->name = "Drugs";
$obj->count = $drugs_count;
array_push($data, $obj);
}

echo json_encode($data);
?>