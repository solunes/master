<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>
<script type="text/javascript"> 
$(document).ready(function() {
    $('table.admin-table').dataTable({
        "paging": {{ config('solunes.table_pagination') }},
        @if(config('solunes.table_pagination')=='true')
        "pageLength": {{ config('solunes.table_pagination_count') }},
        "lengthMenu": [[{{ config('solunes.table_pagination_count') }}, {{ config('solunes.table_pagination_count')*2 }}, {{ config('solunes.table_pagination_count')*5 }}, {{ config('solunes.table_pagination_count')*10 }}, -1], [{{ config('solunes.table_pagination_count') }}, {{ config('solunes.table_pagination_count')*2 }}, {{ config('solunes.table_pagination_count')*5 }}, {{ config('solunes.table_pagination_count')*10 }}, "Todos"] ],
        @endif
        @if(config('solunes.list_vertical_scroll')>0)
        "scrollY": "{{ config('solunes.list_vertical_scroll') }}px",
        "scrollCollapse": true,
        @endif
        @if(config('solunes.list_horizontal_scroll')=='true')
        "scrollX": true,
        @else
        "responsive": true,
        @endif
        "autoWidth": false,
        "order": [0, "asc"],
        "language": {
            @if(config('solunes.table_pagination')=='true')
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            @else
            "info": " ",
            @endif
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
        @if(config('solunes.table_pagination')=='true')
        "pageLength": {{ config('solunes.table_pagination_count') }},
        "lengthMenu": [[{{ config('solunes.table_pagination_count') }}, {{ config('solunes.table_pagination_count')*2 }}, {{ config('solunes.table_pagination_count')*5 }}, {{ config('solunes.table_pagination_count')*10 }}, -1], [{{ config('solunes.table_pagination_count') }}, {{ config('solunes.table_pagination_count')*2 }}, {{ config('solunes.table_pagination_count')*5 }}, {{ config('solunes.table_pagination_count')*10 }}, "Todos"] ],
        @endif
        @if(config('solunes.list_vertical_scroll')>0)
        "scrollY": "{{ config('solunes.list_vertical_scroll') }}px",
        "scrollCollapse": true,
        @endif
        @if(config('solunes.list_horizontal_scroll')=='true')
        "scrollX": true,
        @else
        "responsive": true,
        @endif
        "autoWidth": false,
        "bSort" : false,
        "language": {
            @if(config('solunes.table_pagination')=='true')
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            @else
            "info": " ",
            @endif
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
        @if(config('solunes.list_vertical_scroll')>0)
        "scrollY": "{{ config('solunes.list_vertical_scroll') }}px",
        "scrollCollapse": true,
        @endif
        @if(config('solunes.list_horizontal_scroll')=='true')
        "scrollX": true,
        @else
        "responsive": true,
        @endif
        "autoWidth": false,
        //"lengthMenu": [ [25, 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, "Todos"] ],
        'columnDefs': [{
            'targets': 0,
            'searchable':false,
            'orderable':false,
        }],
        "language": {
            "info": " ",
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