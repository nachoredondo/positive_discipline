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
        <title>Mesa</title>
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
        <script src="<?php echo APP_ROOT ?>/node_modules/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <script src="../../assets/sweetalert/sweetalert.min.js"></script>
        <script src="<?php echo APP_ROOT ?>/node_modules/sortablejs/sortable.min.js"></script>
        <style>
            .grid-square {
                width: 100px;
                height: 100px;
                display: inline-block;
                padding: 10px;
                margin: 12px;
            }

            #table {
                width: 570px;
                height: 345px;
                border: 10px solid #cccccc;
                margin-left: auto;
                margin-right: auto;
                background-image: url('../../assets/img/table/main/night.jpg');
                background-repeat:no-repeat;
                background-size:contain;
            }

            .color {
                width:18px;
                height:18px;
                margin:10px;
                border-radius: 5px;
                border: 1px gray solid
            }

            #paint {
                border: 3px solid #555555;
            }

            #white {
                width:18px;
                height:18px;
                background:white;
                border:2px solid;
                border-radius: 2px;
                margin:10px;
            }
        </style>
        <script type="text/javascript">

            function background(value){
                let table = document.getElementById("table");
                table.style.backgroundImage = 'url(../../assets/img/table/main/'+ value + ')';
            }

            // draw part
            let canvas,
                ctx,
                flag = false,
                prevX = 0,
                cordX = 0,
                prevY = 0,
                cordY = 0,
                dot_flag = false;

            let x = "black",
                y = 2;

            function init() {
                canvas = document.getElementById('paint');
                ctx = canvas.getContext("2d");
                w = canvas.width;
                h = canvas.height;

                canvas.addEventListener("touchstart", function (e) {
                    if (e.target == canvas) {
                        e.preventDefault();
                    }
                    findxy('down', e)
                }, false);
                canvas.addEventListener("touchend", function (e) {
                    if (e.target == canvas) {
                        e.preventDefault();
                    }
                    findxy('up', e)
                }, false);
                canvas.addEventListener("touchmove", function (e) {
                    if (e.target == canvas) {
                        e.preventDefault();
                    }
                    findxy('move', e)
                }, false);

                canvas.addEventListener("mousemove", function (e) {
                    findxy('move', e)
                }, false);
                canvas.addEventListener("mousedown", function (e) {
                    findxy('down', e)
                }, false);
                canvas.addEventListener("mouseup", function (e) {
                    findxy('up', e)
                }, false);
                canvas.addEventListener("mouseout", function (e) {
                    findxy('out', e)
                }, false);
            }

            function color(obj) {
                switch (obj.id) {
                    case "green":
                        x = "green";
                        break;
                    case "blue":
                        x = "blue";
                        break;
                    case "red":
                        x = "red";
                        break;
                    case "yellow":
                        x = "yellow";
                        break;
                    case "orange":
                        x = "orange";
                        break;
                    case "black":
                        x = "black";
                        break;
                    case "white":
                        x = "white";
                        break;
                }
                if (x == "white") y = 14;
                else y = 2;

            }

            function draw() {
                ctx.beginPath();
                ctx.moveTo(prevX, prevY);
                ctx.lineTo(cordX, cordY);
                ctx.strokeStyle = x;
                ctx.lineWidth = y;
                ctx.stroke();
                ctx.closePath();
            }

            function cleaner() {
                swal({
                    title: "¿Estás seguro de que quieres borrar el dibujo?",
                    icon: "warning",
                    buttonsStyling: false,
                    buttons: ["No", "Si"],
                })
                .then((willDelete) => {
                    if (willDelete) {
                        ctx.clearRect(0, 0, w, h);
                        document.getElementById("canvasimg").style.display = "none";
                    } else {
                        swal("El dibujo no ha sido borrado");
                    }
                })
                .catch(function() { writeToScreen('err: Hubo un error al borrar el dibujo.', true)});
            }

            function findxy(res, e) {
                if (res == 'down') {
                    prevX = cordX;
                    prevY = cordY;
                    if (e.clientX) {
                        cordX = e.clientX - canvas.getBoundingClientRect().left;
                        cordY = e.clientY - canvas.getBoundingClientRect().top;
                    } else {
                        cordX = e.touches[0].pageX - canvas.getBoundingClientRect().left;
                        cordY = e.touches[0].pageY - canvas.getBoundingClientRect().top;
                    }

                    flag = true;
                    dot_flag = true;
                    if (dot_flag) {
                        ctx.beginPath();
                        ctx.fillStyle = x;
                        ctx.fillRect(cordX, cordY, 2, 2);
                        ctx.closePath();
                        dot_flag = false;
                    }
                }
                if (res == 'up' || res == "out") {
                    flag = false;
                }
                if (res == 'move') {
                    if (flag) {
                        prevX = cordX;
                        prevY = cordY;
                        if (e.clientX) {
                            cordX = e.clientX - canvas.getBoundingClientRect().left;
                            cordY = e.clientY - canvas.getBoundingClientRect().top;
                        } else {
                            cordX = e.touches[0].pageX - canvas.getBoundingClientRect().left;
                            cordY = e.touches[0].pageY - canvas.getBoundingClientRect().top;
                        }
                        draw();
                    }
                }
            }

            function hide_show(checkbox){
                let div = document.getElementById(checkbox.value)
                if (div.style.visibility === "hidden") {
                    div.style.visibility = "";
                } else {
                    div.style.visibility = "hidden";
                }
            }
        </script>
    </head>
    <body id="page-top" onload="init()">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container mb-5">
                <!-- Rules Section Heading-->
                <h2 class="text-white">.</h2>
                <div class="mr-5">
                    <h2 class="text-center text-uppercase text-secondary mt-5">Mesa de la calma</h2>
                    <!-- Icon Divider-->
                    <div class="divider-custom">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                    <h5 class="text-uppercase text-secondary mb-3 ml-4">
                        1. Ordenar y escoger elementos.
                    </h5>
                    <div id="content-div" class="col-11 mx-auto mb-5">
                        <div class="mb-2 col-3">
                            <div class="row ml-1">
                                <label>Tipo de fondo</label>
                            </div>
                            <select id="responsable_act" name="responsable_act" style="width:180px;" onchange="background(this.value)" class="form-control mt-1">
                                <option value="night.jpg">Noche</option>
                                <option value="beach.jpg">Playa</option>
                                <option value="beach2.png">Playa infantil</option>
                                <option value="planet.jpg">Montañas</option>
                                <option value="sun.jpg">Ficción</option>
                                <option value="sun2.jpg">Puesta de sol</option>
                                <option value="tree_house.jpg">Bosque encantado</option>
                                <option value="wolf.jpg">Lobo</option>
                            </select>
                        </div>
                        <div class="control-group">
                            <div class="row mb-2">
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" name="daily" type="checkbox" value="profile" onclick="hide_show(this)" id="profile-check" checked> Perfil
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" name="daily" type="checkbox" value="elephant" onclick="hide_show(this)" id="elephant-check" checked> Elefante
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" name="daily" type="checkbox" value="bear" onclick="hide_show(this)" id="bear-check" checked> Oso
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" name="daily" type="checkbox" value="rabbit" onclick="hide_show(this)" id="rabbit-check" checked> Conejo
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" name="daily" type="checkbox" value="horse" onclick="hide_show(this)" id="horse-check" checked> Caballo
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" name="daily" type="checkbox" value="ball" onclick="hide_show(this)" id="ball-check" checked> Pelota
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" type="checkbox" value="car" onclick="hide_show(this)" id="car-check" checked> Coche
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" type="checkbox" value="rose" onclick="hide_show(this)" id="rose-check" checked> Rosa
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" type="checkbox" value="tree" onclick="hide_show(this)" id="tree-check" checked> Arbol
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" type="checkbox" value="tree2" onclick="hide_show(this)" id="tree2-check" checked> Arbol
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" type="checkbox" value="plant" onclick="hide_show(this)" id="plant-check" checked> Planta
                                </div>
                                <div class="ml-5 col-1 mb-1">
                                    <input class="form-check-input check_child" type="checkbox" value="plant2" onclick="hide_show(this)" id="plant2-check" checked> Planta
                                </div>
                            </div>
                        </div>
                        <div id="table" >
                            <div class="grid-square">
                                <img id="profile" src="../../assets/img/user_child/unicorn.png" width="50" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="elephant" src="../../assets/img/table/elephant.png" width="70" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="bear" src="../../assets/img/table/teddy.png" width="50" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="rabbit" src="../../assets/img/table/rabbit.png" width="50" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="horse" src="../../assets/img/table/horse.png" width="60" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="ball" src="../../assets/img/table/ball.png" width="50" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="car" src="../../assets/img/table/car.png" width="70" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="rose" src="../../assets/img/table/rose.png" width="50" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="tree" src="../../assets/img/table/tree.png" width="50" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="tree2" src="../../assets/img/table/tree2.png" width="50" height="50">
                            </div>
                            <div class="grid-square">
                                <img id="plant" src="../../assets/img/table/plant.png" width="50" height="50">
                            </div>
                            <div class="grid-square">
                               <img id="plant2" src="../../assets/img/table/plant2.png" width="50" height="50">
                            </div>
                        </div>
                    </div>
                    <h5 class="text-uppercase text-secondary mt-2 ml-4">
                        2. Cuadro a dibujar.
                    </h5>
                    <div id="draw" class="row mt-4 col-xs-10 col-sm-9 col-md-8 col-lg-8 mx-auto">
                        <canvas class="mx-auto" id="paint" width="400" height="400" class=""></canvas>
                        <div id="palet">
                            <div id="choose-color">
                                <span>Elige color</span>
                                <div class="color" style="background:green;" id="green" onclick="color(this)"></div>
                                <div class="color" style="background:blue;" id="blue" onclick="color(this)"></div>
                                <div class="color" style="background:red;" id="red" onclick="color(this)"></div>
                                <div class="color" style="background:yellow;" id="yellow" onclick="color(this)"></div>
                                <div class="color" style="background:orange;" id="orange" onclick="color(this)"></div>
                                <div class="color" style="background:black;" id="black" onclick="color(this)"></div>
                            </div>
                            <div id="Choose-cleaner" class="mt-3">
                                <span>Borrador</span>
                                <div id="white" onclick="color(this)"></div>
                            </div>
                            <img id="canvasimg" style="" style="display:none;">
                            <button class="btn btn-primary btn-lg mt-4" id="create_child" type="button" onclick="cleaner()">Borrar</button>
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
            new Sortable(table, {
                animation: 600,
                ghostClass: 'blue-background-class'
            });
        </script>
    </body>
</html>
