<script type="text/javascript"> 
$(document).ready(function() {
    $('table.admin-table').dataTable({
        "paging": {{ config('solunes.table_pagination') }},
        "pageLength": {{ config('solunes.table_pagination_count') }},
        "autoWidth": false,
        "responsive": true,
      	"lengthMenu": [[{{ config('solunes.table_pagination_count') }}, {{ config('solunes.table_pagination_count')*2 }}, {{ config('solunes.table_pagination_count')*5 }}, {{ config('solunes.table_pagination_count')*10 }}, -1], [{{ config('solunes.table_pagination_count') }}, {{ config('solunes.table_pagination_count')*2 }}, {{ config('solunes.table_pagination_count')*5 }}, {{ config('solunes.table_pagination_count')*10 }}, "Todos"] ],
        "order": [0, "asc"],
        "language": {
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos en la tabla",
            "infoFiltered": "(filtrado de _MAX_ items)",
            "lengthMenu": "Mostrar _MENU_ items por página",
            "zeroRecords": "No se encontró nada",
            "search": "Buscar en página:",
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
    $('table.admin-multilevel-table').dataTable({
        "paging": {{ config('solunes.table_pagination') }},
        "pageLength": {{ config('solunes.table_pagination_count') }},
        "autoWidth": false,
        "responsive": true,
        "bSort" : false,
        "lengthMenu": [[{{ config('solunes.table_pagination_count') }}, {{ config('solunes.table_pagination_count')*2 }}, {{ config('solunes.table_pagination_count')*5 }}, {{ config('solunes.table_pagination_count')*10 }}, -1], [{{ config('solunes.table_pagination_count') }}, {{ config('solunes.table_pagination_count')*2 }}, {{ config('solunes.table_pagination_count')*5 }}, {{ config('solunes.table_pagination_count')*10 }}, "Todos"] ],
        "language": {
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay datos en la tabla",
            "infoFiltered": "(filtrado de _MAX_ items)",
            "lengthMenu": "Mostrar _MENU_ items por página",
            "zeroRecords": "No se encontró nada",
            "search": "Buscar en página:",
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
        //"pageLength": 1000,
        "autoWidth": false,
        "responsive": true,
        //"lengthMenu": [ [25, 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, "Todos"] ],
        'columnDefs': [{
            'targets': 0,
            'searchable':false,
            'orderable':false,
        }],
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