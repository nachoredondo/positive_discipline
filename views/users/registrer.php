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
                            <input type="hidden" class="form-control ml-3" name="type" required/ value="<?php echo $type;?>">
                            <?php
                                if ($type == "adult"):
                            ?>
                            <div class="row">
                                <div class="input-group no-border">
                                    <input type="text" placeholder="Usuario" class="form-control ml-3" name="user" required/>
                                    <input type="email" placeholder="Correo" class="form-control ml-3 mr-3" name="email" required/>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="input-group no-border">
                                    <input type="text" placeholder="Nombre" class="form-control ml-3" name="name" required/>
                                    <input type="text" placeholder="Apellidos" class="form-control ml-3 mr-3" name="surnames"/>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="input-group no-border">
                                    <input type="password" placeholder="Contraseña" class="form-control ml-3" name="password" maxLength="128" required/>
                                    <input type="password" placeholder="Confirmar contraseña" maxLength="128" class="form-control ml-3 mr-3" name="password-confirm" required/>
                                </div>
                            </div>
                            <?php
                                else:
                            ?>
                            <div class="row">
                                <div class="input-group no-border">
                                    <input type="text" placeholder="Nombre" class="form-control ml-3" name="name" required/>
                                    <input type="text" placeholder="Usuario tutor" class="form-control ml-3 mr-3" name="user-tutor" required/>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="row mt-2">
                                    <div class="ml-3 mt-2" style="width:auto;">
                                        <select id="age" name="age" class="form-control mt-0">
                                            <option value="">Edad</option>
                                            <?php
                                                for ($i = 3; $i <= 17; $i++) {
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
        </script>
    </body>
</html>
