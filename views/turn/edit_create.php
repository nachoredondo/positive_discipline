<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/task.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);
$responsable_age = User::get_responsable($_SESSION['user']);

if (isset($_REQUEST['id'])) {
	$task = Task::get_task_by_id($_REQUEST['id']);
	$date_start = inverse_date($task->date_start());
	$date_end = inverse_date($task->date_end());
	$date_modification = inverse_date($task->date_modification());
	// $time_start = $task->time_start();
	// $time_end = $task->time_end();
	$value_submit = "Editar";
} else {
	$task = new Task();
	$date_start = date("d-m-Y");
	$date_end = strtotime ('1 year' , strtotime($date_start)); // year is added
	$date_end = date ('d-m-Y', $date_end);
	$date_modification = date("d-m-Y", time());
	// $time_start = date("G:i", time());
	// $time_end = date("G:i", time());
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
		<title>Crear tarea</title>
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../../assets/datatables/dataTables.bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
		<script src="../../assets/datatables/jquery.dataTables.min.js"></script>
		<script src="../../assets/datatables/dataTables.bootstrap.min.js"></script>

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
				<h2 class="text-center text-uppercase text-secondary"><?php echo $value_submit;?> tarea
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
							<input name="id" type="hidden" value="<?php echo $_REQUEST['id']; ?>"/>
							<input name="id_educator" type="hidden" value="<?php echo $user->id(); ?>"/>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Nombre</label>
										<i class="fas fa-microphone ml-3 mt-4" id="audio-name"></i>
									</div>
									<input class="form-control mr-5" id="name" name="name" type="text" required="required" data-validation-required-message="Introduzca el nombre." placeholder="Título" value="<?php echo $task->name; ?>"/>
									<p class="help-block text-danger" required style="display:none;"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Descripción</label>
										<i class="fas fa-microphone ml-3 mt-4" id="audio-description"></i>
									</div>
									<input class="form-control" id="description" name="description" type="textarea" placeholder="Descripción..." required="required" data-validation-required-message="Introduzca la descripción."value="<?php echo $task->description; ?>"/>
									<p class="help-block text-danger" style="display:none;"></p>
								</div>
							</div>
							<div class="control-group">
								 <div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="table-responsive">
										<table id="the-table" class="table table-striped compact nowrap" style="min-width:100%">
											<thead><!-- Leave empty. Column titles are automatically generated --></thead>
										</table>
									</div>
									<p class="help-block text-danger" style="display:none;"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Fecha inicio</label>
									</div>
									<input type="text" class="form-control monthpicker" name="date_start" autocomplete="off" value="<?php echo $date_start; ?>"/>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Fecha fin</label>
									</div>
									<input type="text" class="form-control monthpicker" name="date_end" autocomplete="off" value="<?php echo $date_end; ?>"/>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Fecha modificación</label>
									</div>
									<input type="text" class="form-control monthpicker" name="date_modification" autocomplete="off" value="<?php echo $date_modification; ?>"/>
								</div>
							</div>
							<!-- <div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2 d-flex align-items-center">
									<div class="col">
										<div class="row ml-1">
											<label>Hora desde</label>
										</div>
										<div class="input-group clockpicker">
											<input type="text" class="form-control" name="time_start" value="<?php echo $time_start; ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
									</div>
									<div class="col">
										<div class="row ml-1">
											<label>Hora hasta</label>
										</div>
										<div class="input-group clockpicker">
											<input type="text" class="form-control" name="time_end"  value="<?php echo $time_end; ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
									</div>
								</div>
							</div> -->
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Frecuencia</label>
									</div>
									<div class="ml-3">
										<div class="form-check">
											<input class="form-check-input check_child" name="daily" type="checkbox" onclick="frecuency_not_day(this)" id="daily" <?php if ($task->daily) echo "checked"; ?>
											/> Diariamente
										</div>
										<div class="form-check">
											<input class="form-check-input check_child" name="weekly" type="checkbox" onclick="frecuency_not_day(this)" id="weekly" <?php if ($task->weekly) echo "checked"; ?>
											/> Semanalmente
										</div>
										<div class="form-check">
											<input class="form-check-input check_child" name="monthly" type="checkbox" onclick="frecuency_not_day(this)" id="monthly" <?php if ($task->monthly) echo "checked"; ?>
											/> Mensualmente
										</div>
										<div class="form-check">
											<input class="form-check-input check_child" name="monday" type="checkbox" onclick="frecuency_day(this)" id="monday" <?php if ($task->monday) echo "checked"; ?>
											/> Lunes
										</div>
										<div class="form-check">
											<input class="form-check-input check_child" name="thursday" type="checkbox" onclick="frecuency_day(this)" id="thursday" <?php if ($task->thursday) echo "checked"; ?>
											/> Martes
										</div>
										<div class="form-check">
											<input class="form-check-input check_child" name="wenesday" type="checkbox" onclick="frecuency_day(this)" id="wenesday" <?php if ($task->wenesday) echo "checked"; ?>
											/> Miércoles
										</div>
										<div class="form-check">
											<input class="form-check-input check_child" name="tuesday" type="checkbox" onclick="frecuency_day(this)" id="tuesday" <?php if ($task->tuesday) echo "checked"; ?>
											/> Jueves
										</div>
										<div class="form-check">
											<input class="form-check-input check_child" name="friday" type="checkbox" onclick="frecuency_day(this)" id="friday" <?php if ($task->friday) echo "checked"; ?>
											/> Viernes
										</div>
										<div class="form-check">
											<input class="form-check-input check_child" name="saturday" type="checkbox" onclick="frecuency_day(this)" id="saturday" <?php if ($task->saturday) echo "checked"; ?>
											/> Sábado
										</div>
										<div class="form-check">
											<input class="form-check-input check_child" name="sunday" type="checkbox" onclick="frecuency_day(this)" id="sunday" <?php if ($task->sunday) echo "checked"; ?>
											/> Domingo
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
			let not_day_frecuency = ['daily', 'weekly', 'monthly'];
			let all_frecuency = not_day_frecuency.concat(['monday', 'thursday', 'wenesday', 'tuesday', 'friday', 'saturday', 'sunday']);

			function frecuency_day(element){
				if (element.checked) {
					for (var i = 0; i < not_day_frecuency.length; i++) {
						let input = document.getElementById(not_day_frecuency[i]);
						if (input.checked){
							input.checked = false;
						}
					}
				}
			}

			function frecuency_not_day(element){
				if (element.checked) {
					for (var i = 0; i < all_frecuency.length; i++) {
						let input = document.getElementById(all_frecuency[i]);
						if (input.checked && element != input){
							input.checked = false;
						}
					}
				}
			}

			$('.clockpicker').clockpicker({
				donetext: 'Confirmar'
			});

			$(document).ready(function(){
				let sr = new webkitSpeechRecognition();

				$("#audio-name").mousedown(function(){
					recognition("#name");
				});

				$("#audio-description").mousedown(function(){
					recognition("#description");
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

			function img_user(img) {
				return '<img id="img-user" src="../../assets/img/user_child/' + img + '" height="50" width="48"/>';
			}

			function update_select(select_object){
				let value_select = select_object.value;
				let selects = document.getElementsByClassName("child_selected");
				let number_child = selects.length;
				let all_positions = []
				let previous_positions = [];
				for (var i = 0; i < number_child; i++) {
					all_positions.push(i+1)
					previous_positions.push(parseInt(selects[i].value));
				}
				all_positions = all_positions.filter(function (item) {
				    return previous_positions.indexOf(item) === -1;
				});
				for (var i = 0; i < number_child; i++) {
					if (selects[i].value == value_select && selects[i] != select_object){
						value_select = all_positions[0];
						let parent_select = selects[i].parentNode;
						selects[i].remove();
						let select = create_select(number_child-1, value_select);
						parent_select.appendChild(select);
					}
				}
			}

			function create_select(number_child, value_select=null){
				let select = document.createElement("select");
				select.classList.add("form-check-input");
				select.classList.add("child_selected");
				select.name = "id_child_position[]";
				select.setAttribute("onchange", "update_select(this)");
				for (var i = 1; i <= number_child + 1; i++) {
					let option = document.createElement("option");
					option.value = i;
					option.text = i;
					if (value_select == null && number_child + 1 == i) {
						option.selected = true;
					} else if (value_select == i) {
						option.selected = true;
					}
					select.add(option);
				}
				return select;
			}

			function option_remove(number_child, value_select_deleted) {
				if (document.getElementsByClassName("child_selected")) {
					let selects = document.getElementsByClassName("child_selected");
					for (var i = 0; i < number_child; i++) {
						value_select = selects[i].value;
						let parent_select = selects[i].parentNode;
						selects[i].remove();
						if (value_select_deleted < value_select) {
							value_select -= 1;
						}
						let select = create_select(number_child-1, value_select);
						parent_select.appendChild(select);
					}
				}
			}

			function option_add(number_child) {
				if (document.getElementsByClassName("child_selected")) {
					let selects = document.getElementsByClassName("child_selected");
					for (var i = 0; i < number_child; i++) {
						var option = document.createElement("option");
						option.text = number_child + 1;
						option.value = number_child + 1;
						selects[i].add(option);
					}
				}
			}

			function select_turn(child_selected) {
				let tr = child_selected.parentNode.parentNode.parentNode;
				let div_select = tr.getElementsByClassName("turn")[0];
				if(child_selected.checked) {
					let number_child = document.getElementsByClassName("child_selected").length;
					div_select.firstChild.remove();
					option_add(number_child);
					let select = create_select(number_child);
					div_select.appendChild(select);
				} else {
					let value_select_deleted = div_select.firstChild.value;
					div_select.firstChild.remove();
					let number_child = document.getElementsByClassName("child_selected").length;
					option_remove(number_child, value_select_deleted);
					let span = document.createElement("span");
					let textContent = document.createTextNode("Sin escoger");
					span.appendChild(textContent);
					div_select.appendChild(span);
				}
			}

			function check_user(id_task, id_user) {
				let check = "";
				if (id_task) {
					check = "checked";
				}
				return  '<div class="form-check">' +
							'<input onclick="select_turn(this)" class="form-check-input check_child" name="id_user_child[]" type="checkbox" value="' + id_user + '" id="flexCheckDefault" ' + check + ' >' +
						'</div>';
			}

			function position_user(position, number_child) {
				let element = '<div class="form-check turn">';
				if (position != null) {
					element += '<select class="form-check-input child_selected" id="slcAutos" name="id_child_position[]" onchange="update_select(this)">';
					for (var i = 1; i <= number_child; i++) {
						element += '<option value="' + i + '"';
						if (i == position) {
							element += 'selected';
						}
						element += '>' + i + '</option>';
					}
					element += '</select>';
				} else {
					element += "<span>Sin escoger</span>"
				}

				 element += '</div>';

				return element;
			}

			window.addEventListener('load', function () {
				let table = $('#the-table').DataTable({
					order: [[1, 'asc']],
					serverSide: true,
					bPaginate: false,
					bFilter: false,
					bInfo: false,
					language: {
						url: "<?php echo APP_ROOT; ?>/assets/datatables/es.json",
					},
					columns: [
						{
							sorting: false,
							title:'Hijo escogido',
							render: function (_, _, row) { return check_user(row.id_task, row.id) },
							"searchable": false,
						},
						{
							data: 'name',
							title: 'Nombre',
						},
						{
							sorting: false,
							title:'Posición siguiente turno',
							render: function (_, _, row) { return position_user(row.position, row.number_child) },
							"searchable": false,
						},
						{
							data: 'age',
							title: 'Edad',
							"searchable": false,
						},
						{
							data: 'image',
							title: 'Contraseña',
							render: function (_, _, row) { return img_user(row.password) },
							defaultContent: ' - ',
						},
					],
					ajax: {
						method: 'POST',
						url: "<?php echo APP_ROOT; ?>api/user/list_child_task.php",
						data: function (params) {
							params.id_user =  <?php echo $user->id(); ?>;
							params.id_task =  "<?php
												if (isset($_REQUEST['id'])) {
													echo $_REQUEST['id'];
												} else {
													echo 'NULL';
												}
											?>";
							return params;
						},
						error: function(xhr) {
							if (xhr.status === 401) { // Session expired
								window.location.reload();
							} else {
								console.log(xhr);
							}
						},
					},
				});
			});

		</script>
	</body>
</html>
