<?php
require_once 'app/models/model.php';

class UsersController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->fields = array("tipo","fecha","nombre","compañia","email","status","acción");
    $this->url = '?c=Users&a=Data';
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $title = "Configuración / Usuarios";
      $new = '?c=Users&a=New';
      $content = 'app/components/index.php';
      $filters = 'app/views/users/filters.php';
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function New(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      require_once 'app/views/users/new.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $result = array();
      $total = $this->model->get("count(id) as total", "users")->total;
      $sql = '';
      if (!empty($_GET['nameFilter'])) { $sql .= " and username LIKE '%" . $_GET['nameFilter'] . "%'"; }
      if (!empty($_GET['companyFilter'])) { $sql .= " and company LIKE '%" . $_GET['companyFilter'] . "%'"; }
      if (!empty($_GET['emailFilter'])) { $sql .= " and email LIKE '%" . $_GET['emailFilter'] . "%'"; }
      if (!empty($_GET['fromFilter'])) { $sql .= " and createdAt  >='" . $_REQUEST['fromFilter']." 00:00:00'"; }
      if (!empty($_GET['toFilter'])) { $sql .= " and createdAt <='" . $_REQUEST['toFilter']." 23:59:59'"; }
      if (!empty($_GET['statusFilter'])) {
        $statusValues = $_GET['statusFilter'];
        $sql .= "AND (status = '$statusValues[0]'";
        for ($i = 1; $i < count($statusValues); $i++) {
            $sql .= " OR status = '$statusValues[$i]'";
        }
        $sql .= ")";
      }
      $filtered = $this->model->get("count(id) as total", "users",$sql,)->total;
      $colum = isset($_GET['colum']) ? $_GET['colum'] : 'company';
      $order = isset($_GET['order']) ? $_GET['order'] : 'desc';
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
      $list = $this->model->list("id,type as tipo,createdAt as fecha,username as nombre,company as compañia,email,if(status=1,'Activo','Inactivo') as status","users",$sql);
      require_once "app/views/users/list.php";
    } else {
      $this->model->redirect();
    }
  }

  public function Profile(){
    require_once "lib/check.php";
    if (in_array(1, $permissions) and isset($_REQUEST["id"])){
      $filters = "and id = " . $_REQUEST['id'];
      $id = $this->model->get('*','users',$filters);
      $userPermissions = json_decode($id->permissions, true);
      require_once 'app/views/users/profile.php';
    } else if (!in_array(1, $permissions) and ($_REQUEST["id"] == $user->id) ){
      $id = $user;
      require_once 'app/views/users/profile.php';
    }
    else {
      $this->model->redirect();
    }
  }

  public function Status(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $item = new stdClass();
      $item->status = $_REQUEST['status'];
      $id = $_REQUEST['id'];
      $this->model->update('users',$item,$id);
      echo ($_REQUEST['status'] == 1)
        ? "<a hx-get='?c=Users&a=Status&id=$id&status=0' hx-swap = 'outerHTML' class='block mx-3 text-teal-900 hover:text-teal-700 cursor-pointer float-right'><i class='ri-toggle-fill text-2xl'></i> Desactivar</a>"
        : "<a hx-get='?c=Users&a=Status&id=$id&status=1' hx-swap = 'outerHTML' class='block mx-3 text-teal-900 hover:text-teal-700 cursor-pointer float-right'><i class='ri-toggle-line text-2xl'></i> Activar</a>";
    } else {
      $this->model->redirect();
    }
  }

  public function Save(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      header('Content-Type: application/json');
      $response = [];
      $item = new stdClass();
      $item->username = $_REQUEST['name'];
      $item->email = $_REQUEST['email'];
      $item->lang = 'en';
      $item->password = $_REQUEST['newpass'];
      $item->permissions = '[]';
      $item->company = $_REQUEST['company'];
      $item->phone = $_REQUEST['phone'];
      $item->type = $_REQUEST['type'];
      $item->city = $_REQUEST['city'];
      $cpass = $_REQUEST['cpass'];
      $item->price = preg_replace('/[^0-9]+/', '', $_REQUEST['price']);
      $item->products = json_encode($_REQUEST['products']);
      $cpass = $_REQUEST['cpass'];
      if ($cpass != '' and $cpass != $item->password) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Las contraseñas no coinciden", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      if (strlen($item->password) < 4) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "La contraseña debe tener almenos 4 caracteres", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      if ($this->model->get('email','users',"and email = '$item->email'")) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "El email ya existe", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }

      $item->password = password_hash($item->password, PASSWORD_DEFAULT);
      $id = $this->model->save('users', $item);
      if ($id !== false) {
        $message = '{"type": "success", "message": "Usuario guardado", "close" : "closeModal"}';
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

  public function UpdatePermission(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $userId = $_REQUEST['userId'];
      $pId = $_REQUEST['pId'];
      $action = $_REQUEST['action'];
      $name = $_REQUEST['name'];
      $filters = "and id = $userId";
      $permissions = json_decode($this->model->get('permissions','users',$filters)->permissions);
      if ($action == 0) {
        $newArr = array_filter($permissions, function($value) use ($pId) {
          return $value != $pId;
        });
      } else {
        $newArr = array_merge($permissions, [intval($pId)]);
      }
      $item = new stdClass();
      sort($newArr);
      $item->permissions = json_encode(array_values($newArr));
      $id = $this->model->update('users',$item,$userId);
      $color = (in_array($pId,$newArr)) ? 'bg-teal-900 hover:bg-teal-700' : 'bg-gray-500 hover:bg-gray-600';
      $action = (in_array($pId,$newArr)) ? '0' : '1';
      echo "<button 
      hx-put='?c=Users&a=UpdatePermission&userId=$userId&pId=$pId&action=$action&name=$name'
      hx-swap = 'outerHTML'
      hx-trigger='click'
      class='text-white text-sm py-2 px-4 m-1 rounded-md $color transition'>
        <div hx-get='?c=Home&a=Sidebar' hx-target='#sidebarMenu' hx-trigger='load' hx-swap='innetHtml'>
          $name
        </div>
      </button>";
    }
  }

  public function Update(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $item = new stdClass();
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          if($k != 'id') {
            $item->{$k} = $val;
          }
        }
      }
      $id = $this->model->update('users',$item,$_REQUEST['id']);
      if ($id !== false) {
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

  public function UpdatePassword(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $item = new stdClass();
      $item->password = $_REQUEST['newpass'];
      $cpass = $_REQUEST['cpass'];
      if ($cpass != '' and $cpass != $item->password) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Las contraseñas no coinciden", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }
      if (strlen($item->password) < 4) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "La contraseña debe tener almenos 4 caracteres", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
        exit;
      }
      $item->password = password_hash($item->password, PASSWORD_DEFAULT);
      $id = $this->model->update('users',$item,$_REQUEST['id']);
      if ($id !== false) {
        $message = '{"type": "success", "message": "Contraseña Actualizada", "close" : ""}';
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