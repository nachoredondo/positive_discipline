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
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container mb-5">
                <!-- Rules Section Heading-->
                <h2 class="text-white">.</h2>
                <h2 class="text-center text-uppercase text-secondary">Gestor de turnos</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Rules Section -->
                <div class="row">
                    <div class="col-lg-9 mx-auto">
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

            function edit_rules(){
                let prueba = "<?php if ($_SESSION['type']){ echo '<button type=\"button\" title=\"Realizado\" class=\"check-btn btn btn-info btn-sm mr-3\"><i class=\"fas fa-check\"></i></button><button type=\"button\" title=\"Detalles\" class=\"edit-btn btn btn-success btn-sm\"><i class=\"fas fa-edit\"></i></button>';
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
                    language: {
                        url: "<?php echo APP_ROOT; ?>/assets/datatables/es.json",
                    },
                    columns: [
                        {
                            data: 'name',
                            title: 'Name',
                        },
                        {
                            data: 'description',
                            title: 'Descripción',
                            "searchable": false,
                        },
                        // {
                        //     data: 'consequences',
                        //     title: 'Siguiente turno',
                        // },
                        // {
                        //     data: 'consequences',
                        //     title: 'Estado',
                        //     defaultContent: edit_rules(),
                        //     "searchable": false,
                        // },
                        // {
                        //     data: 'consequences',
                        //     title: 'Anterior turno',
                        // },
                        {
                            sorting: false,
                            defaultContent: edit_rules(),
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
                    console.log(data);
                    if (this.classList.contains('edit-btn')) {
                        make_request('<?php echo APP_ROOT ?>views/turn/edit_create.php', { id: data["t_id_task"] });
                    } else if (this.classList.contains('check-btn')) {
                        make_request('<?php echo APP_ROOT ?>views/turn/control.php', { id: data["t_id_task"] });
                    } else {
                        console.error("Botón pulsado desconocido!");
                    }
                });
            });

        </script>
    </body>
</html>
