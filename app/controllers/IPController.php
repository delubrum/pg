<?php
require_once 'app/models/model.php';

class IPController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function IP(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and id = " . $_REQUEST['id'];
      $id = $this->model->get('*','wo a',$filters);
      $filters = "and woId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','mr_items',$filters)->total;
      $qty = $net - $id->paste;
      $qtybit = $this->model->get('SUM(net) as total','bc_items',$filters)->total;
      $recovered =  $this->model->get('SUM(net) as total','bc_items'," and type = 'Ingreso' and woId = $id->id")->total;
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
      $woId = $_REQUEST['id'];
      $this->model->update('wo',$item,$woId);
      //Alert
      $alert = new stdClass();
      $mudpClient = $_REQUEST['mudpClient'];
      $paste = $this->model->get('paste','wo',"and id = $woId")->paste;
      $net = $this->model->get('SUM(kg-tara) as total','mr_items',"and woId = $woId")->total;
      $perc = number_format($mudpClient/($net - $paste)*100);
      echo $perc;
      if ($perc >= 25) {
        $alert->title = "El Lote con id $woId tuvo un porcentaje de Lodos de Destilación mayor al 25%";
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
      $item->invoiceUser = $_REQUEST['user'];
      $id = $_REQUEST['id'];
      $item->drumsSended = ceil($this->model->get("mpClient", "wo" , "and id = $id")->mpClient / 170);
      $this->model->update('wo',$item,$id);
      // $product = $this->model->get("productId", "rm" , "and id = $id")->productId;
      // if ($product != 6) {
      $itemb = new stdClass();
      $itemb->type = 'Factura';
      $itemb->code = $_REQUEST['invoice'];
      $itemb->rmId = $id;
      $itemb->user = $_REQUEST['user'];
      $itemb->kg = $this->model->get("mpClient", "wo" , "and id = $id")->mpClient;
      $itemb->drumsSended = ceil($this->model->get("mpClient", "wo" , "and id = $id")->mpClient / 170);
      $itemb->barrels = 0;
      $itemb->drums = $this->model->get('drums','wo'," and id = $id")->drums;
      $itemb->price = 0;
      $this->model->save('transport',$itemb);
      // }
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