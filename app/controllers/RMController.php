<?php
require_once 'app/models/model.php';

class RMController{
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

  public function Index(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $title = "Registro de Material / Registro";
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
      if (!empty($_GET['RM'])) { $sql .= " and a.id LIKE '%" . $_GET['RM'] . "%'"; }
      if (!empty($_GET['from'])) { $sql .= " and a.date  >='" . $_REQUEST['from']." 00:00:00'"; }
      if (!empty($_GET['to'])) { $sql .= " and a.date <='" . $_REQUEST['to']." 23:59:59'"; }
      if (!empty($_GET['status'])) {
        $statusValues = $_GET['status'];
        $sql .= "AND (a.status = '$statusValues[0]'";
        for ($i = 1; $i < count($statusValues); $i++) {
            $sql .= " OR a.status = '$statusValues[$i]'";
        }
        $sql .= ")";
      }
      $filtered = $this->model->get("count(id) as total", "rm a",$sql,)->total;
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
          "showMessage" => '{"type": "error", "message": "Complete la columna PESO BRUTO"}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),1)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna PESO BRUTO CLIENTE"}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      if (!$this->emptyColum(json_decode($_REQUEST['table'], true),6)) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Complete la columna ESTADO DEL TAMBOR"}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      $item = new stdClass();
      $item->clientId = $_REQUEST['clientId'];
      $item->productId = $_REQUEST['productId'];
      $item->date = $_REQUEST['date'];
      $item->userId = $_SESSION["id-SIPEC"];
      $item->data = $_REQUEST['table'];
      $item->status = 'Terminar R.M.';
      $rm = $this->model->save('rm',$item);
      $itemb = new stdClass();
      $itemb->type = 'RM';
      $itemb->code = $rm;
      $itemb->start = $_REQUEST['start'];
      $itemb->end = $_REQUEST['end'];
      $itemb->qty = $_REQUEST['qty'];
      $itemb->user = $_REQUEST['user'];
      $itemb->price = preg_replace('/[^0-9]+/', '', $_REQUEST['price']);
      $id = $this->model->save('transport',$itemb);
      $hxTriggerData = json_encode([
        "listChanged" => true,
        "showMessage" => '{"type": "success", "message": "RM Guardado"}'
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

}