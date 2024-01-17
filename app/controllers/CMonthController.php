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
      $filters = 'app/views/certificates/month/filters.php';
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
      require_once 'app/views/reports/certificate.php';
    } else {
      $this->model->redirect();
    }
  }

  public function PD(){
    require_once "lib/check.php";
    if (in_array(8, $permissions)) {
      require_once 'views/layout/header.php';
      require_once 'views/certificates/pd.php';
    } else {
      $this->model->redirect();
    }
  }

  public function PDData(){
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(8, $permissions)) {
      $result[] = array();
      $i=0;
      foreach($this->model->list('a.*,b.company as clientname, c.name as productname','rm a'," and clientId = $user->id and a.status = 'Cerrado'",'LEFT JOIN users b ON a.clientId = b.id LEFT JOIN products c ON a.productId = c.id') as $r) {
        $result[$i]['id'] = $r->id;
        $result[$i]['date'] = $r->date;
        $result[$i]['product'] = $r->productname;
        $pd = "<a href='?c=RM&a=PD&id=$r->id' type='button' target='_blank' class='btn btn-primary float-right m-1'><i class='fas fa-eye'></i> Ver</a>";
        $result[$i]['action'] = "$pd";
        $i++;
      }
      echo json_encode($result);
    } else {
      $this->model->redirect();
    }
  }

  

}