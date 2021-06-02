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
        <link rel="stylesheet" href="externo/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="estilos/style.css">
        <link rel="stylesheet" href="estilos/room.css">
        <title>Hotel Cache - Login/Register</title>
    </head>

    <?php
    require_once(__DIR__ . '/autoload.php');

    use \functions\functions as func;
    use \conexion\conectDB as db;
    
    $sesion = new func();
    
    $sesion->checkSession();
    
    if(isset($_GET)){
        $db = new db($_SESSION['rol']);
        $id = key($_GET);
        $info = $db->loadFullData($id);
        $datos_habitacion = $db->loadRoomData($info['tipo_de_habitacion']);
        
        $img = $db->loadRoomImg($info['tipo_de_habitacion']);
        $services = $db->loadServices();
        
        $checkin = $_COOKIE['checkin'];
        $checkout = $_COOKIE['checkout'];
        $dias = $sesion->dateDiff($checkin, $checkout);
    }
    
    if(isset($_POST['enviar'])){
        try{
        if(isset($_POST['servicio'])){
            $db->reserve($_SESSION['usuario']->id, $checkin, $checkout, $id, $dias,$_POST['servicio']);
            
        }else{
            $db->reserve($_SESSION['usuario']->id, $checkin, $checkout, $id, $dias);
            
        }
        }catch(Exception $e){
            echo $e->getMessage();
        }finally{
            unset($_POST);
        }
    }
    
    
    ?>
    <body>
        <div class="container-fluid"><!--Contenedor principal-->
            <?php require_once('header.php') ?>
           <div class="single_product">
        <div class="container-fluid" style=" background-color: #fff; padding: 11px;">
            <div class="row">
                <div class="col-lg-2 order-lg-1 order-2">
                    <ul class="image_list">
                        <li data-image="<?php echo $img[0]['imagen_habitacion']?>"><img src="<?php echo $img[0]['imagen_habitacion']?>" alt="<?php echo $img[0]['descripcion_imagen']?>"></li>
                        <li data-image="<?php echo $img[1]['imagen_habitacion']?>"><img src="<?php echo $img[1]['imagen_habitacion']?>" alt="<?php echo $img[1]['descripcion_imagen']?>"></li>
                        <li data-image="<?php echo $img[2]['imagen_habitacion']?>"><img src="<?php echo $img[2]['imagen_habitacion']?>" alt="<?php echo $img[2]['descripcion_imagen']?>"></li>
                    </ul>
                </div>
                <div class="col-lg-4 order-lg-2 order-1">
                    <div class="image_selected"><img src="<?php echo $img[0]['imagen_habitacion']?>" alt="<?php echo $img[0]['descripcion_imagen']?>"></div>
                </div>
                <div class="col-lg-6 order-3">
                    <div class="product_description">
                        
                        <div class="product_name"><?php echo $datos_habitacion['tipo_habitacion'];?></div>
                        <div> <span class="product_price"><?php echo ($info['precio']* $dias)." €"; ?></span></div>
                        <div> <span class="product_saved">Precio Noche:</span> <span style='color:black'><?php echo $info['precio']." €"; ?><span> </div><br>
                        <div> <span class="product_saved">Fecha Entrada: <?php echo $checkin; ?></span> <span class="product_saved">Fecha Salida:<?php echo $checkout; ?><span> </div>    
                        <hr class="singleline">
                        <div> <span class="product_info">Características:</span><br><br>
                              <span class="product_info">Tamaño: <?php echo $info['m2']." m2"; ?><span><br>
                              <span class="product_info">Internet: <?php if($info['internet'] == 1){ echo "Si";}else{echo "No";}; ?><span><br>
                              <span class="product_info">Ventana: <?php if($info['ventana'] == 1){ echo "Si";}else{echo "No";};?><span><br>
                              <span class="product_info">Servicio de Limpieza: <?php if($info['servicio_limpieza'] == 1){ echo "Si";}else{echo "No";};?><span> 
                        </div>
                        <div>
                            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                            <div class="row">
                                <div class="col-md-7"> </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-xs-6" style="margin-left: 15px;">
                                    <span class="product_options">Bonus Services:</span><br><br>
                                    <fieldset name="services">
                                        <?php for($i=0; $i < sizeof($services);$i++){?>
                                        <input type="checkbox" name="servicio[]" value="<?php echo $services[$i]['id'] ?>">
                                        <label class="product_info" for="<?php echo $services[$i]['nombre_servicio']; ?>"><?php echo " ".$services[$i]['nombre_servicio']; ?></label>
                                        <span class="product_info"> <?php echo "+".$services[$i]['precio_servicio']." €" ?> </span>
                                        <a tabindex="0"
                                           role="button" 
                                           data-toggle="popover" 
                                           data-trigger="focus" 
                                           data-content="<?php echo $services[$i]['descripcion'];?>">
                                           <img src="img/logo/info.png">
                                        </a><br>
                                        <?php } ?>
                                    </fieldset>
                                </div>
                                
                            </div>
                        </div>
                        <hr class="singleline">
                        <div class="order_info d-flex flex-row">
                            
                        </div>
                        <div class="row">
                            <div class="col-xs-6"> <input type="submit" class="btn btn-primary shop-button" name="enviar" value="Reservar">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            <?php require_once('footer.php') ?>
        </div>
        <script src="externo/jquery/jquery-3.5.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.7/js/tether.min.js"></script>

        <script src="externo/bootstrap/js/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-highlight@3.5.0/jquery.highlight.min.js"></script>
        <script src="js/index.js"></script> 
        <script>
                $(function(){
                    $('[data-toggle="popover"]').popover();
                })
        </script>
        
    </body>
</html>










