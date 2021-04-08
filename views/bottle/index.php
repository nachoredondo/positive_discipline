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
        <title>Botella</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="../../assets/img/favicon.ico" />
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
                <h2 class="text-white">.</h2>
                <div class="mr-5">
                    <h2 class="text-center text-uppercase text-secondary mt-5">Botella de la calma</h2>
                    <!-- Icon Divider-->
                    <div class="divider-custom">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                    <div class="col-lg-9 mx-auto mb-5">
                        <h5 class="row">Tutorial según se indica en el canal de el Bosque de las Fantasías de Youtube</h5>
                        <h5 class="row mt-3 text-info ">Los materiales que se necesitan son:</h5>
                        <ul>
                            <li class="mb-1">Bote de cristal o de plástico</li>
                            <li class="mb-1">Agua caliente</li>
                            <li class="mb-1">Jabón líquido</li>
                            <li class="mb-1">Purpurina de colores y/o pequeños elementos decorativos permeables</li>
                            <li class="mb-1">Silicona o cola blanca</li>
                        </ul>
                        <h5 class="row text-info ">Los pasos a seguir son:</h5>
                        <ol>
                            <li class="mb-1">&nbsp;Rellenar agua caliente al bote.</li>
                            <li class="mb-1">&nbsp;Echar y mezclar jabón líquido transparente, la mezcla se podrá hacer con una cuchara por ejemplo.</li>
                            <li class="mb-1">&nbsp;Echar y mezclar purpurina de colores a nuestro gusto.</li>
                            <li class="mb-1">&nbsp;Echar y mezclar elementos decorativos a nuestro gusto.</li>
                            <li class="mb-1">&nbsp;Cerrar el bote bien con silicona o cola blanca.</li>
                            <li class="mb-1">&nbsp;Agita el bote y disfruta.</li>
                        </ol>
                        <h5 class="row text-uppercase text-info mt-2 mb-2">Video de youtube</h5>
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
    </body>
</html>
