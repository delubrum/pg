<?php
require_once 'app/models/model.php';

class IPController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->fields = array("RM","fecha","creador","cliente","producto","status","factura","acción");
    $this->url = '?c=RM&a=Data';
  }

  public function IP(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and a.rmId = " . $_REQUEST['id'];
      $id = $this->model->get('a.*, b.paste, b.reactor, c.company as clientname, d.name as productname','bc a',$filters,'LEFT JOIN rm b ON a.rmId = b.id LEFT JOIN users c ON b.clientId = c.id LEFT JOIN products d ON b.productId = d.id');
      $filters = "and rmId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','rm_items',$filters)->total;
      $qty = $net - $id->paste;
      $rmId = $_REQUEST['id'];
      $bcId = $this->model->get("id","bc"," and rmId = $rmId")->id;
      $filters = "and bcId = " .  $bcId;
      $qtybit = $this->model->get('SUM(net) as total','bc_items',$filters)->total;
      $recovered =  $this->model->get('SUM(net) as total','bc_items'," and type = 'Ingreso' and bcid = $id->id")->total;
      $pr = number_format($recovered/$qty*100);
      require_once 'app/views/rm/ip.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Save(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      header('Content-Type: application/json');
      if ($_POST['cero'] != 0) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "El calculo debe ser cero", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }
      $item = new stdClass();
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          if($k != 'id' and $k != 'cero') {
            $item->{$k} = $val;
          }
        }
      }
      $item->ipAt = date("Y-m-d H:i:s");
      $item->status = 'Facturación';
      $bcId = $_REQUEST['id'];
      $rmId = $this->model->get("*","bc"," and id = $bcId")->rmId;
      $this->model->update('rm',$item,$rmId);
      //Alert
      $alert = new stdClass();
      $mudpClient = $_REQUEST['mudpClient'];
      $paste = $this->model->get('paste','rm',"and id = $rmId")->paste;
      $net = $this->model->get('SUM(kg-tara) as total','rm_items',"and rmId = $rmId")->total;
      $perc = number_format($mudpClient/($net - $paste)*100);
      echo $perc;
      if ($perc >= 25) {
        $alert->title = "El RM con id $rmId tuvo un porcentaje de Lodos de Destilación mayor al 25%";
        $this->model->save('notifications',$alert);
      }
      if ($id !== false) {
        $hxTriggerData = json_encode([
          "listChanged" => true,
          "showMessage" => '{"type": "success", "message": "Análisis Guardado", "close" : "closeModal"}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
      }
    } else {
      $this->model->redirect();
    }
  }

  public function IV(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $id = $_REQUEST['id'];
      require_once 'app/views/rm/iv.php';
    } else {
      $this->model->redirect();
    }
  }

  public function IVSave(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      header('Content-Type: application/json');
      $item = new stdClass();
      $item->status = 'Cerrado';
      $item->invoice = $_REQUEST['invoice'];
      $item->invoiceAt = date("Y-m-d H:i:s");
      $this->model->update('rm',$item,$_REQUEST['id']);
      $id = $_REQUEST['id'];
      $itemb = new stdClass();
      $itemb->type = 'Factura';
      $itemb->code = $_REQUEST['invoice'];
      $itemb->user = $_REQUEST['user'];
      $itemb->drums = $this->model->get("count(id) as total", "bc_items" , "and id = $id")->total;
      $itemb->kg = $this->model->get("sum(net + drum) as total", "bc_items" , "and id = $id")->total;
      $clientId = $this->model->get("clientId", "rm" , "and id = $id")->clientId;
      $itemb->price = $this->model->get("price", "users" , "and id = $clientId")->price;
      $this->model->save('transport',$itemb);
      $hxTriggerData = json_encode([
        "listChanged" => true,
        "showMessage" => '{"type": "success", "message": "Factura Guardada", "close" : "closeModal"}'
      ]);
      header('HX-Trigger: ' . $hxTriggerData);
      http_response_code(204);
    } else {
      $this->model->redirect();
    }
  }

}