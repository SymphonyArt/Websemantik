<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="textstyle.css">
  <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <title>Detail Pahlawan</title>
</head>

<body>
<?php include ("nav.php")?>
  <div class="kontener">
    <div class="pahlawan-details">
      <div class="pahlawan-image">
        <img src="assets/sukarno.jpg" alt="Nama Pahlawan">
      </div>
      <div class="pahlawan-info">
        <h1>Nama Pahlawan</h1>
        <p>Deskripsi Pahlawan. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        <div class="col-md-8">
            <table class="table table-striped txtable">
              <tbody>
                <tr>
                  <th class = "txth">Nama</th>
                  <td class = "txtd">[Your Name]</td>
                </tr>
                <tr>
                  <th class = "txth">Lahir</th>
                  <td class = "txtd">[Your Date of Birth]</td>
                </tr>
                <tr>
                  <th class = "txth">Wafat</th>
                  <td class = "txtd">[Your Date of Death]</td>
                </tr>
                <tr>
                  <th class = "txth">Berasal dari Provinsi</th>
                  <td class = "txtd">[Your Province]</td>
                </tr>
                <tr>
                  <th class = "txth">Keterangan</th>
                  <td class = "txtd">[Your Bio]</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>
    </div>
    <div class="additional-info">
      <div class="youtube-video">
        <h2>Video Pahlawan</h2>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
      </div>
      <div class="google-maps">
        <h2>Tempat Lahir</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12345.678910111213!2dLONGITUDE!3dLATITUDE!4d0.0000001!5sPlace+Name!2m2!1dLATITUDE!2dLONGITUDE!5e0!3m2!1sen!2sus!4v1234567890!5m2!1sen!2sus" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>
  </div>
</body>

</html>
