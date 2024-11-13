<!DOCTYPE html>
<html lang="es">
<head>
  <title>SIPEC | Recibo de Materiales</title>
  <link rel="icon" sizes="192x192" href="app/assets/img/logo.png">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="https://adminlte.io/themes/v3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js"></script>
  <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css?v=3.2.0">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

  <style>
    .tabla th, .tabla td {
      border: 1px solid black;
      border-collapse: collapse;
      padding:5px;
      text-align:center;
    }
  </style>
</head>

<div class="row p-4">    
  <table style='text-align:center;padding:0' class="mb-4 col-12">
    <tr>
      <td style='width:33%'><img style='width:200px' src='app/assets/img/logo2.png'></td>
      <td style='width:33%'><h1> RECIBO DE MATERIALES</h1></td>
      <td style='width:33%;font-size:18px'>
        <b>Código:</b> 
        <br>
        <b>Fecha:</b> 
        <br>
        <b>Versión:</b> 01
      </td>
    </tr>
  </table>
</div>

<div class="row px-4 pb-2">
    <div class="col-sm-1">
      <b>RM:</b> <?php echo $id->id?>
    </div>
    <div class="col-sm-2">
      <b>FECHA:</b> <?php echo $id->createdAt?>
    </div>
    <div class="col-sm-2">
      <b>REACTOR:</b>
      <?php echo $id->reactor?>
    </div>
</div>


<div class="row px-4 py-2">

  <div class="col-sm-4">
    <b>FECHA Y HORA CARGUE:</b> <?php echo $id->datetime?>
  </div>

  <div class="col-sm-3">
    <b>RESPONSABLE:</b> <?php echo $id->operatorname?>
  </div>
  <div class="col-sm-3">
    <b>PASTA QUE NO ENTRA:</b> <?php echo $id->paste?>
  </div>

  <div class="col-sm-2">
    <b>TOTAL A CARGAR:</b> <?php echo $net - $id->paste ?>
  </div>

  <div class="col-sm-12 mt-2 mb-4">
    <b>NOTAS:</b> <?php echo $id->notes ?>
  </div>

  <table class="tabla" style="width:100%;">
    <tr>
        <th style="width:40px">N°</th>
        <th>Cliente</th>
        <th>Producto</th>
        <th>Remision</th>
        <th>Tipo de Envase</th>
        <th>Peso Bruto<br>Eco</th>
        <th>Peso Bruto<br>Cliente</th>
        <th>Peso Taras<br>Eco</th>
        <th>Peso Taras<br>Cliente</th>
        <th>Peso Neto<br>Eco</th>
        <th>Peso Neto<br>Cliente</th>
        <th>Estado</th>
        <th>Derrames</th>
    </tr>
    <?php 
    $i=0;$kg=0;$kg_client=0;$tara=0;$tara_client=0;$net=0;$net_client=0;
    $filters = "and woId = " . $_REQUEST['id'];
    foreach($this->model->list('a.*,b.company,c.name as productname','mr_items a',$filters,'LEFT JOIN clients b on a.clientId = b.id LEFT JOIN products c on a.productId = c.id') as $r) {
    ?>
    </tr>
      <td><?php echo "<b>" . ($i+1) . "</b>" ?></td>
      <td><?php echo $r->company ?></td>
      <td><?php echo $r->productname ?></td>
      <td><?php echo $r->remision ?></td>
      <td><?php echo $r->type ?></td>
      <td><?php $kg += $r->kg; echo $r->kg ?></td>
      <td><?php $kg_client += $r->kg_client; echo $r->kg_client ?></td>
      <td><?php $tara += $r->tara; echo $r->tara ?></td>
      <td><?php $tara_client += $r->tara_client; echo $r->tara_client ?></td>
      <td><?php $net += ($r->kg - $r->tara); echo number_format($r->kg - $r->tara,2) ?></td>
      <td><?php $net_client += $r->kg_client - $r->tara_client; echo number_format($r->kg_client - $r->tara_client,2) ?></td>
      <td><?php echo $r->status ?></td>
      <td><?php echo $car = ($r->car == "1") ? 'Vehículo' : ''?>
      <?php echo ($r->bucket == "1") ? 'Caneca' : ''?>
      <?php echo ($r->plant == "1") ? 'Planta' : ''?>
      </td>        
    </tr>
    <?php $i++; } ?>
    <tr>
      <td COLSPAN="5"><b>Σ</b></td>
      <td><?php echo "<b>$kg</b>" ?></td>
      <td><?php echo "<b>$kg_client</b>" ?></td>
      <td><?php echo "<b>$tara</b>" ?></td>
      <td><?php echo "<b>$tara_client</b>" ?></td>
      <td><?php echo "<b>" . $net . "</b>" ?></td>
      <td><?php echo "<b>" . $net_client . "</b>" ?></td>
      <td COLSPAN="2"></td>
    </tr>
  </table>    
</div>