<?php
require_once 'app/models/model.php';

class ProjectsController{
  private $model;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(2, $permissions)) {
      $fields = array("id","name","status","action");
      $url = '?c=Projects&a=Data';
      $new = '?c=Projects&a=New';
      require_once 'app/components/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function New(){
    require_once "lib/check.php";
    if (in_array(2, $permissions)) {
      if (!empty($_REQUEST['id'])) {
        $filters = "and id = " . $_REQUEST['id'];
        $id = $this->model->get('*','projects', $filters);
      }
      require_once 'app/views/projects/new.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(2, $permissions)) {
      $result = array();
      $total = $this->model->get("count(id) as total", "projects")->total;
      $sql = '';
      if (!empty($_POST['search']['value'])) {
        $sql .= " and (id LIKE '%" . $_POST['search']['value'] . "%'";
        $sql .= " OR code LIKE '%" . $_POST['search']['value'] . "%'";
        $sql .= " OR name LIKE '%" . $_POST['search']['value'] . "%'";
        $sql .= " OR scope LIKE '%" . $_POST['search']['value'] . "%')";
      }
      $filtered = $this->model->get("count(id) as total","projects",$sql)->total;
      if (!empty($_POST['order'])) {
        $columns = array("id","name","status","action");
        $sql .= " ORDER BY " . $columns[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'];
      }
      $sql .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
      foreach ($this->model->list("id,concat(code,' - ',name,' - ',scope) as name,if(status=1,'Enabled','Disabled') as status","projects",$sql) as $k => $v) {
        $b1 = ($v->status != 'Enabled') 
        ? "<a hx-get='?c=Projects&a=Status&id=$v->id&status=1' hx-on:htmx:after-request='table.ajax.reload( null, false );' class='block mx-3 float-right'><i class='ri-toggle-line cursor-pointer text-gray-500 hover:text-gray-700 text-2xl'></i> </a>" 
        : "<a hx-get='?c=Projects&a=Status&id=$v->id&status=0' hx-on:htmx:after-request='table.ajax.reload( null, false );' class='block mx-3 float-right'><i class='ri-toggle-fill text-blue-500 hover:text-blue-700 cursor-pointer text-2xl'></i> </a>";
        $b2 = "<a hx-get='?c=Projects&a=New&id=$v->id' hx-target='#myModal' @click='showModal = true' class='block text-blue-500 hover:text-blue-700 cursor-pointer float-right mx-3'><i class='ri-edit-2-line text-2xl'></i></a>";
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

  public function Save(){
    require_once "lib/check.php";
    if (in_array(2, $permissions)) {
      header('Content-Type: application/json');
      $item = new stdClass();
      $table = 'projects';
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
        $response['status'] = 'success';
        $response['message'] = 'Success';
        (empty($_POST['id'])) ? $response['id'] = $id : '';
      } else {
        http_response_code(500); // Código de estado HTTP Internal Server Error
        $response['status'] = 'error';
        $response['message'] = 'Error';
      }
      echo json_encode($response);
    } else {
      $this->model->redirect();
    }
  }

  public function Status(){
    require_once "lib/check.php";
    if (in_array(2, $permissions)) {
      $item = new stdClass();
      $item->status = $_REQUEST['status'];
      $this->model->update('projects',$item,$_REQUEST['id']);
    } else {
      $this->model->redirect();
    }
  }

}