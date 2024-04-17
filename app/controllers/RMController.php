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

  public function ClientProducts(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $filters = "and type = 'Cliente' and id = " . $_REQUEST['clientId'];
      echo "<option value='' selected disabled></option>";
      foreach(json_decode($this->model->get('products','users',$filters)->products) as $r) {
        $name = $this->model->get('name','products',"and id = $r")->name;
        echo "<option value='$r'>$name</option>";
      }
    } else {
      $this->model->redirect();
    }
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

      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),1)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna PESO BRUTO CLIENTE", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),6)) {
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
      $item->type = $_REQUEST['type'];
      $item->returnToClient = $_REQUEST['returnToClient'];
      $rm = $this->model->save('rm',$item);
      $itemb = new stdClass();
      $itemb->type = 'RM';
      $itemb->code = $rm;
      $itemb->qty = $_REQUEST['qty'];
      $itemb->user = $_REQUEST['user'];
      $itemb->drums = count(json_decode($_REQUEST['table']));
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
      $item->status = 'Producción';
      $this->model->update('rm',$item,$_REQUEST['id']);
      $id = $_REQUEST['id'];
      foreach(json_decode($this->model->get("data","rm","and id = $id")->data) as $r) {
        $items->rmId = $id;
        $items->kg = $r[0];
        $items->kg_client = $r[1];
        $items->tara = $r[2];
        $items->tara_client = $r[3];
        $items->status = $r[6];
        $car = ($r[7] == "true") ? 'Vehículo' : '';
        $bucket = ($r[8] == "true") ? 'Caneca' : '';
        $plant = ($r[9] == "true") ? 'Planta' : '';
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
      $id = $this->model->get('a.*,d.username as operatorname,b.company as clientname, c.name as productname, b.city','rm a',$filters,'LEFT JOIN users b ON a.clientId=b.id LEFT JOIN products c ON a.productId = c.id LEFT JOIN users d ON a.operatorId=d.id');
      $filters = "and rmId = " . $_REQUEST['id'];
      $net = $this->model->get('SUM(kg-tara) as total','rm_items',$filters)->total;
      require_once 'app/views/reports/rm.php';
    } else {
      $this->model->redirect();
    }
  }

}