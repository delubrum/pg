<?php
require_once 'app/models/model.php';

class CPDController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();

  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(8, $permissions)) {
      $title = "Recuperación Todos";
      $fields = array("id","fecha","producto","acción");
      $url = '?c=CPD&a=Data';
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
      foreach($this->model->list('a.*, c.name as producto','mr_items a','','LEFT JOIN clients b ON a.clientId = b.id LEFT JOIN products c ON a.productId = c.id') as $r) {
        $result[$i]['id'] = $r->woId;
        $result[$i]['fecha'] = '1';
        $result[$i]['producto'] = $r->producto;
        $result[$i]['acción'] = "<a href='?c=Reports&a=PD&id=$r->woId' type='button' target='_blank' class='text-teal-900 hover:text-teal-700'><i class='ri-eye-line text-2xl'></i></a>";
        $i++;
      }
      echo json_encode($result);
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