<?php
require 'classes/session.php';
require 'classes/user.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);

$childs = [];
if ($_SESSION['type']) {
    $childs = User::get_childs($user->id());
    $name_tutor = $user->name();
} else {
	$user_tutor = User::get_parent($user->id());
	$name_tutor = User::get_user_from_id($user_tutor)->user();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <title>Disciplina positiva</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="<?php echo APP_ROOT; ?>assets/img/logo.png"/>
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css"/>
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="<?php echo APP_ROOT ?>css/styles.css" rel="stylesheet"/>

        <!-- Bootstrap core JS-->
        <script src="<?php echo APP_ROOT ?>assets/jquery/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <style type="text/css">
        	.rules img {
        		border: 1px solid #555555;
        	}
        	.bottle img {
        		border: 1px solid #555555;
        	}
        	.scheeduler img {
        		border: 1px solid #555555;
        	}
        </style>

    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include 'views/general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container">
                <div class="mr-5">
                    <h2 class="text-center text-uppercase text-secondary mt-4 ml-5">
                        Disciplina positiva
                    </h2>
                    <!-- Icon Divider-->
	                <div class="divider-custom">
	                    <div class="divider-custom-line"></div>
	                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
	                    <div class="divider-custom-line"></div>
	                </div>
                    <div id="draw" class="mt-4 col-xs-10 col-sm-9 col-md-8 col-lg-8 mx-auto">
		                <?php  if ($_SESSION['type'] && !$childs) :  ?>
		                	<div class="mb-4">
			                	<h6 class="text-danger">
			                		Aviso: el tutor '<?php echo $user->name() ?>' no tiene niños asociados, se recomienda <a href="<?php echo APP_ROOT;?>views/users/profile_child.php">crear usuario</a></h6>
			                </div>
		                <?php  endif;  ?>
                    	<span class="row">
                    		<?php if ($_SESSION['type']): ?>
                    			El objetivo de esta página es el de educar a niños de forma amena y divertida, para conseguir este objetivo los tutores tienen:
                    		<?php else: ?>
                    			Para que la educación sea agradable y divertida se utilizan las siguientes herramientas:
                    		<?php endif; ?>
                    	</span>

                    	<div class="list ml-4 mb-5">
			                <div class="rules mt-4">
			                    <a href="<?php echo APP_ROOT; ?>views/rules/"><h4 class="text-uppercase text-info row">
			                        <i class='fas fa-list mr-2'></i> Gestor de normas
			                    </h4></a>
			                    <span class="row">
			                    	Tabla de normas, creadas por el tutor '<?php echo $name_tutor ?>', con las imágenes de sus consecuencias.
			                    </span>
			                    <?php if (!$_SESSION['type']): ?>
				                    <button type='button' id="show-img-rule" title='Ver foto' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-img'>
				                    	Ver foto
				                    	<i class='fas fa-images'></i>
				                    </button>
				                    <img class="row mt-2" id="img-rule" src="<?php echo APP_ROOT; ?>assets/img/main/rules.jpg" height="250" width="300" style="display:none;"/>
				                    <button type='button' id="hide-img-rule" title='Ocultar foto' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-img' style="display:none">
				                    	Ocultar foto
				                    	<i class='fas fa-images'></i>
				                    </button>
			                	<?php endif; ?>
			                	<?php if ($_SESSION['type']): ?>
				                    <button type='button' id="show-video-rule" title='Ver vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-video'>
				                    	Ver vídeo
				                    	<i class='fas fa-video'></i>
				                    </button>
				                    <video class="row mt-2" id="video-rule" controls controlsList="nodownload" poster="<?php echo APP_ROOT; ?>/assets/img/video.jpg" muted style="display:none" width="300">
			                            <source src="<?php echo APP_ROOT; ?>/assets/video/rules.mp4">
			                            Tu navegador no soporta el formato del vídeo.
			                        </video>
				                    <button type='button' id="hide-video-rule" title='Ocultar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-video' style="display:none">
				                    	Ocultar vídeo
				                    	<i class='fas fa-video'></i>
				                    </button>
			                    <?php endif; ?>
			                </div>

			                <div class="scheeduler mt-5">
			                    <a href="<?php echo APP_ROOT; ?>views/meeting/"><h4 class="text-uppercase text-info row">
			                        <i class='fas fa-calendar-alt mr-2'></i>Agenda familiar
			                    </h4></a>
			                    <span class="row">
			                    	En esta agenda se ven y gestionan juntas/ reuniones familiares por medio de un calendario.
			                    </span>
			                    <?php if ((!$_SESSION['type'] && $user->age() > 10) || $_SESSION['type']): ?>
			                    <span class="row">
			                    	La juntas las pueden crear, editar y eliminar los tutores y niños de más de 10 años.
			                    </span>
			                    <?php endif; ?>
			                    <?php if (!$_SESSION['type'] && $user->age() < 11): ?>
				                    <button type='button' id="show-img-calendar" title='Ver foto' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-img'>
				                    	Ver foto
				                    	<i class='fas fa-images'></i>
				                    </button>
				                    <img class="row mt-2" id="img-calendar" src="<?php echo APP_ROOT; ?>assets/img/main/calendar.jpg" height="250" width="300" style="display:none;"/>
				                    <button type='button' id="hide-img-calendar" title='Ocultar foto' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-img' style="display:none">
				                    	Ocultar foto
				                    	<i class='fas fa-images'></i>
				                    </button>
			                	<?php endif; ?>
			                	<?php if ((!$_SESSION['type'] && $user->age() > 10) || $_SESSION['type']): ?>
				                    <button type='button' id="show-video-calendar" title='Mostrar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-video'>
				                    	Ver vídeo
				                    	<i class='fas fa-video'></i>
				                    </button>
				                    <video class="row mt-2" id="video-calendar" controls controlsList="nodownload" poster="<?php echo APP_ROOT; ?>/assets/img/video.jpg" muted style="display:none" width="300">
			                            <source src="<?php echo APP_ROOT; ?>/assets/video/scheedule.mp4">
			                            Tu navegador no soporta el formato del vídeo.
			                        </video>
				                    <button type='button' id="hide-video-calendar" title='Ocultar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-video' style="display:none">
				                    	Ocultar vídeo
				                    	<i class='fas fa-video'></i>
				                    </button>
			                    <?php endif; ?>
			                </div>

			                <?php if (!$_SESSION['type']): ?>
		                    <div class="wheel mt-5">
			                    <a href="<?php echo APP_ROOT; ?>views/wheel/"><h4 class="text-uppercase text-info row">
			                        <i class='fas fa-cog mr-1'></i> Rueda de la ira
			                    </h4></a>
			                    <span class="row">
			                    	Es un juego con el que al pinchar en la rueda saca una de las opciones al azar.
			                    </span>
			                    <button type='button' id="show-video-wheel" title='Mostrar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-video'>
				                    	Ver vídeo
				                    	<i class='fas fa-video'></i>
				                    </button>
				                    <video class="row mt-2" id="video-wheel" controls controlsList="nodownload" poster="<?php echo APP_ROOT; ?>/assets/img/video.jpg" muted style="display:none" width="300">
			                            <source src="<?php echo APP_ROOT; ?>/assets/video/wheel.mp4">
			                            Tu navegador no soporta el formato del vídeo.
			                        </video>
				                    <button type='button' id="hide-video-wheel" title='Ocultar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-video' style="display:none">
				                    	Ocultar vídeo
				                    	<i class='fas fa-video'></i>
				                    </button>
			                </div>
			                <?php endif; ?>

			                <?php if ($_SESSION['type']): ?>
			                <div class="wheel mt-5">
			                    <a href="<?php echo APP_ROOT; ?>views/turn/"><h4 class="text-uppercase text-info row">
			                        <i class='fas fa-check mr-2'></i> Gestor de turnos
			                    </h4></a>
			                    <span class="row">
			                    	Se muestra una lista de tareas que, dependiendo de cuando se repitan y la fecha del último turno, pueden tener diferentes estados: realizada, pendiente, no realizada o finalizada.
			                    </span>
			                    <button type='button' id="show-video-wheel" title='Mostrar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-video'>
				                    	Ver vídeo
				                    	<i class='fas fa-video'></i>
			                    </button>
			                    <video class="row mt-2" id="video-wheel" controls controlsList="nodownload" poster="<?php echo APP_ROOT; ?>/assets/img/video.jpg" muted style="display:none" width="300">
		                            <source src="<?php echo APP_ROOT; ?>/assets/video/wheel.mp4">
		                            Tu navegador no soporta el formato del vídeo.
		                        </video>
			                    <button type='button' id="hide-video-wheel" title='Ocultar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-video' style="display:none">
			                    	Ocultar vídeo
			                    	<i class='fas fa-video'></i>
			                    </button>
			                </div>
			                <?php endif; ?>

			                <?php if (!$_SESSION['type']): ?>
			                <div class="table mt-5">
			                    <a href="<?php echo APP_ROOT; ?>views/table/"><h4 class="text-uppercase text-info row">
			                        <i class='fas fa-paint-brush mr-1'></i> Mesa de la paz
			                    </h4></a>
			                    <div class="row">
			                    	Sección con dos dibujos diferentes:
			                    	<ul>
				                    	<li>
				                    		Uno predefinido: en el que eliges el fondo y los elementos a mostrar. Además los elementos se pueden mover a la parte que uno quiera.
				                    	</li>
				                    	<li>
				                    		Otro a dibujar: con una palete donde escoger el color para pintar o un borrador.
				                    	</li>
				                    </ul>
			                    </div>
			                    <button type='button' id="show-video-table" title='Mostrar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-video'>
				                    	Ver vídeo
				                    	<i class='fas fa-video'></i>
			                    </button>
			                    <video class="row mt-2" id="video-table" controls controlsList="nodownload" poster="<?php echo APP_ROOT; ?>/assets/img/video.jpg" muted style="display:none" width="300">
		                            <source src="<?php echo APP_ROOT; ?>/assets/video/table.mp4">
		                            Tu navegador no soporta el formato del vídeo.
		                        </video>
			                    <button type='button' id="hide-video-table" title='Ocultar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-video' style="display:none">
			                    	Ocultar vídeo
			                    	<i class='fas fa-video'></i>
			                    </button>
			                </div>
			            	<?php endif; ?>

			            	<?php if ($_SESSION['type']): ?>
			            	<div class="stop mt-5">
			                    <a href="<?php echo APP_ROOT; ?>views/stop/"><h4 class="text-uppercase text-info row">
			                    	<i class='fas fa-hand-paper mr-2'></i>STOP
			                    </h4></a>
			                    <span class="row">
			                    	Esta sección sirve para cuando el tutor pierde la paciencia y no es capaz de educar a los niños, con lo que se puede calmar con un vídeo de youtube, imagen, audio o/y vídeo personal todos ellos personalizables.
			                    </span>
			                    <button type='button' id="show-video-stop" title='Mostrar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-video'>
				                    	Ver vídeo
				                    	<i class='fas fa-video'></i>
			                    </button>
			                    <video class="row mt-2" id="video-stop" controls controlsList="nodownload" poster="<?php echo APP_ROOT; ?>/assets/img/video.jpg" muted style="display:none" width="300">
		                            <source src="<?php echo APP_ROOT; ?>/assets/video/stop.mp4">
		                            Tu navegador no soporta el formato del vídeo.
		                        </video>
			                    <button type='button' id="hide-video-stop" title='Ocultar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-video' style="display:none">
			                    	Ocultar vídeo
			                    	<i class='fas fa-video'></i>
			                    </button>
			                </div>

			            	<div class="profile-child mt-5">
			                    <a href="<?php echo APP_ROOT; ?>views/users/profile_tutor.php"><h4 class="text-uppercase text-info row">
			                        <i class='fas fa-user mr-2'></i>Perfil de usuario
			                    </h4></a>
			                    <span class="row">
			                    	Se puede añadir, crear y editar usuarios niños del tutor '<?php echo $user->name() ?>', cambiar los datos del perfil, cambiar la contraseña y eliminar el usuario.
			                    </span>
			                    <button type='button' id="show-video-user-child" title='Mostrar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-video'>
				                    	Ver vídeo
				                    	<i class='fas fa-video'></i>
			                    </button>
			                    <video class="row mt-2" id="video-user-child" controls controlsList="nodownload" poster="<?php echo APP_ROOT; ?>/assets/img/video.jpg" muted style="display:none" width="300">
		                            <source src="<?php echo APP_ROOT; ?>/assets/video/create_child.mp4">
		                            Tu navegador no soporta el formato del vídeo.
		                        </video>
			                    <button type='button' id="hide-video-user-child" title='Ocultar vídeo' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-video' style="display:none">
			                    	Ocultar vídeo
			                    	<i class='fas fa-video'></i>
			                    </button>
			                </div>
			                <?php endif; ?>

			                <?php if (!$_SESSION['type']): ?>
			                <div class="bottle mt-5">
			                    <a href="<?php echo APP_ROOT; ?>views/bottle/"><h4 class="text-uppercase text-info row">
			                        <i class='fas fa-wine-bottle mr-1'></i> Botella de la calma
			                    </h4></a>
			                    <span class="row">
			                    	Sección en la que se ve un ejemplo de botella y un tutorial breve para saber como hacerla.
			                    </span>
			                    <button type='button' id="show-img-bottle" title='Ver foto' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 show-img'>
			                    	Ver foto
			                    	<i class='fas fa-images'></i>
			                    </button>
			                    <img class="row mt-2" id="img-bottle" src="<?php echo APP_ROOT; ?>assets/img/main/bottle.jpg" height="300" width="300" style="display:none;"/>
			                    <button type='button' id="hide-img-bottle" title='Ocultar foto' class='edit-btn btn btn-primary btn-sm mr-2 mt-2 hide-img' style="display:none">
			                    	Ocultar foto
			                    	<i class='fas fa-images'></i>
			                    </button>
			                </div>

			                <div class="profile-tutor mt-5">
			                    <a href="<?php echo APP_ROOT; ?>views/users/profile_child.php"><h4 class="text-uppercase text-info row">
			                        <i class='fas fa-user mr-2'></i> Perfil de usuario
			                    </h4></a>
			                    <span class="row">
			                    	Se puede cambiar la foto y nombre de perfil pulsando en la propia foto de usuario y en "Mi perfil", dentro del menú desplegable del usuario.
			                    </span>
			                </div>
			            	<?php endif; ?>
		                </div>

                    	<span class="row">
	                    	(*) El icono &nbsp<i class="fas fa-question-circle mt-1" title="Sección de ayuda"></i>&nbsp de ayuda, da información útil para entender como funciona cada parte.
	                    </span>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <?php include 'views/general/footer_template.php'; ?>
        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
        <div class="scroll-to-top d-lg-none position-fixed mt-5">
            <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
        </div>

        <!-- Contact form JS-->
        <script src="<?php echo APP_ROOT; ?>assets/mail/jqBootstrapValidation.js"></script>
        <!-- Core theme JS-->
        <script src="<?php echo APP_ROOT; ?>js/scripts.js"></script>
        <script type="text/javascript">
        	$(".show-img").click(function (){
        		let name = this.id.split("show-")[1];
        		$("#" + this.id).hide({ duration: 500});
        		$("#" + name).show({ duration: 500});
        		$("#hide-" + name).show({ duration: 500});
        	})
        	$('.hide-img').click(function (){
        		let name = this.id.split("hide-")[1];
        		$("#" + this.id).hide({ duration: 500});
        		$("#" + name).hide({ duration: 500});
        		$("#show-" + name).show({ duration: 500});
        	})

            $(".show-video").click(function (){
        		let name = this.id.split("show-")[1];
        		$("#" + this.id).hide({ duration: 500});
        		$("#" + name).show({ duration: 500});
        		$("#hide-" + name).show({ duration: 500});
        	})
        	$('.hide-video').click(function (){
        		let name = this.id.split("hide-")[1];
        		$("#" + this.id).hide({ duration: 500});
        		$("#" + name).hide({ duration: 500});
        		$("#show-" + name).show({ duration: 500});
        	})
        </script>
    </body>
</html>
