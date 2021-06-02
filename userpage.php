<!DOCTYPE html>

<html>
    <?php
    require_once (__DIR__ . '/autoload.php');

    use \functions\functions as func;
    use \usuario\Usuario as user;
    use \conexion\conectDB as DB;
    use \habitacion\Habitacion as room;

$tool = new func();

    $tool->checkSession();
    $conec = new DB($_SESSION['rol']);
    ?>
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
        <style>
            footer{
                bottom: 0;
            }
        </style>
        <title>Pagina de <?php echo $_SESSION['usuario']->nombre; ?></title>
        <?php
        if (isset($_POST['logout'])) {
            session_destroy();
            session_unset();
            header('location:index.php');
        }
        if (isset($_POST['validar']) && !empty($_POST['reservas'])) {
            $conec->ValidateReserve($_POST['reservas']);
        }
        if (isset($_POST['borrar']) && !empty($_POST['reservas'])) {
            $conec->DeleteReserve($_POST['reservas']);
        }
        if (isset($_POST['disable']) && !empty($_POST['habitaciones'])) {
            $conec->toggleRoomState($_POST['habitaciones'], b'0');
        }
        if (isset($_POST['enable']) && !empty($_POST['habitaciones'])) {
            $conec->toggleRoomState($_POST['habitaciones'], 1);
        }
        if (isset($_POST['upload-room']) && $_POST['precio'] != "" && $_POST['m2'] != 0) {
            $room1 = new room(null,$_POST['m2'], $_POST['ventana'], $_POST['type'], $_POST['limpieza'], $_POST['internet'], $_POST['precio']);
            $conec->createRoom($room1);
        }
        ?>
    </head>
    <body>
        <div class="container-fluid">
            <?php require_once 'header.php'; ?>

            <div class="row">
                <div class="col-2 border border-primary p-0 text-center bg-light">
                    <img class="col-6 avatar " src="img/Avatar/default-avatar.png">
                    <p><?php echo $_SESSION['usuario']->nombre; ?></p>
                    <div class="p-0 pt-2 pb-2 col-12 border border-dark text-center">Overview</div>
                    <div class="p-0 pt-2 pb-2 col-12 border border-dark text-center">Reserves</div>
                    <div class="p-0 pt-2 pb-2 col-12 border border-dark text-center">Preferences</div>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <input type="submit" name="logout" class="p-0 pt-2 pb-2 col-12 border border-dark text-center" value ="Logout">
                    </form>
                </div>
                <div class="container col-10 p-0 border border-primary d-flex d-flex-column">
                    <div class="col-12 border border-primary justify-content-center">
                        <?php if ($_SESSION['rol'] == "admin") { ?>
                            <h1 class="col-md-6 m-auto text-center ">ADMINISTRATOR PANEL</h1>
                            <?php
                            $reservas = $conec->loadPendingReserves();
                            if (!empty($reservas)) {
                                ?>
                                <div class="col-md-12 justify-content-center d-flex flex-column mt-2">

                                    <form class="d-grid p-2 m-auto" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                        <h3 class="col-md-12 m-auto p-2 text-center">Validate Reserves</h3>
                                        <select class="col-md-12 m-auto" name="reservas[]" multiple="multiple">
                                            <?php
                                            foreach ($reservas as $reserva) {
                                                echo '<option value="' . $reserva['reserva'] . '">Reserve Nº: ' . $reserva['reserva'] . ' CheckIn: ' . $reserva['entrada'] . ' CheckOut: ' . $reserva['salida'] . ' Room: ' . $reserva['habitacion'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <div class="d-flex flex-row justify-content-center">
                                            <input class="btn-success btn col-md-2 m-2" type="submit" name="validar" value="Validate">
                                            <input class="btn-danger btn col-md-2 m-2" type="submit" name="borrar" value="Delete">
                                        </div>
                                    </form> 
                                </div>
                                <hr>
                                <?php
                            }
                            $available = $conec->loadRooms(1);
                            $disabled = $conec->loadRooms(0);
                            ?>
                            <div>
                                <h3 class="col-md-12 m-auto p-2 text-center">Modify Room State</h3>
                                <div class="col-md-12 justify-content-center d-flex flex-row mt-2">
                                    <form class="d-grid p-2 m-auto" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                        <h4 class="col-md-12 m-auto p-2 text-center">Disable Rooms</h4>
                                        <select class="col-md-12 m-auto" name="habitaciones[]" multiple="multiple">
                                            <?php
                                                                        
                                            foreach ($available as $room) {
                                                echo '<option value="' . $room->id . '">ID:' . $room->id . ' Room Type: ' . $conec->loadRoomData($room->tipo)['tipo_habitacion'] . ' Precio: ' . $room->precio . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <div class="d-flex flex-row justify-content-center">
                                            <input class="btn-success btn col-md-3 m-2" type="submit" name="disable" value="Disable">
                                        </div>
                                    </form>
                                    <form class="d-grid p-2 m-auto" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                        <h4 class="col-md-12 m-auto p-2 text-center">Enable Rooms</h4>
                                        <select class="col-md-12 m-auto" name="habitaciones[]" multiple="multiple">
                                            <?php
                                            foreach ($disabled as $room) {
                                                echo '<option value="' . $room->id . '">ID:' . $room->id . ' Room Type: ' . $conec->loadRoomData($room->tipo)['tipo_habitacion'] . ' Precio: ' . $room->precio . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <div class="d-flex flex-row justify-content-center">
                                            <input class="btn-success btn col-md-3 m-2" type="submit" name="enable" value="Enable">
                                        </div>
                                    </form>
                                </div>
                            </div>  
                            <hr>
                            <div>
                                <h3 class="col-md-12 m-auto p-2 text-center">Create new Room</h3>
                                <form class="d-flex p-2 m-auto flex-row" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                    <div class="col-md-6 d-flex flex-column">
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center"  for="m2">m2 de la habitacion</label><input type="number" name="m2" placeholder="m2">
                                        </div>
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center" for="precio">Precio noche</label><input type="number" name="precio" placeholder="precio">
                                        </div>
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center"  for="ventana">Ventana</label>
                                            <select class="col-md-12 m-auto" name="ventana">
                                                <option value="b'1'">Si</option>
                                                <option value="b'0'">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex flex-column">
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center" for="limpieza">Servicio de limpieza</label>
                                            <select class="col-md-12 m-auto" name="limpieza">
                                                <option value="b'1'">Si</option>
                                                <option value="b'0'">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center" for="internet">Internet</label>
                                            <select class="col-md-12 m-auto" name="internet">
                                                <option value="b'1'">Si</option>
                                                <option value="b'0'">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center" for="internet">Room Type</label>
                                            <select class="col-md-12 m-auto" name="type">
                                                <?php
                                                $types = $conec->getTypes();
                                                foreach ($types as $type) {
                                                    ?>
                                                    <option value="<?php echo $type['id'] ?>"><?php echo $type['tipo_habitacion'] ?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>
                                        <div class=" justify-content-center m-auto">
                                            <input class="btn-success btn col-md-12 m-2" type="submit" name="upload-room" value="Upload room">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php require_once 'footer.php'; ?>
        </div>
    </body>
    <script src="externo/jquery/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="externo/bootstrap/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-highlight@3.5.0/jquery.highlight.min.js"></script>
    <script src="js/index.js"></script>
</html>
