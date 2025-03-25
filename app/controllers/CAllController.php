<?php
require_once 'app/models/model.php';

class CAllController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(11, $permissions)) {
      $title = "Recuperación Todos";
      $fields = array("fecha","cliente","acción");
      $url = '?c=CAll&a=Data';
      $content = 'app/components/indexdt.php';
      //$filters = 'app/views/rm/filters.php';
      $jspreadsheet = false;
      $datatables = true;
      $paginate = true;
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(11, $permissions)) {
      $result[] = array();
      $i=0;
      foreach($this->model->list('invoiceAt as fecha, clientId, c.company as cliente',"mr_items a","and invoiceAt is not null GROUP BY MONTH(b.invoiceAt), YEAR(b.invoiceAt), clientId",'LEFT JOIN wo b on a.woId = b.id LEFT JOIN clients c on clientId = c.id') as $r) {
        $result[$i]['fecha'] = $r->fecha;
        $result[$i]['cliente'] = $r->cliente;
        $result[$i]['acción'] = "<a href='?c=CAll&a=Detail&date=$r->fecha&userId=$r->clientId' type='button' target='_blank' class='text-teal-900 hover:text-teal-700'><i class='ri-eye-line text-2xl'></i></a>";

        $i++;
      }
      echo json_encode($result);
    } else {
      $this->model->redirect();
    }
  }

  public function Detail(){
    require_once "lib/check.php";
    if (in_array(11, $permissions)) {
      if (isset($_REQUEST['userId'])) {
        $userId = $_REQUEST['userId'];
        $user = $this->model->get('*','clients'," and id = $userId");
      }
      $date = $_REQUEST['date'];
      require_once 'app/views/reports/certificate.php';
    } else {
      $this->model->redirect();
    }
  } 

}