<?php
require_once 'app/models/model.php';

class RMController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->fields = array("RM","fecha","creador","cliente","producto","status","factura","acción");
    $this->url = '?c=RM&a=Data';
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $title = "Recibo de Material";
      $new = '?c=RM&a=New';
      $content = 'app/components/index.php';
      $filters = 'app/views/rm/filters.php';
      $jspreadsheet = true;
      require_once 'app/views/index.php';
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
      require_once 'app/views/rm/new.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Clients(){
    $employees = [];
    foreach($this->model->list("*","users"," and type = 'Cliente' and status = 1 ORDER BY company ASC") as $r) {
      $employees[] = [
        'id' => $r->id,
        'name' => $r->company
      ];
    }
    header('Content-Type: application/json');
    echo json_encode($employees);
  }

  public function Data(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $total = $this->model->get("count(id) as total", "rm")->total;
      $sql = '';
      if (!empty($_GET['RMFilter'])) { $sql .= " and a.id LIKE '%" . $_GET['RMFilter'] . "%'"; }
      if (!empty($_GET['userFilter'])) { $sql .= " and d.username LIKE '%" . $_GET['userFilter'] . "%'"; }
      if (!empty($_GET['productFilter'])) { $sql .= " and c.name LIKE '%" . $_GET['productFilter'] . "%'"; }
      if (!empty($_GET['fromFilter'])) { $sql .= " and a.date  >='" . $_REQUEST['fromFilter']." 00:00:00'"; }
      if (!empty($_GET['toFilter'])) { $sql .= " and a.date <='" . $_REQUEST['toFilter']." 23:59:59'"; }
      if (!empty($_GET['statusFilter'])) {
        $statusValues = $_GET['statusFilter'];
        $sql .= "AND (a.status = '$statusValues[0]'";
        for ($i = 1; $i < count($statusValues); $i++) {
            $sql .= " OR a.status = '$statusValues[$i]'";
        }
        $sql .= ")";
      }
      $filtered = $this->model->get("count(a.id) as total", "rm a",$sql,'LEFT JOIN users b ON a.clientId = b.id LEFT JOIN products c ON a.productId = c.id LEFT JOIN users d ON a.userId = d.id')->total;
      $colum = isset($_GET['colum']) ? $_GET['colum'] : 'RM';
      $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
      if ($order === 'asc') {
        $newOrder = 'desc';
      } else {
        $newOrder = 'asc';
      }
      $sql .= " ORDER BY $colum $newOrder";
      $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
      $perPage = 10;
      $start = ($page - 1) * $perPage;
      $sql .= " LIMIT $start,$perPage";
      $list = $this->model->list('a.id as RM,a.date as fecha,a.status,a.invoice as factura,d.username as creador,b.company as cliente, c.name as producto','rm a',$sql,'LEFT JOIN users b ON a.clientId = b.id LEFT JOIN products c ON a.productId = c.id LEFT JOIN users d ON a.userId = d.id');
      require_once "app/views/rm/list.php";
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

  public function Save(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      header('Content-Type: application/json');

      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),0)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna PESO BRUTO", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }


      $array = json_decode($_REQUEST['table']);

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


      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),1)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna TIPO ENVASE", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),2)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna PESO BRUTO CLIENTE", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),7)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna ESTADO DEL TAMBOR", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      $item = new stdClass();
      $item->clientId = $_REQUEST['clientId'];
      $item->productId = $_REQUEST['productId'];
      $item->date = $_REQUEST['date'];
      $item->userId = $_SESSION["id-APP"];
      $item->data = $_REQUEST['table'];
      $item->status = 'Terminar R.M.';
      $item->remission = $_REQUEST['remission'];
      $item->drumsReturned = $_REQUEST['drumsReturned'];
      $item->returnToClient = $_REQUEST['returnToClient'];
      $item->barrels = substr_count($_REQUEST['table'], '"Cuñete"');
      $item->drums = substr_count($_REQUEST['table'], '"Tambor"');
      $rm = $this->model->save('rm',$item);
      $itemb = new stdClass();
      $itemb->type = 'RM';
      $itemb->code = $rm;
      $itemb->rmId = $rm;
      $itemb->user = $_REQUEST['user'];
      $itemb->drumsReturned = $_REQUEST['drumsReturned'];
      $itemb->barrels = substr_count($_REQUEST['table'], '"Cuñete"');
      $itemb->drums = substr_count($_REQUEST['table'], '"Tambor"');
      $sum = 0;
      foreach (json_decode($_REQUEST['table']) as $row) {
        $sum += (float)$row[0];
      }
      $itemb->kg = $sum;
      $clientId = $_REQUEST['clientId'];
      $price = $_REQUEST['price'];
      $itemb->price = $this->model->get("$price", "users" , "and id = $clientId")->{$price};
      $id = $this->model->save('transport',$itemb);
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

  public function RM(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and a.id = " . $_REQUEST['id'];
      $id = $this->model->get('a.*,b.company as clientname, c.name as productname, b.city','rm a',$filters,'LEFT JOIN users b ON a.clientId = b.id LEFT JOIN products c ON a.productId = c.id');
      $filters = "and rmId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','rm_items',$filters)->total;
      require_once 'app/views/rm/rm.php';
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

  public function Update(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      header('Content-Type: application/json');
      $item = new stdClass();
      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),2)) {
      $hxTriggerData = json_encode([
        "showMessage" => '{"type": "error", "message": "Complete la columna TARA", "close" : ""}'
      ]);
      header('HX-Trigger: ' . $hxTriggerData);
      http_response_code(204);
      exit;
      }

      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),3)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna TARA CLIENTE", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }
      $item->rmAt = date("Y-m-d H:i:s");
      $item->datetime = $_REQUEST['datetime'];
      $item->operatorId = $_REQUEST['operatorId'];
      $item->reactor = $_REQUEST['reactor'];
      $item->paste = $_REQUEST['paste'];
      $item->toreturn = $_REQUEST['toreturn'];
      $item->surplus = $_REQUEST['surplus'];
      $item->notes =$_REQUEST['notes'];
      $item->data = $_REQUEST['table'];
      $itemb = new stdClass();
      $itemb->rmId = $_REQUEST['id'];
      $items = new stdClass();
      $this->model->save('bc',$itemb);
      $id = $_REQUEST['id'];
      $item->status = ($this->model->get('productId','rm'," and id = $id")->productId == 6) ? 'Facturación' : 'Producción';
      $this->model->update('rm',$item,$_REQUEST['id']);
      $id = $_REQUEST['id'];
      foreach(json_decode($this->model->get("data","rm","and id = $id")->data) as $r) {
        $items->rmId = $id;
        $items->type = $r[0];
        $items->kg = $r[1];
        $items->kg_client = $r[2];
        $items->tara = $r[3];
        $items->tara_client = $r[4];
        $items->status = $r[7];
        $car = ($r[8] == "true") ? 'Vehículo' : '';
        $bucket = ($r[9] == "true") ? 'Caneca' : '';
        $plant = ($r[10] == "true") ? 'Planta' : '';
        $items->spills = "$car $bucket $plant";
        $this->model->save('rm_items',$items);
      }
      $hxTriggerData = json_encode([
        "listChanged" => true,
        "showMessage" => '{"type": "success", "message": "RM Terminado", "close" : "closeModal"}'
      ]);
      header('HX-Trigger: ' . $hxTriggerData);
      http_response_code(204);
      exit;
    } else {
      $this->model->redirect();
    }
  }

  public function Detail(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and a.id = " . $_REQUEST['id'];
      $id = $this->model->get('*,b.username as operatorname','wo a',$filters,'LEFT JOIN users b ON a.operatorId=b.id');
      $filters = "and woId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','mr_items',$filters)->total;
      require_once 'app/views/reports/rm.php';
    } else {
      $this->model->redirect();
    }
  }

  public function UpdateField(){
    require_once "lib/check.php";
    if (in_array(13, $permissions)) {
      print_r($_POST);
      header('Content-Type: application/json');
      $item = new stdClass();
      $item->{$_REQUEST['field']} = $_REQUEST['val'];
      if ($this->model->update('rm',$item,$_REQUEST['id'])) {
        $message = '{"type": "success", "message": "Dato Actualizado", "close" : ""}';
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

}