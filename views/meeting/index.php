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
        <title>Agenda</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="<?php echo APP_ROOT; ?>/assets/img/logo.png"/>
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../../css/styles.css" rel="stylesheet" />

        <link href='../../assets/fullcalendar/main.min.css' rel='stylesheet' />
        <script src='../../assets/fullcalendar/main.min.js'></script>
        <script src="../../assets/sweetalert/sweetalert.min.js"></script>
        <script>

        function inverse_date(date) {
            let date_parts = date.split("-");
            let date_inverse = date_parts[2] + "-" + date_parts[1] + "-" + date_parts[0];
            return date_inverse;
        }

        function control_description(data) {
            let max_description = 45;
            if (data.length > max_description) {
                return data.substring(0,max_description) + "...";
            } else {
                return data;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                firstDay: 1,
                eventSources: [
                    {
                        url: '<?php echo APP_ROOT ?>/api/meeting/calendar_events.php',
                        method: 'POST',
                        color: '#32CD32',
                        textColor: '#fafafa',
                        className: 'font-weight-bold event-item'
                    },
                ],
                eventClick: function(info) {
                    let id = info.event['id'];
                    $.post({
                        url: "<?php echo APP_ROOT ?>/api/meeting/calendar_meeting.php",
                        data: {
                            id: id
                        },
                    }).done(function (data) {
                        let json = JSON.parse(data);
                        let date = inverse_date(json['date']);
                        let description = control_description(json['description']);
                        let modal = $('#new-event').modal();
                        modal.find('.meeting-title').text(json['title']);
                        modal.find('.modal-body .description').text(description);
                        modal.find('.modal-body .date').text(date);
                        modal.find('.modal-body .hour-start').text(json['start']);
                        modal.find('.modal-body .hour-end').text(json['end']);
                        modal.find('.modal-body .record').text(json['file_act']);
                        document.getElementById("record").href = '<?php echo APP_ROOT ?>/files/meeting/'+json['file_act'];
                        modal.find('.modal-footer [name=id]')[0].value = id;
                    }).fail(function(xhr) {
                        console.error(xhr);
                    });
                },
            });
            calendar.render();
          });

        </script>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container mb-5">
                <!-- Meeting Section Heading-->
                <h2 class="text-center text-uppercase text-secondary mt-4 ml-5">
                    Agenda
                    <button id="popoverId" class="popoverThis btn">
                        <i class="fas fa-question-circle fa-2x" title="Sección de ayuda"></i>
                    </button>
                    <div id="popoverContent" class="hide d-none">
                        <p>Agenda familiar para gestionar juntas por medio de un calendario.</p>
                        <p>Estas juntas se podrán editar, crear y eliminar por tutores y/o niños de más de 10 años.</p>
                    </div>
                </h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Meeting Section -->
                <div class="row">
                    <div class="col-lg-11 mx-auto">
                        <div id='calendar'></div>
                    </div>
                </div>

                <?php if ($user->age() > 10 || $_SESSION['type']): ?>
                    <div class="text-center">
                        <a href="edit_create.php">
                            <button class="btn btn-primary btn-xl mt-4" id="create_child" type="button">Crear junta</button>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <!-- Footer-->
        <?php include '../general/footer_template.php'; ?>
        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
        <div class="scroll-to-top d-lg-none position-fixed mt-5">
            <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
        </div>

        <div class="modal fade" id="new-event" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h5 class="text-secondary m-0">Información de junta</h5>
                            <h4 class="meeting-title text-center mt-3"></h4>
                        </div>
                    </div>
                    <div class="modal-body">
                        <table class="totals-table table-bordered m-0 w-100">
                            <tr>
                                <th colspan="2" class="p-2 text-center">Datos de la junta</th>
                            </tr>
                            <tr>
                                <th class="p-2">Descripción</th>
                                <td><span class="p-2 description text-muted"></span></td>
                            </tr>
                            <tr>
                                <th class="p-2">Fecha</th>
                                <td><span class="p-2 date text-muted"></span></td>
                            </tr>
                            <tr>
                                <th class="p-2">Hora inicio</th>
                                <td><span class="p-2 hour-start text-muted"></span></td>
                            </tr>
                            <tr>
                                <th class="p-2">Hora fin</th>
                                <td><span class="p-2 hour-end text-muted"></span></td>
                            </tr>
                            <tr>
                                <th class="p-2">Acta</th>
                                <td><a href="#" class="p-2 record text-muted" id="record"></a></td>
                            </tr>
                        </table>
                    </div>
                    <form class="form" method="POST" action="./edit_create.php" style="margin: 0">
                        <div class="modal-footer justify-content-between mb-3">
                            <input type="hidden" name="id"/>
                            <button type="button" class="btn btn-secondary my-0 ml-4" data-dismiss="modal">Cerrar</button>
                            <?php if ($user->age() > 10 || $_SESSION['type']): ?>
                                <button type="submit" class="btn btn-primary my-0 mr-4">Editar junta</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Contact form JS-->
        <script src="../../assets/mail/jqBootstrapValidation.js"></script>
        <!-- Core theme JS-->
        <script src="../../js/scripts.js"></script>
        <script type="text/javascript">
            <?php if ($action === 'create_meeting'): ?>
                swal({
                    title: "Junta creada",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'update_meeting'): ?>
                swal({
                    title: "Junta actualizada",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'delete_meeting'): ?>
                swal({
                    title: "Junta borrada",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php endif; ?>

            $(document).ready(function(){
                $('[data-toggle="popover"]').popover({
                    placement: 'bottom',
                    html: true,
                })
            });

            $('#popoverId').popover({
                html: true,
                title: 'Sección de ayuda',
                placement: 'bottom',
                content: $('#popoverContent').html(),
            });

            $('#popoverId').click(function (e) {
                e.stopPropagation();
            });

            $(document).click(function (e) {
                if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
                    $('#popoverId').popover('hide');
                }
            });

        </script>
    </body>
</html>
