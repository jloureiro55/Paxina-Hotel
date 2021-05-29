<?php 
var_dump($db->loadRoomData($habitaciones[$i]->tipo)); ?>
<div class="card" style="width: 18rem;">
  <img class="card-img-top" src="..." alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><?php echo $habitaciones[$i]->tipo; ?></h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <?php if($_SESSION['rol']== 'estandar'){ ?>
    <a href="#" class="btn btn-primary">Reservar</a>
    <?php }else{ ?>
    <a href="registerLogin.php" class="btn btn-primary">Registrate/Inicia sesion</a>
    <?php } ?>
  </div>
</div>