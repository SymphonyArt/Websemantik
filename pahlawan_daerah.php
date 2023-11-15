<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="textstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Pahlawan Daerah</title>
</head>
<body>
    
    <?php
    include('nav.php');
    require_once 'vendor/autoload.php';
    \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    \EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    \EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    \EasyRdf\RdfNamespace::set('pahlawan', 'http://www.tubeswebsemantik/pahlawan#');

    $sparqlEndpoint = 'http://localhost:3030/fix/query';

    $daerah = $_GET['daerah'];

    $sparql = new \EasyRdf\Sparql\Client($sparqlEndpoint);
    $results = [];
    if (isset($_GET['daerah'])) {

        // Menentukan query berdasarkan variabel region
    
                $query = "
                    SELECT ?individual ?idLabel
                    WHERE {
                        ?individual pahlawan:Asal pahlawan:$daerah .
                        ?individual rdfs:label ?label .
                        OPTIONAL {
                            ?individual rdfs:label ?idLabel.
                            FILTER (LANG(?idLabel) = 'id')
                          }
                    }
                    GROUP BY ?individual ?idLabel";
        

        $results = $sparql->query($query);
    }
    ?>
    <div class="container">
        <div class="category">
            <span>Pahlawan Daerah</span>
        </div>
        <div class="table-responsive">
            <div class="containerB mt-5 custom-bg-container">
                <table class="table mx-auto custom-table">
                    <thead>
                        <tr>
                            <th class="text-center">Nama Pahlawan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($results as $result) {
                            echo '<tr>';
                            echo '<td class="text-center">' . $result->idLabel . '</td>';
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
