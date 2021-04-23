<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/rule.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);
$message = $_REQUEST['message'] ?? '';

if (isset($_REQUEST['id'])) {
    $rule = Rule::get_rule($_REQUEST['id']);
    $value_submit = "Editar";
    $img_rule = $rule->img_consequences();
    $id_rule = $_REQUEST['id'];
    $id_children = $rule->get_children($_REQUEST['id']);
    $id_educator = $rule->id_educator;
} else {
    $rule = new Rule();
    $value_submit = "Crear";
    $img_rule = "";
    $id_rule = "";
    $id_children = [];
    $id_educator = $user->id();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Norma</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="<?php echo APP_ROOT; ?>/assets/img/logo.png"/>
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../../css/styles.css" rel="stylesheet"/>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../../assets/datatables/dataTables.bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <script src="../../assets/datatables/jquery.dataTables.min.js"></script>
        <script src="../../assets/datatables/dataTables.bootstrap.min.js"></script>
        <script src="../../assets/sweetalert/sweetalert.min.js"></script>
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../../css/styles.css" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container mb-3">
                <!-- Contact Section Heading-->
                <h2 class="text-white">.</h2>
                <h2 class="text-center text-uppercase text-secondary"><?php echo $value_submit;?> norma</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Contact Section Form-->
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <form id="contactForm" method="post" action="control.php" name="sentMessage" novalidate="novalidate" enctype="multipart/form-data">
                            <input name="id" type="hidden" value="<?php echo $id_rule; ?>"/>
                            <input name="img_saved" type="hidden" value="<?php echo $rule->img_consequences; ?>"/>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <div class="row ml-1">
                                        <label>Título</label>
                                        <label class="text-danger ml-1">✱</label>
                                        <i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-title"></i>
                                    </div>
                                    <input class="form-control mr-5" id="title" name="title" type="text" required="required" data-validation-required-message="Introduzca el título." placeholder="Título" value="<?php echo $rule->title; ?>"/>
                                    <p class="help-block text-danger" style="display:none;"></p>
                                </div>
                            </div>
                            <?php
                                if ($_SESSION['type']) {
                            ?>
                            <div class="control-group">
                                 <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <div class="row ml-1">
                                        <label>Niñ@</label>
                                        <label class="text-danger ml-1">✱</label>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="the-table" class="table table-striped compact nowrap" style="min-width:100%">
                                            <thead><!-- Leave empty. Column titles are automatically generated --></thead>
                                        </table>
                                    </div>
                                    <p class="help-block text-danger" style="display:none;"></p>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                            <input name="id_educator" type="hidden" value="<?php echo $id_educator; ?>"/>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <div class="row ml-1">
                                        <label>Descripción</label>
                                        <i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-description"></i>
                                    </div>
                                    <input class="form-control" id="description" name="description" type="textarea" placeholder="Descripción..." required="required" data-validation-required-message="Introduzca la descripción." value="<?php echo $rule->description; ?>"/>
                                    <p class="help-block text-danger" style="display:none;"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <div class="row ml-1">
                                        <label>Consecuencias</label>
                                        <i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-consequences"></i>
                                    </div>
                                    <input class="ml-1 form-control" id="consequences" name="consequences" type="text" placeholder="Consecuencias..." required="required" data-validation-required-message="Por favor introduce el nombre" value="<?php echo $rule->consequences; ?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                    <label>Imagen consecuencias</label>
                                    <?php if ($img_rule != "") {
                                        echo "<img class='mt-2 ml-2' src='" . APP_ROOT . "files/img/rules/" . $img_rule . "' height='80'/>";
                                        echo "<label for='files'>Cambiar imagen</label>";
                                    } ?>
                                    <input id="file_image" class="form-control mb-3" type="file" name="fimagen" accept="image/gif, image/jpeg, image/jpg, image/png" style="font-size: large"/>
                                    <span id="text_file_image">Ningún archivo seleccionado</span>
                                    <p class="help-block text-danger" style="display:none;"></p>
                                </div>
                            </div>
                            <div id="success"></div>
                            <div class="form-group mt-3">
                                <button class="btn btn-primary btn-lg ml-2 mb-2" id="createEditButton" name="form" value="<?php echo $value_submit;?>" type="submit"><?php echo $value_submit;?></button>
                                <?php if ($value_submit != "Crear") {
                                    echo '<button class="btn btn-primary btn-lg ml-2 mb-2" id="deleteButton" name="form" value="delete" type="submit">Eliminar</button>';
                                }?>
                                <a href="index.php">
                                    <button class="btn btn-primary btn-lg ml-2 mb-2" id="create_child" type="button">Volver</button>
                                </a>
                            </div>
                        </form>
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
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Contact form JS-->
        <script src="../../assets/mail/jqBootstrapValidation.js"></script>
        <!-- Core theme JS-->
        <script src="../../js/scripts.js"></script>
        <script type="text/javascript">
            let file_image = document.getElementById("file_image");
            let text_file_image = document.getElementById("text_file_image");
            file_image.onchange = function () {
                text_file_image.innerHTML = file_image.files[0].name;
            };

            $(document).ready(function(){
                let sr = new webkitSpeechRecognition();

                $("#audio-title").mousedown(function(){
                    recognition("#title");
                });

                $("#audio-description").mousedown(function(){
                    recognition("#description");
                });

                $("#audio-consequences").mousedown(function(){
                    recognition("#consequences");
                });

                function  recognition(id){
                    // start recognition speech
                    sr.start();

                    const $consequences = document.querySelector(id);

                    sr.onresult = result => {
                        let last_element = result.results.length - 1;
                        let text_listened = result.results[last_element][0].transcript;
                        $consequences.value += text_listened;
                    }

                    sr.onend = () => {
                        // Stop when the audio finish
                        sr.stop()
                    };
                }
            });

            function img_user(img) {
                return '<img id="img-user" src="../../assets/img/user_child/' + img + '" height="50" width="48"/>';
            }

            function check_user(id_rule, id_user) {
                let check = "";
                if (id_rule) {
                    check = "checked";
                }
                return  '<div class="form-check">' +
                            '<input class="form-check-input" name="id_user_child[]" type="checkbox" value="' + id_user + '" id="flexCheckDefault" ' + check + ' >' +
                        '</div>';
            }

            window.addEventListener('load', function () {
                let table = $('#the-table').DataTable({
                    order: [[1, 'asc']],
                    serverSide: true,
                    bPaginate: false,
                    bFilter: false,
                    bInfo: false,
                    language: {
                        url: "<?php echo APP_ROOT; ?>/assets/datatables/es.json",
                    },
                    columns: [
                        {
                            sorting: false,
                            title:'Niñ@ escogid@',
                            render: function (_, _, row) { return check_user(row.id_rule, row.id) },
                            "searchable": false,
                        },
                        {
                            data: 'name',
                            title: 'Nombre',
                            render: function (_, _, row) { return max_text(row.name) },
                        },
                        {
                            data: 'age',
                            title: 'Edad',
                            "searchable": false,
                        },
                        {
                            data: 'image',
                            title: 'Contraseña',
                            render: function (_, _, row) { return img_user(row.password) },
                            defaultContent: ' - ',
                        },
                    ],
                    ajax: {
                        method: 'POST',
                        url: "<?php echo APP_ROOT; ?>api/user/list_child_rules.php",
                        data: function (params) {
                            params.id_user =  <?php echo $user->id(); ?>;
                            params.id_rule =  "<?php
                                                if (isset($_REQUEST['id'])) {
                                                    echo $_REQUEST['id'];
                                                } else {
                                                    echo 'NULL';
                                                }
                                            ?>";
                            return params;
                        },
                        error: function(xhr) {
                            if (xhr.status === 401) { // Session expired
                                window.location.reload();
                            } else {
                                console.log(xhr);
                            }
                        },
                    },
                });
            });

            <?php if ($message): ?>
                swal({
                    title: '<?php echo $message; ?>',
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "error",
                    button: "Vale",
                }).catch(swal.noop);
            <?php endif; ?>

        </script>
    </body>
</html>
