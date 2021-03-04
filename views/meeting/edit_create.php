<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/meeting.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);
$responsable_age = User::get_responsable($_SESSION['user']);

if (isset($_REQUEST['id'])) {
	$meeting = Meeting::get_meeting_by_id($_REQUEST['id']);
	$meeting_date = inverse_date($meeting->date);
	$meeting_start = $meeting->start;
	$meeting_end = $meeting->end;
	$value_submit = "Editar";
} else {
	$meeting = new Meeting();
	$meeting_date = date("d-m-Y", time() + 86400); // a day is added to calculate tomorrow
	$meeting_start = "00:00";
	$meeting_end = "00:00";
	$value_submit = "Crear";
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<title>Norma</title>
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
		<script src="<?php echo APP_ROOT ?>/assets/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
		<link rel="stylesheet" href="<?php echo APP_ROOT ?>/assets/css/bootstrap-datetimepicker.min.css" />

		<script src="<?php echo APP_ROOT ?>/assets/clockpicker/bootstrap-clockpicker.min.js" type="text/javascript"></script>
		<script src="<?php echo APP_ROOT ?>/assets/clockpicker/jquery-clockpicker.min.js" type="text/javascript"></script>
		<script src="<?php echo APP_ROOT ?>/assets/clockpicker/clockpicker.js" type="text/javascript"></script>
		<link rel="stylesheet" href="<?php echo APP_ROOT ?>/assets/clockpicker/bootstrap-clockpicker.min.css" />
		<link rel="stylesheet" href="<?php echo APP_ROOT ?>/assets/clockpicker/jquery-clockpicker.min.css" />
		<link rel="stylesheet" href="<?php echo APP_ROOT ?>/assets/clockpicker/clockpicker.css" />
		<link rel="stylesheet" href="<?php echo APP_ROOT ?>/assets/clockpicker/standalone.css" />
	</head>
	<body id="page-top">
		<!-- Navigation-->
		<?php include '../general/headerbar.php' ?>
		<!-- Contact Section-->
		<section class="page-section" id="contact">
			<div class="container mb-3">
				<!-- Contact Section Heading-->
				<h2 class="text-white">.</h2>
				<h2 class="text-center text-uppercase text-secondary"><?php echo $value_submit;?> norma
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
						<form id="contactForm" method="post" action="create_meeting.php" name="sentMessage" novalidate="novalidate" enctype="multipart/form-data">
							<input name="id" type="hidden" value="<?php echo $_POST['id']; ?>"/>
							<input name="file_saved" type="hidden" value="<?php echo $meeting->file_act(); ?>"/>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Título</label>
										<i class="fas fa-microphone ml-3 mt-4" id="audio-title"></i>
									</div>
									<input class="form-control mr-5" id="title" name="title" type="text" required="required" data-validation-required-message="Introduzca el título." placeholder="Título" value="<?php echo $meeting->title; ?>"/>
									<p class="help-block text-danger" required style="display:none;"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Descripción</label>
										<i class="fas fa-microphone ml-3 mt-4" id="audio-description"></i>
									</div>
									<input class="form-control" id="description" name="description" type="textarea" placeholder="Descripción..." required="required" data-validation-required-message="Introduzca la descripción."value="<?php echo $meeting->description; ?>"/>
									<p class="help-block text-danger" style="display:none;"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Listado de temas a tratar</label>
										<i class="fas fa-microphone ml-3 mt-4" id="audio-topics"></i>
									</div>
									<textarea class="form-control" id="topics" name="topics"  placeholder="Temas..." rows="3" required="required" data-validation-required-message="Introduzca la descripción."><?php echo $meeting->topics; ?></textarea>
									<p class="help-block text-danger" style="display:none;"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Fecha</label>
									</div>
									<input type="text" id="date" class="form-control monthpicker" name="date" autocomplete="off" value="<?php echo $meeting_date; ?>"/>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2 d-flex align-items-center">
									<div class="col">
										<div class="row ml-1">
											<label>Desde hora</label>
										</div>
										<div class="input-group clockpicker">
											<input type="text" class="form-control" name="date_start" value="<?php echo $meeting_start; ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
									</div>
									<div class="col">
										<div class="row ml-1">
											<label>Hasta hora</label>
										</div>
										<div class="input-group clockpicker">
											<input type="text" class="form-control" name="date_end" value="<?php echo $meeting_end; ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls pb-4 d-flex align-items-center">
									<div class="col-4">
										<div class="row ml-1">
											<label>Responsable acta</label>
										</div>
										<select id="responsable_act" name="responsable_act" class="form-control mt-1">
											<!-- <option value="">Responable acta...</option> -->
											<?php
												foreach ($responsable_age as $responsable) {
													echo '<option value="'.$responsable["id"];
													if ($responsable['educator'] == "1") {
														echo ' selected';
													}
													echo '">'.$responsable["name"].'</option>';
												}
											?>
										</select>
									</div>
									<div class="mt-1">
										<div class="row ml-1">
											<label>Acta</label>
										</div>
										<input class="form-control" type="file" name="fimagen" accept="image/gif, image/jpeg, image/png, application/pdf, application/vnd.ms-excel, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword, .docx" style="font-size: large"/>
									</div>
								</div>
							</div>
							<div class="form-group mt-3">
								<button class="btn btn-primary btn-lg ml-3" id="createEditButton" name="form" value="<?php echo $value_submit;?>" type="submit"><?php echo $value_submit;?></button>
								<a href="index.php">
									<button class="btn btn-primary btn-lg ml-3" id="create_child" type="button">Volver</button>
								</a>
								<button class="btn btn-primary btn-lg ml-3" id="deleteButton" name="form" value="delete" type="submit">Eliminar</button>
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
			$('.clockpicker').clockpicker({
				donetext: 'Hecho'
			});

			$(document).ready(function(){
				let sr = new webkitSpeechRecognition();

				$("#audio-title").mousedown(function(){
					recognition("#title");
				});

				$("#audio-description").mousedown(function(){
					recognition("#description");
				});

				$("#audio-topics").mousedown(function(){
					recognition("#topics");
				});

				function  recognition(id){
					// start recognition speech
					sr.start();

					const $consequences = document.querySelector(id);

					sr.onresult = result => {
						let last_element = result.results.length - 1;
						let text_listened = result.results[last_element][0].transcript;
						if ($consequences.value != "") {
							if (id == "#topics") {
								$consequences.value += "\n- " + text_listened;
							} else {
								$consequences.value += " " + text_listened;
							}
						} else {
							if (id == "#topics") {
								$consequences.value = "- " + text_listened;
							} else {
								$consequences.value = text_listened;
							}
						}
					}

					sr.onend = () => {
						// Stop when the audio finish
						sr.stop()
					};
				}
			});

			$('.monthpicker').datetimepicker({
				format: 'DD-MM-YYYY',
				viewMode: 'months',
				useCurrent: false,
				icons: {
					time: "far fa-clock",
					date: "far fa-calendar",
					up: "fa fa-chevron-up",
					down: "fa fa-chevron-down",
					previous: 'fa fa-chevron-left',
					next: 'fa fa-chevron-right',
					today: 'fa fa-screenshot',
					clear: 'fa fa-trash',
					close: 'fas fa-times'
				},
			});

		</script>
	</body>
</html>
