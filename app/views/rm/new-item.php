<header>
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <script src="assets/plugins/select2/js/select2.full.min.js"></script>
</header>

<form method="post" id="formItem">
    <div class="modal-header">
        <h5 class="modal-title">New Record</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">

        <div class="col-sm-12">
            <div class="form-group">
                <label>* Type</label>
                <div class="input-group">
                    <input type="hidden" name="bcId" value="<?php echo $id ?>">
                    <select class="form-control" name="type" id="type" style="width: 100%;" required>
                        <option value=''></option>
                        <option value='Caldera'>Caldera</option>
                        <option value='Ingreso'>Ingreso</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-12 d-none net">
            <div class="form-group">
                <label>* Peso Neto:</label>
                <div class="input-group">
                  <input class="form-control" type="number" step="0.01" min="0" name="net" id="net">
                </div>
            </div>
        </div>

        <div class="col-sm-12 d-none net">
            <div class="form-group">
                <label>* Peso Tambor:</label>
                <div class="input-group">
                  <input class="form-control" type="number" step="0.01" min="0" name="drum" id="drum">
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label>* Temperatura:</label>
                <div class="input-group">
                  <input class="form-control" type="number" step="0.01" min="0" name="temp" required>
                </div>
            </div>
        </div>


        <div class="col-sm-12">
            <div class="form-group">
                <label>Notes:</label>
                <div class="input-group">
                <textarea class="form-control" name="notes" style="height:100px"></textarea>
                </div>
            </div>
        </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

<script>
$(document).on('change','#type', function(e) {
    if($(this).val() === 'Ingreso') {
        $(".net").removeClass("d-none");
        $("#net").attr("required", true);
        $("#drum").attr("required", true);
    } else {
        $(".net").addClass("d-none");
        $("#net").attr("required", false);
        $("#drum").attr("required", false);
    }
});

$(document).on('submit', '#formItem', function(e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    if (document.getElementById("formItem").checkValidity()) {
        $("#loading").show();
        $.post( "?c=BC&a=SaveItem", $( "#formItem" ).serialize()).done(function(res) {
          $("#loading").hide();
          $("#xsModal").hide();
          table.ajax.reload( null, false );
          tableb.ajax.reload( null, false );
          $("#mp").html(res);
          $("#pr").html((res/$("#torecover").html()*100).toFixed());
        });
    }
});
</script>

