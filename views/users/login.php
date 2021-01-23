<?php
$type = $_REQUEST['type'];
session_start();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Positive discipline to educate children" />
        <meta name="author" content="Ignacio Redondo Arroyo" />
        <title>Login</title>
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
                filter:brightness(0.97);
            }
        </style>
    </head>
    <body id="image">
        <div class="container">
        </div>
        <div class="mt-3 text-secondary text-center">
             <!-- Contact Section Heading-->
                <h1 class="page-section-heading text-center text-uppercase text-secondary mb-0">Disciplina positiva</h1>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Contact Section Form-->
            <div class="container d-flex align-items-center flex-column">
                <h4 class="text-uppercase text-secondary">Iniciar sesión</h4>
                <div class="card-header">
                    <div class="text-center">
                    <?php if (isset($_SESSION['login-error'])): unset($_SESSION['login-error']) ?>
                        <span class="badge badge-danger mb-0">Contraseña o correo incorrecto</span>
                    <?php endif ?>
                    </div>
                    <div class="flex-group">
                        <form class="form" method="post" action="checklogin.php" role="form" id="the-form">
                            <input type="hidden" name="type" value="<?php echo $type;?>">
                            <?php
                                if ($type == "adult"):
                            ?>
                                <div class="row">
                                    <div class="input-group no-border">
                                        <div class="form-control-lg">
                                            <input type="email" placeholder="Correo" class="form-control" name="email" required>
                                        </div>
                                        <div class="form-control-lg">
                                            <input type="password" placeholder="Contraseña" class="form-control" name="password" maxLength="128" required>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                else:
                            ?>
                                <div class="row">
                                    <div class="input-group no-border">
                                        <div class="form-control-lg">
                                            <input type="text" placeholder="Usuario tutor" class="form-control" name="tutor" required>
                                        </div>
                                        <div class="mt-2 mr-3">
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
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <img id="img-user" src="../../assets/img/user_child/robot.png" height="130" width="125" style="display:none"/>
                                </div>
                            <?php
                                endif;
                            ?>
                            <div class="text-center mt-2">
                                <button type="submit" class="btn btn-primary mr-2" name="login">Entrar</button>
                                <a href="registrer.php?type=<?php echo $type;?>">
                                    <input type="button" class="btn btn-primary ml-2" value="Crear usuario"/>
                                </a>
                                <a href="../../">
                                    <input type="button" class="btn btn-primary ml-2" value="Tipo usuario"/>
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
