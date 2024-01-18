<?php
require_once 'app/models/model.php';

class CPDController{
  private $model;
  private $notifications;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
    $this->notifications = $this->model->list('title,itemId,url,target,permissionId','notifications', "and status = 1");
    $this->fields = array("id","fecha","producto","acción");
    $this->url = '?c=CPD&a=Data';
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(8, $permissions)) {
      $title = "Paquete de Despacho";
      $content = 'app/components/index.php';
      $filters = 'app/views/certificates/pd/filters.php';
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }


  public function Data(){
    require_once "lib/check.php";
    if (in_array(8, $permissions)) {
      $sql = "and clientId = $user->id and a.status = 'Cerrado'";
      $total = $this->model->get("count(a.id) as total", "rm a",$sql)->total;
      if (!empty($_GET['idFilter'])) { $sql .= " and a.id LIKE '%" . $_GET['idFilter'] . "%'"; }
      if (!empty($_GET['productFilter'])) { $sql .= " and c.name LIKE '%" . $_GET['productFilter'] . "%'"; }
      if (!empty($_GET['fromFilter'])) { $sql .= " and a.invoiceAt  >='" . $_REQUEST['fromFilter']." 00:00:00'"; }
      if (!empty($_GET['toFilter'])) { $sql .= " and a.invoiceAt <='" . $_REQUEST['toFilter']." 23:59:59'"; }
      $filtered = $this->model->get("count(a.id) as total", "rm a",$sql,'LEFT JOIN users b ON a.clientId = b.id LEFT JOIN products c ON a.productId = c.id')->total;
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
      $list = $this->model->list('a.*,b.company as clientname, c.name as productname','rm a',$sql,'LEFT JOIN users b ON a.clientId = b.id LEFT JOIN products c ON a.productId = c.id');
      require_once "app/views/certificates/pd/list.php";
    } else {
      $this->model->redirect();
    }
  }

  public function Detail(){
    require_once "lib/check.php";
    if (in_array(8, $permissions)) {
      require_once 'views/layout/header.php';
      require_once 'views/certificates/pd.php';
    } else {
      $this->model->redirect();
    }
  } 

}