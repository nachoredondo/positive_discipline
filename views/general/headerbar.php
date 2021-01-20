<!-- Navigation-->
<nav class="navbar navbar-expand-lg bg-secondary fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Disciplina positiva</a>
        <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menú
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#rules">Normas</a></li>
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#juntas">Juntas</a></li>
                <?php  if ($_SESSION['type']){  ?>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact">Turnos</a></li>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact">Stop</a></li>
                <?php } else {  ?>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact">Rueda</a></li>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact">Mesa</a></li>
	                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact">Botella</a></li>
                <?php } ?>
            </ul>
        </div>
        <a class="nav-link dropdown-toggle" href="#" id="navUserDropdown" data-toggle="dropdown">
			<span class="mx-1"><?php echo $_SESSION['fullname'] ?></span>
			<i class="fas fa-user"></i>
		</a>
		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navUserDropdown">
			<a class="dropdown-item" href="#">Mi perfil</a>
			<a class="dropdown-item" href="../users/logout.php">
				<i class="fas fa-sign-out-alt"></i> Log out
			</a>
		</div>
    </div>
</nav>
