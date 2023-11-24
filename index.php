<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Semantik Kelompok 3</title>
</head>
<body>
    <?php require("nav.php"); ?>
    <div class="container">
        <div class="newcontainer">
            <div class="homepagetext">
                <h1><b>M &nbsp;E &nbsp;R &nbsp;D &nbsp;E &nbsp;K &nbsp;A</b></h1>
            </div>
            <div class="homepagetextsecond">
                <h4><p>Dengan rasa hormat dan terima kasih, mari rayakan jasa-jasa pahlawan kita</p></h4>
            </div>
        </div>
        
        <form action="searchada.php" method="post" name="searchForm">
            <div class="box">
                <i class="fas fa-search"></i>
                <input type="text" name="searchTerm" placeholder="Nama/Jenis Pahlawan">
                <button type="submit" name="submitSearch">Search</button>
            </div>
        </form>
    </div>
</body>
<?php require("footer.php"); ?>
</html>
