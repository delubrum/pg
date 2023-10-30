<header>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
</header>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary float-right new btn-lg">
                    <i class="fas fa-plus"></i> Adicionar
                </button>
                <h2 class="m-0 text-dark">Productos</h2>
                
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card p-4 listTable"> 
            <table id="list" class="display table-striped text-md">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Status</th>
                        <th class="text-right">Acción</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
</div>
</div>

<script>
$(document).on("click", ".new", function() {
    id = $(this).data('id');
    $("#loading").show();
    $.post( "?c=Products&a=New", {id}).done(function( data ) {
        $("#loading").hide();
        $('#xsModal').modal('toggle');
        $('#xsModal .modal-content').html(data);
    });
});

$(document).on('click','.status', function(e) {
    id = $(this).data("id");
    status = $(this).data("status");
    $("#loading").show();
    $.post("?c=Products&a=Status", { id,status }).done(function( res ) {
        location.reload();
    });
});

$(document).ready(function() {
    var table = $('#list').DataTable({
        'order': [[1, 'asc']],
        'lengthChange' : false,
        'paginate': false,
        'scrollX' : true,
        'autoWidth' : false,
        'ajax': {
            'url':'?c=Products&a=Data',
            'dataSrc': ''
        },
        'columns': [
            { data: 'name' },
            { data: 'status' },
            { data: 'action' }
        ]
    });
});
</script>