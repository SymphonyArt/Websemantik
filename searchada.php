<?php
require_once 'vendor/autoload.php';

use EasyRdf\Sparql\Client;

\EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
\EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
\EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
\EasyRdf\RdfNamespace::set('pahlawan', 'http://www.tubeswebsemantik/pahlawan#');

$sparqlEndpoint = 'http://localhost:3030/tubes/query';
$sparql = new Client($sparqlEndpoint);

if (isset($_POST['submitSearch'])) {
    $searchTerm = $_POST['searchTerm'];

    if (strpos($searchTerm, '_') !== false) {
        $query = "
        SELECT ?individual (SAMPLE(?label) as ?sampleLabel)
        WHERE {
            ?individual rdf:type pahlawan:$searchTerm.
            ?individual rdfs:label ?label.
        }
        GROUP BY ?individual
        ";
    } else {
        $query = "
        SELECT ?individual (SAMPLE(?label) as ?sampleLabel)
        WHERE {
            ?individual rdfs:label ?label.
            FILTER (
                CONTAINS(UCASE(?label), UCASE('$searchTerm'))
            )
        }
        GROUP BY ?individual
        ";
    }
    $results = $sparql->query($query);}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search ada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/27ec7e7fc6.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-image: url("bgopung.png");
            background-repeat: no-repeat;
            background-size: cover;
        }

        td {
            border-radius: 8px 12px 8px 12px;
            overflow: hidden;
            padding: 8px 12px 8px 12px;
        }

        .box {
            margin: 50px;
            height: 50px;
            display: flex;
            padding: 10px 0px 10px 20px;
            cursor: pointer;
            background: #F9F4EC;
            border-radius: 90px;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 3)
        }

        .box input {
            width: 100%;
            outline: none;
            border: none;
            font-weight: 500;
            background: transparent;
        }

        .box button {
            background-color: #BCE5F9;
            margin: 0;
            padding: 10px 30px;
            border-radius: 90px;
            border: none;
            font-weight: 500;
            outline: none;
            letter-spacing: 1px;
            cursor: pointer;
        }

        .custom-table {
            border-collapse: separate;
            border-spacing: 10px;
            padding: 8px 12px 8px 12px;
        }

        .custom-bg-container {
            background-color: #53748F;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include("nav.php") ?>
<div class="container">
    <div class="table-responsive">
        <div class="containerB mt-5 custom-bg-container">
            <table class="table mx-auto custom-table">
                <tbody>
                <?php
foreach ($results as $result) {
    echo '<tr>';
    if (property_exists($result, 'sampleLabel')) {
        echo '<td class="text-center">' . $result->sampleLabel . '</td>';
    } else {
        echo '<script>';
    echo 'alert("Data not found!");';  
    echo 'window.location.href = "index.php";';
    echo '</script>';
    }
    echo '</tr>';
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

