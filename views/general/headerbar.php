<!-- Navigation-->
<nav class="navbar navbar-expand-lg bg-secondary fixed-top" id="mainNav">
    <div class="container">
        <h4>
            <a class="navbar-brand js-scroll-trigger" href="#page-top">Disciplina positiva</a>
        </h4>
        <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menú
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#rules">Normas</a></li>
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#juntas">Juntas</a></li>
                <?php  if ($_SESSION['type']){  ?>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#turnos">Turnos</a></li>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#stop">Stop</a></li>
                <?php } else {  ?>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#rueda">Rueda</a></li>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#mesa">Mesa</a></li>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#botella">Botella</a></li>
                <?php } ?>
            </ul>
        </div>
        <a class="nav-link dropdown-toggle" href="#" id="navUserDropdown" data-toggle="dropdown">
			<span class="mx-1"><?php echo $_SESSION['name'] ?></span>
			<i class="fas fa-user"></i>
		</a>
		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navUserDropdown">
			<a class="dropdown-item" href="../users/edit_user.php">Mi perfil</a>
			<a class="dropdown-item" href="../users/logout.php">
				<i class="fas fa-sign-out-alt"></i> Cerrar sesión
			</a>
		</div>
    </div>
</nav>
