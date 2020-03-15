<?php

require_once 'config.php';
header('Access-Control-Allow-Origin: *');

class jsonData{
    public $name = '';
    public $children = array();
}

$objMain = new jsonData;
$objMain->name = "Nano Cluster";
/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////// WikiPathways
$obj = new jsonData;
$obj->name = 'WikiPathways';
$viewName = 'vselfclusterwp';
// Get All intractions
$query = "Select * from $viewName where IRI like '%Interaction%'";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    $objInt = new jsonData;
    $objInt->name = 'Interaction';
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objInt->children, $array);
    }
    
    array_push($obj->children, $objInt);
}

// Get All Complexes
$query = "Select * from $viewName where IRI like '%Complex%'";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{ 
    $objComp = new jsonData;
    $objComp->name = 'Complex';
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objComp->children, $array);
    }
    
    array_push($obj->children, $objComp);
}


// Get All Gene
$query = "Select * from $viewName where IRI like '%gene%' OR IRI Like '%ensembl%'";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{ 
    $objGene = new jsonData;
    $objGene->name = 'Gene';
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objGene->children, $array);
    }
    
    array_push($obj->children, $objGene);
}

// Get All Molecule
$query = "Select * from $viewName where IRI like '%chebi%'";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{ 
    $objMolecule = new jsonData;
    $objMolecule->name = 'Molecule';
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objMolecule->children, $array);
    }
    
    array_push($obj->children, $objMolecule);
}

// Get All Metabolome
$query = "Select * from $viewName where IRI like '%hmdb%'";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{ 
    $objMetabolome = new jsonData;
    $objMetabolome->name = 'Metabolome';
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objMetabolome->children, $array);
    }
    
    array_push($obj->children, $objMetabolome);
}

// Get All Rate Genome
$query = "Select * from $viewName where IRI like '%rgd%'";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{ 
    $objRat = new jsonData;
    $objRat->name = 'Rate Genome';
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objRat->children, $array);
    }
    
    array_push($obj->children, $objRat);
}

// Get All microRNAs
$query = "Select * from $viewName where IRI like '%mirbase%'";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{ 
    $objmicroRNAs = new jsonData;
    $objmicroRNAs->name = 'microRNAs';
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objmicroRNAs->children, $array);
    }
    
    array_push($obj->children, $objmicroRNAs);
}

// Get All Protein
$query = "Select * from $viewName where IRI like '%uniprot%'";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{ 
    $objProtein = new jsonData;
    $objProtein->name = 'Protein';
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objProtein->children, $array);
    }
    
    array_push($obj->children, $objProtein);
}

//Get All others
$query = "Select * from $viewName where IRI NOT like '%Complex%' "
        . "AND IRI NOT like '%Interaction%' "
        . "AND IRI NOT like '%gene%' "
        . "AND IRI NOT like '%uniprot%' "
        . "AND IRI NOT like '%mirbase%' "
        . "AND IRI NOT like '%rgd%' "
        . "AND IRI NOT like '%hmdb%' "
        . "AND IRI NOT like '%chebi%' "
        . "AND IRI NOT like '%ensembl%' ";

$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{ 
    $objOther = new jsonData;
    $objOther->name = 'Other';
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objOther->children, $array);
    }
    
     array_push($obj->children, $objOther);
}

array_push($objMain->children, $obj);

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//=======================================================================================================
// LIDDI (vselfclusterliddi)
$objLIDDI = new jsonData;
$objLIDDI->name = 'LIDDI';
$viewName = 'vselfclusterliddi';

$query = "Select * from $viewName";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objLIDDI->children, $array);
    }
}
array_push($objMain->children, $objLIDDI);
//////////////////////////////////////////////////////////////////////////////////////////
//======================================================================================
// neXt Prot (vSelfClusterNextProt)
$objnext = new jsonData;
$objnext->name = 'neXtProt';
$viewName = 'vSelfClusterNextProt';
$query = "Select * from $viewName";
$result = mysqli_query($con, $query);
if(mysqli_num_rows($result) > 0)
{    
    while($row = mysqli_fetch_assoc($result))
    {
        $array = array();
        $array = array('name' => $row["IRI"], 'value' => (int)$row["GroupCount"]);
        array_push($objnext->children, $array);
    }
}
array_push($objMain->children, $objnext);

//====================================================================================
echo json_encode($objMain); // All data in on json file.
?>