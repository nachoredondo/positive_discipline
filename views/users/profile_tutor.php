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
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container mb-5">
                <!-- Contact Section Heading-->
                <h2 class="text-white">.</h2>
                <h2 class="text-center text-uppercase text-secondary">Datos de usuario</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Contact Section Form-->
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <a href="profile_child.php">
                            <button class="btn btn-primary btn-xl ml-1" id="create_child" type="button">Crear usuario hijo</button>
                        </a>
                        <form id="contactForm" method="post" action="update_user.php" name="sentMessage" novalidate="novalidate">
                            <input class="form-control" id="type" name="type" type="hidden" required="required" value="<?php echo $_SESSION['type']?>" />
                            <input class="form-control" id="id-user" name="id" type="hidden" required="required" value="<?php echo $user->id();?>" />
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <label>Usuario</label>
                                    <input class="form-control" id="user" name="user" type="text" required="required" value="<?php echo $user->user();?>"data-validation-required-message="Por favor introduce el usuario." placeholder="Usuario" />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <label>Correo</label>
                                    <input class="form-control" id="email" name="email" type="email" placeholder="Correo" required="required" value="<?php echo $user->email();?>"data-validation-required-message="Por favor introduce el correo." />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <label>Name</label>
                                    <input class="form-control" id="name" name="name" type="text" placeholder="Nombre" required="required" value="<?php echo $user->name();?>"data-validation-required-message="Por favor introduce el nombre" />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <label>Name</label>
                                    <input class="form-control" id="name" name="surnames" type="text" placeholder="Apellidos" required="required" value="<?php echo $user->surnames();?>"data-validation-required-message="Por favor introduce los apellidos" />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <br />
                            <div id="success"></div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-xl ml-2" id="sendMessageButton" name="form" value="data" type="submit">Actualizar datos</button>
                            </div>
                        </form>
                        <form id="update-pwd" method="post" action="update_user.php" name="sentMessage" novalidate="novalidate">
                            <input class="form-control" id="type-pwd" name="type" type="hidden" required="required" value="<?php echo $_SESSION['type']?>" />
                            <input class="form-control" id="id-user-pwd" name="id" type="hidden" required="required" value="<?php echo $user->id();?>" />
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <label>Contraseña</label>
                                    <input class="form-control" id="pwd" name="pwd" type="text" placeholder="Contraseña" required="required" data-validation-required-message="Por favor introduce la contraseña." />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <label>Confirmar contraseña</label>
                                    <input class="form-control" id="pwd-confirm" name="pwd-confirm" type="text" placeholder="Contraseña" required="required" data-validation-required-message="Por favor confirma la contraseña." />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <br />
                            <div class="form-group">
                                <button class="btn btn-primary btn-xl ml-2" id="button-update-pwd" name="form" value="pwd" type="submit">Cambiar contraseña</button>
                                <button class="btn btn-primary btn-xl ml-2 mr-4" id="button-delete-tutor" name="form" value="delete-tutor" type="submit">Eliminar usuario</button>
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
