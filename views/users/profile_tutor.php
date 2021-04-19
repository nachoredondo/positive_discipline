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
        <title>User</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="<?php echo APP_ROOT; ?>/assets/img/logo.png"/>
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../../css/styles.css" rel="stylesheet" />

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
            <div class="container">
                <!-- Contact Section Heading-->
                <h2 class="text-center text-uppercase text-secondary mt-4">Datos de usuario</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Contact Section Form-->
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <h3 class="row mt-2 mb-3 ml-1 text-info">Tabla de niños:</h3>
                        <div class="table-responsive">
                            <table id="the-table" class="table table-striped compact nowrap" style="min-width:100%">
                                <thead><!-- Leave empty. Column titles are automatically generated --></thead>
                            </table>
                        </div>
                        <a href="profile_child.php">
                            <button class="btn btn-primary btn-lg ml-1 mb-2" id="create_child" type="button">Crear niño</button>
                        </a>
                        <h3 class="row mt-5 ml-1 text-info">Datos perfil:</h3>
                        <form id="contactForm" method="post" action="update_user.php" name="sentMessage" novalidate="novalidate">
                            <input class="form-control" id="type" name="type" type="hidden" required="required" value="<?php echo $_SESSION['type']?>" />
                            <input class="form-control" id="id-user" name="id" type="hidden" required="required" value="<?php echo $user->id();?>" />
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <div class="row ml-1">
                                        <label>Usuario</label>
                                        <i class="fas fa-microphone ml-3 mt-4" id="audio-user"></i>
                                    </div>
                                    <input class="form-control" id="user" name="user" type="text" required="required" value="<?php echo $user->user();?>"data-validation-required-message="Por favor introduce el usuario." placeholder="Usuario" />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <div class="row ml-1">
                                        <label>Correo</label>
                                        <i class="fas fa-microphone ml-3 mt-4" id="audio-email"></i>
                                    </div>
                                    <input class="form-control" id="email" name="email" type="email" placeholder="Correo" required="required" value="<?php echo $user->email();?>"data-validation-required-message="Por favor introduce el correo." />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <div class="row ml-1">
                                        <label>Nombre</label>
                                        <i class="fas fa-microphone ml-3 mt-4" id="audio-name"></i>
                                    </div>
                                    <input class="form-control" id="name" name="name" type="text" placeholder="Nombre" required="required" value="<?php echo $user->name();?>"data-validation-required-message="Por favor introduce el nombre" />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <div class="row ml-1">
                                        <label>Apellidos</label>
                                        <i class="fas fa-microphone ml-3 mt-4" id="audio-surnames"></i>
                                    </div>
                                    <input class="form-control" id="surnames" name="surnames" type="text" placeholder="Apellidos" required="required" value="<?php echo $user->surnames();?>"data-validation-required-message="Por favor introduce los apellidos" />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <br />
                            <div id="success"></div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-lg ml-2 mb-2" id="sendMessageButton" name="form" value="data" type="submit">Actualizar datos</button>
                            </div>
                        </form>
                        <h3 class="row mt-5 ml-1 text-info">Cambiar contraseña:</h3>
                        <form id="update-pwd" method="post" action="update_user.php" name="sentMessage" novalidate="novalidate">
                            <input class="form-control" id="type-pwd" name="type" type="hidden" required="required" value="<?php echo $_SESSION['type']?>" />
                            <input class="form-control" id="id-user-pwd" name="id" type="hidden" required="required" value="<?php echo $user->id();?>" />
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <label>Contraseña</label>
                                    <input class="form-control" id="pwd" name="pwd" type="password" placeholder="Contraseña" required="required" data-validation-required-message="Por favor introduce la contraseña." />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <label>Confirmar contraseña</label>
                                    <input class="form-control" id="pwd-confirm" name="pwd-confirm" type="password" placeholder="Contraseña" required="required" data-validation-required-message="Por favor confirma la contraseña." />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <br />
                            <div class="form-group">
                                <button class="btn btn-primary btn-lg ml-2 mb-2" id="button-update-pwd" name="form" value="pwd" type="submit">Cambiar contraseña</button>
                                <button class="btn btn-primary btn-lg ml-2 mb-2" id="button-delete-tutor" name="form" value="delete-tutor" type="submit">Eliminar usuario</button>
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
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
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

            function img_user(img) {
                return '<img id="img-user" src="../../assets/img/user_child/' + img + '" height="50" width="48"/>'
            }

            window.addEventListener('load', function () {
                let table = $('#the-table').DataTable({
                    order: [[1, 'asc']],
                    serverSide: true,
                    lengthMenu: [[5, 10, -1], [5, 10, 'Todos']],
                    language: {
                        url: "<?php echo APP_ROOT; ?>/assets/datatables/es.json",
                    },
                    columns: [
                        {
                            data: 'name',
                            title: 'Nombre',
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
                        url: "<?php echo APP_ROOT; ?>api/user/list_child.php",
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
                        make_request('<?php echo APP_ROOT ?>views/users/profile_child.php', { id: data["id"] });
                    } else if (this.classList.contains('remove-btn')) {
                        swal({
                            title: "¿Estás seguro de que quieres borrar el usuario?",
                            icon: "warning",
                            buttonsStyling: false,
                            buttons: ["No", "Si"],
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                make_request('<?php echo APP_ROOT ?>views/users/edit_update_child.php', {
                                    id: data["id"],
                                    from: 'delete_child'
                                });
                            } else {
                                swal("El usuario no ha sido borrado");
                            }
                        })
                        .catch(function() { writeToScreen('err: Hubo un error al borrar la norma.', true)});

                    } else {
                        console.error("Botón pulsado desconocido!");
                    }
                });
            });

            $(document).ready(function(){
                let sr = new webkitSpeechRecognition();
                $("#audio-user").mousedown(function(){
                    recognition("#user");
                });
                $("#audio-email").mousedown(function(){
                    recognition("#email");
                });
                $("#audio-name").mousedown(function(){
                    recognition("#name");
                });
                $("#audio-surnames").mousedown(function(){
                    recognition("#surnames");
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

            <?php if ($action === 'create_user'): ?>
                swal({
                    title: "Usuario creado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'update' || $action === 'data'): ?>
                swal({
                    title: "Usuario actualizado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'pwd'): ?>
                swal({
                    title: "Contraseña actualizada",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'delete_child'): ?>
                swal({
                    title: "Usuario borrado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php endif; ?>
        </script>
    </body>
</html>
