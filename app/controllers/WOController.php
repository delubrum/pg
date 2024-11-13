<?php
require_once 'app/models/model.php';

class WOController{
  private $model;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(18, $permissions)) {
      $title = "Registro / Lote";
      $fields = array("id","fecha","creador","status","acción");
      $url = '?c=WO&a=Data';
      $new = '?c=WO&a=New';
      $content = 'app/components/indexdt.php';
      $datatables = true;
      $jspreadsheet = true;
      $paginate = true;
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(18, $permissions)) {
      $result[] = array();
      $i=0;
      foreach($this->model->list('a.*,b.username','wo a','','LEFT JOIN users b ON a.userId = b.id') as $r) {
        $result[$i]['id'] = $r->id;
        $result[$i]['fecha'] = $r->createdAt;
        $result[$i]['creador'] = $r->username;
        $result[$i]['status'] = $r->status;

        $edit = "<a hx-get='?c=WO&a=WO&id=$r->id' hx-target='#myModal' @click='showModal = true' class='text-teal-900 hover:text-teal-700 cursor-pointer'><i class='ri-edit-2-line'></i> Completar</a>";
        if ($r->status == 'Producción' || $r->status == 'Iniciado') { $edit = "<a hx-get='?c=BC&a=BC&id=$r->id' hx-target='#myModal' @click='showModal = true' class='text-teal-900 hover:text-teal-700 cursor-pointer'><i class='ri-hammer-line text-2xl'></i> Producir</a>"; }
        if ($r->status == 'Análisis' and in_array(15,$permissions)) { $edit = "<a hx-get='?c=IP&a=IP&id=$r->id' hx-target='#myModal' @click='showModal = true' class='text-teal-900 hover:text-teal-700 cursor-pointer'><i class='ri-edit-2-line text-2xl'></i> Análisis</a>"; }
        if ($r->status == 'Facturación' and in_array(10,$permissions)) { $edit = "<a hx-get='?c=IP&a=IV&id=$r->id' hx-target='#myModal' @click='showModal = true' class='text-teal-900 hover:text-teal-700 cursor-pointer'><i class='ri-exchange-dollar-line text-2xl'></i> Facturar</a>"; }
        if ($r->status == 'Cerrado') { $edit = ""; }
        $rm = ($r->status != 'Completar') ? "<br><a href='?c=RM&a=Detail&id=$r->id' target='_blank' class='text-teal-900 hover:text-teal-700'><i class='ri-file-line text-2xl'></i> Lote</a>" : "";
        $bc = (($r->status == 'Facturación' || $r->status == 'Cerrado' || $r->status == 'Análisis')) ? "<br><a href='?c=BC&a=Detail&id=$r->id' target='_blank' class='text-teal-900 hover:text-teal-700'><i class='ri-file-line text-2xl'></i> Bitácora</a>" : "";
        $pd = (($r->status == 'Facturación' || $r->status == 'Cerrado')) ? "<br><a href='?c=Reports&a=PD&id=$r->id' target='_blank' class='text-teal-900 hover:text-teal-700'><i class='ri-file-line text-2xl'></i> Paquete Despacho</a>" : "";
        $action = <<<HTML
        <div x-data="{ open: false }" style="position: fixed; top: auto; left: auto;">
          <i @click="open = !open" class="ri-more-2-fill text-2xl cursor-pointer text-teal-900 hover:text-teal-700"></i>
          <div x-show="open" @click.away="open = false" class="absolute right-10 origin-top-right z-50 rounded-md shadow-lg">
              <div class="cursor-pointer bg-white rounded-md py-2 px-4">
                  $edit $rm $bc $pd
              </div>
          </div>
        </div>
        HTML;

        $result[$i]['acción'] = "$edit $rm $bc $pd";
        $i++;
      }
      echo json_encode($result);
    } else {
      $this->model->redirect();
    }
  }

  public function New(){
    require_once "lib/check.php";
    if (in_array(18, $permissions)) {
      $fields = array("","id","rmId","fecha","cliente","remision","producto","envase","pesoEco","pesoCliente");
      $url = '?c=WO&a=ItemsData';
      require_once 'app/views/wo/new.php';
    } else {
      $this->model->redirect();
    }
  }

  public function ItemsData(){
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $result[] = array();
      $i=0;
      foreach($this->model->list('a.*,b.date,c.company,d.name','mr_items a','and woId is null','LEFT JOIN mr b ON a.mrId = b.id LEFT JOIN clients c ON a.clientId = c.id LEFT JOIN products d ON a.productId = d.id') as $r) {
        $result[$i][''] = "<input type='checkbox' name='drums[]' value='$r->id'>";
        $result[$i]['id'] = $r->id;
        $result[$i]['rmId'] = $r->mrId;
        $result[$i]['fecha'] = $r->date;
        $result[$i]['cliente'] = $r->company;
        $result[$i]['remision'] = $r->remision;
        $result[$i]['producto'] = $r->name;
        $result[$i]['envase'] = $r->type;
        $result[$i]['pesoEco'] = $r->kg;
        $result[$i]['pesoCliente'] = $r->kg_client;
        $i++;
      }
      echo json_encode($result);
    } else {
      $this->model->redirect();
    }
  }

  public function Save(){
    require_once "lib/check.php";
    if (in_array(18, $permissions)) {
      header('Content-Type: application/json');
      $item = new stdClass();
      $table = 'wo';
      $item->drums = json_encode($_REQUEST['drums'],true);
      $item->userId = $_SESSION["id-APP"];
      $item->status = "Completar";
      $id = $this->model->save($table,$item);
      $items = new stdClass();
      $items->woId = $id;
      $this->model->updateAll('mr_items',$items,$_REQUEST['drums']);
      if ($id !== false) {
        $message = '{"type": "success", "message": "Lote guardado", "close" : "closeModal"}';
        $hxTriggerData = json_encode([
          "listChanged" => true,
          "showMessage" => $message
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
      }
    } else {
      $this->model->redirect();
    }
  }

  public function WO(){
    require_once "lib/check.php";
    if (in_array(18, $permissions)) {
      $filters = "and woId = " . $_REQUEST['id'];
      $data = json_encode($this->model->list('id,clientId,remision,productId,type,kg,kg_client,tara,tara_client,kg-tara as net, kg_client-tara_client as net_client,status','mr_items a',$filters), true);
      $id = $_REQUEST['id'];
      require_once 'app/views/wo/wo.php';
    } else {
      $this->model->redirect();
    }
  }

  function emptyColum(array $data, $columnIndex) {
    if ($columnIndex < 0 || $columnIndex >= count($data[0])) {
      return true; // Invalid column index
    }
    foreach ($data as $row) {
      if (empty($row[$columnIndex])) {
        return false; // At least one empty value found in the column
      }
    }
    return true; // No empty values found in the column
  }

  public function Update(){
    require_once "lib/check.php";
    if (in_array(18, $permissions)) {
      header('Content-Type: application/json');
      $item = new stdClass();
      if (!$this->emptyColum(json_decode($_REQUEST['wo'], true),5)) {
      $hxTriggerData = json_encode([
        "showMessage" => '{"type": "error", "message": "Complete la columna TARA", "close" : ""}'
      ]);
      header('HX-Trigger: ' . $hxTriggerData);
      http_response_code(204);
      exit;
      }

      if (!$this->emptyColum(json_decode($_REQUEST['wo'], true),6)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna TARA CLIENTE", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }
      $item->completeAt = date("Y-m-d H:i:s");
      $item->datetime = $_REQUEST['datetime'];
      $item->operatorId = $_REQUEST['operatorId'];
      $item->reactor = $_REQUEST['reactor'];
      $item->paste = $_REQUEST['paste'];
      // $item->toreturn = $_REQUEST['toreturn'];
      // $item->surplus = $_REQUEST['surplus'];
      $item->notes =$_REQUEST['notes'];
      $item->wo = $_REQUEST['wo'];
      $item->status = 'Producción';
      // $item->status = ($this->model->get('productId','rm'," and id = $id")->productId == 6) ? 'Facturación' : 'Producción';
      $this->model->update('wo',$item,$_REQUEST['id']);
      foreach (json_decode($_REQUEST['wo']) as $r) {
        echo $r['0'];
        $items = new stdClass();
        $items->tara = $r['7'];
        $items->tara_client = $r['8'];
        $items->plant = $r['15'];
        $this->model->update('mr_items',$items,$r['0']);
      }
      $hxTriggerData = json_encode([
        "listChanged" => true,
        "showMessage" => '{"type": "success", "message": "WO Completada", "close" : "closeModal"}'
      ]);
      header('HX-Trigger: ' . $hxTriggerData);
      http_response_code(204);
      exit;
    } else {
      $this->model->redirect();
    }
  }

}