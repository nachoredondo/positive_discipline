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
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <script src="<?php echo APP_ROOT ?>/node_modules/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../../assets/datatables/dataTables.bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <script src="../../assets/datatables/jquery.dataTables.min.js"></script>
        <script src="../../assets/datatables/dataTables.bootstrap.min.js"></script>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container mb-5">
                <!-- Rules Section Heading-->
                <h2 class="text-white">.</h2>
                <div class="mr-5">
                    <h2 class="text-center text-uppercase text-secondary">Rueda</h2>
                    <!-- Icon Divider-->
                    <div class="divider-custom">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                </div>
                <!-- Wheel Section -->
                <h3 class="text-uppercase text-center text-secondary mb-3">Listado de opciones</h3>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <div class="table-responsive">
                            <table id="the-table" class="center table table-striped compact nowrap" style="min-width:100%">
                                <thead><!-- Leave empty. Column titles are automatically generated --></thead>
                            </table>
                        </div>
                        <a href="edit_create.php">
                            <button class="btn btn-primary btn-lg ml-5 mt-4" id="create_child" type="button">Crear norma</button>
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

            function push_image(image){
                if (image == "") {
                    return "<em>Sin imagen</em>";
                } else {
                    return '<img src="<?php echo APP_ROOT; ?>/files/img/wheel/' + image + '" height="80">'
                }
            }

            window.addEventListener('load', function () {
                let table = $('#the-table').DataTable({
                    order: [[1, 'asc']],
                    serverSide: true,
                    "searching": false,
                    "bPaginate": false,
                    "info": false,
                    language: {
                        url: "<?php echo APP_ROOT; ?>/assets/datatables/es.json",
                    },
                    columns: [
                        {
                            data: 'name',
                            title: 'Nombre',
                        },
                        {
                            data: 'image',
                            title: 'Imagen',
                            "searchable": false,
                            render: function (_, _, row) { return push_image(row.image) },
                            defaultContent: ' Sin imagen ',
                        },
                        {
                            sorting: false,
                            defaultContent:
                                '<button type="button" title="Detalles" class="edit-btn btn btn-success btn-sm mr-2"><i class="fas fa-edit"></i></button>' +
                                '<button type="button" title="Informe" class="remove-btn btn btn-info btn-sm"><i class="fas fa-trash-alt"></i></button>',
                            "searchable": false,
                        },
                    ],
                    ajax: {
                        method: 'POST',
                        data: {
                            'id':'<?php echo $user->id(); ?>'
                        },
                        url: "<?php echo APP_ROOT; ?>api/wheel/list_wheel.php",
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
                        make_request('<?php echo APP_ROOT ?>/views/wheel/edit_create.php', { id: data["id"] });
                    } else if (this.classList.contains('remove-btn')) {
                        make_request(
                            '<?php echo APP_ROOT ?>/views/wheel/control.php',
                            {
                                id: data["id"],
                                form: "delete"
                            }
                        );
                    } else {
                        console.error("Unknown button pressed!");
                    }
                });
            });

        </script>
    </body>
</html>
