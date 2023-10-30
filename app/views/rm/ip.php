<form method="post" id="formIP">
  <div class="modal-header">
  <h5 class="modal-title">Informe Proceso y Análisis</b></h5>
  <input type="hidden" name="id" value="<?php echo $id->id ?>">
  <input type="hidden" name="status" value="<?php echo $status ?>">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <div class="row mb-4">
        <div class="col-sm-1">
            <b>LOTE:</b> <?php echo $id->id ?>
        </div>
        <div class="col-sm-1">
            <b>RM:</b> <?php echo $id->rmId ?>
        </div>
        <div class="col-sm-3">
            <b>CLIENTE:</b> <?php echo $id->clientname ?>
        </div>
        <div class="col-sm-2">
           <b>PRODUCTO: </b> <?php echo $id->productname ?>
        </div>
        <div class="col-sm-1">
            <b>REACTOR:</b> <?php echo $id->reactor ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-2 offset-3 text-center">
            <b>PESO MP A RECUPERAR:</b> <h2 class="text-primary" id="torecover"><?php echo $qty ?></h2>
        </div>

        <div class="col-sm-2 text-center">
        <b>CALCULO CERO:</b> <h2 class="text-danger" id="calc">0</h2>
        </div>

        <div class="col-sm-2 text-center">
        <b>% RECUPERACIÓN CLIENTE:</b> <h2 class="text-danger" id="calcMPC">0</h2>
        </div>

    </div>

    <div class="row">


        <div class="col-sm-3">
            <div class="form-group">
                <label>* Peso Recuperado Cliente:</label>
                <div class="input-group">
                <input class="form-control" id="mpc" name="mpClient" required>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>* Lodos del Proceso Cliente:</label>
                <div class="input-group">
                <input class="form-control" id="lp" name="mudpClient" required>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>* Destilado Humedo Cliente:</label>
                <div class="input-group">
                <input class="form-control" id="dh" name="distilledClient" required>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>* Perdida Evaporación Cliente:</label>
                <div class="input-group">
                <input class="form-control" id="pe" name="evaporationClient" required>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>* Apariencia:</label>
                <div class="input-group">
                <input class="form-control" id="pe" name="apariencia" value="Liquido Transparente" required>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>* Olor:</label>
                <div class="input-group">
                <input class="form-control" id="pe" name="olor" value="Característico" required>
                </div>
            </div>
        </div>


        <div class="col-sm-2">
            <div class="form-group">
                <label>* Densidad (g/ml):</label>
                <div class="input-group">
                <input class="form-control" id="pe" name="densidad" required>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>* % Humedad:</label>
                <div class="input-group">
                <input class="form-control" id="pe" name="humedad" required>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>* % PH:</label>
                <div class="input-group">
                <input class="form-control" id="pe" name="ph" required>
                </div>
            </div>
        </div>




      </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Guardar</button>
  </div>

</form>


<script>


$(document).ready(function() {
    torecover = Number($("#torecover").html());
    mp = Number($("#mpc").val());
    lp = Number($("#lp").val());
    dh = Number($("#dh").val());
    pe = Number($("#pe").val());
    calc = (torecover-mp-lp-dh-pe).toFixed(2);
    calcMPC = ((mp/torecover)*100).toFixed();

    $("#calc").html(calc);
    $("#calcMPC").html(calcMPC);
});

$(document).on("change", "#mpc,#lp,#dh,#pe", function(e) {
    torecover = Number($("#torecover").html());
    mp = Number($("#mpc").val());
    lp = Number($("#lp").val());
    dh = Number($("#dh").val());
    pe = Number($("#pe").val());
    calc = (torecover-mp-lp-dh-pe).toFixed(2);
    calcMPC = ((mp/torecover)*100).toFixed();

    $("#calc").html(calc);
    $("#calcMPC").html(calcMPC);

});

$(document).on('submit', '#formIP', function(e) {
    e.stopImmediatePropagation();
    e.preventDefault();
    if ($("#calc").html() != 0) {
      toastr.error('El Cálculo debe ser 0');
      return;
    }
    if (document.getElementById("formIP").checkValidity()) {
        $("#loading").show();
        $.post( "?c=IP&a=Update", $( "#formIP" ).serialize()).done(function(res) {
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

