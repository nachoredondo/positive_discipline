<?php
require '../../classes/session.php';
require '../../classes/user.php';

Session::check_login_redirect();

if (isset($_POST['id'])) {
    $user = User::get_user_from_id($_POST['id']);
} else {
    $user = User::get_user_from_user($_SESSION['user']);
}

if (!$_SESSION['type']) {
    $tutor_child = explode("_", $_SESSION['user'])[0];
}

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
                    <div class="col-lg-8 mx-auto">
                        <form id="update-pwd" method="post" action="edit_update_child.php" name="sentMessage" novalidate="novalidate">
                            <input class="form-control" id="id" name="id" type="hidden" required="required" value="<?php echo $user->id();?>" />
                            <input class="form-control" id="tutor" name="tutor" type="hidden" placeholder="Usuario tutor" required="required" data-validation-required-message="Introduzca el usuario tutor." value="<?php echo ($_SESSION['type']) ? $_SESSION['user'] : $tutor_child;?>" />
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <div class="row ml-1">
                                        <label>Nombre</label>
                                        <i class="fas fa-microphone ml-3 mt-4" id="audio-name"></i>
                                    </div>
                                    <input class="form-control" id="name" name="name" type="text" placeholder="Nombre" required="required" data-validation-required-message="Introduzca el nombre."
                                    value="<?php echo ($_SESSION['type']) ? ($_SESSION['type']) ? $user->name() : '' : $_SESSION['name'];?>" />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="row mt-4">
                                    <div class="ml-3 mt-2 col-4 mb-0 pb-2">
                                        <select id="age" name="age" class="form-control mt-0" style="font-size: large">
                                            <option value="">Edad</option>
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
                                    <div class="mt-2 col-4 mr-5 mb-0 pb-2">
                                        <select id="img-form" class="form-control mt-0" name="img" style="font-size: large">
                                            <option value="no_image.png">Elegir foto</option>
                                            <option value="robot.png"<?php echo ($user->image() == "robot.png") ? "selected ": "";?>>Robot</option>
                                            <option value="bear.png" <?php echo ($user->image() == "bear.png") ? "selected ": "";?>>Oso</option>
                                            <option value="dog.jpeg" <?php echo ($user->image() == "dog.jpeg") ? "selected ": "";?>>Perro</option>
                                            <option value="ball.jpg" <?php echo ($user->image() == "ball.jpg") ? "selected ": "";?>>Pelota</option>
                                            <option value="unicorn.png" <?php echo ($user->image() == "unicorn.png") ? "selected ": "";?>>Unicornio</option>
                                            <option value="whale.png" <?php echo ($user->image() == "whale.png") ? "selected ": "";?>>Ballena</option>
                                        </select>
                                        <div class="ml-5 mt-3">
                                            <img id="img-user" src="../../assets/img/user_child/robot.png" height="150" width="140" style="display:none"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="form-group ml-2">
                                <button class="btn btn-primary btn-xl ml-2 mr-4" id="button-update-pwd" name="from" value="<?php echo ($_SESSION['type']) ? "create-child" : "update-user";?>" type="submit">
                                    <?php echo ($_SESSION['type'] && (!isset($_POST['id']))) ? "Crear" : "Editar";?>
                                </button>
                            <?php
                                if ($_SESSION['type'] && (!isset($_POST['id'])) || (!$_SESSION['type']))
                            ?>
                                    <button class="btn btn-primary btn-xl ml-2 mr-4" id="button-update-pwd" name="from" value="delete-child" type="submit">Eliminar</button>
                                </form>
                            <?php
                                if ($_SESSION['type']):
                            ?>
                                    <a href="profile_tutor.php">
                                        <button class="btn btn-primary btn-xl ml-1" id="create_child" type="button">Volver</button>
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
            $(document).ready(
                function cambio() {
                    let img_select =document.getElementById("img-form");
                    let option = img_select.options[img_select.selectedIndex].value;
                    if (option == "no_image.png") {
                        $('#img-user').hide({ duration: 500 });
                        let img = document.getElementById('img-user');
                        img.src = "../../assets/img/user_child/" + option;
                    } else {
                        let img = document.getElementById('img-user');
                        img.src = "../../assets/img/user_child/" + option;
                        $('#img-user').show({ duration: 500 });
                    }
                }
            );

            $('#img-form').on('change', function () {
                let option = this.options[this.selectedIndex].value;
                if (option == "no_image.png") {
                    $('#img-user').hide({ duration: 500 });
                    let img = document.getElementById('img-user');
                    img.src = "../../assets/img/user_child/" + option;
                } else {
                    let img = document.getElementById('img-user');
                    img.src = "../../assets/img/user_child/" + option;
                    $('#img-user').show({ duration: 500 });
                }
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
        </script>
    </body>
</html>
