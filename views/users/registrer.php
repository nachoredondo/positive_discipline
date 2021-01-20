<?php
$type = $_REQUEST['type'];

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
            <h1 class="text-uppercase text-secondary mt-4">Crear usuario</h1>
            <div class="mt-4 container d-flex align-items-center flex-column">
                <div class="card-header">
                    <div class="flex-group">
                        <?php
                        if (isset($_GET['success'])):
                            if ($_GET['success'] == 'false'):
                                if (isset($_GET['error'])):
                                    if ($_GET['error'] == "password-length") :
                                        ?>
                                        <span class="badge badge-danger mb-2">Formato incorrecto de contraseña, mínimo 8 caracteres alfanuméricos</span>
                                        <?php
                                    endif;
                                    if ($_GET['error'] == "incorrect_camp" && isset($_GET['message'])) :
                                        ?>
                                        <span class="badge badge-danger mb-2"><?php echo $_GET['message']; ?></span>
                                        <?php
                                    endif;
                                endif;
                            endif;
                        endif;
                        ?>
                        <form class="form" method="post" action="create_user.php" role="form" id="the-form">
                            <input type="hidden" class="form-control ml-3" name="type" required/ value="<?php echo $type;?>">
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
                            <!-- <div class="row mt-2">
                                <div class="input-group no-border">
                                    <input type="text" placeholder="Edad" class="form-control" name="age" required/>
                                    <input type="text" placeholder="Foto perfil" class="form-control ml-3" name="image"/>
                                </div>
                            </div>
                            <div class="row mt-2 ml-2">
                                <label>Selecciona la foto de perfil:</label>
                                <input type="file" class="form-control-file mt-1" value="Foto perfil" name="imagen" />
                            </div> -->
                            <div class="text-center mt-4 ml-5 mr-5">
                                <button type="submit" class="btn btn-primary btn-block" name="login">Crear</button>
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
    </body>
</html>
