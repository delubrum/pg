<header>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
</header>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h2 class="m-0 text-dark">Paquetes de Despacho</h2>
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
								<th>Fecha</th>
								<th>Producto</th>
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

$(document).ready(function() {
	var table = $('#list').DataTable({
		'order': [[1, 'desc']],
		'lengthChange' : false,
		'paginate': false,
		'scrollX' : true,
		'autoWidth' : false,
		'ajax': {
			'url':'?c=Certificate&a=PDData',
			'dataSrc': function (json) {
				// Check if the data array is not empty or null
				if (json != '') {
					return json;
				} else {
					return []; // Return an empty array to prevent rendering
				}
			},
		},
		'columns': [
			{ data: 'date' },
			{ data: 'product' },
			{ data: 'action' }
		]
	});
});
</script>