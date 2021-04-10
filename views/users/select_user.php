<?php


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Positive discipline to educate children" />
        <meta name="author" content="Ignacio Redondo Arroyo" />
        <title>Elegir usuario</title>
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
            <h1 class="text-uppercase text-secondary mt-4">Elegir usuario</h1>
            <div class="mt-3 container d-flex align-items-center flex-column">
                <div class="flex-group">
                     <div class="text-center row mt-3">
                        <a href="login.php?type=adult">
                        	<h5>Tutor@</h5>
                            <img src="../../assets/img/adult2.jpg" width="160">
<!--                             <input type="button" class="btn btn-primary ml-2" value="Crear usuario adulto"/>
 -->                     </a>
                        <a class="ml-3" href="login.php?type=child">
                        	<h5>Niñ@</h5>
                        	<img src="../../assets/img/child2.jpg" width="160">
<!--                             <input type="button" class="btn btn-primary ml-2" value="Crear usuario menor de edad"/>
 -->                    </a>
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
