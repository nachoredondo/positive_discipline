<?php
$type = $_REQUEST['type'];
if ($type == "adult") {
    $name_type = "adulto";
}else {
    $name_type = "niño";
}

if (isset($_GET['error'])) {
    if ($_GET['error'] == "password-length") {
        $text_error = "Formato incorrecto de contraseña, mínimo 8 caracteres alfanuméricos";
    } elseif ($_GET['error'] == "no-age") {
        $text_error = "Sin escoger edad";
    } elseif ($_GET['error'] == "no-img") {
        $text_error = "Sin elegir foto";
    } elseif ($_GET['error'] == "no-user-tutor") {
        $text_error = "No existe el usuario tutor";
    } elseif ($_GET['error'] == "no-pass-tutor") {
        $text_error = "Contraseña del tutor incorrecta";
    } elseif ($_GET['error'] == "incorrect_camp") {
        if (isset($_GET['message'])) {
            $text_error = $_GET['message'];
        } else {
            $text_error = "Error";
        }
    } else {
        $text_error = "Error";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Positive discipline to educate children" />
        <meta name="author" content="Ignacio Redondo Arroyo" />
        <title>Crear usuario</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="../../assets/img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../../css/styles.css" rel="stylesheet" />

        <style>
            #image {
                background-color: rgba(0,0,0,0.8);
                background-image:url(../../assets/img/main.jpg);
                height: 100%;
                max-width: 100%;
                filter:brightness(0.95);
            }
        </style>
    </head>
    <body id="image">
        <div class="text-secondary text-center">
            <h1 class="text-uppercase text-secondary mt-4">Crear usuario <?php echo $name_type; ?></h1>
            <div class="mt-4 container d-flex align-items-center flex-column">
                <div class="card-header">
                    <div class="flex-group">
                        <?php
                        if (isset($_GET['success'])):
                            if ($_GET['success'] == 'false'):
                                if (isset($_GET['error'])):
                                        ?>
                                    <span class="badge badge-danger mb-2"><?php echo $text_error; ?></span>
                                    <?php
                                endif;
                            endif;
                        endif;
                        ?>
                        <form class="form" method="post" action="create_user.php" role="form" id="the-form">
                            <input type="hidden" class="form-control ml-3" name="type" required value="<?php echo $type;?>"/>
                            <?php
                                if ($type == "adult"):
                            ?>
                            <div class="row">
                                <div class="input-group no-border">
                                    <input type="text" placeholder="Usuario" class="form-control ml-3" name="user" id="user" required size="25"/>
                                    <i class="fas fa-microphone ml-1 mt-2" id="audio-user"></i>
                                    <input type="email" placeholder="Correo" class="form-control ml-4" name="email" id="email" required/>
                                    <i class="fas fa-microphone ml-1 mt-2 mr-3" id="audio-email" style="visibility: hidden"></i>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="input-group no-border">
                                    <input type="text" placeholder="Nombre" class="form-control ml-3" name="name" id="name" required/>
                                    <i class="fas fa-microphone ml-1 mt-2" id="audio-name"></i>
                                    <input type="text" placeholder="Apellidos" class="form-control ml-4" name="surnames" id="surnames"/>
                                    <i class="fas fa-microphone ml-1 mt-2 mr-3" id="audio-surnames"></i>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="input-group no-border">
                                    <input type="password" placeholder="Contraseña" class="form-control ml-3" name="password" maxLength="128" id="password">
                                    <i class="fas fa-microphone ml-1 mt-2" id="audio-password" style="visibility: hidden"></i>
                                    <input type="password" placeholder="Confirmar contraseña" maxLength="128" class="form-control ml-4 mr-3" name="password-confirm" id="confirm-password" required/>
                                    <i class="fas fa-microphone ml-1 mt-2" id="audio-confirm-password" style="visibility: hidden"></i>
                                </div>
                            </div>
                            <?php
                                else:
                            ?>
                            <div class="row">
                                <div class="input-group no-border">
                                    <input type="text" placeholder="Nombre" class="form-control ml-3 col-lg-6" name="name" required id="name-child"/>
                                    <i class="fas fa-microphone ml-1 mt-2" id="audio-name-child"></i>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="input-group no-border">
                                    <input type="text" placeholder="Usuario tutor..." class="form-control ml-3" name="user-tutor" required id="user-tutor-child"/>
                                    <i class="fas fa-microphone ml-1 mt-2 mr-2" id="audio-user-tutor-child"></i>
                                    <input type="password" placeholder="Contraseña tutor..." class="form-control ml-3 mr-3" name="password-tutor" required/>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="row mt-2">
                                    <div class="ml-3 mt-2" style="width:auto;">
                                        <select id="age" name="age" class="form-control mt-0">
                                            <option value="">Edad</option>
                                            <?php
                                                for ($i = 6; $i <= 18; $i++) {
                                                    echo '<option value="', $i, '">', $i, '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mt-2 col-2 mr-5">
                                        <select id="img-form" class="form-control mt-0" name="img" style="width:auto;">
                                            <option value="">Elegir foto</option>
                                            <option value="robot.png">Robot</option>
                                            <option value="bear.png">Oso</option>
                                            <option value="dog.jpeg">Perro</option>
                                            <option value="ball.jpg">Pelota</option>
                                            <option value="unicorn.png">Unicornio</option>
                                            <option value="whale.png">Ballena</option>
                                        </select>
                                    </div>
                                    <div class="ml-5">
                                        <img id="img-user" src="../../assets/img/user_child/robot.png" height="150" width="140" style="display:none"/>
                                    </div>
                                </div>

                            </div>
                            <?php
                                endif;
                            ?>
                            <div class="text-center mt-4 ml-5 mr-5">
                                <button type="submit" class="btn btn-primary mr-2" name="login">Crear</button>
                                <a href="login.php?type=<?php echo $type;?>">
                                    <input type="button" class="btn btn-primary ml-2" value="Volver"/>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php include '../general/footer.php'; ?>
        </div>
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Contact form JS-->
        <script src="../../assets/mail/jqBootstrapValidation.js"></script>
        <script src="../../assets/mail/contact_me.js"></script>
        <script type="text/javascript">
            $('#img-form').on('change', function () {
                let option = this.options[this.selectedIndex].value;
                if (option == "") {
                    $('#img-user').hide({ duration: 500 });
                    sleep(500);
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

                $("#audio-password").mousedown(function(){
                    recognition("#password");
                });

                $("#audio-confirm-password").mousedown(function(){
                    recognition("#confirm-password");
                });

                $("#audio-name-child").mousedown(function(){
                    recognition("#name-child");
                });

                $("#audio-user-tutor-child").mousedown(function(){
                    recognition("#user-tutor-child");
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
