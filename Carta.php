<?php 
$imagen = $db->loadRoomImg($habitaciones[$i]->tipo);
$datos = $db->loadRoomData($habitaciones[$i]->tipo);?>
<div class="card" id="<?php echo $habitaciones[$i]->id; ?>" style="width: 18rem;">
  <img class="card-img-top" src="<?php echo $imagen['imagen_habitacion'];?>" alt="<?php echo $imagen['descripcion_imagen']; ?>">
  <div class="card-body">
    <h5 class="card-title"><?php echo $datos['tipo_habitacion'] ?></h5>
    <p class="card-text"><?php echo $datos['descripcion']; ?></p>
    <?php if($_SESSION['rol']== 'estandar'){ ?>
    <a href="#" class="btn btn-primary">Reservar</a>
    <?php }else{ ?>
    <a href="registerLogin.php" class="btn btn-primary">Registrate/Inicia sesion</a>
    <?php } ?>
  </div>
</div>