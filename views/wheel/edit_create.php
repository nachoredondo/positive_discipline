<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/option_wheel.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);

if (isset($_REQUEST['id'])) {
	$option = Option_wheel::get_option_by_id($_REQUEST['id']);
	$value_submit = "Editar";
	$id_option = $_REQUEST['id'];
	$img_option = $option->image();
} else {
	$option = new Option_wheel();
	$value_submit = "Crear";
	$id_option = "";
	$img_option = "";
}

// print($_REQUEST['id']);
// exit();
// echo $option->image();
// echo $img_option;
// exit();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<title>Rueda</title>
		<!-- Favicon-->
		<link rel="icon" type="image/x-icon" href="../../assets/img/favicon.ico" />
		<!-- Font Awesome icons (free version)-->
		<script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
		<!-- Google fonts-->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
		<link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
		<!-- Core theme CSS (includes Bootstrap)-->
		<link href="../../css/styles.css" rel="stylesheet" />
				<!-- Bootstrap core JS-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Third party plugin JS-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
		<script src="<?php echo APP_ROOT ?>/assets/js/moment.js" type="text/javascript"></script>
	</head>
	<body id="page-top">
		<!-- Navigation-->
		<?php include '../general/headerbar.php' ?>
		<!-- Contact Section-->
		<section class="page-section" id="contact">
			<div class="container mb-3">
				<!-- Contact Section Heading-->
				<h2 class="text-white">.</h2>
				<h2 class="text-center text-uppercase text-secondary"><?php echo $value_submit;?> opción
				</h2>
				<!-- Icon Divider-->
				<div class="divider-custom">
					<div class="divider-custom-line"></div>
					<div class="divider-custom-icon"><i class="fas fa-star"></i></div>
					<div class="divider-custom-line"></div>
				</div>
				<!-- Contact Section Form-->
				<div class="row">
					<div class="col-lg-8 mx-auto">
						<form id="contactForm" method="post" action="control.php" name="sentMessage" novalidate="novalidate" enctype="multipart/form-data">
							<input name="id" type="hidden" value="<?php echo $id_option; ?>"/>
							<input name="id_user" type="hidden" value="<?php echo $user->id(); ?>"/>
							<input name="img_saved" type="hidden" value="<?php echo $option->image(); ?>"/>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Nombre</label>
										<i class="fas fa-microphone ml-3 mt-4" id="audio-name"></i>
									</div>
									<input class="form-control mr-5" id="name" name="name" type="text" required="required" data-validation-required-message="Introduzca el título." placeholder="Título" value="<?php echo $option->name; ?>"/>
									<p class="help-block text-danger" required style="display:none;"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Imagen</label>
									</div>
									<?php if ($option->image() != "") {
										echo "<img class='mt-2 ml-2' src='" . APP_ROOT . "files/img/wheel/" . $option->image() . "' height='80'/>";
										echo "<label for='files'>Cambiar imagen</label>";
									} ?>
									<input id="files" class="mt-1 form-control mb-3" type="file" name="fimagen" accept="image/gif, image/jpeg, image/png" style="font-size: large"/>
								</div>
							</div>
							<div class="form-group mt-3">
								<button class="btn btn-primary btn-lg ml-2" id="createEditButton" name="form" value="<?php echo $value_submit;?>" type="submit"><?php echo $value_submit;?></button>
								<?php if ($value_submit != "Crear") {
									echo '<button class="btn btn-primary btn-lg ml-3" id="deleteButton" name="form" value="delete" type="submit">Eliminar</button>';
								}?>
								<a href="index.php">
									<button class="btn btn-primary btn-lg ml-3" id="create_child" type="button">Volver</button>
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
		<!-- Footer-->
		<?php include '../general/footer_template.php'; ?>
		<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
		<div class="scroll-to-top d-lg-none position-fixed mt-5">
			<a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
		</div>
		<!-- Contact form JS-->
		<script src="../../assets/mail/jqBootstrapValidation.js"></script>
		<!-- Core theme JS-->
		<script src="../../js/scripts.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				let sr = new webkitSpeechRecognition();

				$("#audio-name").mousedown(function(){
					recognition("#name");
				});

				function  recognition(id){
					// start recognition speech
					sr.start();

					const $consequences = document.querySelector(id);

					sr.onresult = result => {
						let last_element = result.results.length - 1;
						let text_listened = result.results[last_element][0].transcript;
						if ($consequences.value != "") {
							$consequences.value += " " + text_listened;
						} else {
							$consequences.value = text_listened;
						}
					}

					sr.onend = () => {
						// Stop when the audio finish
						sr.stop()
					};
				}
			});
		</script>
	</body>
</html>
