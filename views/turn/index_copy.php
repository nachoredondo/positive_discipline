// function make_request(path, params, method) {
            //     method = method || "post"; // Set method to post by default if not specified.

            //     var form = document.createElement("form");
            //     form.setAttribute("method", method);
            //     form.setAttribute("action", path);

            //     for (var key in params) {
            //         if (params.hasOwnProperty(key)) {
            //             var hiddenField = document.createElement("input");
            //             hiddenField.setAttribute("type", "hidden");
            //             hiddenField.setAttribute("name", key);
            //             hiddenField.setAttribute("value", params[key]);

            //             form.appendChild(hiddenField);
            //         }
            //     }

            //     document.body.appendChild(form);
            //     form.submit();
            // }

            // function push_state(date){
            //     if (image == "") {
            //         return "<em>Sin imagen</em>";
            //     } else {
            //         return '<img class="mx-auto d-block" src="<?php echo APP_ROOT; ?>/files/img/rules/' + image + '" height="80">'
            //     }
            // }

            // function edit_turn(){
            //     let prueba = "<?php if ($_SESSION['type']){ echo '<button type=\"button\" title=\"Detalles\" class=\"edit-btn btn btn-success btn-sm mr-2\"><i class=\"fas fa-edit\"></i></button><button type=\"button\" title=\"Informe\" class=\"remove-btn btn btn-info btn-sm\"><i class=\"fas fa-trash-alt\"></i></button>';
            //             } else  {
            //                 echo '';
            //             }
            //         ?>";
            //     return prueba;
            // }

            window.addEventListener('load', function () {
            //     let table = $('#the-table').DataTable({
            //         order: [[1, 'asc']],
            //         serverSide: true,
            //         language: {
            //             url: "<?php echo APP_ROOT; ?>/assets/datatables/es.json",
            //         },
            //         columns: [
            //             {
            //                 data: 'name',
            //                 title: 'Nombre',
            //             },
            //             {
            //                 data: 'description',
            //                 title: 'Descripcion',
            //                 "searchable": false,
            //             },
            //             {
            //                 data: 'first_child',
            //                 title: 'Hijo',
            //                 render: child_show(),
            //             },
            //             {
            //                 data: 'state',
            //                 title: 'Imagen consecuencias',
            //                 "searchable": false,
            //                 render: function (_, _, row) { return push_state(row) },
            //                 defaultContent: ' Sin imagen ',
            //             },
            //             {
            //                 sorting: false,
            //                 defaultContent: edit_rules(),
            //                 "searchable": false,
            //             },
            //         ],
            //         ajax: {
            //             method: 'POST',
            //             url: "<?php echo APP_ROOT; ?>api/rules/list_turn.php",
            //             data: function (params) {
            //                 params.id_user =  <?php echo $user->id(); ?>;
            //                 return params;
            //             },
            //             error: function(xhr) {
            //                 if (xhr.status === 401) { // Session expired
            //                     window.location.reload();
            //                 } else {
            //                     console.log(xhr);
            //                 }
            //             },
            //         },
            //     });
            //     $('#the-table tbody').on('click', 'button', function () {
            //         let data = table.row($(this).parents('tr')).data();
            //         if (this.classList.contains('edit-btn')) {
            //             make_request('<?php echo APP_ROOT ?>views/rules/edit_create.php', { id: data["id"] });
            //         } else if (this.classList.contains('remove-btn')) {
            //             swal({
            //                 title: "¿Estás seguro de que quieres borrar el turno?",
            //                 icon: "warning",
            //                 buttonsStyling: false,
            //                 buttons: ["No", "Si"],
            //             })
            //             .then((willDelete) => {
            //                 if (willDelete) {
            //                     make_request(
            //                         '<?php echo APP_ROOT ?>views/turn/control.php',
            //                         {
            //                             id: data["id"],
            //                             form: "delete"
            //                         }
            //                     );
            //                 } else {
            //                     swal("El turno no ha sido borrado");
            //                 }
            //             })
            //             .catch(function() { writeToScreen('err: Hubo un error al borrar el turno.', true)});

            //         } else {
            //             console.error("Botón pulsado desconocido!");
            //         }
            //     });
            // });