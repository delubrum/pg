<?php
require_once 'app/models/model.php';

class ReportsController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->fields = array("RM","fecha","creador","cliente","producto","status","factura","acción");
    $this->url = '?c=RM&a=Data';
  }

  public function PD(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and id = " . $_REQUEST['id'];
      $id = $this->model->get('*','wo',$filters);
      $filters = "and woId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','mr_items',$filters)->total;
      $kg = $this->model->get('SUM(kg) as total','mr_items',$filters)->total;
      $tara = $this->model->get('SUM(tara) as total','mr_items',$filters)->total;
      require_once 'app/views/reports/pd.php';
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
      $itemb = new stdClass();
      $itemb->type = 'Factura';
      $itemb->code = $_REQUEST['invoice'];
      $itemb->start = $_REQUEST['start'];
      $itemb->end = $_REQUEST['end'];
      $itemb->qty = $_REQUEST['qty'];
      $itemb->user = $_REQUEST['user'];
      $itemb->price = preg_replace('/[^0-9]+/', '', $_REQUEST['price']);
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