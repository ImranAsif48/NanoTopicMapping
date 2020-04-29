<?php

require_once 'config.php';
header('Access-Control-Allow-Origin: *');

$topic = '';

if(isset($_GET['iri']))
{
    $iri = $_GET['iri'];
    $topic = "t.IRI = '$iri'";
}
else if(isset($_GET['lbl']))
{
    $label = $_GET['lbl'];
    $topic = "rdfsLabel like '$label%'";
}
else if(isset($_GET['proteinName']))
{
     $label = $_GET['proteinName'];
     $topic = "t.rdfsLabel like '%$label%'";
}
else if(isset($_GET['gene']))
{
    
}
else if(isset($_GET['topic']))
{
    $t = $_GET['topic'];
    if($t=='WikiPathways Interaction')
    {
        $topic = "t.IRI like '%Interaction%'";
    }
    else if($t=='WikiPathways Complex')
    {
        $topic = "t.IRI like '%Complex%'";
    }
    else if($t=='Gene')
    {
        $topic = "t.IRI like '%gene%' or t.IRI like '%ensembl%'";
    }
    else if($t=='Molecule')
    {
        $topic = "t.IRI like '%chebi%'";
    }
    else if($t=='Metabolome')
    {
        $topic = "t.IRI like '%hmdb%'";
    }
    else if($t=='Genome')
    {
        $topic = "t.IRI like '%rgd%'";
    }
    else if($t=='microRNAs')
    {
        $topic = "t.IRI like '%mirbase%'";
    }
    else if($t=='Protein')
    {
        $topic = "t.IRI like '%uniprot%' or t.IRI like '%nextprot%'";
    }
    else if($t=='Biological')
    {
        $topic = "t.IRI like '%bel2nanopub%'";
    }
    else if($t=='Gene Disease Association (GDAs)')
    {
        $topic = "t.IRI like '%rdf.disgenet%'";
    }
    else if($t=='Drugs')
    {
        $topic = "t.IRI like '%LIDDI_%'";
    }
}

$data = array();

$query = "Select * from topic t inner join nanopubs np on t.npId = np.npId where $topic";
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
?>

