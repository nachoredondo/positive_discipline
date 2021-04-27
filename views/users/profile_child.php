<?php
require '../../classes/session.php';
require '../../classes/user.php';

Session::check_login_redirect();

if (isset($_POST['id'])) {
    $user = User::get_user_from_id($_POST['id']);
} else if (!$_SESSION['type']) {
    $user = User::get_user_from_user($_SESSION['user']);
    $user_image = $user->image();
} else {
    $user = new User();
    $user_image = "bear.png";
}

if (!$_SESSION['type'] && !isset($_POST['id'])) {
    $tutor_child = explode("_", $_SESSION['user'])[0];
}
$action = $_REQUEST['action'] ?? '';
$success = $_REQUEST['success'] ?? '';
$message = $_REQUEST['message'] ?? '';

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
        <link href="../../css/styles.css" rel="stylesheet"/>
        <script src="../../assets/sweetalert/sweetalert.min.js"></script>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container mb-5">
                <!-- Contact Section Heading-->
                <div class="mr-2">
                    <h2 class="text-white">.</h2>
                    <h2 class="text-center text-uppercase text-secondary">Datos de usuario</h2>
                    <!-- Icon Divider-->
                    <div class="divider-custom">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                </div>
                <!-- Contact Section Form-->
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <form id="update-pwd" method="post" action="edit_update_child.php" name="sentMessage" novalidate="novalidate">
                            <input class="form-control" id="id" name="id" type="hidden" required="required" value="<?php echo $user->id();?>" />
                            <input class="form-control" id="tutor" name="tutor" type="hidden" placeholder="Usuario tutor" required="required" data-validation-required-message="Introduzca el usuario tutor." value="<?php echo ($_SESSION['type']) ? $_SESSION['user'] : $tutor_child;?>" />
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls">
                                    <div class="row ml-4">
                                        <label>Nombre</label>
                                        <i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-name"></i>
                                    </div>
                                    <input class="form-control ml-4" id="name" name="name" type="text" placeholder="Nombre" required="required" data-validation-required-message="Introduzca el nombre."
                                    value="<?php echo ($_SESSION['type']) ? ($_SESSION['type']) ? $user->name() : '' : $_SESSION['name'];?>" />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <?php if ($_SESSION['type']): ?>
                            <div class="control-group">
                                <div class="floating-label-form-group">
                                    <div class="col-8 col-sm-6">
                                        <label>Edad</label>
                                        <select id="age" name="age" class="form-control mt-0" style="font-size: large">
                                            <?php
                                                for ($i = 6; $i <= 17; $i++) {
                                                    echo '<option value="', $i,'" ';
                                                    if ($user->age() == $i)
                                                        echo 'selected ';
                                                    echo '>', $i, '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="control-group">
                                <div class="floating-label-form-group">
                                    <div class="col-8 col-sm-6">
                                            <label>Elegir foto</label>
                                        <select id="img-form" class="form-control mb-2" name="img" style="font-size: large">
                                            <option value="bear.png" <?php echo ($user->image() == "bear.png") ? "selected ": "";?>>Oso</option>
                                            <option value="dog.png" <?php echo ($user->image() == "dog.jpeg") ? "selected ": "";?>>Perro</option>
                                            <option value="whale.png" <?php echo ($user->image() == "whale.png") ? "selected ": "";?>>Ballena</option>
                                            <option value="pig.png" <?php echo ($user->image() == "pig.png") ? "selected ": "";?>>Cerdo</option>
                                            <option value="panda.png" <?php echo ($user->image() == "Panda.png") ? "selected ": "";?>>Panda</option>
                                            <option value="unicorn.png" <?php echo ($user->image() == "unicorn.png") ? "selected ": "";?>>Unicornio</option>
                                            <option value="captain.png" <?php echo ($user->image() == "captain.png") ? "selected ": "";?>>Capitán américa</option>
                                            <option value="ball.png" <?php echo ($user->image() == "ball.jpg") ? "selected ": "";?>>Pelota</option>
                                            <option value="robot.png"<?php echo ($user->image() == "robot.png") ? "selected ": "";?>>Robot</option>
                                            <option value="rocket.png" <?php echo ($user->image() == "rocket.png") ? "selected ": "";?>>Cohete</option>
                                        </select>
                                        <?php if ($_SESSION['type']): ?>
                                            <div class="ml-5 mt-2 mb-2">
                                                <img id="img-user-tutor" src="../../assets/img/user_child/<?php echo $user_image; ?>" height="110" width="102"/>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="form-group mt-2">
                                <button class="btn btn-primary btn-lg ml-3 mb-2" id="button-update-pwd" name="from" value="<?php echo ($_SESSION['type'] && (!isset($_POST['id']))) ? "create-child" : "update-user";?>" type="submit">
                                    <?php echo ($_SESSION['type'] && (!isset($_POST['id']))) ? "Crear" : "Editar";?>
                                </button>
                            <?php
                                if ($_SESSION['type'] && (isset($_POST['id']))):
                            ?>
                                    <button class="btn btn-primary btn-lg ml-2 mb-2" id="button-update-pwd" name="from" value="delete-child" type="submit">Eliminar</button>
                                </form>
                            <?php
                                endif;
                                if ($_SESSION['type']):
                            ?>
                                    <a href="profile_tutor.php">
                                        <button class="btn btn-primary btn-lg ml-2 mb-2" id="create_child" type="button">Volver</button>
                                    </a>
                            <?php
                                endif;
                            ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <?php include '../general/footer_template.php'; ?>
        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
        <div class="scroll-to-top d-lg-none position-fixed">
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
        <script type="text/javascript">
            $('#img-form').on('change', function () {
                let option = this.options[this.selectedIndex].value;
                let type = "<?php echo $_SESSION['type']; ?>";
                if (type) {
                    id_img = "img-user-tutor";
                } else {
                    id_img = "img-user";
                }
                let img = document.getElementById(id_img);
                img.src = "../../assets/img/user_child/" + option;
                $('#'+id_img).show({ duration: 500 });
            });

            $(document).ready(function(){
                let sr = new webkitSpeechRecognition();
                $("#audio-name").mousedown(function(){
                    recognition("#name");
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

            <?php if ($success): ?>
                swal({
                    title: '<?php echo $message; ?>',
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "error",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'update'): ?>
                swal({
                    title: "Usuario actualizado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php endif; ?>
        </script>
    </body>
</html>
