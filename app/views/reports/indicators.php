<header>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
</header>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h2 class="m-0 text-dark">Indicadores</h2>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                    <h3 class="card-title">Filtros</h3>
                    <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin-top:0px;">
                                    <i class="fas fa-minus"></i>
                            </button>
                    </div>
            </div>
						<div class="card-body" style="display: block;">
							<form id="filter_form" method="post" autocomplete="off" enctype="multipart/form-data" action="?c=Indicators&a=Index">
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
												<label>* Cliente:</label>
												<div class="input-group">
														<select class="form-control select2" name="clientId" id="client" style="width: 100%;" required>
																<option value=''></option>
																<?php foreach ($this->model->list("*","users"," and type = 'Cliente' and status = 1") as $r) { ?>     
																		<option value='<?php echo $r->id?>'><?php echo $r->company?></option>
																<?php } ?>
														</select>
												</div>
										</div>
									</div>
									<div class="col-sm-4">
											<div class="form-group">
													<label>Producto:</label>
													<div class="input-group">
													<select class="form-control" name="priority">
															<option value=''></option>
															<option <?php echo (!empty($_REQUEST['priority']) and 'Low' == $_REQUEST['priority']) ? 'selected' : ''; ?> value='Low'>Low</option>
															<option <?php echo (!empty($_REQUEST['priority']) and 'Medium' == $_REQUEST['priority']) ? 'selected' : ''; ?> value='Medium'>Medium</option>
															<option <?php echo (!empty($_REQUEST['priority']) and 'High' == $_REQUEST['priority']) ? 'selected' : ''; ?> value='High'>High</option>
													</select>
													</div>
											</div>
									</div>
									<div class="col-sm-4">
											<div class="form-group">
													<label>Año:</label>
													<input class="form-control" type="number" min="2023" step="1" name="year" value="<?php echo (isset($_REQUEST['year'])) ? $_REQUEST['year'] : date("Y"); ?>" >
											</div>
									</div>
								</div>
								<button type="submit" class="btn btn-primary float-right"><i class="fas fa-search"></i> Buscar</button>
							</form>
						</div>
          </div>
        </div>
        <div class="card p-4 listTable"> 
					<table id="list" class="cell-border ">
						<thead>
							<tr>
								<th>Mes</th>
								<th>Cotaminado <br> Devolución</th>
								<th>Cotaminado <br> Excedente</th>
								<th>Total <br> Cotaminado</th>
								<th>Procesado <br> Devolución</th>
								<th>% Procesado <br> Devolución </th>
								<th>Procesado <br> Excedente</th>
								<th>% Procesado <br> Excedente </th>
								<th>Total <br> Procesado</th>
								<th>Lodos <br> Devolución</th>
								<th>% Lodos <br> Devolución</th>
								<th>Lodos <br> Excedente</th>
								<th>% Lodos <br> Excedente</th>
								<th>Total <br> Lodos</th>
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
		'searching' : false,
		'info': false,
		'sort' : false,
		'ajax': {
			'url':'?c=Indicators&a=Data',
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
			{ data: 'cd' },
			{ data: 'ce' },
			{ data: 'ct' },
			{ data: 'pd' },
			{ data: 'ppd' },
			{ data: 'pe' },
			{ data: 'ppe' },
			{ data: 'pt' },
			{ data: 'ld' },
			{ data: 'pld' },
			{ data: 'le' },
			{ data: 'ple' },
			{ data: 'lt' },
		]
	});
});
</script>