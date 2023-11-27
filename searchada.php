<?php
require_once 'vendor/autoload.php';

use EasyRdf\Sparql\Client;

\EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
\EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
\EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
\EasyRdf\RdfNamespace::set('pahlawan', 'http://www.tubeswebsemantik/pahlawan#');
\EasyRdf\RdfNamespace::set('dbc', 'http://dbpedia.org/resource/Category:');
\EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
\EasyRdf\RdfNamespace::set('dbpedia', 'http://dbpedia.org/property/');
\EasyRdf\RdfNamespace::set('dbr', 'http://dbpedia.org/resource/');
\EasyRdf\RdfNamespace::set('gold', 'http://purl.org/linguistics/gold/');
\EasyRdf\RdfNamespace::set('dbp', 'http://dbpedia.org/property/');
\EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
\EasyRdf\RdfNamespace::set('geo', 'http://www.w3.org/2003/01/geo/wgs84_pos#');
\EasyRdf\RdfNamespace::set('dbt', 'http://dbpedia.org/resource/Template:');
$sparqlDBp = new \EasyRdf\Sparql\Client('http://dbpedia.org/sparql');
$sparqlEndpoint = 'http://localhost:3030/pahlawan/query';
$sparql = new Client($sparqlEndpoint);
    $searchTerm = $_POST['searchTerm'];
    
    if (strpos($searchTerm, '_') !== false) {
        $query = "
        SELECT ?individual ?idLabel
        WHERE {
          ?individual rdf:type pahlawan:$searchTerm .
          ?individual rdfs:label ?label .
          OPTIONAL {
            ?individual rdfs:label ?idLabel.
            FILTER (LANG(?idLabel) = 'id')
          }
        } 
        GROUP BY ?individual ?idLabel";
      
    } else {
      
        $query = "     
        SELECT ?individual ?idLabel  
        WHERE {
          ?individual rdfs:label ?label .
          FILTER (CONTAINS(UCASE(?label), UCASE('$searchTerm'))).   
        
          OPTIONAL {
            ?individual rdfs:label ?idLabel . 
            FILTER(lang(?idLabel) = 'id').
          }
        }
        GROUP BY ?individual ?idLabel
      ";
      
    }
    
    $results = $sparql->query($query);
    
    if (count($results) == 0) {
        $queryDBp = "
        SELECT DISTINCT ?label 
        WHERE {
          {
            SELECT DISTINCT ?subject ?label 
            WHERE {
              ?subject foaf:name ?label .
              FILTER (LANGMATCHES(LANG(?label), 'en'))
            }
          }
          UNION 
          {
            SELECT DISTINCT ?subject ?label
            WHERE {
              ?subject dbp:name ?label . 
              FILTER (LANGMATCHES(LANG(?label), 'en'))
            }
          } 
          UNION
          {
            SELECT DISTINCT ?subject ?label
            WHERE {
              ?subject rdfs:label ?label .
              FILTER (LANGMATCHES(LANG(?label), 'en')) 
            }
          }
          
          FILTER regex(?label, '$searchTerm', 'i')
          ?subject dbp:wikiPageUsesTemplate dbt:National_Heroes_of_Indonesia. 
        }";
    
        $resultDBp = $sparqlDBp->query($queryDBp);
    }
      
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<title>Pahlawan </title>
</head>
<body>
<?php include("nav.php") ?>
<div class="container">
    <div class="table-responsive">
        <div class="containerB mt-5 custom-bg-container">
            <table class="table mx-auto custom-table">
                <tbody>
                <?php
  
         
  $labels = array(); // Initialize an empty array to store labels
                
  // Process results from the first SPARQL query ($results)
  if (!empty($results)) {
      foreach ($results as $r) {
          if (isset($r->idLabel)) {
              // Pastikan $label bertipe string
              $label = (string)$r->idLabel;
              if (!empty($label)) {
                  $labels[$label] = 'Local';
              }
          }
      }
  }
  
  // Process results from the second SPARQL query ($resultDBp)
  if (!empty($resultDBp) ) {
      foreach ($resultDBp as $r) {
          $label = (string)$r->label;
          if (!empty($label)) {
          
              if (!isset($labels[$label])) {
                  $labels[$label] = 'DBpedia';
              }
          }
      }
  }

  if (empty($labels)) {
      echo '<script>';
      echo 'Swal.fire({';
      echo '  title: "Gagal!",';
      echo '  text: "Data yang kamu cari tidak ada.",';
      echo '  icon: "error",';
      echo '  confirmButtonText: "OK"';
      echo '}).then(function() {';
      echo '  window.location.href = "index.php";';
      echo '});';
      echo '</script>';
  } else {
      // Buat formulir di luar loop jika $labels tidak kosong
      echo '<form id="regionForm" action="detail.php" method="get">';
      foreach ($labels as $label => $source) {
          echo '<tr>';
          echo '<td class="text-center">';
          echo '<a href="detail.php?orang=' . $label . '&source=' . $source . '" style="color: black; text-decoration: none;">';
          echo $label . ' (' . $source . ')';
          echo '</a>';
          echo '</td>';
          echo '</tr>';
      }
      echo '</form>';
  } 
         ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
<?php require("footer.php"); ?>
</html>
