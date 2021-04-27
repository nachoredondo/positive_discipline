<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/meeting.php';

Session::check_login_redirect();
$meeting = Meeting::get_meeting_by_id($_REQUEST['id']);

$act = $meeting->file_act();
if ($act == "") {
	$value_submit = "Crear";
} else {
	$value_submit = "Editar";
}
// exit();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<meta name="description" content="" />
		<meta name="author" content="" />
		<title>Agenda</title>
		<!-- Favicon-->
		<link rel="icon" type="image/x-icon" href="<?php echo APP_ROOT; ?>/assets/img/logo.png"/>
		<!-- Font Awesome icons (free version)-->
		<script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
		<!-- Google fonts-->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
		<link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
		<!-- Core theme CSS (includes Bootstrap)-->
		<link href="../../css/styles.css" rel="stylesheet"/>
				<!-- Bootstrap core JS-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Third party plugin JS-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
		<script src="<?php echo APP_ROOT ?>/assets/js/moment.js" type="text/javascript"></script>
		<script src="../../assets/sweetalert/sweetalert.min.js"></script>
	</head>
	<body id="page-top">
		<!-- Navigation-->
		<?php include '../general/headerbar.php' ?>
		<!-- Contact Section-->
		<section class="page-section" id="contact">
			<div class="container mb-3">
				<!-- Contact Section Heading-->
				<h2 class="text-white">.</h2>
				<h2 class="text-center text-uppercase text-secondary"><?php echo $value_submit;?> acta
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
						<form id="contactForm" method="post" action="create_meeting_minutes.php" name="sentMessage" novalidate="novalidate" enctype="multipart/form-data">
							<input name="id" type="hidden" value="<?php echo $_POST['id']; ?>"/>
							<input name="file_saved" type="hidden" value="<?php echo $meeting->file_act(); ?>"/>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls pb-4 d-flex align-items-center">
									<div class="row ml-1 col-12 col-lg-4">
										<div clas="row">
											<?php if ($act != "") {
	                                                echo "<a href='" . APP_ROOT . "files/meeting/" . $act . "' class='p-2 record text-muted' id='record' download><button type='button' title='Editar' class='edit-btn btn btn-info mr-2'>Descargar <i class='fas fa-download'></i></button></a>";
	                                                echo "<label for='files'>Cambiar acta</label>";
	                                            } else {
	                                                echo '<div class="row ml-1">
	                                                    <label>Adjuntar acta</label>
	                                                    <label class="text-danger ml-2">✱</label>
	                                                </div>';
	                                            }
	                                        ?>
											<input id="file_image" type="file" class="form-control" name="fimagen" accept="image/gif, image/jpg, image/jpeg, image/png, application/pdf, application/vnd.ms-excel, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword, .docx" style="font-size: large"/>
											<span id="text_file_image">Ningún archivo seleccionado</span>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group mt-3">
								<button class="btn btn-primary btn-lg ml-2 mb-2" id="createEditButton" name="form" value="<?php echo $value_submit;?>" type="submit"><?php echo $value_submit;?></button>
								<a href="index.php">
									<button class="btn btn-primary btn-lg ml-2 mb-2" id="create_child" type="button">Volver</button>
								</a>
								<button class="btn btn-primary btn-lg ml-2 mb-2" id="deleteButton" name="form" value="delete" type="submit">Eliminar</button>
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
			let file_image = document.getElementById("file_image");
			let text_file_image = document.getElementById("text_file_image");
			file_image.onchange = function () {
				text_file_image.innerHTML = file_image.files[0].name;
			};
		</script>
	</body>
</html>
