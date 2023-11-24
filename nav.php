<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="textstyle.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Goudy+Bookletter+1911&display=swap" rel="stylesheet">
    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Schoolbell&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/27ec7e7fc6.js" crossorigin="anonymous"></script>
    <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
  
<nav class="navbar navbar-expand-lg navbar-dark bg-nav ">
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php">
            <img src = "assets/newlogo 1.png">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 custom-font">
             <li class="nav-item bg-text">
                <a class="nav-link" href="pahlawan_nasional.php">Pahlawan Nasional</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle bg-text " href="pahlawan_daerah.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Pahlawan Daerah
                </a>
                <form id="regionForm" action="pahlawan_daerah.php" method="get">
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="pahlawan_daerah.php?daerah=Timur_Indonesia">Timur Indonesia</a></li>
        <li><a class="dropdown-item" href="pahlawan_daerah.php?daerah=Tengah_Indonesia">Tengah Indonesia</a></li>
        <li><a class="dropdown-item" href="pahlawan_daerah.php?daerah=Barat_Indonesia">Barat Indonesia</a></li>
    </ul>
</form>
              </li>

            </ul>
            <form action="searchada.php" method="post" name="searchForm">
            <div class="box">
                <i class="fas fa-search"></i>
                <input type="text" name="searchTerm" placeholder="Nama/Jenis Pahlawan">
                <button type="submit" name="submitSearch">Search</button>
            </div>
        </form>
          </div>
        </div>
      </nav>
</body>    
</html>



