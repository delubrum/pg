<?php
require_once 'app/models/model.php';

class CMonthController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(5, $permissions)) {
      $title = "Recuperación Mensual";
      $fields = array("fecha","acción");
      $url = '?c=CMonth&a=Data';
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
    if (in_array(5, $permissions)) {
      $result[] = array();
      $i=0;
      foreach($this->model->list('invoiceAt as fecha',"mr_items a","and clientId = $user->id and invoiceAt is not null GROUP BY MONTH(b.invoiceAt), YEAR(b.invoiceAt)",'LEFT JOIN wo b on a.woId = b.id') as $r) {
        $result[$i]['fecha'] = $r->date;
        $i++;
      }
      echo json_encode($result);
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