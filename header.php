        <div class="row">
            <header class="p-0">
                <nav class="navbar navbar-expand-lg navbar-light bg-info"><!--Barra de navegación-->
                  <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>
                    <img src="img/logo/hotel_cache_v2.svg" class="logo" alt="logo_hotel"> <!--Logotipo-->
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                      <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="#">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="rooms.php">Rooms</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#">Location</a>
                        </li>
                        <li class="nav-item">
                            <?php if($_SESSION['rol']!='visitante'){?>
                            <a class="nav-link" href="userpage.php"><?php
				$user = json_decode($_SESSION['usuario']);	
 				echo $user->nombre;
 				?></a>
                            <?php }else{?>
                          <a class="nav-link" href="registerLogin.php">Sign in/Login</a>
                            <?php }?>
                        </li>
                    </ul>
                  </div>
                  </div>
                </nav>
              </header>
        </div>
    
    