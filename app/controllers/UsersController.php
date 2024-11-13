<?php
require_once 'app/models/model.php';

class UsersController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $title = "Configuración / Usuarios";
      $fields = array("id","date","name","email","status","action");
      $url = '?c=Users&a=Data';
      $new = '?c=Users&a=New';
      $datatables = true;
      $content = 'app/components/indexdt.php';
      // $filters = 'app/views/users/filters.php';
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
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $i=0;
      foreach ($this->model->list("id,createdAt as date,username as name,email,if(status=1,'Activo','Inactivo') as status","users") as $k => $v) {
        $b1 = ($v->status != 'Activo')
        ? "<a hx-get='?c=Users&a=Status&id=$v->id&status=1' hx-swap = 'outerHTML' class='block mx-3 text-teal-900 hover:text-teal-700 cursor-pointer float-right'><i class='ri-toggle-line text-2xl'></i> Activar</a>" 
        : "<a hx-get='?c=Users&a=Status&id=$v->id&status=0' hx-swap = 'outerHTML' class='block mx-3 text-teal-900 hover:text-teal-700 cursor-pointer float-right'><i class='ri-toggle-fill text-2xl'></i> Desactivar</a>";
        $b2 = "<a hx-get='?c=Users&a=Profile&id=$v->id' hx-target='#myModal' @click='showModal = true' class='block text-teal-900 hover:text-teal-700 cursor-pointer float-right mx-3'><i class='ri-edit-2-line text-2xl'></i> Editar</a>";
        $result[] = (array)$v + ['action' => "$b1$b2"];
      }
      echo json_encode($result);
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
      $item->type = $_REQUEST['type'];
      $cpass = $_REQUEST['cpass'];
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