<?php
require '../../classes/session.php';
require '../../classes/user.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);
$action = $_REQUEST['action'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Gestor de turnos</title>
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../../assets/datatables/dataTables.bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <script src="../../assets/datatables/jquery.dataTables.min.js"></script>
        <script src="../../assets/datatables/dataTables.bootstrap.min.js"></script>
        <script src="../../assets/sweetalert/sweetalert.min.js"></script>
        <style>
            #the-table_length {
                margin-left:75px;
            }
        </style>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <!-- Rules Section Heading-->
            <div class="container mb-5">
                <h2 class="text-center text-uppercase text-secondary mt-4">Gestor de turnos</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Rules Section -->
                <div class="row">
                    <div class="col-lg-9 mx-auto">
                        <h4 class="row mt-2 mb-3 ml-1 text-info">Lista de turnos:</h4>
                        <div class="table-responsive">
                            <table id="the-table" class="table table-striped compact nowrap" style="min-width:100%">
                                <thead><!-- Leave empty. Column titles are automatically generated --></thead>
                            </table>
                        </div>
                        <a href="edit_create.php">
                            <button class="btn btn-primary btn-lg ml-5 mt-4" id="create_turn" type="button">Crear tarea</button>
                        </a>
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
            function make_request(path, params, method) {
                method = method || "post"; // Set method to post by default if not specified.

                var form = document.createElement("form");
                form.setAttribute("method", method);
                form.setAttribute("action", path);

                for (var key in params) {
                    if (params.hasOwnProperty(key)) {
                        var hiddenField = document.createElement("input");
                        hiddenField.setAttribute("type", "hidden");
                        hiddenField.setAttribute("name", key);
                        hiddenField.setAttribute("value", params[key]);

                        form.appendChild(hiddenField);
                    }
                }

                document.body.appendChild(form);
                form.submit();
            }

            function date_bbdd_end_day(date) {
                date = date.split("-");
                return new Date(date[0], date[1]-1, date[2], 23, 59, 59)
            }

            function date_bbdd_start_day(date) {
                date = date.split("-");
                return new Date(date[0], date[1]-1, date[2])
            }

            function not_realized(){
                return "<span class='text-danger'>" +
                            "<i class='fas fa-circle'></i> Sin realizar" +
                            "<button type='button' title='Realizar' class='check-btn btn btn-success btn-sm ml-2'><i class='fas fa-check'></i>" +
                            "</button>" +
                        "</span>";
            }

            function pending(){
                return "<span class='text-warning'>" +
                            "<i class='fas fa-circle'></i> Pendiente" +
                            "<button type='button' title='Realizar' class='check-btn btn btn-success btn-sm ml-2'><i class='fas fa-check'></i>" +
                            "</button>" +
                        "</span>";
            }

            function success(){
                return "<span class='text-success'>" +
                            "<i class='fas fa-circle'></i> Realizado" +
                        "</span>";
            }

            function finalized(){
                return "<span class='text-dark'>" +
                            "<i class='fas fa-circle'></i> Finalizado" +
                        "</span>";
            }

            function state_daily(date_modification, date_now) {
                date_end_day = date_bbdd_end_day(date_modification);
                let ms_day = 24 * 60 * 60 * 1000;
                let yesterday = new Date(date_now.getTime() - ms_day);
                yesterday_start = new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate())
                if (date_end_day <= yesterday_start) {
                    return not_realized();
                } else if (date_end_day <= date_now) {
                    return pending();
                } else {
                    return success();
                }
            }

            function start_day_week(date){
                let day = date.getDay() || 7;
                if (day !== 1) {
                    day -= 1;
                    date_end_week = date.setDate(date.getDate() - day);   // added days
                    date_end_week = new Date(date_end_week);
                } else {
                    date_end_week = date;
                }
                return new Date(date_end_week.getFullYear(), date_end_week.getMonth(), date_end_week.getDate());
            }

            function last_day_week(date){
                let day = date.getDay() || 7;
                if (day !== 7) {
                    if (day == 1) {
                        day = 6;
                    } else if (day == 2){
                        day = 5;
                    } else if (day == 3){
                        day = 4;
                    } else if (day == 4){
                        day = 3;
                    } else if (day == 5){
                        day = 2;
                    } else if (day == 6){
                        day = 1;
                    }
                    date_end_week = date.setDate(date.getDate() + day)   // added days
                } else {
                    date_end_week = date;
                }

                return new Date(date_end_week);
            }

            function state_weekly(date_modification, date_now) {
                let previous_week_now = date_now;
                previous_week_now = previous_week_now.setDate(previous_week_now.getDate() - 7);
                let date_start_previous = start_day_week(new Date(previous_week_now));
                date_modification = date_bbdd_end_day(date_modification);
                let date_end_week_modification = last_day_week(date_modification);
                date_now = new Date();
                if (date_end_week_modification <= date_start_previous) {
                    return not_realized();
                } else if (date_end_week_modification <= date_now) {
                    return pending();
                } else {
                    return success();
                }
            }

            function first_day_month(date) {
                let year = date.getFullYear();
                let month = date.getMonth();
                return new Date(year, month, 1);
            }

            function last_day_month(date) {
                let year = date.getFullYear();
                let month = date.getMonth();
                if (month == 11) {
                    year = year + 1;
                    month = 1;
                } else {
                    month += 1;
                }
                let first_day = new Date(year, month, 1);
                let last_day_month = first_day.setHours(-1);
                last_day = new Date(last_day_month);
                return new Date(date.getFullYear(), date.getMonth(), last_day.getDate(), 23, 59, 59)
            }

            function state_monthly(date_modification, date_now) {
                last_day_modification = last_day_month(date_modification);
                date_previous_month =  new Date(date_now.setMonth(date_now.getMonth() - 1));
                first_day_previous_month = first_day_month(date_previous_month);
                date_now = new Date();
                if (last_day_modification <= first_day_previous_month) {
                    return not_realized();
                } else if (last_day_modification <= date_now) {
                    return pending();
                } else {
                    return success();
                }
            }

            function search_previous_day(day_now, data) {
                let days = [
                    data.monday,
                    data.monday,
                    data.wenesday,
                    data.thursday,
                    data.friday,
                    data.saturday,
                    data.sunday
                ]
                let number_previous_day = 1;
                for (var i = days.length + day_now - 2; i > day_now - 2; i--) {
                    let aux = i % 7;
                    if (days[aux] == "1") {
                        break;
                    }
                    number_previous_day += 1;
                }
                return number_previous_day;
            }

            function pending_not_realized_days(day_now, data, date_now, date_end_day) {
                let previous_day = search_previous_day(day_now, data);
                previous_day = date_now.setDate(date_now.getDate() - previous_day);
                previous_day = new Date(previous_day);
                if (date_end_day < previous_day) {
                    return not_realized();
                } else {
                    return pending();
                }
            }

            function state_days(date_now, data) {
                date_end_day = date_bbdd_end_day(data.date_modification);
                let day_modification = date_end_day.getDay() || 7;
                let day_now  = date_now.getDay() || 7;
                if (day_modification == day_now) {
                    if ((day_now == 1 && data.monday == "1" && date_end_day > date_now) ||
                        (day_now == 2 && data.thursday == "1" && date_end_day > date_now) ||
                        (day_now == 3 && data.wenesday == "1" && date_end_day > date_now) ||
                        (day_now == 4 && data.tuesday == "1" && date_end_day > date_now) ||
                        (day_now == 5 && data.friday == "1" && date_end_day > date_now) ||
                        (day_now == 6 && data.saturday == "1" && date_end_day > date_now) ||
                        (day_now == 7 && data.sunday == "1" && date_end_day > date_now))
                        return success();
                    else
                        return pending_not_realized_days(day_now, data, date_now, date_end_day);
                } else
                    return pending_not_realized_days(day_now, data, date_now, date_end_day);
            }

            function state(data){
                let date_end = date_bbdd_end_day(data.date_end);
                let date_modification = date_bbdd_start_day(data.date_modification);
                let date_now = new Date();
                if (date_now <= date_end) {
                    if (data.daily == "1") {
                        return state_daily(data.date_modification, date_now);
                    } else if (data.weekly == "1") {
                        return state_weekly(data.date_modification, date_now);
                    } else if (data.monthly == "1") {
                        return state_monthly(date_modification, date_now);
                    } else {
                        return state_days(date_now, data);
                    }
                    return "Sin estado";
                } else {
                    return finalized();
                }
            }

            function control_description(data) {
                let max_description = 45;
                if (data.length > max_description) {
                    return data.substring(0,max_description) + "...";
                } else {
                    return data;
                }
            }

            function edit_rules(){
                let prueba = "<?php if ($_SESSION['type']){ echo '<button type=\"button\" title=\"Detalles\" class=\"edit-btn btn btn-info btn-sm\"><i class=\"fas fa-edit\"></i></button>';
                        } else  {
                            echo '';
                        }
                    ?>";
                return prueba;
            }

            window.addEventListener('load', function () {
                let table = $('#the-table').DataTable({
                    order: [[1, 'asc']],
                    serverSide: true,
                    bFilter: false,
                    language: {
                        url: "<?php echo APP_ROOT; ?>/assets/datatables/es.json",
                    },
                    columns: [
                        {
                            data: 't_name',
                            title: 'Nombre',
                        },
                        {
                            data: 'first_child_name',
                            title: 'Siguiente turno',
                        },
                        {
                            data: 'last_child_name',
                            title: 'Estado',
                            render: function (_, _, row) { return state(row) },
                            "searchable": false,
                        },
                        {
                            data: 'last_child_name',
                            title: 'Anterior turno',
                        },
                        {
                            sorting: false,
                            render: function (_, _, row) { return edit_rules() },
                            "searchable": false,
                        },
                    ],
                    ajax: {
                        method: 'POST',
                        url: "<?php echo APP_ROOT; ?>api/turn/list_task.php",
                        data: function (params) {
                            params.id_user =  <?php echo $user->id(); ?>;
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
                $('#the-table tbody').on('click', 'button', function () {
                    let data = table.row($(this).parents('tr')).data();
                    if (this.classList.contains('edit-btn')) {
                        make_request('<?php echo APP_ROOT ?>views/turn/edit_create.php', { id: data["t_id_task"] });
                    } else if (this.classList.contains('check-btn')) {
                        make_request('<?php echo APP_ROOT ?>views/turn/control.php',
                                        {
                                            id: data["t_id_task"],
                                            form: "Siguiente-turno",
                                        }
                                    );
                    } else {
                        console.error("Botón pulsado desconocido!");
                    }
                });
            });

            <?php if ($action === 'update_task'): ?>
                swal({
                    title: "Turno actualizado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'create_option'): ?>
                swal({
                    title: "Turno creado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'delete_task'): ?>
                swal({
                    title: "Turno borrado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'next_turn'): ?>
                swal({
                    title: "Turno actualizado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php endif; ?>

        </script>
    </body>
</html>
