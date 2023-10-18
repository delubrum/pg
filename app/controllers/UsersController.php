<?php
require_once 'app/models/model.php';

class UsersController{
  private $model;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(1, $permissions)) {
      $fields = array("id","date","name","email","status","action");
      $url = '?c=Users&a=Data';
      $new = '?c=Users&a=New';
      require_once 'app/components/index.php';
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
      $result = array();
      $total = $this->model->get("count(id) as total", "users")->total;
      $sql = '';
      if (!empty($_POST['search']['value'])) {
        $sql .= " and (id LIKE '%" . $_POST['search']['value'] . "%'";
        $sql .= " OR username LIKE '%" . $_POST['search']['value'] . "%'";
        $sql .= " OR email LIKE '%" . $_POST['search']['value'] . "%')";
      }
      $filtered = $this->model->get("count(id) as total", "users",$sql,)->total;

      if (!empty($_POST['order'])) {
        $columns = array("id","date","name","email","status","action");
        $sql .= " ORDER BY " . $columns[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'];
      }
      $sql .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
      foreach ($this->model->list("id,createdAt as date,username as name,email,if(status=1,'Enabled','Disabled') as status","users",$sql) as $k => $v) {
        $b1 = ($v->status != 'Enabled')
        ? "<a hx-get='?c=Users&a=Status&id=$v->id&status=1' hx-on:htmx:after-request='table.ajax.reload( null, false );' class='block mx-3 float-right'><i class='ri-toggle-line cursor-pointer text-gray-500 hover:text-gray-700 text-2xl'></i> </a>" 
        : "<a hx-get='?c=Users&a=Status&id=$v->id&status=0' hx-on:htmx:after-request='table.ajax.reload( null, false );' class='block mx-3 float-right'><i class='ri-toggle-fill text-blue-500 hover:text-blue-700 cursor-pointer text-2xl'></i> </a>";
        $b2 = "<a hx-get='?c=Users&a=Profile&id=$v->id' hx-target='#myModal' @click='showModal = true' class='block text-blue-500 hover:text-blue-700 cursor-pointer float-right mx-3'><i class='ri-edit-2-line text-2xl'></i></a>";
        $result[] = (array)$v + ['action' => "$b1$b2"];
      }
      $json_data = array(
        "draw" => intval($_POST['draw']),
        "recordsTotal" => intval($total),
        "recordsFiltered" => intval($filtered),
        "data" => $result
      );
      echo json_encode($json_data);
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
      $this->model->update('users',$item,$_REQUEST['id']);
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
      $item->type = $_REQUEST['type'];
      $item->lang = 'en';
      $item->payroll = $_REQUEST['payroll'];
      $item->overtime = $_REQUEST['overtime'];
      $item->hour = $_REQUEST['hour'];
      $item->permissions = [];
      $cpass = $_REQUEST['cpass'];
      if ($cpass != '' and $cpass != $item->password) {
        http_response_code(400);
        $response['status'] = 'error';
        $response['message'] = 'Passwords do not match';
        echo json_encode($response);
        exit;
      }
      if (strlen($item->password) < 4) {
        http_response_code(400);
        $response['status'] = 'error';
        $response['message'] = 'Password must be at least 4 characters long';
        echo json_encode($response);
        exit;
      }
      if ($this->model->get('email','users',"and email = '$item->email'")) {
        http_response_code(400);
        $response['status'] = 'error';
        $response['message'] = 'Email already existsh';
        echo json_encode($response);
        exit;
      }
      $item->password = password_hash($item->password, PASSWORD_DEFAULT);
      $id = $this->model->save('users', $item);
      if ($id !== false) {
        $response['status'] = 'success';
        $response['message'] = 'Success';
        $response['id'] = $id;
        echo json_encode($response);
        exit;
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
      $color = (in_array($pId,$newArr)) ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-500 hover:bg-gray-600';
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

}