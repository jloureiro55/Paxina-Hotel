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
    $user = json_decode($_SESSION['usuario']);
    if ($_SESSION['rol'] == 'visitante') {
        header('location:index.php');
    }
    if (isset($_POST['update'])) {
        $telf = $user->telf;
        $nombre = $user->nombre;
        $email = $user->email;
        $direccion = $user->direccion;

        if ($_POST['name'] != $user->nombre && !empty($_POST['nombre'])) {
            $nombre = $_POST['nombre'];
            $user->nombre = $nombre;
        }
        if ($_POST['telf'] != $user->telf && $tool->phone($_POST['telf'])) {
            $telf = $_POST['telf'];
            $user->telf = $telf;
        }
        if ($_POST['email'] != $user->email && $tool->validateEmail($_POST['email'])) {
            $telf = $_POST['email'];
            $user->email = $email;
        }
        if ($_POST['address'] != $user->direccion) {
            $direccion = $_POST['address'];
            $user->direccion = $direccion;
        }

        $conec->updateUserData($user->id, $nombre, $email, $telf, $direccion);
        $_SESSION['usuario'] = json_encode($user);
    }
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
        <title>Pagina de <?php echo $user->nombre; ?></title>
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
            $room1 = new room(null, $_POST['m2'], $_POST['ventana'], $_POST['type'], $_POST['limpieza'], $_POST['internet'], $_POST['precio']);
            $conec->createRoom($room1);
        }
        if (isset($_POST['update-room'])) {
            $conec->updateRoom($_POST);
        }
        ?>
    </head>
    <body>
        <div class="container-fluid">
            <?php require_once 'header.php'; ?>

            <div class="row">
                <div class="col-2 border border-primary p-0 text-center bg-light">
                    <img class="col-6 avatar " src="img/Avatar/default-avatar.png">
                    <p><?php echo $user->nombre; ?></p>
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
                                            <label class="text-center"  for="m2">m2 Room</label><input type="number" name="m2" placeholder="m2" min="1">
                                        </div>
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center" for="precio">Price</label><input type="number" name="precio" placeholder="precio" min="0">
                                        </div>
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center"  for="ventana">Window</label>
                                            <select class="col-md-12 m-auto" name="ventana">
                                                <option value="b'1'">Yes</option>
                                                <option value="b'0'">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex flex-column">
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center" for="limpieza">Cleaning Service</label>
                                            <select class="col-md-12 m-auto" name="limpieza">
                                                <option value="b'1'">Yes</option>
                                                <option value="b'0'">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 m-auto d-flex flex-column">
                                            <label class="text-center" for="internet">Internet</label>
                                            <select class="col-md-12 m-auto" name="internet">
                                                <option value="b'1'">Yes</option>
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
                                <hr>
                                <form class="d-grid p-2 m-auto" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                    <h3 class="col-md-12 m-auto p-2 text-center">VIEW USER RESERVES</h3>
                                    <select class="col-md-6 m-auto p-2" name="user">
                                        <?php
                                        $users = $conec->getUsers();
                                        foreach ($users as $usuario) {
                                            ?>
                                            <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['id'] . "." . $usuario['nombre']; ?> </option>
                                        <?php } ?>
                                    </select>

                                    <div class=" col-md-6 d-flex flex-row m-auto">
                                        <input class="btn-success btn col-md-4 m-2 m-auto mt-2" type="submit" name="search" value="Search">
                                        <input class="btn-success btn col-md-4 m-2 m-auto mt-2" type="submit" name="searchAll" value="View All Users">
                                    </div>
                                </form>
                                <?php
                                if (isset($_POST['search'])) {
                                    $reservasuser = $conec->getReserves($_POST['user']);
                                    if (!empty($reservasuser)) {
                                        ?>
                                        <div class="col-md-12 justify-content-center d-flex flex-column mt-2">
                                            <h4 class="col-md-12 m-auto p-2 text-center"><?php echo strtoupper($conec->getUsername($_POST['user'])[0]); ?>'S RESERVES</h4>

                                            <?php
                                            foreach ($reservasuser as $reserva) {
                                                $habitacion = $conec->getReservedRoom($reserva['num_reserva']);
                                                ?>
                                                <div class="p-0 pt-2 pb-2 col-md-8 m-auto border border-primary text-center">
                                                    <span>Reserve ID: <?php echo $reserva['num_reserva']; ?> Room: <?php echo $habitacion[0]['tipo_habitacion'] ?>  CheckIn date: <?php echo $reserva['fecha_entrada']; ?> CheckOut date: <?php echo $reserva['fecha_salida']; ?> Price <?php echo $habitacion[1]['precio'] * $reserva['num_dias'] . "€" ?> Validated: <?php
                                                        if ($reserva['validada'] == 1) {
                                                            echo "Yes";
                                                        } else {
                                                            echo "Pending";
                                                        };
                                                        ?> </span>
                                                </div>    
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <h5 class="col-md-12 m-auto p-2 text-center">The user have 0 reserves.</h5>
                                        <?php
                                    }
                                } else if (isset($_POST['searchAll'])) {
                                    $reservasuser = $conec->getReserves();
                                    if (!empty($reservasuser)) {
                                        ?>
                                        <div class="col-md-12 justify-content-center d-flex flex-column mt-2">
                                            <h4 class="col-md-12 m-auto p-2 text-center">ALL RESERVES</h4>

                                            <?php
                                            foreach ($reservasuser as $reserva) {
                                                $habitacion = $conec->getReservedRoom($reserva['num_reserva']);
                                                ?>
                                                <div class="p-0 pt-2 pb-2 col-md-8 m-auto border border-primary text-center">
                                                    <span>Reserve ID: <?php echo $reserva['num_reserva']; ?> User:<?php echo " " . $conec->getUsername($reserva['id_usuario'])[0] ?> Room: <?php echo $habitacion[0]['tipo_habitacion'] ?>  CheckIn date: <?php echo $reserva['fecha_entrada']; ?> CheckOut date: <?php echo $reserva['fecha_salida']; ?> Price <?php echo $habitacion[1]['precio'] * $reserva['num_dias'] . "€" ?> Validated: <?php
                                                        if ($reserva['validada'] == 1) {
                                                            echo "Yes";
                                                        } else {
                                                            echo "Pending";
                                                        };
                                                        ?> </span>
                                                </div>    
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <h5 class="col-md-12 m-auto p-2 text-center">There are no reserves on the system.</h5>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <hr>
                            <h3 class="col-md-12 m-auto p-2 text-center">MODIFY ROOM DATA</h3>
                            <?php
                            $rooms = $conec->loadAllRooms();
                            ?>
                            <form class="d-grid p-2 m-auto" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                <select class="col-md-6 m-auto p-2" name="habitacion">
                                    <?php foreach ($rooms as $room) { ?>
                                        <option value="<?php echo $room->id ?>">ID: <?php echo $room->id ?> Type: <?php echo $conec->loadRoomData($room->tipo)['tipo_habitacion']; ?> </option>
                                    <?php }
                                    ?>
                                </select>
                                <input class="btn-success btn col-md-2 m-2 m-auto mt-2" type="submit" name="ChooseRoom" value="Search">
                            </form>
                            <?php
                            if (isset($_POST['ChooseRoom'])) {
                                $dataRoom = $conec->loadFullData($_POST['habitacion']);
                                ?>
                                <h3 class="col-md-12 m-auto p-2 text-center">MODIFYING ROOM ID: <?php echo $dataRoom['id']; ?></h3>
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                <div class="d-flex p-2 m-auto flex-row">
                                    
                                        <div class="col-md-6 d-flex flex-column">
                                            <div class="col-md-4 m-auto d-flex flex-column">
                                                <label class="text-center"  for="m2">m2 Room</label><input type="number" name="m2" placeholder="m2" value="<?php echo $dataRoom['m2']; ?>" min="1">
                                            </div>
                                            <div class="col-md-4 m-auto d-flex flex-column">
                                                <label class="text-center" for="precio">Price</label><input type="number" name="precio" placeholder="precio" value="<?php echo $dataRoom['precio']; ?>" min="0">
                                            </div>
                                            <div class="col-md-4 m-auto d-flex flex-column">
                                                <label class="text-center"  for="ventana">Window</label>
                                                <select class="col-md-12 m-auto" name="ventana" value="<?php echo $dataRoom['ventana']; ?>"">
                                                    <?php if ($dataRoom['ventana'] == 1) { ?> <option value="b'1'" selected>Yes</option><?php } else { ?>
                                                        <option value="b'1'" selected>Yes</option>
                                                        <?php
                                                    }
                                                    if ($dataRoom['ventana'] == 0) {
                                                        ?>
                                                        <option value="b'0'" selected>No</option>
                                                    <?php } else { ?>
                                                        <option value="b'0'">No</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-flex flex-column">
                                            <div class="col-md-4 m-auto d-flex flex-column">
                                                <label class="text-center" for="limpieza">Cleaning Service</label>
                                                <select class="col-md-12 m-auto" name="limpieza" value="<?php echo $dataRoom['servicio_limpieza']; ?>">
                                                    <?php if ($dataRoom['servicio_limpieza'] == 1) { ?> <option value="b'1'" selected>Yes</option><?php } else { ?>
                                                        <option value="b'1'" selected>Yes</option>
                                                        <?php
                                                    }
                                                    if ($dataRoom['servicio_limpieza'] == 0) {
                                                        ?>
                                                        <option value="b'0'" selected>No</option>
                                                    <?php } else { ?>
                                                        <option value="b'0'">No</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 m-auto d-flex flex-column">
                                                <label class="text-center" for="internet">Internet</label>
                                                <select class="col-md-12 m-auto" name="internet" value="<?php echo $dataRoom['internet']; ?>">
                                                    <?php if ($dataRoom['internet'] == 1) { ?> <option value="b'1'" selected>Yes</option><?php } else { ?>
                                                        <option value="b'1'" selected>Yes</option>
                                                        <?php
                                                    }
                                                    if ($dataRoom['internet'] == 0) {
                                                        ?>
                                                        <option value="b'0'" selected>No</option>
                                                    <?php } else { ?>
                                                        <option value="b'0'">No</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 m-auto d-flex flex-column">
                                                <label class="text-center" for="internet">Room Type</label>
                                                <select class="col-md-12 m-auto" name="type" value="<?php echo $dataRoom['id']; ?>">
                                                    <?php
                                                    $types = $conec->getTypes();
                                                    foreach ($types as $type) {

                                                        if ($dataRoom['id'] == $type['id']) {
                                                            ?>
                                                            <option value="<?php echo $type['id'] ?>" selected><?php echo $type['tipo_habitacion'] ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $type['id'] ?>"><?php echo $type['tipo_habitacion'] ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="id" value="<?php echo $dataRoom['id']; ?>">
                                            <div class=" justify-content-center m-auto">
                                                <input class="btn-success btn col-md-12 m-2" type="submit" name="update-room" value="Update room">
                                            </div>
                                        </div>
                                </div>
                                </form>
                                <?php
                            }
                        }
                        ?>
                        <h1 class="col-md-6 m-auto text-center ">USERPAGE PANEL</h1>
                        <?php
                        $reservas = $conec->getReserves($user->id);
                        if (!empty($reservas)) {
                            ?>
                            <div class="col-md-12 justify-content-center d-flex flex-column mt-2">
                                <h4 class="col-md-12 m-auto p-2 text-center">YOUR RESERVES</h4>

                                <?php
                                foreach ($reservas as $reserva) {
                                    $habitacion = $conec->getReservedRoom($reserva['num_reserva']);
                                    ?>
                                    <div class="p-0 pt-2 pb-2 col-md-8 m-auto border border-primary text-center">
                                        <span>Reserve ID: <?php echo $reserva['num_reserva']; ?> Room: <?php echo $habitacion[0]['tipo_habitacion'] ?>  CheckIn date: <?php echo $reserva['fecha_entrada']; ?> CheckOut date: <?php echo $reserva['fecha_salida']; ?> Price <?php echo $habitacion[1]['precio'] * $reserva['num_dias'] . "€" ?> Validated: <?php
                                            if ($reserva['validada'] == 1) {
                                                echo "Yes";
                                            } else {
                                                echo "Pending";
                                            };
                                            ?> </span>
                                    </div>    
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <hr>
                        <div class="col-md-12 justify-content-center d-flex flex-row mt-2">
                            <div class="col-md-6 justify-content-center d-flex flex-column mt-2">
                                <h4 class="col-md-12 m-auto p-2 text-center">USER DATA</h4>
                                <div class="col-md-12 m-auto p-2 text-center">
                                    <p>Name: <?php echo $user->nombre; ?></p>
                                    <p>Email: <?php echo $user->email; ?></p>
                                    <p>Phone Number: <?php echo $user->telf; ?></p>
                                    <?php if ($user->direccion != "") { ?>
                                        <p>Adress: <?php echo $user->direccion; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6 justify-content-center d-flex flex-column mt-2">
                                <h4 class="col-md-12 m-auto p-2 text-center">UPDATE USER DATA</h4>
                                <form class="col-md-12 m-auto p-2 text-center" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" METHOD="post">
                                    <label for="name">Name: </label><input class="col-md-6" type="text" name="name" value="<?php echo $user->nombre; ?>"><br><br>
                                    <label for="emal">Email: </label><input class="col-md-6" type="text" name="email" value="<?php echo $user->email; ?>"><br><br>
                                    <label for="telf">Telefono: </label><input class="col-md-6" type="text" name="telf" value="<?php echo $user->telf; ?>"><br><br>
                                    <label for="address">Address: </label><input class="col-md-6 ml-2" type="text" name="address" value="<?php
                                    if ($user->direccion != "") {
                                        echo $user->direccion;
                                    }
                                    ?>"><br>
                                    <input class="btn-primary btn col-md-2 m-2" type="submit" name="update" value="Update Info">

                                </form>
                            </div>
                        </div>
                        <hr>
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
