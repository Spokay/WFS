<?php

?>

<!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- custom CSS -->
    <link rel="stylesheet" href="assets/css/rain.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>WFS</title>
</head>
<body>
<?php
if (empty($_GET)){
?>
    <div class="rain front-row"></div>
    <div class="rain back-row"></div>

    <div>
        <?php
        include("inc/header.inc.php");
        ?>
        <div class="row d-flex justify-content-center align-items-center button-container">
            <form class="form my-2 my-lg-0 col-6" method="get" enctype="multipart/form-data">
                <input class="form-control mr-sm-2 mb-4 btn-block" type="search" placeholder="Search" aria-label="Search" name="search" id="search">
                <button class="btn btn-primary btn-block my-2 my-sm-0" type="submit" id="button">Search</button>
            </form>
        </div>
        <?php
        include("inc/footer.inc.php");
        ?>

    </div>


<?php
}if(isset($_GET['search'])){
    /* récupère le text en json de l'API */
    /*$homepage = file_get_contents("http://api.openweathermap.org/data/2.5/group?id=524901,703448,2643743&units=metric&appid=a147fa6ec315fbf966c4015846d1f5e3");*/
    $homepage = file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=".$_GET['search']."&units=metric&appid=a147fa6ec315fbf966c4015846d1f5e3");
    /*decode le json pour en faire un objet */
    $data = json_decode($homepage);
    /* récupère le nom de la ville */
    $cityNameSearch = file_get_contents("https://api.teleport.org/api/cities/?search=".$_GET['search']."&limit=10");
    $dataNameSearch = json_decode($cityNameSearch);
    /* récupère le lien de l'API de la ville recherchée */
    $cityLink = $dataNameSearch->_embedded->{'city:search-results'}[0]->_links->{'city:item'}->href;
    $linkContent = file_get_contents("$cityLink");
    $dataNameSearchResult = json_decode($linkContent);
    $urbanArea = $dataNameSearchResult->_links->{'city:urban_area'}->href;
    /* passe le nom de la ville dans une api pour les images */
    $imgAPI = file_get_contents("$urbanArea"."images/");
    $dataImg = json_decode($imgAPI);

?>
    <div class="card bg-dark text-white mt-5 height-city-card">
        <img src="assets/img/Paris.jpg" class="card-img-top" alt="paris">
        <div class="card-img-overlay bg-light city-card text-dark">
            <h5 class="card-title city-name"><?=  $data->name ?></h5>
            <p class="card-text city-temp"><?= $data->weather[0]->description ?></p>
            <p class="card-text temp-desc"><?= round($data->main->feels_like) ?>&deg;</p>
            <img src="<?php
               echo $dataImg->photos[0]->image->web;
            ?>" alt="" class="img-fluid">
        </div>
    </div>

    <?php
}
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <script
            src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous"></script>
    <script src="assets/js/weather.js" type="text/javascript"></script>
<script src="assets/js/rain.js"></script>
</body>
</html>