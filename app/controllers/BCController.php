<?php
require_once 'app/models/model.php';

class BCController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function BC(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and id = " . $_REQUEST['id'];
      $id = $this->model->get('*','wo',$filters);
      require_once 'app/views/rm/bc.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Results(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and id = " . $_REQUEST['id'];
      $id = $this->model->get('*','wo',$filters);
      $filters = "and woId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','mr_items',$filters)->total;
      $qty = $net - $id->paste;
      $recovered =  $this->model->get('SUM(net) as total','bc_items'," and type = 'Ingreso' and woId = $id->id")->total;
      $pr = number_format($recovered/$qty*100);
      echo "
      <div class='text-center'>
          <b>PESO MP A RECUPERAR</b> <br><span class='text-2xl text-teal-700 font-bold'>$qty</span>
      </div>
      <div class='text-center'>
          <b>PESO MARTERIAL RECUPERADO</b> <br><span class='text-2xl text-teal-700 font-bold'>$recovered</span>
      </div>
      <div class='text-center'>
        <b>% RECUPERACIÓN</b> <br><span class='text-2xl text-teal-700 font-bold'>$pr %</span>
      </div>
      ";
    } else {
      $this->model->redirect();
    }
  }

  public function NewItem(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $id = $_REQUEST['id'];
      require_once 'app/views/rm/new-item.php';
    } else {
      $this->model->redirect();
    }
  }

  public function SaveItem(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      header('Content-Type: application/json');
      $item = new stdClass();
      $wo = new stdClass();
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          if($k != 'id') {
            $item->{$k} = $val;
          }
        }
      }
      $item->userId = $_SESSION["id-APP"];
      $item->woId = $_REQUEST['id'];
      if (empty($this->model->get("*","bc_items","and woId = $item->woId")->id)) {
        $wo->start = date("Y-m-d H:i:s");
        $wo->status = 'Iniciado';
        $this->model->update('wo',$wo,$item->woId);
      }
      $id = $this->model->save('bc_items',$item);
      $alert = new stdClass();

      //Alert 2 hours

      if ($this->model->get('type','bc_items',"and id = '$id'")->type == 'Ingreso') {
        if ($this->model->get('createdAt','bc_items',"and id < '$id' and type = 'Ingreso' ORDER BY id DESC LIMIT 1")) {
          $current = $this->model->get('createdAt','bc_items',"and id = '$id'")->createdAt;
          $previous = $this->model->get('createdAt','bc_items',"and id < '$id' and type = 'Ingreso' ORDER BY id DESC LIMIT 1")->createdAt;
          $startDate = new DateTime($current);
          $endDate = new DateTime($previous);
          $interval = $startDate->diff($endDate);
          $hours = $interval->h + ($interval->days * 24);
          if ($hours >= 2) {
            $alert->title = "El Lote con id $woId presenta un tambor que tomó mas de 2 horas";
            $this->model->save('notifications',$alert);
          }
        }
      }
      $hxTriggerData = json_encode([
        "listItemsChanged" => true,
        "showMessage" => '{"type": "success", "message": "Registrado", "close" : "closeNested"}'
      ]);
      header('HX-Trigger: ' . $hxTriggerData);
      http_response_code(204);
    } else {
      $this->model->redirect();
    }
  }

  public function DataItems(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $i=0;
      $filters = "and woId = " . $_REQUEST['id'] . " and a.type='Ingreso'";
      echo "
        <thead>
        <tr>
            <th>Nro</th>
            <th>Fecha</th>
            <th>Peso <br> Neto</th>
            <th>Peso <br> Tambor</th>
            <th>T°</th>
            <th>Notas</th>
            <th>Usuario</th>
        </tr>
        </thead>
        <tbody>
      ";
      foreach($this->model->list('a.*,b.username','bc_items a',$filters,'LEFT JOIN users b on a.userId = b.id') as $r) {
        $index = ($i!=0) ? $i : '';
        echo "
        <tr>
          <td>$index</td>
          <td>$r->createdAt</td>
          <td>$r->net</td>
          <td>$r->drum</td>
          <td>$r->temp</td>
          <td>$r->notes</td>
          <td>$r->username</td>
        </tr>
        ";
        $i++;
      }
      echo "</tbody>";
    } else {
      $this->model->redirect();
    }
  }

  public function DataItemsB(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $i=0;
      $filters = "and woId = " . $_REQUEST['id'] . " and a.type='Caldera'";
      echo "
        <thead>
          <tr>
            <th>Fecha</th>
            <th>T°</th>
            <th>Notas</th>
            <th>Usuario</th>
          </tr>
        </thead>
        <tbody>
      ";
      foreach($this->model->list('a.*,b.username','bc_items a',$filters,'LEFT JOIN users b on a.userId = b.id') as $r) {
        $index = ($i!=0) ? $i : '';
        echo "
        <tr>
          <td>$r->createdAt</td>
          <td>$r->temp</td>
          <td>$r->notes</td>
          <td>$r->username</td>
        </tr>
        ";
        $i++;
      }
      echo "</tbody>";
    } else {
      $this->model->redirect();
    }
  }

  public function Save(){
    require_once "lib/check.php";
    if (in_array(4, $permissions)) {
      header('Content-Type: application/json');

      if ($_POST['water1'] <= $_POST['water0']) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Agua Final menor que Agua Inicial", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }

      if ($_POST['gas1'] <= $_POST['gas0']) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Gas Final menor que Gas Inicial", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }

      if ($_POST['energy1'] <= $_POST['energy0']) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Energía Final menor que Energía Inicial", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }

      $woId = $_REQUEST['id'];

      if ($this->model->get('count(id) as total','bc_items'," and woId = '$woId'")->total < 3) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "No hay Ingresos Suficientes", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }


      $item = new stdClass();
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          if($k != 'id') {
            $item->{$k} = $val;
          }
        }
      }
      $itemb = new stdClass();
      $itemb->producedAt = date("Y-m-d H:i:s");
      $itemb->status = 'Análisis';
      $this->model->update('wo',$itemb,$woId);
      //Alert
      $alert = new stdClass();
      $net = $this->model->get('SUM(kg-tara) as total','mr_items',"and woId = $woId")->total;
      $total = $net - $this->model->get('paste','wo',"and id = $woId")->paste;
      $recover = $this->model->get('SUM(net) as total','bc_items',"and woId = '$woId'")->total;
      $perc = ($recover/$total*100);
      if ($perc < 70) {
        $alert->title = "El Lote con id $woId tuvo un porcentaje de recuperación menor al 70%";
        $this->model->save('notifications',$alert);
      }

      if ($perc >= 90) {
        $alert->title = "El Lote con id $woId tuvo un porcentaje de recuperación >= al 90%";
        $this->model->save('notifications',$alert);
      }

      //Alert 11 hours total
      if ($this->model->get('createdAt','bc_items',"and woId = $woId ORDER BY id DESC limit 1")->createdAt) {
        $first = $this->model->get('createdAt','bc_items',"and woId = $woId ORDER BY id ASC limit 1")->createdAt;
        $last = $this->model->get('createdAt','bc_items',"and woId = $woId ORDER BY id DESC limit 1")->createdAt;
        $startDate = new DateTime($first);
        $endDate = new DateTime($last);
        $interval = $startDate->diff($endDate);
        $hours = $interval->h + ($interval->days * 24);
        if ($hours >= 11) {
          $alert->title = "El Lote con id $woId duró mas de 11 horas en producción";
          $this->model->save('notifications',$alert);
        }
      }

      if (strtotime($this->model->get('createdAt','bc_items',"and woId = $woId ORDER BY id ASC limit 1")->createdAt) > strtotime(date("Y-m-d") . " 18:15:00")) {
        $alert->title = "El Lote con id $woId inició luego de las 6:15 pm";
        $this->model->save('notifications',$alert);
      }



      // if ($id !== false) {
        $hxTriggerData = json_encode([
          "listChanged" => true,
          "showMessage" => '{"type": "success", "message": "Bitacora Guardada", "close" : "closeModal"}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
      // }
    } else {
      $this->model->redirect();
    }
  }

  public function Update(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $item = new stdClass();
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          if($k != 'id') {
            $item->{$k} = $val;
          }
        }
      }
      $id = $this->model->update('wo',$item,$_REQUEST['id']);
    } else {
      $this->model->redirect();
    }
  }


  public function Detail(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = " and id = " . $_REQUEST['id'];
      $id = $this->model->get('*','wo',$filters);
      $filters = " and woId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','mr_items',$filters)->total;
      $qty = $net - $id->paste;
      $qtybit = $this->model->get('SUM(net) as total','bc_items',$filters)->total;
      $status = "Bitacora";
      require_once 'app/views/reports/bc.php';
    } else {
      $this->model->redirect();
    }
  }

  

}