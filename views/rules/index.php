<?php
require '../../classes/session.php';
require '../../classes/user.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);
$action = $_REQUEST['action'] ?? '';

$childs = [];
if ($_SESSION['type']) {
    $childs = User::get_childs($user->id());
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Normas</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="<?php echo APP_ROOT; ?>/assets/img/logo.png"/>
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
                <h2 class="text-center text-uppercase text-secondary mt-4 ml-5">
                    Normas
                    <button id="popoverId" class="popoverThis btn">
                        <i class="fas fa-question-circle fa-2x" title="Sección de ayuda"></i>
                    </button>
                    <div id="popoverContent" class="hide d-none">
                        <p>Tabla para gestionar normas</p>
                        <p>Solo los tutores pueden crear, editar y eliminar las normas.</p>
                    </div>
                </h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Rules Section -->
                <div class="row">
                    <div class="col-lg-9 mx-auto">
                        <h4 class="row mt-2 ml-1 mb-4 text-info">
                            <span class="mt-1">Lista de normas
                            <?php if ($_SESSION['type']): ?>
                                 de:
                            <?php else :
                                    echo ":";
                                endif;
                            ?>
                                </span>
                                <select class="form-control ml-3 col-5 col-sm-4 col-md-3" id="select-child" <?php if (!$_SESSION['type']) echo ' style="display:none"'; ?>>
                                    <option value='all'>los hijos</option>
                                    <?php
                                        foreach ($childs as $key => $value) {
                                            $id_child = $value['id'];
                                            $name_child = $value['name'];
                                            echo "<option value='$id_child'>$name_child</option>";
                                        }
                                    ?>
                                </select>
                        </h4>
                        <div class="table-responsive">
                            <table id="the-table" class="table table-striped compact nowrap" style="min-width:100%">
                                <thead><!-- Leave empty. Column titles are automatically generated --></thead>
                            </table>
                        </div>
                        <?php
                            if ($_SESSION['type']) {
                        ?>
                                <a href="edit_create.php">
                                    <button class="btn btn-primary btn-lg ml-5 mt-4" id="create_child" type="button">Crear norma</button>
                                </a>
                        <?php
                            }
                        ?>
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
                    return '<img class="mx-auto d-block" src="<?php echo APP_ROOT; ?>/files/img/rules/' + image + '" height="65">'
                }
            }

            function edit_rules(){
                let icons = "<button type='button' title='Editar' class='edit-btn btn btn-success btn-sm mr-2'><i class='fas fa-edit'></i></button><button type='button' title='Eliminar' class='remove-btn btn btn-info btn-sm'><i class='fas fa-trash-alt'></i></button>";
                return icons;
            }

            window.addEventListener('load', function () {
                let table = $('#the-table').DataTable({
                    order: [[1, 'asc']],
                    serverSide: true,
                    lengthMenu: [[5, 10, -1], [5, 10, 'Todas']],
                    language: {
                        url: "<?php echo APP_ROOT; ?>/assets/datatables/es.json",
                    },
                    columns: [
                        {
                            data: 'title',
                            title: 'Título',
                            render: function (_, _, row) { return max_text(row.title) },
                        },
                        // {
                        //     data: 'description',
                        //     title: 'Descripción',
                        //     "searchable": false,
                        // },
                        {
                            data: 'consequences',
                            title: 'Consequencias',
                            render: function (_, _, row) { return max_text(row.consequences) },
                        },
                        {
                            data: 'img_consequences',
                            title: 'Imagen consecuencias',
                            "searchable": false,
                            render: function (_, _, row) { return push_image(row.img_consequences) },
                            defaultContent: ' Sin imagen ',
                        },
                        <?php if ($_SESSION['type']): ?>
                        {
                            sorting: false,
                            defaultContent: edit_rules(),
                            "searchable": false,
                        },
                        <?php endif; ?>
                    ],
                    ajax: {
                        method: 'POST',
                        url: "<?php echo APP_ROOT; ?>api/rules/list_rules.php",
                        data: function (params) {
                            let select_child = document.getElementById('select-child');
                            params.child = select_child.options[select_child.selectedIndex].value;
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
                        make_request('<?php echo APP_ROOT ?>views/rules/edit_create.php', { id: data["id"] });
                    } else if (this.classList.contains('remove-btn')) {
                        swal({
                            title: "¿Estás seguro de que quieres borrar la norma?",
                            icon: "warning",
                            buttonsStyling: false,
                            buttons: ["No", "Si"],
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                make_request(
                                    '<?php echo APP_ROOT ?>views/rules/control.php',
                                    {
                                        id: data["id"],
                                        form: "delete"
                                    }
                                );
                            } else {
                                swal("La norma no ha sido borrada");
                            }
                        })
                        .catch(function() { writeToScreen('err: Hubo un error al borrar la norma.', true)});

                    } else {
                        console.error("Botón pulsado desconocido!");
                    }
                });

                function refresh_table() {
                    table.draw();
                }

                $('#select-child').on('change', function () {
                    refresh_table();
                })
            });


            <?php if ($action === 'update_rule'): ?>
                swal({
                    title: "Norma actualizada",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'create_option'): ?>
                swal({
                    title: "Norma creada",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'delete_rule'): ?>
                swal({
                    title: "Norma borrada",
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
