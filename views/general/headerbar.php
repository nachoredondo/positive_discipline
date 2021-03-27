<?php

$user_child = User::get_user_from_user($_SESSION['user'])

?>

<!-- Navigation-->
<nav class="navbar navbar-expand-lg bg-secondary fixed-top" id="mainNav">
    <div class="container">
        <h5>
            <a class="navbar-brand js-scroll-trigger" href="<?php echo APP_ROOT; ?>index.php">Disciplina positiva</a>
        </h5>
        <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menú
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <?php  if ($_SESSION['type']) :  ?>
                <a class="ml-5" href="../stop/">
                    <img id="img-user" src="../../assets/img/stop.png" height="80" width="90"/>
                </a>
            <?php  else:;  ?>
                <img id="img-user" src="../../assets/img/user_child/<?php  echo $user_child->image();  ?>" height="130" width="125"/>
            <?php  endif;  ?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="../rules/">Normas</a></li>
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="../meeting/">Juntas</a></li>
                <?php  if ($_SESSION['type']){  ?>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="../turn/">Turnos</a></li>
                <?php } else {  ?>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="../wheel/">Rueda</a></li>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="../table">Mesa</a></li>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="../bottle">Botella</a></li>
                <?php } ?>
            </ul>
        </div>
        <a class="nav-link dropdown-toggle" href="#" id="navUserDropdown" data-toggle="dropdown">
			<span class="mx-1"><?php echo $_SESSION['name'] ?></span>
			<i class="fas fa-user"></i>
		</a>
		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navUserDropdown">
			<a class="dropdown-item" href="../users/<?php echo ($_SESSION['type']) ? 'profile_tutor' : 'profile_child'?>.php">Mi perfil</a>
			<a class="dropdown-item" href="../users/logout.php">
				<i class="fas fa-sign-out-alt"></i> Cerrar sesión
			</a>
		</div>
    </div>
</nav>
