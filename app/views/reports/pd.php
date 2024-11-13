<!DOCTYPE html>
<html lang="es">
<head>
  <title>SIPEC | Paquete de Despacho</title>
  <link rel="icon" sizes="192x192" href="app/assets/img/logo.png">
  <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css?v=3.2.0">

  <style>
    .tabla th, .tabla td {
      border: 1px solid black;
      border-collapse: collapse;
      text-align:center;
    }

    body {
      background-image: url('app/assets/img/membrete.jpg');
      background-repeat: no-repeat;
      background-size: cover;
    }

    .margin {
      padding-top:200px;
    }

  </style>
</head>
 
<body class="p-4">

  <p class="pt-4 mt-4">
  <?php 
    $meses = array(
      1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
      5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
      9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    );

    // Fecha a formatear
    $fecha = isset($id->invoiceAt) ? $id->invoiceAt : $id->producedAt; // Cambia esto por la fecha que necesitas

    // Convierte la fecha en formato legible
    $timestamp = strtotime($fecha);
    $dia = date('j', $timestamp);
    $mes = $meses[date('n', $timestamp)];
    $año = date('Y', $timestamp);

    // Formatea la fecha en texto
    $fecha_formateada = "$dia de $mes de $año";

    // Imprime la fecha formateada
    echo $fecha_formateada; // Salida: 14 de agosto de 2023
  ?>
  </p>


  <section class="margin">
    <!-- <center>
    <table class="tabla" style="width:100%; margin-top:25px;">
      <tr>
        <th>Cliente</th>
        <th>Producto</th>
        <th>Lote</th>
        <th>RM</th>
      </tr>
      <tr>
        <td><?php echo $id->clientname ?></td>
        <td><?php echo $id->productname ?></td>
        <td><?php echo $id->bcId ?></td>
        <td><?php echo $id->id ?></td>

      </tr>
    </table>
    </center> -->
    <p>
    <h4 class="text-center mt-4">INFORME DE PROCESO</h4>
    <!-- <h5 class="text-center">
      <span>LOTE: <?php echo $id->bcId ?></span>
      <span>RM: <?php echo $id->id ?></span>
    </h5> -->
    </p>
    <center>
    <table class="tabla text-center" style="width:100%">
      <tr>
        <td style="width:50% !important">Peso bruto recibido</td>
        <td><?php echo $kg?></td>
      </tr>
      <tr>
        <td>Peso taras</td>
        <td><?php echo $tara?></td>
      </tr>
      <tr>
        <td>Pasta que no entra</td>
        <td><?php echo $id->paste?></td>
      </tr>
      <tr>
        <td>Peso materia prima para recuperar</td>
        <td><?php echo $net - $id->paste ?></td>
      </tr>
      <tr>
        <td>Peso material recuperado</td>
        <td><?php echo $id->mpClient ?></td>
      </tr>
      <tr>
        <td>Lodos del proceso</td>
        <td><?php echo $id->mudpClient ?></td>
      </tr>
      <tr>
        <td>Destilado húmedo</td>
        <td><?php echo $id->distilledClient ?></td>
      </tr>
      <tr>
        <td>Perdida por evaporación</td>
        <td><?php echo $id->evaporationClient ?></td>
      </tr>
      <tr>
        <td>Porcentaje de Recuperación</td>
        <td><?php echo number_format($id->mpClient/($net - $id->paste)*100) ?></td>
      </tr>
    </table>

    <table class="tabla" style="width:100%; margin-top:25px;">
      <tr>
        <td>Lodos de Destilación %</td>
        <td>Húmedad %</td>
        <td>Evaporación %</td>
        
      </tr>
      <tr>
        <td><?php echo number_format($id->mudpClient/($net - $id->paste)*100,2) ?></td>
        <td><?php echo number_format($id->distilledClient/($net - $id->paste)*100,2) ?></td>
        <td><?php echo number_format($id->evaporationClient/($net - $id->paste)*100,2) ?></td>
      </tr>
    </table>
    </center>
  </section>

  <section class="mt-4">
    <h4 class="text-center">CERTIFICADO DE ANÁLISIS</h4>
    <center>
    <table class="tabla" style="width:100%; margin-top:25px;">
      <tr>
        <th>PARAMETRO</td>
        <th>ESPECIFICACIÓN</td>
        <th>RESULTADO</td>
      </tr>
      <tr>
        <td>Apariencia</td>
        <td>Liquido Transparente</td>
        <td>Liquido Transparente</td>
      </tr>
      <tr>
        <td>Olor</td>
        <td>Característico</td>
        <td>Característico</td>
      </tr>
      <tr>
        <td>Densidad (g/ml)</td>
        <td>0.800 - 0.900</td>
        <td><?php echo $id->densidad ?></td>
      </tr>
      <tr>
        <td>% Humedad</td>
        <td>4.90 - 9.50</td>
        <td><?php echo $id->humedad ?></td>
      </tr>
      <tr>
        <td>% PH</td>
        <td>5</td>
        <td><?php echo $id->ph ?></td>
      </tr>
    </table>
    <p>
      <i><b>Nota:</b> única humedad dado que los tambores están homogenizados, por lo tanto, la humedad es la misma en cada tambor.</i>
    </p>
    </center>

  </section>

  <section class="mt-4">
    <h4 class="text-center">RELACIÓN DE PESOS</h4>
    <center>
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
    </center>
    <br>
    <p>
      <b>PESO NETO RECIBIDO ECOAMBIENTALES:</b> <?php echo $net ?>
      <br>
      <b>PESO NETO REMISIÓN COMPAÑIA:</b> <?php echo $net_client ?>
    </p> 
    <!-- <p>
      <b>Observaciones:</b> Estos fueron los pesos reflejados por parte de <b>PROCESOS ECOAMBIENTALES</b>. Hay una diferencia de <b><?php echo number_format($net_client - $net,2) ?> Kg</b> respecto a la remisión <b><?php echo $id->remission ?></b>. Tener en cuenta pasta que no entra <b><?php echo $id->paste?> Kg</b> 
    </p>
  </section>

  <section class="mt-4">
    <h4 class="text-center">CONTROL DE TAMBORES</h4>
    <center>
    <table class="tabla" style="width:100%;">
      <tr>
          <th style="width:120px">Tipo</th>
          <th>Prestamo</th>
          <th>Devolución</th>
          <th>Saldo Anterior</th>
          <th>Saldo Actual</th>
      </tr>
      </tr>
        <th>Plásticos</th>
        <td><?php echo $this->model->get('count(id) as total','bc_items',"and bcId = $id->bcId")->total-1?></td>
        <td>0</td>
        <td>0</td>
        <td>0</td>           
      </tr>
      </tr>
        <th>Metalicos</th>
        <td>0</td>
        <td><?php echo $this->model->get('count(id) as total','rm_items',"and rmId = $id->id")->total?></td>
        <td>0</td>
        <td>0</td>           
      </tr>
    </table>  
    </center>
  </section> -->

</body>
</html>

