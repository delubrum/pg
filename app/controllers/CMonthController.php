<?php
require_once 'app/models/model.php';

class CMonthController{
  private $model;
  private $notifications;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->notifications = $this->model->list('title,itemId,url,target,permissionId','notifications', "and status = 1");
    $this->fields = array("fecha","acción");
    $this->url = '?c=CMonth&a=Data';
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(5, $permissions)) {
      $title = "Recuperación Mensual";
      $content = 'app/components/index.php';
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    require_once "lib/check.php";
    if (in_array(5, $permissions)) {
      $total = $this->model->get("count(id) as total", "rm","and clientId = $user->id and invoiceAt is not null GROUP BY MONTH(invoiceAt), YEAR(invoiceAt)")->total;
      $sql = "and clientId = $user->id and invoiceAt is not null GROUP BY MONTH(invoiceAt), YEAR(invoiceAt)";
      if (!empty($_GET['fromFilter'])) { $sql .= " and createdAt  >='" . $_REQUEST['fromFilter']." 00:00:00'"; }
      if (!empty($_GET['toFilter'])) { $sql .= " and createdAt <='" . $_REQUEST['toFilter']." 23:59:59'"; }
      $filtered = $this->model->get("count(id) as total", "rm",$sql)->total;
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
      $list = $this->model->list('invoiceAt as date','rm',$sql);
      require_once "app/views/certificates/month/list.php";
    } else {
      $this->model->redirect();
    }
  }

  public function Detail(){
    require_once "lib/check.php";
    if (in_array(5, $permissions)) {
      $date = $_REQUEST['date'];
      require_once 'app/views/reports/certificate.php';
    } else {
      $this->model->redirect();
    }
  } 

}