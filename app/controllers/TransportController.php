<?php
require_once 'app/models/model.php';

class TransportController{
  private $model;
  private $notifications;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->notifications = $this->model->list('title,itemId,url,target,permissionId','notifications', "and status = 1");
    $this->fields = array("fecha","tipo","código","responsable","origen","destino","tambores","kg","valor");
    $this->url = '?c=Transport&a=Data';
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(9, $permissions)) {
      $title = "Reportes / Transporte";
      $content = 'app/components/index.php';
      $filters = 'app/views/transport/filters.php';
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    require_once "lib/check.php";
    if (in_array(9, $permissions)) {
      $total = $this->model->get("count(id) as total", "transport")->total;
      $sql = '';
      if (!empty($_GET['code'])) { $sql .= " and code LIKE '%" . $_GET['code'] . "%'"; }
      if (!empty($_GET['from'])) { $sql .= " and createdAt  >='" . $_REQUEST['from']." 00:00:00'"; }
      if (!empty($_GET['to'])) { $sql .= " and createdAt <='" . $_REQUEST['to']." 23:59:59'"; }
      if (!empty($_GET['type'])) {
        $statusValues = $_GET['type'];
        $sql .= "AND (type = '$statusValues[0]'";
        for ($i = 1; $i < count($statusValues); $i++) {
            $sql .= " OR type = '$statusValues[$i]'";
        }
        $sql .= ")";
      }
      $filtered = $this->model->get("count(id) as total", "transport",$sql,)->total;
      $colum = isset($_GET['colum']) ? $_GET['colum'] : 'createdAt';
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
      $list = $this->model->list("createdAt as fecha,type as tipo,code as código,user as responsable, drums as tambores,kg,price as valor","transport",$sql);
      require_once "app/views/transport/list.php";
    } else {
      $this->model->redirect();
    }
  }

  public function Status(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $item = new stdClass();
      $item->status = $_REQUEST['status'];
      $id = $_REQUEST['id'];
      $this->model->update('products',$item,$id);
      echo ($_REQUEST['status'] == 1)
        ? "<a hx-get='?c=Products&a=Status&id=$id&status=0' hx-swap = 'outerHTML' class='block mx-3 text-teal-900 hover:text-teal-700 cursor-pointer float-right'><i class='ri-toggle-fill text-2xl'></i> Desactivar</a>"
        : "<a hx-get='?c=Products&a=Status&id=$id&status=1' hx-swap = 'outerHTML' class='block mx-3 text-teal-900 hover:text-teal-700 cursor-pointer float-right'><i class='ri-toggle-line text-2xl'></i> Activar</a>";
    } else {
      $this->model->redirect();
    }
  }

  public function Save(){
    require_once "lib/check.php";
    if (in_array(4, $permissions)) {
      header('Content-Type: application/json');
      $name = $_POST['name'];
      if ($this->model->get('id','products',"and name = '$name'")) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "El producto ya existe", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }
      
      $item = new stdClass();
      $table = 'products';
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          if($k != 'id') {
            $item->{$k} = $val;
          }
        }
      }

      empty($_POST['id'])
      ? $id = $this->model->save($table,$item)
      : $id = $this->model->update($table,$item,$_POST['id']);
      if ($id !== false) {
        (empty($_POST['id'])) 
          ? $message = '{"type": "success", "message": "Producto guardado", "close" : "closeModal"}'
          : $message = '{"type": "success", "message": "Producto actualizado", "close" : "closeModal"}';
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