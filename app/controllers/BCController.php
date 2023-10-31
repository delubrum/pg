<?php
require_once 'app/models/model.php';

class BCController{
  private $model;
  private $notifications;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->notifications = $this->model->list('title,itemId,url,target,permissionId','notifications', "and status = 1");
    $this->fields = array("RM","fecha","creador","cliente","producto","status","factura","acción");
    $this->url = '?c=RM&a=Data';
  }

  public function BC(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and a.rmId = " . $_REQUEST['id'];
      $id = $this->model->get('a.*, b.paste, b.reactor, c.company as clientname, d.name as productname','bc a',$filters,'LEFT JOIN rm b ON a.rmId = b.id LEFT JOIN users c ON b.clientId = c.id LEFT JOIN products d ON b.productId = d.id');
      require_once 'app/views/rm/bc.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Results(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and a.rmId = " . $_REQUEST['id'];
      $id = $this->model->get('a.*, b.paste, b.reactor, c.company as clientname, d.name as productname','bc a',$filters,'LEFT JOIN rm b ON a.rmId = b.id LEFT JOIN users c ON b.clientId = c.id LEFT JOIN products d ON b.productId = d.id');
      $filters = "and rmId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','rm_items',$filters)->total;
      $qty = $net - $id->paste;
      $recovered =  $this->model->get('SUM(net) as total','bc_items'," and type = 'Ingreso' and bcid = $id->id")->total;
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
      $rm = new stdClass();
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          $item->{$k} = $val;
        }
      }
      $item->userId = $_SESSION["id-APP"];
      $bcId = $_REQUEST['bcId'];
      $rmId = $this->model->get("*","bc","and id = $bcId")->rmId;
      if (empty($this->model->get("*","bc_items","and bcId = $bcId")->id)) {
        $rm->start = date("Y-m-d H:i:s");
        $rm->status = 'Iniciado';
        $this->model->update('rm',$rm,$rmId);
      }
      $this->model->save('bc_items',$item);
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
      $filters = "and bcId = " . $_REQUEST['id'] . " and a.type='Ingreso'";
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
      $filters = "and bcId = " . $_REQUEST['id'] . " and a.type='Caldera'";
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

      $item = new stdClass();
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          if($k != 'id') {
            $item->{$k} = $val;
          }
        }
      }
      $id = $this->model->save('bc',$item);
      $itemb = new stdClass();
      $itemb->bcAt = date("Y-m-d H:i:s");
      $itemb->status = 'Análisis';
      $bcId = $_REQUEST['id'];
      $rmId = $this->model->get("*","bc"," and id = $bcId")->rmId;
      $this->model->update('rm',$itemb,$rmId);
      if ($id !== false) {
        $hxTriggerData = json_encode([
          "listChanged" => true,
          "showMessage" => '{"type": "success", "message": "Bitacora Guardada", "close" : "closeModal"}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
      }
    } else {
      $this->model->redirect();
    }
  }


  public function Detail(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and a.rmId = " . $_REQUEST['id'];
      $id = $this->model->get('a.*, b.paste, b.reactor, c.company as clientname, d.name as productname','bc a',$filters,'LEFT JOIN rm b ON a.rmId = b.id LEFT JOIN users c ON b.clientId = c.id LEFT JOIN products d ON b.productId = d.id');
      $filters = "and rmId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','rm_items',$filters)->total;
      $qty = $net - $id->paste;
      $status = "Bitacora";
      require_once 'app/views/reports/bc.php';
    } else {
      $this->model->redirect();
    }
  }

  

}