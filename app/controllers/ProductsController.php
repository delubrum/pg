<?php
require_once 'app/models/model.php';

class ProductsController{
  private $model;
  private $notifications;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->notifications = $this->model->list('title,itemId,url,target,permissionId','notifications', "and status = 1");
    $this->fields = array("fecha","nombre","status","acción");
    $this->url = '?c=Products&a=Data';
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(4, $permissions)) {
      $title = "Configuración / Productos";
      $new = '?c=Products&a=New';
      $content = 'app/components/index.php';
      $filters = 'app/views/products/filters.php';
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function New(){
    require_once "lib/check.php";
    if (in_array(4, $permissions)) {
      if (!empty($_REQUEST['id'])) {
        $filters = "and id = " . $_REQUEST['id'];
        $id = $this->model->get('*','products', $filters);
      }
      require_once 'app/views/products/new.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    require_once "lib/check.php";
    if (in_array(4, $permissions)) {
      $total = $this->model->get("count(id) as total", "products")->total;
      $sql = '';
      if (!empty($_GET['name'])) { $sql .= " and name LIKE '%" . $_GET['name'] . "%'"; }
      if (!empty($_GET['from'])) { $sql .= " and createdAt  >='" . $_REQUEST['from']." 00:00:00'"; }
      if (!empty($_GET['to'])) { $sql .= " and createdAt <='" . $_REQUEST['to']." 23:59:59'"; }
      if (!empty($_GET['status'])) {
        $statusValues = $_GET['status'];
        $sql .= "AND (status = '$statusValues[0]'";
        for ($i = 1; $i < count($statusValues); $i++) {
            $sql .= " OR status = '$statusValues[$i]'";
        }
        $sql .= ")";
      }
      $filtered = $this->model->get("count(id) as total", "products",$sql,)->total;
      $colum = isset($_GET['colum']) ? $_GET['colum'] : 'fecha';
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
      $list = $this->model->list("id,createdAt as fecha, name as nombre,if(status=1,'Activo','Inactivo') as status","products",$sql);
      require_once "app/views/products/list.php";
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
      $item = new stdClass();
      $table = 'products';
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          if($k != 'id') {
            $item->{$k} = $val;
          }
        }
      }
      $name = $_POST['name'];
      if ($this->model->get('id','products',"and name = '$name'")) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "text": "El producto ya existe"}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }

      empty($_POST['id'])
      ? $id = $this->model->save($table,$item)
      : $id = $this->model->update($table,$item,$_POST['id']);
      if ($id !== false) {
        (empty($_POST['id'])) 
          ? $message = '{"type": "success", "text": "Producto guardado"}'
          : $message = '{"type": "success", "text": "Producto actualizado"}';
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