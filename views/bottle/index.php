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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <title>Botella</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="<?php echo APP_ROOT; ?>/assets/img/logo.png"/>
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css"/>
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../../css/styles.css" rel="stylesheet"/>

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

            video {
              width: 100%;
              height: auto;
              margin-left: 10px;
              margin-right: 10px;
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
                <div class="mr-5">
                    <h2 class="text-center text-uppercase text-secondary mt-4 ml-5">
                        Botella de la calma
                        <button id="popoverId" class="popoverThis btn">
                            <i class="fas fa-question-circle fa-2x" title="Sección de ayuda"></i>
                        </button>
                        <div id="popoverContent" class="hide d-none">
                            Tutorial con 3 partes:
                            <ol>
                                <li>Materiales necesarios</li>
                                <li>Pasos a seguir</li>
                                <li>Vídeo de youtube</li>
                            </ol>
                        </div>
                    </h2>
                    <!-- Icon Divider-->
                    <div class="divider-custom">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                    <div class="col-lg-9 mx-auto mb-5">
                        <video class="mb-4" controls controlsList="nodownload" poster="<?php echo APP_ROOT; ?>/assets/img/video.jpg" loop autoplay muted>
                            <source src="<?php echo APP_ROOT; ?>/assets/video/bottle_example.mp4">
                            Tu navegador no soporta el formato del vídeo.
                        </video>
                        <h5 class="row">Tutorial según se indica en el canal "El Bosque de las Fantasías" de Youtube</h5>
                        <h5 class="row mt-3 text-info">Los materiales que se necesitan son:</h5>
                        <ul>
                            <li class="mb-1">Bote de cristal o de plástico</li>
                            <img class="ml-4" id="profile" src="../../assets/img/bottle/bottle.png" width="55" height="75">
                            <li class="mt-2 mb-1">Agua caliente</li>
                            <img class="ml-3" id="profile" src="../../assets/img/bottle/water.jpg" width="95" height="65">
                            <li class="mt-2 mb-1">Jabón líquido</li>
                            <img class="ml-3" id="profile" src="../../assets/img/bottle/soap.jpg" width="95" height="65">
                            <li class="mt-2 mb-1">Purpurina de colores y/o pequeños elementos decorativos</li>
                            <img class="ml-3" id="profile" src="../../assets/img/bottle/purpurin.jpg" width="95" height="65">
                            <li class="mt-2">Silicona o cola blanca</li>
                            <img class="ml-3" id="profile" src="../../assets/img/bottle/glue.png" width="75" height="85">
                        </ul>
                        <h5 class="row text-info ">Los pasos a seguir son:</h5>
                        <ol>
                            <li class="mb-2">&nbsp;Rellenar agua caliente al bote.</li>
                            <li class="mb-2">&nbsp;Echar y mezclar jabón líquido transparente, la mezcla se puede hacer con una cuchara.</li>
                            <li class="mb-2">&nbsp;Echar y mezclar purpurina de colores a nuestro gusto.</li>
                            <li class="mb-2">&nbsp;Echar y mezclar elementos decorativos a nuestro gusto.</li>
                            <li class="mb-2">&nbsp;Cerrar el bote bien con silicona o cola blanca.</li>
                            <li class="mb-2">&nbsp;Agita el bote y disfruta.</li>
                        </ol>
                        <h5 class="row text-info mt-4 mb-3">Video de youtube</h5>
                        <div class="responsiveContent">
                            <iframe src="https://www.youtube.com/embed/YARHS1gFDuM"
                                    allowfullscreen="" frameborder="0"></iframe>
                        </div>
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
