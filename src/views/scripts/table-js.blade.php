<script type="text/javascript"> 
$(document).ready(function() {
    $('table.admin-table').dataTable({
        "pageLength": 25,
        "autoWidth": false,
        "responsive": true,
      	"lengthMenu": [ [25, 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, "Todos"] ],
        "language": {
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos en la tabla",
            "infoFiltered": "(filtrado de _MAX_ items)",
            "lengthMenu": "Mostrar _MENU_ items por página",
            "zeroRecords": "No se encontró nada",
            "search": "Buscar:",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "paginate": {
                "first":      "Primero",
                "last":       "Último",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activa para ordenar de manera ascendente",
                "sortDescending": ": activa para ordenar de manera descendente"
            }
        },
    });
    $('table.admin-table-checkbox').dataTable({
        "paging": false,
        "pageLength": 1000,
        "autoWidth": false,
        "responsive": true,
        "lengthMenu": [ [25, 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, "Todos"] ],
        'columnDefs': [{
            'targets': 0,
            'searchable':false,
            'orderable':false,
        }],
        'order': [1, 'asc'],
        "language": {
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos en la tabla",
            "infoFiltered": "(filtrado de _MAX_ items)",
            "lengthMenu": "Mostrar _MENU_ items por página",
            "zeroRecords": "No se encontró nada",
            "search": "Buscar:",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "paginate": {
                "first":      "Primero",
                "last":       "Último",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activa para ordenar de manera ascendente",
                "sortDescending": ": activa para ordenar de manera descendente"
            }
        },
    });
    $('#example-select-all').on('click', function(){
      // Check/uncheck all checkboxes in the table
      $('input[type="checkbox"]').prop('checked', this.checked);
   });
});
</script>