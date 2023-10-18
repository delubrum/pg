<?php
require_once 'app/models/model.php';

class ReportController{
  private $model;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      $fields = array("id","date","user","project","reported","action");
      $url = '?c=Report&a=Data';
      $new = '?c=Report&a=New';
      require_once 'app/components/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function New(){
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {
      require_once 'app/views/report/new.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(3, $permissions)) {

      $result = array();
      $total = $this->model->get("count(id) as total", "report a")->total;
      $sql = '';
      if (!empty($_POST['search']['value'])) {
        $sql .= " and (a.id LIKE '%" . $_POST['search']['value'] . "%'";
        $sql .= " OR b.username LIKE '%" . $_POST['search']['value'] . "%'";
        $sql .= " OR a.hours LIKE '%" . $_POST['search']['value'] . "%'";
        $sql .= " OR c.name LIKE '%" . $_POST['search']['value'] . "%')";
      }
      $filtered = $this->model->get("count(a.id) as total", "report a",$sql, 'LEFT JOIN users b on a.userId = b.id LEFT JOIN projects c on a.projectId = c.id')->total;

      if (!empty($_POST['order'])) {
        $columns = array("id","date","user","project","reported","action");
        $sql .= " ORDER BY " . $columns[$_POST['order'][0]['column']] . " " . $_POST['order'][0]['dir'];
      }
      $sql .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
      foreach ($this->model->list("a.id,a.createdAt as date,b.username as user,concat(c.code,' - ',c.name,' - ',c.scope) as project,a.hours as reported,if(a.status=1,'Enabled','Disabled') as status", "report a",$sql, 'LEFT JOIN users b on a.userId = b.id LEFT JOIN projects c on a.projectId = c.id') as $k => $v) {
        $b = (in_array(4, $permissions)) 
        ? "<a hx-get='?c=Report&a=Status&id=$v->id&status=1' hx-on:htmx:after-request='table.ajax.reload( null, false );' class='block mx-3 float-right'><i class='ri-check-line cursor-pointer text-blue-500 hover:text-blue-700 text-2xl'></i> </a>"
        : "";
        $result[] = (array)$v + ['action' => "$b"];
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
      $item = new stdClass();
      $table = 'report';
      foreach($_POST as $k => $val) {
        if (!empty($val)) {
          if($k != 'id') {
            $item->{$k} = $val;
          }
        }
      }
      $item->userId = $_SESSION["id-APP"];
      $id = $this->model->save($table,$item);
      $notification = new stdClass();
      $notification->itemId = $id;
      $notification->permissionId = 4;
      $notification->title = 'New Report';
      $notification->url = '?c=Report&a=Index';
      $notification->target = '#content';
      $this->model->save('notifications',$notification);
      echo "<h4 class='float-right mr-14 mt-4 text-blue-500'>You have already reported your hours today. Thank you!</h4>";
    } else {
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

}