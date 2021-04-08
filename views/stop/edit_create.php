<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/option_stop.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);
$stop = Option_Stop::get_stop_by_iduser($user->id());
$youtube_stop = Option_Stop::get_youtube_option_by_iduser($user->id());
$image_stop = Option_Stop::get_image_option_by_iduser($user->id());
$audio_stop = Option_Stop::get_audio_option_by_iduser($user->id());
$video_stop = Option_Stop::get_video_option_by_iduser($user->id());

if (isset($_REQUEST['type'])) {
    $youtube_enabled = False;
    $image_enabled = False;
    $audio_enabled = False;
    $video_enabled = False;
    if ($_REQUEST['type'] == "youtube") {
        $youtube_enabled = True;
    }
    if ($_REQUEST['type'] == "image") {
        $image_enabled = True;
    }
    if ($_REQUEST['type'] == "audio") {
        $audio_enabled = True;
    }
    if ($_REQUEST['type'] == "video") {
        $video_enabled = True;
    }
} else {
    if ($youtube_stop) {
        $youtube_enabled = True;
        $image_enabled = False;
        $audio_enabled = False;
        $video_enabled = False;
    } else {
        $youtube_enabled = False;
        $image_enabled = False;
        $audio_enabled = False;
        $video_enabled = False;
        if ($image_stop) {
            $image_enabled = True;
        } else {
            if ($audio_stop) {
                $audio_enabled = True;
            } else {
                if ($video_stop) {
                    $video_enabled = True;
                } else {
                    $image_enabled = True;
                }
            }
        }
    }
}

if ($youtube_stop) {
    $submit_youtube = "Editar";
    $link_saved = "https://www.youtube.com/watch?v=".$youtube_stop->link;
} else {
    $youtube_stop = new Option_Stop();
    $submit_youtube = "Crear";
    $link_saved = "";
}

if ($image_stop) {
    $submit_image = "Editar";
} else {
    $image_stop = new Option_Stop();
    $submit_image = "Crear";
}

if ($audio_stop) {
    $submit_audio = "Editar";
} else {
    $audio_stop = new Option_Stop();
    $submit_audio = "Crear";
}

if ($video_stop) {
    $submit_video = "Editar";
} else {
    $video_stop = new Option_Stop();
    $submit_video = "Crear";
}

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
        <script src="<?php echo APP_ROOT ?>/node_modules/jquery/dist/jquery.min.js"></script>
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
                    <h2 class="text-center text-uppercase text-secondary">Personalizar stop</h2>
                    <!-- Icon Divider-->
                    <div class="divider-custom">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                    <div class="col-lg-8 mx-auto d-flex align-items-center">
                        <div>
                            <input class="mr-1" type="radio" name="election" value="youtube" <?php if ($youtube_enabled) echo "checked"; ?>/>Youtube
                        </div>
                        <div class="ml-4">
                            <input class="mr-1" type="radio" name="election" value="image" <?php if ($image_enabled) echo "checked"; ?>/>Imagen
                        </div>
                        <div class="ml-4">
                            <input class="mr-1" type="radio" name="election" value="audio" <?php if ($audio_enabled) echo "checked"; ?>/>Audio
                        </div>
                        <div class="ml-4">
                            <input class="mr-1" type="radio" name="election" value="video" <?php if ($video_enabled) echo "checked"; ?>/>Video
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <form id="form_youtube" method="post" action="control.php" name="sentMessage" novalidate="novalidate" enctype="multipart/form-data"
                            <?php
                            if (!$youtube_enabled) {
                             echo 'style="display:none"';
                            }
                            ?>>
                                <input name="id" type="hidden" value="<?php echo $youtube_stop->id(); ?>"/>
                                <input name="id_user" type="hidden" value="<?php echo $user->id(); ?>"/>
                                <input name="link_saved" type="hidden" value="<?php echo $youtube_stop->link(); ?>"/>
                                <input name="position_old" type="hidden" value="<?php echo $youtube_stop->position(); ?>"/>
                                <input name="type" type="hidden" value="youtube"/>
                                <div class="control-group">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <div class="row ml-1">
                                            <label>Título</label>
                                            <i class="fas fa-microphone ml-3 mt-4" id="audio-name-youtube"></i>
                                        </div>
                                        <input class="form-control mr-5" id="name-youtube" name="title" type="text" required="required" data-validation-required-message="Introduzca el nombre." placeholder="Título" value="<?php echo $youtube_stop->name(); ?>"/>
                                    </div>
                                </div>
                                <div class="control-group mt-1">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <p class="help-block text-danger" required style="display:none;"></p>
                                        <?php if ($youtube_stop->id_user() != "0") {
                                            echo '<iframe src="https://www.youtube.com/embed/'.$youtube_stop->link.'" allowfullscreen="" frameborder="0"></iframe>';
                                            echo "<label for='files'>Cambiar video de youtube</label>";
                                            }
                                        ?>
                                        <div class="row ml-1">
                                            <label>Enlace</label>
                                        </div>
                                        <input class="form-control mb-3" type="text" name="link_new" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo $link_saved; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <div class="row ml-1">
                                            <label>Posición</label>
                                        </div>
                                        <select id="position-youtube" name="position" class="form-control mt-1 mb-2 col-3" style="font-size: large">
                                            <?php
                                                if ($submit_youtube == "Crear") {
                                                    $total_select_youtube = count($stop) + 1;
                                                    $default_value_youtube = count($stop) + 1;
                                                } else {
                                                    $total_select_youtube = count($stop);
                                                    $default_value_youtube = $youtube_stop->position();
                                                }

                                                for ($i = 1; $i <= $total_select_youtube; $i++) {
                                                    echo '<option value="', $i,'" ';
                                                    if ($default_value_youtube == $i)
                                                        echo 'selected ';
                                                    echo '>', $i, '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <button class="btn btn-primary btn-lg ml-3" id="createEditButtonYoutube" name="form" value="<?php echo $submit_youtube;?>" type="submit"><?php echo $submit_youtube;?></button>
                                    <?php if ($youtube_stop->id_user() != "0") { ?>
                                        <button class="btn btn-primary btn-lg ml-3" id="deleteButtonYoutube" name="form" value="delete" type="submit">Eliminar</button>
                                    <?php } ?>
                                    <a href="index.php">
                                        <button class="btn btn-primary btn-lg ml-3" type="button">Volver</button>
                                    </a>
                                </div>
                            </form>
                            <form id="form_image" method="post" action="control.php" name="sentMessage" novalidate="novalidate" enctype="multipart/form-data"
                            <?php
                            if (!$image_enabled) {
                             echo 'style="display:none"';
                            }
                            ?>>
                                <input name="id" type="hidden" value="<?php echo $image_stop->id(); ?>"/>
                                <input name="id_user" type="hidden" value="<?php echo $user->id(); ?>"/>
                                <input name="file_saved" type="hidden" value="<?php echo $image_stop->link(); ?>"/>
                                <input name="position_old" type="hidden" value="<?php echo $image_stop->position(); ?>"/>
                                <input name="type" type="hidden" value="image"/>
                                <div class="control-group">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <div class="row ml-1">
                                            <label>Título</label>
                                            <i class="fas fa-microphone ml-3 mt-4" id="audio-name-image"></i>
                                        </div>
                                        <input class="form-control mr-5" id="name-image" name="title" type="text" required="required" data-validation-required-message="Introduzca el nombre." placeholder="Título" value="<?php echo $image_stop->name(); ?>"/>
                                    </div>
                                </div>
                                <div class="control-group mt-3">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <p class="help-block text-danger" required style="display:none;"></p>
                                        <?php if ($image_stop->id_user() != "0") {
                                            echo "<img class='mt-2 ml-2' src='" . APP_ROOT . "files/stop/img/" . $image_stop->link() . "' height='150'/>";
                                            echo "<label for='files'>Cambiar imagen</label>";
                                        } ?>
                                        <input class="form-control mb-3" type="file" name="file" accept="image/gif, image/jpeg, image/png" style="font-size: large"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <div class="row ml-1">
                                            <label>Posición</label>
                                        </div>
                                        <select id="position-image" name="position" class="form-control mt-1 mb-2 col-3" style="font-size: large">
                                            <?php
                                                if ($submit_image == "Crear") {
                                                    $total_select_image = count($stop) + 1;
                                                    $default_value_image = count($stop) + 1;
                                                } else {
                                                    $total_select_image = count($stop);
                                                    $default_value_image = $image_stop->position();
                                                }

                                                for ($i = 1; $i <= $total_select_image; $i++) {
                                                    echo '<option value="', $i,'" ';
                                                    if ($default_value_image == $i)
                                                        echo 'selected ';
                                                    echo '>', $i, '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button class="btn btn-primary btn-lg ml-3" id="createEditButtonImage" name="form" value="<?php echo $submit_image;?>" type="submit"><?php echo $submit_image;?></button>
                                    <?php if ($image_stop->id_user() != "0") { ?>
                                        <button class="btn btn-primary btn-lg ml-3" id="deleteButtonImage" name="form" value="delete" type="submit">Eliminar</button>
                                    <?php } ?>
                                    <a href="index.php">

                                        <button class="btn btn-primary btn-lg ml-3" type="button">Volver</button>
                                    </a>
                                </div>
                            </form>

                            <form id="form_audio" method="post" action="control.php" name="sentMessage" novalidate="novalidate" enctype="multipart/form-data"
                            <?php
                            if (!$audio_enabled) {
                             echo 'style="display:none"';
                            }
                            ?>>
                                <input name="id" type="hidden" value="<?php echo $audio_stop->id(); ?>"/>
                                <input name="id_user" type="hidden" value="<?php echo $user->id(); ?>"/>
                                <input name="file_saved" type="hidden" value="<?php echo $audio_stop->link(); ?>"/>
                                <input name="position_old" type="hidden" value="<?php echo $audio_stop->position(); ?>"/>
                                <input name="type" type="hidden" value="audio"/>
                                <div class="control-group">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <div class="row ml-1">
                                            <label>Título</label>
                                            <i class="fas fa-microphone ml-3 mt-4" id="audio-name-audio"></i>
                                        </div>
                                        <input class="form-control mr-5" id="name-audio" name="title" type="text" required="required" data-validation-required-message="Introduzca el nombre." placeholder="Título" value="<?php echo $audio_stop->name(); ?>"/>
                                    </div>
                                </div>
                                <div class="control-group mt-3">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">

                                        <p class="help-block text-danger" required style="display:none;"></p>
                                        <?php if ($audio_stop->id_user() != "0") {
                                            echo "<audio controls height='200' controlsList='nodownload'>
                                                    <source src='".APP_ROOT."files/stop/audio/".$audio_stop->link()."'>
                                                    Tu navegador no soporta el formato del audio.
                                                </audio>";
                                            echo "<label for='files'>Cambiar audio</label>";
                                        } ?>
                                        <input class="form-control mb-3" type="file" name="file" accept="audio/*" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <div class="row ml-1">
                                            <label>Posición</label>
                                        </div>
                                        <select id="position-audio" name="position" class="form-control mt-1 mb-2 col-3" style="font-size: large">
                                            <?php
                                                if ($submit_audio == "Crear") {
                                                    $total_select_audio = count($stop) + 1;
                                                    $default_value_audio = count($stop) + 1;
                                                } else {
                                                    $total_select_audio = count($stop);
                                                    $default_value_audio = $audio_stop->position();
                                                }

                                                for ($i = 1; $i <= $total_select_audio; $i++) {
                                                    echo '<option value="', $i,'" ';
                                                    if ($default_value_audio == $i)
                                                        echo 'selected ';
                                                    echo '>', $i, '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button class="btn btn-primary btn-lg ml-3" id="createEditButtonAudio" name="form" value="<?php echo $submit_audio;?>" type="submit"><?php echo $submit_audio;?></button>
                                    <?php if ($audio_stop->id_user() != "0") { ?>
                                        <button class="btn btn-primary btn-lg ml-3" id="deleteButtonAudio" name="form" value="delete" type="submit">Eliminar</button>
                                    <?php } ?>
                                    <a href="index.php">
                                        <button class="btn btn-primary btn-lg ml-3" type="button">Volver</button>
                                    </a>
                                </div>
                            </form>

                            <form id="form_video" method="post" action="control.php" name="sentMessage" novalidate="novalidate" enctype="multipart/form-data"
                            <?php
                            if (!$video_enabled) {
                             echo 'style="display:none"';
                            }
                            ?>>
                                <input name="id" type="hidden" value="<?php echo $video_stop->id(); ?>"/>
                                <input name="id_user" type="hidden" value="<?php echo $user->id(); ?>"/>
                                <input name="file_saved" type="hidden" value="<?php echo $video_stop->link(); ?>"/>
                                <input name="position_old" type="hidden" value="<?php echo $video_stop->position(); ?>"/>
                                <input name="type" type="hidden" value="video"/>
                                <div class="control-group">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <div class="row ml-1">
                                            <label>Título</label>
                                            <i class="fas fa-microphone ml-3 mt-4" id="audio-name-video"></i>
                                        </div>
                                        <input class="form-control mr-5" id="name-video" name="title" type="text" required="required" data-validation-required-message="Introduzca el nombre." placeholder="Título" value="<?php echo $video_stop->name(); ?>"/>
                                    </div>
                                </div>
                                <div class="control-group mt-3">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">

                                        <p class="help-block text-danger" required style="display:none;"></p>
                                        <?php if ($video_stop->id_user() != "0") {
                                            echo "<video controls height='200' controlsList='nodownload' poster='".APP_ROOT."assets/img/video.jpg'>
                                                    <source src='".APP_ROOT."files/stop/video/".$video_stop->link()."'>
                                                    Tu navegador no soporta el formato del video.
                                                </video>";
                                            echo "<label for='files'>Cambiar video</label>";
                                        } ?>
                                        <input class="form-control mb-3" type="file" name="file" accept="video/mp4,video/x-m4v,video/*" style="font-size: large"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                        <div class="row ml-1">
                                            <label>Posición</label>
                                        </div>
                                        <select id="position-video" name="position" class="form-control mt-1 mb-2 col-3" style="font-size: large">
                                            <?php
                                                if ($submit_video == "Crear") {
                                                    $total_select_video = count($stop) + 1;
                                                    $default_value_video = count($stop) + 1;
                                                } else {
                                                    $total_select_video = count($stop);
                                                    $default_value_video = $video_stop->position();
                                                }

                                                for ($i = 1; $i <= $total_select_video; $i++) {
                                                    echo '<option value="', $i,'" ';
                                                    if ($default_value_video == $i)
                                                        echo 'selected ';
                                                    echo '>', $i, '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button class="btn btn-primary btn-lg ml-3" id="createEditButtonVideo" name="form" value="<?php echo $submit_video;?>" type="submit"><?php echo $submit_video;?></button>
                                    <?php if ($video_stop->id_user() != "0") { ?>
                                        <button class="btn btn-primary btn-lg ml-3" id="deleteButtonVideo" name="form" value="delete" type="submit">Eliminar</button>
                                    <?php } ?>
                                    <a href="index.php">
                                        <button class="btn btn-primary btn-lg ml-3" type="button">Volver</button>
                                    </a>
                                </div>
                            </form>
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
            let election = $("input[name = 'election']")
            election.click( function () {
                if (this.getAttribute("checked") == null) {
                    let types = ['youtube', 'audio', 'image', 'video'];
                    // let types = ['image', 'audio', 'video'];
                    for (var i = types.length - 1; i >= 0; i--) {
                        $("#form_" + types[i]).hide();
                    }
                    $("#form_" + this.value).show({ duration: 500});

                    for (var i = election.length - 1; i >= 0; i--) {
                        election[i].removeAttribute("checked");
                    }
                    $(this).attr('checked', true);
                }
            });

            $(document).ready(function(){
                let sr = new webkitSpeechRecognition();

                $("#audio-name-image").mousedown(function(){
                    recognition("#name-image");
                });

                $("#audio-name-video").mousedown(function(){
                    recognition("#name-video");
                });

                $("#audio-name-audio").mousedown(function(){
                    recognition("#name-audio");
                });

                $("#audio-name-youtube").mousedown(function(){
                    recognition("#name-youtube");
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
