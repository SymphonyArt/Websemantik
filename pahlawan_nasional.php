<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="textstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Pahlawan Nasional</title>
</head>
<body>
    <!-- Navbar -->
    <?php 
    include ("nav.php");
    require_once 'vendor/autoload.php';

    \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    \EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    \EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    \EasyRdf\RdfNamespace::set('pahlawan', 'http://www.tubeswebsemantik/pahlawan#');

    $sparqlEndpoint = 'http://localhost:3030/tubes/query';

    $sparql = new \EasyRdf\Sparql\Client($sparqlEndpoint);

    // Jumlah item per halaman
    $itemsPerPage = 30;
    
    // Halaman yang diminta, atau default ke halaman pertama
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Hitung offset untuk hasil query
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Sesuaikan query dengan LIMIT dan OFFSET
    $query = "
        SELECT ?subject (SAMPLE(?label) AS ?uniqueLabel)
        WHERE {
            ?subject rdfs:label ?label
        }
        GROUP BY ?subject 
        LIMIT $itemsPerPage OFFSET $offset";

    $results = $sparql->query($query);

    // Hitung total item dalam dataset
    // Hitung total item dalam dataset
$totalItemsQuery = "
SELECT (COUNT(?subject) AS ?totalItems)
WHERE {
    ?subject rdfs:label ?label
}";
$totalItemsResult = $sparql->query($totalItemsQuery);
$totalItemsLiteral = $totalItemsResult[0]->totalItems;

// Convert the EasyRdf\Literal\Integer to a regular integer
$totalItems = $totalItemsLiteral->getValue();

// Hitung total halaman
$totalPages = ceil($totalItems / $itemsPerPage);

    ?>

    <!-- end of nav -->

    <div class="container">
        <div class="category">
            <span>Pahlawan Nasional</span>
        </div>
        <div class="table-responsive">
            <div class="containerB mt-5 custom-bg-container">
                <table class="table mx-auto custom-table">
                    <tbody>
                        <?php foreach ($results as $result) : ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $result->uniqueLabel; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
    <?php
// Tampilkan tombol navigasi
$maxPages = min(6, $totalPages);  // Set the maximum number of pages to display
$startPage = max(1, min($currentPage - floor($maxPages / 2), $totalPages - $maxPages + 1));
$endPage = min($startPage + $maxPages - 1, $totalPages); // Updated line

// Ensure that $endPage does not exceed 6
$endPage = min($endPage, 6); // Added line

for ($i = $startPage; $i <= $endPage; $i++) {
    echo "<li class='page-item" . ($i === $currentPage ? " active" : "") . "'><a class='page-link' href='?page=$i'>$i</a></li>";
}
?>

    </ul>
</nav>
        <!-- End Pagination -->
    </div>

</body>
</html>
