<?php
$type = $_REQUEST['type'];
session_start();
$action = $_REQUEST['action'] ?? '';
$type_translate = $type == "child" ? 'niñ@' : 'tutor';
$other_type = $type == "child" ? 'tutor' : 'child';
$other_user_translate = $type == "child" ? 'tutor' : 'niñ@';

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
        <link rel="icon" type="image/x-icon" href="../../assets/img/logo.png"/>
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../../css/styles.css" rel="stylesheet" />
        <script src="../../assets/sweetalert/sweetalert.min.js"></script>
        <style>
            #image {
                background-color: rgba(0,0,0,0.8);
                <?php  if($type == "tutor"){
                    echo "background-image:url(../../assets/img/main_tutor.jpg);";
                } else {
                    echo "background-image:url(../../assets/img/main_child.jpg);";
                } ?>
                height: 100%;
                max-width: 100%;
                filter:brightness(0.95);
            }
        </style>
    </head>
    <body id="image">
        <div class="container">
        </div>
        <div class="mt-3 text-secondary text-center">
            <?php
                if ($type == "tutor")
                    echo "<div class='mt-5 mb-5'>";
                else
                    echo "<div class='mt-4 mb-4'>";
            ?>
             <!-- Contact Section Heading-->
                <h1 class="page-section-heading text-center text-uppercase text-secondary mb-0">Disciplina positiva</h1>
                <?php
                    if ($type == "tutor")
                        echo "<div class='mt-4 mb-4'>";
                    else
                        echo "<div class='mt-3 mb-3'>";
                ?>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Contact Section Form-->
            <?php
                if ($type == "tutor")
                    echo "<div class='mt-5 mb-5'>";
                else
                    echo "<div class='mt-4 mb-4'>";
            ?>
            <div class="container d-flex align-items-center flex-column">
                <h4 class="text-uppercase text-secondary col-lg-6">
                    Iniciar sesión
                    <?php
                        if ($type == "tutor")
                            echo "tutor/a, madre o padre";
                        else
                            echo "niñ@";
                    ?>
                    <button id="popoverId" class="popoverThis btn">
                        <i class="fas fa-question-circle fa-2x" title="Sección de ayuda"></i>
                    </button>
                    <div id="popoverContent" class="hide d-none">
                        <p>Este icono pemitirá orientar las demás partes de la web</p>
                    </div>
                </h4>
                <div class="card-header">
                    <div class="text-center">
                    <?php if (isset($_SESSION['login-error'])): unset($_SESSION['login-error']) ?>
                        <span class="badge badge-danger mb-0"><?php echo ($type == "tutor") ? "Contraseña o correo incorrecto" : "Tutor o imagen seleccionada incorrecta";?></span>
                    <?php endif ?>
                    </div>
                    <div class="flex-group">
                        <form class="form" method="post" action="checklogin.php" role="form" id="the-form">
                            <input type="hidden" name="type" value="<?php echo $type;?>">
                            <?php
                                if ($type == "tutor"):
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
                                        <div class="form-control-lg ml-2">
                                            <input type="text" placeholder="Usuario tutor" class="form-control" name="tutor" required/>
                                        </div>
                                        <!-- <div class="mt-2 mr-3"> -->
                                        <div class="mt-2 ml-4">
                                            <select id="img-form" class="form-control" name="img" style="width:auto;">
                                                <option value="">Elegir foto</option>
                                                <option value="bear.png">Oso</option>
                                                <option value="dog.png">Perro</option>
                                                <option value="whale.png">Ballena</option>
                                                <option value="pig.png">Cerdo</option>
                                                <option value="panda.png">Panda</option>
                                                <option value="unicorn.png">Unicornio</option>
                                                <option value="captain.png">Capitán américa</option>
                                                <option value="ball.png">Pelota</option>
                                                <option value="robot.png">Robot</option>
                                                <option value="rocket.png">Cohete</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <img class="mt-1" id="img-user" src="../../assets/img/user_child/robot.png" height="130" width="125" style="display:none"/>
                                </div>
                            <?php
                                endif;
                            ?>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary ml-3 mb-2" name="login">Entrar</button>
                                <a href="registrer.php?type=<?php echo $type;?>">
                                    <input type="button" class="btn btn-primary ml-3 mb-2" value="Crear usuario"/>
                                </a>
                                <a href="login.php?type=<?php echo $other_type;?>">
                                    <input type="button" class="btn btn-primary ml-3 mb-2" value="Cambiar usuario <?php echo $other_user_translate;?>"
                                    <?php
                                        if ($type == "child")
                                            echo 'title="Cambiar usuario tutor/a, madre o padre"';
                                    ?>/>
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

            <?php if ($action === 'created'): ?>
                swal({
                    title: "Usario creado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php endif; ?>
        </script>
    </body>
</html>
