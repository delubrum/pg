<?php
require_once 'app/models/model.php';

class CAllController{
  private $model;
  private $notifications;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->notifications = $this->model->list('title,itemId,url,target,permissionId','notifications', "and status = 1");
    $this->fields = array("fecha","cliente","acción");
    $this->url = '?c=CAll&a=Data';
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(11, $permissions)) {
      $title = "Recuperación Todos";
      $content = 'app/components/index.php';
      $filters = 'app/views/certificates/all/filters.php';
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }


  public function Data(){
    require_once "lib/check.php";
    if (in_array(11, $permissions)) {
      $total = $this->model->get("count(id) as total", "rm","and invoiceAt is not null GROUP BY MONTH(invoiceAt), YEAR(invoiceAt), clientId")->total;
      $sql = '';
      if (!empty($_GET['clientFilter'])) { $sql .= " and b.username LIKE '%" . $_GET['clientFilter'] . "%'"; }
      $sql .= " and invoiceAt is not null GROUP BY MONTH(invoiceAt), YEAR(invoiceAt), b.username";
      $filtered = $this->model->get("count(a.id) as total", "rm a",$sql,'LEFT JOIN users b on a.clientId = b.id')->total;
      $colum = isset($_GET['colum']) ? $_GET['colum'] : 'a.createdAt';
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
      $list = $this->model->list('invoiceAt as date, clientId, b.username as clientname','rm a',$sql,'LEFT JOIN users b on a.clientId = b.id');
      require_once "app/views/certificates/all/list.php";
    } else {
      $this->model->redirect();
    }
  }

  public function Detail(){
    require_once "lib/check.php";
    if (in_array(11, $permissions)) {
      if (isset($_REQUEST['userId'])) {
        $userId = $_REQUEST['userId'];
        $user = $this->model->get('*','users'," and id = $userId");
      }
      $date = $_REQUEST['date'];
      require_once 'app/views/reports/certificate.php';
    } else {
      $this->model->redirect();
    }
  } 

}