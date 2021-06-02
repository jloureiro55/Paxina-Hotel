<?php 
/*Este fichero se usa en el fichero rooms.php.
Aqui se crea una variable que contiene una instacia de pdo, donde se llama a dos funciones que filtraran de entre las habitaciones
aquellas que esten dispobibles para el usuario. Y se muestran en pantalla en forma de tarjeta.
 */
$imagen = $db->loadRoomImg($habitaciones[$i]->tipo);
$datos = $db->loadRoomData($habitaciones[$i]->tipo);?>
<div class="card col-4 m-2" id="<?php echo $habitaciones[$i]->id; ?>" style="width: 18rem;">
  <img class="card-img-top" src="<?php echo $imagen[0]['imagen_habitacion'];?>" alt="<?php echo $imagen[0]['descripcion_imagen']; ?>">
  <div class="card-body">
    <h5 class="card-title"><?php echo $datos['tipo_habitacion'] ?></h5>
    <p class="card-text"><?php echo $datos['descripcion']; ?></p>
    <?php if(isset($_POST['busqueda']) && ($_SESSION['rol']== 'estandar' || $_SESSION['rol']== 'admin')){ ?>
    <form action="room.php" method="get"><input type="submit" name="<?php echo $habitaciones[$i]->id?>" class="btn btn-primary" value="Reservar"></form>
    <?php }?>
  </div>
</div>