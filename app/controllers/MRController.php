<?php
require_once 'app/models/model.php';

class MRController{
  private $model;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $title = "Recibo de Material";
      $fields = array("id","rmId","fecha","cliente","remision","producto","envase","pesoEco","pesoCliente","tarasEco","tarasCliente","status","lote");
      $url = '?c=MR&a=Data';
      $new = '?c=MR&a=New';
      $content = 'app/components/indexdt.php';
      //$filters = 'app/views/rm/filters.php';
      $jspreadsheet = true;
      $datatables = true;
      $paginate = true;
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $result[] = array();
      $i=0;
      foreach($this->model->list('a.*,b.date,c.company,d.name','mr_items a','','LEFT JOIN mr b ON a.mrId = b.id LEFT JOIN clients c ON a.clientId = c.id LEFT JOIN products d ON a.productId = d.id') as $r) {
        $result[$i]['id'] = $r->id;
        $result[$i]['rmId'] = $r->mrId;
        $result[$i]['fecha'] = $r->date;
        $result[$i]['cliente'] = $r->company;
        $result[$i]['remision'] = $r->remision;
        $result[$i]['producto'] = $r->name;
        $result[$i]['envase'] = $r->type;
        $result[$i]['pesoEco'] = $r->kg;
        $result[$i]['pesoCliente'] = $r->kg_client;
        $result[$i]['tarasEco'] = $r->tara;
        $result[$i]['tarasCliente'] = $r->tara_client;
        $result[$i]['status'] = $r->kg_client;
        $result[$i]['lote'] = $r->woId;
        $i++;
      }
      echo json_encode($result);
    } else {
      $this->model->redirect();
    }
  }

  public function New(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      if (!empty($_REQUEST['id'])) {
        $filters = "and id = " . $_REQUEST['id'];
        $id = $this->model->get('*','products', $filters);
      }
      require_once 'app/views/mr/new.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Clients(){
    $data = [];
    foreach($this->model->list("*","clients","ORDER BY company ASC") as $r) {
      $data[] = [
        'id' => $r->id,
        'name' => $r->company
      ];
    }
    header('Content-Type: application/json');
    echo json_encode($data);
  }

  public function Products(){
    $data = [];
    foreach($this->model->list("*","products","ORDER BY name ASC") as $r) {
      $data[] = [
        'id' => $r->id,
        'name' => $r->name
      ];
    }
    header('Content-Type: application/json');
    echo json_encode($data);
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

  public function Save(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      header('Content-Type: application/json');

      if (!$this->emptyColum(json_decode($_REQUEST['drums'], true),0)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna PESO BRUTO", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }


      $array = json_decode($_REQUEST['drums']);

      $commaFound = false;

      // Iterate over the elements of the array
      foreach ($array as $subarray) {
          // Iterate over the elements of each subarray
          foreach ($subarray as $value) {
              // Check if the value contains a comma
              if (strpos($value, ',') !== false) {
                  // If a comma is found, set the variable to true and break out of the loop
                  $commaFound = true;
                  break 2;
              }
          }
      }
      
      // Check if a comma was found
      if ($commaFound) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Se encontraron valores con Coma", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }


      if (!$this->emptyColum(json_decode($_REQUEST['drums'], true),4)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna TIPO ENVASE", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      if (!$this->emptyColum(json_decode($_REQUEST['drums'], true),5)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna PESO BRUTO CLIENTE", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      if (!$this->emptyColum(json_decode($_REQUEST['drums'], true),10)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna ESTADO DEL TAMBOR", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }
      $item = new stdClass();
      $item->date = $_REQUEST['date'];
      $item->userId = $_SESSION["id-APP"];
      $item->drums = $_REQUEST['drums'];
      $item->returned = $_REQUEST['returned'];
      $item->operatorId = $_REQUEST['operatorId'];
      $item->price = $_REQUEST['price'];
      $id = $this->model->save('mr',$item);
      $items = new stdClass();
      foreach (json_decode($_REQUEST['drums']) as $r) {
        $items->mrId = $id;
        $items->clientId = $r[0];
        $items->remision = $r[1];
        $items->productId = $r[2];
        $items->type = $r[3];
        $items->kg = $r[4];
        $items->kg_client = $r[5];
        $items->status = $r[10];
        $items->toreturn = $r[11];
        $items->car = $r[12];
        $items->bucket = $r[13];
        $items->plant = $r[14];
        $this->model->save('mr_items',$items);
      }
      $hxTriggerData = json_encode([
        "listChanged" => true,
        "showMessage" => '{"type": "success", "message": "RM Guardado", "close" : "closeModal"}'
      ]);
      header('HX-Trigger: ' . $hxTriggerData);
      http_response_code(204);
    } else {
      $this->model->redirect();
    }
  }

  public function ItemsData(){
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $result[] = array();
      $filters = "and id = " . $_REQUEST['id'];
      echo $this->model->get('data','rm',$filters)->data;
    } else {
      $this->model->redirect();
    }
  }

}
