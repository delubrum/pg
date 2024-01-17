<!DOCTYPE html>
<html lang="es">
<head>
  <title>SIPEC | Certificado Recuperación de Material</title>
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
      background-size: cover;4
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
    $fecha = date('Y-m-d'); // Cambia esto por la fecha que necesitas

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
    <p>
      <h4 class="text-center mt-4">CERTIFICADO RECUPERACIÓN DE MATERIAL</h4>
    </p>
    <center>
      <p>
    <b>Procesos Ecoambientales De Colombia S.A.S con NIT 900.637.896-1</b>,
mediante Licencia Ambiental Expedida por el Área Metropolitana del Valle de
Aburra Resolución 000334 del 25 de febrero de 2015.
  </p>
  <p>
Certifica a:
</p>
<p>
<b><?php echo $user->company ?></b>
</p>
<p>
Quien durante el mes de mayo de 2023 realizó la entrega de los solventes contaminados descritos a continuación, resultantes de su proceso productivo, para su correcta y avalada recuperación.
</p>
<table class="tabla" style="width:100%; margin-top:25px;">
      <tr>
        <th>Producto</th>
        <th>Cantidad (kg)</th>
        <th>Lodos (kg)</th>
      </tr>
      <tr>
        <td>Propyflex</td>
        <td>2.550</td>
        <td>100</td>
      </tr>
      <tr>
        <td>Improsolve</th>
        <td>2.550</td>
        <td>100</td>
      </tr>
      <tr>
    </table>
    <p>
      <i>Lodos resultantes del proceso de recuperación los cuales son aprovechados en la fabricación de anticorrosivos e impermeabilizantes. </i>
    </p>


  <div class="col-7 text-left" style="margin-top:100px">
    <h4>
María Cristina Grajales Bodhert
<br>
Gerente
</h4>
</div>
</center>
  </section>


</body>
</html>