<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="textstyle.css">
  <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <title>Detail Pahlawan</title>
</head>

<body>
<?php include ("nav.php");
 require_once 'vendor/autoload.php';
 require_once __DIR__ . "/vendor/easyrdf/easyrdf/lib/Graph.php";
require_once __DIR__ . "/vendor/easyrdf/easyrdf/lib/GraphStore.php";
 use EasyRdf\Sparql\Client;

 \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
 \EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
 \EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
 \EasyRdf\RdfNamespace::set('pahlawan', 'http://www.tubeswebsemantik/pahlawan#');
 \EasyRdf\RdfNamespace::set('dbc', 'http://dbpedia.org/resource/Category:');
\EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
\EasyRdf\RdfNamespace::set('dbr', 'http://dbpedia.org/resource/');
\EasyRdf\RdfNamespace::set('dbp', 'http://dbpedia.org/property/');
\EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
\EasyRdf\RdfNamespace::set('geo', 'http://www.w3.org/2003/01/geo/wgs84_pos#');
$sparqlDBp = new Client('http://dbpedia.org/sparql');
$sparqlEndpoint = 'http://localhost:3030/pahlawan/query';
$sparql = new Client($sparqlEndpoint);
 $nama = $_GET['orang'];
 $query = "
 SELECT ?pulau_maps ?tempat_lahir ?tempat_wafat ?wiki ?deskripsi ?tanggallahir ?tanggalwafat
WHERE {
  ?subject rdfs:label ?id_label .
  FILTER (lang(?id_label) = 'id')
  FILTER (regex(?id_label, '$nama', 'i'))
  OPTIONAL {
    ?subject pahlawan:pulau_maps ?pulau_maps .
    ?subject pahlawan:tempat_lahir ?tempat_lahir .
    ?subject pahlawan:tempat_wafat ?tempat_wafat . 
    ?subject pahlawan:link_wikipedia ?wiki .
    ?subject pahlawan:deskripsi ?deskripsi .
    ?subject pahlawan:tanggal_wafat ?tanggalwafat .
    ?subject pahlawan:tanggal_lahir ?tanggallahir .
  }
}
";
$results = $sparql->query($query);

$query1 = "
SELECT ?subject ?label ?deathPlace ?deathDate ?birthDate ?birthPlace ?abstract ?lat ?long ?wiki
WHERE {
  VALUES ?labelPredicate { rdfs:label dbp:name foaf:name }
  ?subject ?labelPredicate ?label .
  FILTER regex(?label, '$nama', 'i') 
  FILTER (LANGMATCHES(LANG(?label), 'en'))
  
  OPTIONAL { ?subject dbp:deathPlace ?deathPlace }
  OPTIONAL { ?subject dbp:deathDate ?deathDate }
  OPTIONAL { ?subject dbp:birthDate ?birthDate } 
  OPTIONAL { ?subject dbp:birthPlace ?birthPlace }
  OPTIONAL { ?subject dbo:abstract ?abstract }
  OPTIONAL { ?subject geo:lat ?lat }
  OPTIONAL { ?subject geo:long ?long }
  OPTIONAL { ?subject foaf:isPrimaryTopicOf ?wiki }
   
  ?subject dbp:wikiPageUsesTemplate dbt:National_Heroes_of_Indonesia
} 
LIMIT 1";

$resultDBp = $sparqlDBp->query($query1);

// Initialize $foto_url to a default value
$foto_url ='pp.jpeg';

// Check if $resultDBp has a link_wiki
if (!empty($resultDBp[0]->wiki)) {
    // Use link_wiki for Open Graph operations
    \EasyRDF\RdfNamespace::setDefault('og');
    $wiki = \EasyRdf\Graph::newAndLoad($resultDBp[0]->wiki);
    $foto_url = $wiki->image;
} elseif (!empty($results[0]->wiki)) {
    // Use Wikipedia link if link_wiki is not available in $resultDBp
    \EasyRDF\RdfNamespace::setDefault('og');
    $wikis = \EasyRdf\Graph::newAndLoad($results[0]->wiki->getValue());
    $foto_url = $wikis->image;
}
  ?>
  <div class="kontener">
    <div class="pahlawan-details">
      <div class="pahlawan-image">
      <?php if (!empty($foto_url)) : ?>
    <img src="<?= $foto_url ?>" alt="Nama Pahlawan">
<?php else : ?>
    <img src="pp.jpeg" alt="Nama Pahlawan Default">
<?php endif; ?>
      </div>
      <div class="pahlawan-info">
        
      <h1><?php echo $nama; ?></h1>

      <p>
  <?php
    $abstract = !empty($resultDBp[0]->abstract) ? $resultDBp[0]->abstract : null;
    $deskripsi = !empty($results[0]->deskripsi) ? $results[0]->deskripsi : null;

    if (!is_null($abstract) || !is_null($deskripsi)) {
      echo !is_null($abstract) ? $abstract : $deskripsi;
    } else {
      echo "Tidak ada deskripsi";
    }
  ?>
</p>
        <div class="col-md-8">
            <table class="table table-striped txtable">
              <tbody>
                <tr>
                  <th class = "txth">Nama</th>
                  <td class = "txtd"><?php echo $nama; ?></td>
                </tr>
                <tr>
                  <th class = "txth">Tempat Lahir</th>
                  <td class = "txtd"><?php
$birthPlace = !empty($resultDBp[0]->birthPlace) ? $resultDBp[0]->birthPlace : null;

if (!is_null($birthPlace)) {
    echo $birthPlace;
} else {
    // Additional condition for checking $results tempat_lahir
    $tempatLahir = !empty($results[0]->tempat_lahir) ? $results[0]->tempat_lahir : null;
    echo !is_null($tempatLahir) ? $tempatLahir : 'Tempat Lahir Tidak Tersedia';
}
?></td>
                </tr>
                <tr>
                  <th class = "txth">Tempat Wafat</th>
                  <td class = "txtd"><?php
$deathPlace = !empty($resultDBp[0]->deathPlace) ? $resultDBp[0]->deathPlace : null;

if (!is_null($deathPlace)) {
    echo $deathPlace;
} else {

    $tempatwafat = !empty($results[0]->tempat_wafat) ? $results[0]->tempat_wafat : null;
    echo !is_null($tempatwafat) ? $tempatwafat : 'Tempat wafat Tidak Tersedia';
}
?></td>
                </tr>
                <tr>
                  <th class = "txth">Tanggal Lahir</th>
                  <td class = "txtd"><?php
$birthdate = !empty($resultDBp[0]->birthDate) ? $resultDBp[0]->birthDate : null;

if (!is_null($birthdate)) {
    echo $birthdate;
} else {

    $tanggallahir = !empty($results[0]->tanggallahir) ? $results[0]->tanggallahir : null;
    echo !is_null($tanggallahir) ? $tanggallahir : 'Tanggal lahir Tidak Tersedia';
}
?></td>
                </tr>
                <tr>
                  <th class = "txth">Tanggal Wafat</th>
                  <td class = "txtd"><?php
$deathdate = !empty($resultDBp[0]->deathDate) ? $resultDBp[0]->deathDate : null;

if (!is_null($deathdate)) {
    echo $deathdate;
} else {

    $tanggalwafat = !empty($results[0]->tanggalwafat) ? $results[0]->tanggalwafat : null;
    echo !is_null($tanggalwafat) ? $tanggalwafat : 'Tanggal wafat Tidak Tersedia';
}
?></td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>
    </div>
    <div class="additional-info">
      <div class="google-maps">
        <h2>Tempat Lahir</h2>
        <?php

if (!empty($results[0]->pulau_maps)) {
  echo '<iframe src="' . $results[0]->pulau_maps . '" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';

} else if (!empty($resultDBp[0]->lat) && !empty($resultDBp[0]->long)) {
  
  $lat = (float) $resultDBp[0]->lat;
  $long = (float) $resultDBp[0]->long;

?>

<div id="mapid" style="height: 400px;"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

var map = L.map('mapid').setView([<?php echo $lat; ?>, <?php echo $long; ?>], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

L.marker([<?php echo $lat; ?>, <?php echo $long; ?>]).addTo(map)
  .bindPopup('Lokasi Ini!').openPopup();

</script>

<?php

} else {

?>

<div id="mapid" style="height: 400px;"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

var map = L.map('mapid').setView([0.7893, 113.9213], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors' 
}).addTo(map);

// You can add additional customization or markers for (0, 0) if needed.

</script>

<?php

}

?>
      </div>
    </div>
  </div>
</body>
<?php require("footer.php"); ?>
</html>
