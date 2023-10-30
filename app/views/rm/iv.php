<form method="post" id="formIV">
  <div class="modal-header">
  <h5 class="modal-title">Factura</b></h5>
  <input type="hidden" name="id" value="<?php echo $id ?>">
  <input type="hidden" name="status" value="<?php echo $status ?>">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>* Nro. Factura:</label>
                <div class="input-group">
                <input name="invoice" class="form-control" required>
                </div>
            </div>
        </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Guardar</button>
  </div>

</form>


<script>
$(document).on('submit', '#formIV', function(e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    if (document.getElementById("formIV").checkValidity()) {
        $("#loading").show();
        $.post( "?c=RM&a=Update", $( "#formIV" ).serialize()).done(function(res) {
            if (isNaN(res)) {
                toastr.error(res);
                $("#loading").hide();
            } else {
              location.reload();
            }
        });
    }
});
</script>

