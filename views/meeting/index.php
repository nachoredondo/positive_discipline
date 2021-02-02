<?php
require '../../classes/session.php';
require '../../classes/user.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>User</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="../../assets/img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../../css/styles.css" rel="stylesheet" />

        <link href='../../assets/fullcalendar/main.min.css' rel='stylesheet' />
        <script src='../../assets/fullcalendar/main.min.js'></script>
        <script>

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
                        let modal = $('#new-event').modal();
                        modal.find('.meeting-title').text(json['title']);
                        modal.find('.modal-body .description').text(json['description']);
                        modal.find('.modal-body .date').text(json['date']);
                        modal.find('.modal-body .hour-start').text(json['start']);
                        modal.find('.modal-body .hour-end').text(json['end']);
                        modal.find('.modal-body .record').text(json['record']);
                        modal.find('.modal-body .responsable').text(json['responsable']);
                        modal.find('.modal-footer [name=id]')[0].value = id

                    }).fail(function(xhr) {
                        console.error(xhr);
                    });

                    // change the border color just for fun
                    // info.el.style.borderColor = 'purple';
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
                <h2 class="text-white">.</h2>
                <h2 class="text-center text-uppercase text-secondary">Juntas</h2>
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
                        <div class="modal-title text-truncate">
                            <h6 class="text-secondary m-0">Información de junta</h6>
                            <h5 class="meeting-title title text-truncate my-0"></h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="totals-table table-bordered m-0 w-100">
                            <tr>
                                <th colspan="2" class="text-center">Datos de la junta</th>
                            </tr>
                            <tr>
                                <th>Descripción</th>
                                <td><span class="description text-muted"></span></td>
                            </tr>
                            <tr>
                                <th>Fecha</th>
                                <td><span class="date text-muted"></span></td>
                            </tr>
                            <tr>
                                <th>Hora inicio</th>
                                <td><span class="hour-start text-muted"></span></td>
                            </tr>
                            <tr>
                                <th>Hora fin</th>
                                <td><span class="hour-end text-muted"></span></td>
                            </tr>
                            <tr>
                                <th>Record</th>
                                <td><span class="record text-muted"></span></td>
                            </tr>
                            <tr>
                                <th>Responsable</th>
                                <td><span class="responsable text-muted"></span></td>
                            </tr>
                        </table>
                    </div>
                    <form class="form" method="POST" action="../custom-reports/obras.php" style="margin: 0">
                        <div class="modal-footer justify-content-between">
                            <input type="hidden" name="id"/>
                            <button type="button" class="btn btn-secondary my-0 ml-1" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary my-0 ml-1">Editar junta</button>
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
    </body>
</html>
