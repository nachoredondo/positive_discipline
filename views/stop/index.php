<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/option_stop.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);
$stop = Option_Stop::get_stop_by_iduser($user->id());

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Stop</title>
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
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <script src="<?php echo APP_ROOT ?>/assets/jquery/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <script src="../../assets/sweetalert/sweetalert.min.js"></script>
        <style>
            .responsiveContent {
              position: relative;
              height: 0;
              overflow: hidden;
              padding-bottom: 56.2%;
              margin-bottom: 20px;
            }
            .responsiveContent iframe {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
            }
        </style>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container mb-5">
                <!-- Rules Section Heading-->
                <div class="container mb-5">
                    <h2 class="text-center text-uppercase text-secondary mt-4 ml-2">
                        Stop
                        <button id="popoverId" class="popoverThis btn">
                            <i class="fas fa-question-circle fa-2x" title="Sección de ayuda"></i>
                        </button>
                        <div id="popoverContent" class="hide d-none">
                            <p>Módulo para Stop.</p>
                            <p> Se puede personalizar con un vídeo de youtube, audio, imagen o vídeo normal.</p>
                        </div>
                    </h2>
                    <!-- Icon Divider-->
                    <div class="divider-custom">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                    <?php
                        if (empty($stop)) {
                    ?>
                     <div class="col-lg-9 mx-auto mb-4 mt-2">
                        <h5 class="text-secondary ml-5">Stop sin personalizar</h5>
                    </div>
                    <?php
                        }
                        foreach ($stop as $key => $value) {
                            if ($value->type == "youtube") {
                    ?>
                    <div class="col-lg-9 mx-auto mb-5">
                        <h4 class="text-uppercase text-info ml-4 mb-2">Vídeo youtube</h4>
                        <h6 class="text-secondary ml-5 mb-2"><?php echo $value->name?></h6>
                        <div class="responsiveContent">
                            <iframe src="https://www.youtube.com/embed/<?php echo $value->link?>"
                                    allowfullscreen="" frameborder="0"></iframe>
                        </div>
                    </div>
                    <?php
                            } elseif ($value->type == "image") {
                    ?>
                    <div class="col-lg-9 mx-auto mb-5">
                        <h4 class="text-uppercase text-info ml-4 mb-2">Imagen</h4>
                        <h6 class="text-secondary ml-5 mb-2"><?php echo $value->name?></h6>
                        <img class="img-thumbnail mr-4" id="img_wheel" src="<?php echo APP_ROOT; ?>/files/stop/img/<?php echo $value->link?>" />
                    </div>
                    <?php
                            } elseif ($value->type == "audio") {
                    ?>
                    <div class="col-lg-9 mx-auto mb-5">
                        <h4 class="text-uppercase text-info ml-4 mb-2">Audio</h4>
                        <h6 class="text-uppercase text-secondary ml-5 mb-2"><?php echo $value->name?></h6>
                        <audio controls style="width: 100%" controlsList="nodownload">
                            <source src="<?php echo APP_ROOT; ?>/files/stop/audio/<?php echo $value->link?>">
                        </audio>
                    </div>
                    <?php
                            } elseif ($value->type == "video") {
                    ?>
                    <div class="col-lg-9 mx-auto mb-5">
                        <h4 class="text-uppercase text-info ml-4 mb-2">Vídeo</h4>
                        <h6 class="text-secondary ml-5 mb-2"><?php echo $value->name?></h6>
                        <video controls style="width: 100%" controlsList="nodownload" poster="<?php echo APP_ROOT; ?>/assets/img/video.jpg">
                            <source src="<?php echo APP_ROOT; ?>/files/stop/video/wake_me_up.mp4">
                            Tu navegador no soporta el formato del vídeo.
                        </video>
                    </div>
                    <?php
                            }
                        }
                    ?>
                    <div class="col-lg-9 mx-auto ml-5">
                        <a href="edit_create.php" class="ml-5 mt-2">
                            <button class="btn btn-primary btn-lg" id="create_child" type="button">Personalizar</button>
                        </a>
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
