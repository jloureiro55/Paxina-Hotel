<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="externo/fontawesome-free-5.15.2-web/css/all.css"><!--Iconos-->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Charmonman:wght@700&family=Lovers+Quarrel&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Charm&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="externo/bootstrap/css/bootstrap.min.css"></link>
    <link rel="stylesheet" href="estilos/style.css">
    <title>Paxina de proba</title>
</head>
<body>
    <div class="container-fluid"><!--Contenedor principal-->
        <?php
        session_start();
        require_once('header.php');?>
        <div class="row">
            <section><!--Recuadro de sombra-->
                <div class="calendario">
                 <div class="sombra">
                   <h1>Hotel Cache</h1>
                   <p id="texto_destino">find your destiny</p>
                   <hr/><br>
                   <div id="iconos">
                   <a class="fab fa-facebook fa-3x"></a>
                   <a class="fab fa-instagram fa-3x ms-3"></a>
                 </div><br>
                   <h4>check in</h4>
                   <form action="" class="d-flex flex-column" id="busqueda">
                       <input type="date" id="entrada" class=""><br>
                       <h4>check out</h4>
                       <input type="date" id="salida" class=""><br>
                       <input type="submit" value="Search" id="botonBuscar" class="btn btn-secondary" alt="boton de buscar">
                   </form>
                 </div>
               </div> 
               </section>
        </div>
        <div class="row">
            <section><!--Carrousel-->
                <div id="carouselControls" class="carousel slide" data-ride="carousel">
                  <div class="carousel-inner">
                      <div class="carousel-item active">
                          <img class="d-block w-100 post-img" src="img/habitacion-doble/habitacion_doble.jpg" alt="img_double_room">
                      </div>
                      <div class="carousel-item">
                          <img class="d-block w-100 post-img" src="img/habitacion-suit/habitacion_suit.jpg" alt="img_suit_room">
                      </div>
                      <div class="carousel-item">
                          <img class="d-block w-100 post-img" src="img/habitacion-moderna/habitacion_moderna.jpg" alt="img_modern_room">
                      </div>
                  </div>
                  <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  </a>
                  <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  </a>
              </div>
              </section>
        </div>
        <div class="row">
            <section>
                <div id="servicios" >
                  <div class="dflex cambio">
                    <p id="letra">Enlarge text</p>
                    -<input type="range" id="barra-progreso" min="0" step="50" max="100" default="0" value="0">+
                  </div>
                  <h1 class="d-flex justify-content-center">Our services</h1>
                  <p class="d-flex justify-content-center" id="presentacion">More than twenty years near the coast, offering the best service to our guests.
                    Enjoy the views of our incredible rooms, contemplate the quality, design and comfort.<br>
                    We have done it for you, so you can spend your best vacations.
                    We are available all seasons of the year.</p>
                </div>
              </section>
        </div>
       
            <?php require_once('footer.php') ?>
    </div>  
     
    <script src="externo/jquery/jquery-3.5.1.min.js"></script>
    <script src="externo/bootstrap/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy"
    crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"
    integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-highlight@3.5.0/jquery.highlight.min.js"></script>
    <script src="js/index.js"></script>
</body>
</html>