<?php
require_once 'app/models/model.php';

class ClientsController{
  private $model;
  private $fields;
  private $url;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(17, $permissions)) {
      $title = "Configuración / Clientes";
      $fields = array("id","compañia","ciudad","contactos","acción");
      $url = '?c=Clients&a=Data';
      $new = '?c=Clients&a=New';
      $content = 'app/components/indexdt.php';
      $datatables = true;
      $jspreadsheet = true;
      $paginate = true;
      require_once 'app/views/index.php';
    } else {
      $this->model->redirect();
    }
  }

  public function New(){
    require_once "lib/check.php";
    if (in_array(17, $permissions)) {
      require_once 'app/views/clients/new.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Data(){
    header('Content-Type: application/json');
    require_once "lib/check.php";
    if (in_array(17, $permissions)) {
      $result[] = array();
      $i=0;
      foreach($this->model->list('*','clients') as $r) {
        $result[$i]['id'] = $r->id;
        $result[$i]['compañia'] = $r->company;
        $result[$i]['ciudad'] = $r->city;
        $contacts = "";
        foreach (json_decode($r->contacts) as $item) {
            $contacts .= $item[0] . " | " . $item[1] . " | " . $item[2] . "\n";
        }
        $result[$i]['contactos'] = nl2br($contacts);
        $edit = "<a hx-get='?c=Users&a=Profile&id=$r->id' hx-target='#myModal' @click='showModal = true' class='block text-teal-900 hover:text-teal-700 cursor-pointer float-right mx-3'><i class='ri-edit-2-line text-2xl'></i> Editar</a>";
        $result[$i]['acción'] = "$edit";
        $i++;
      }
      echo json_encode($result);
    } else {
      $this->init->redirect();
    }
  }

  public function Save(){
    require_once "lib/check.php";
    if (in_array(4, $permissions)) {
      header('Content-Type: application/json');
      $company = $_POST['company'];
      if ($this->model->get('id','clients',"and company = '$company'")) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "El cliente ya existe", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }
      $item = new stdClass();
      $table = 'clients';
      $item->company = $_REQUEST['company'];
      $item->city = $_REQUEST['city'];
      $item->contacts = $_REQUEST['data'];
      empty($_POST['id'])
      ? $id = $this->model->save($table,$item)
      : $id = $this->model->update($table,$item,$_POST['id']);
      if ($id !== false) {
        (empty($_POST['id'])) 
          ? $message = '{"type": "success", "message": "Cliente guardado", "close" : "closeModal"}'
          : $message = '{"type": "success", "message": "Cliente actualizado", "close" : "closeModal"}';
        $hxTriggerData = json_encode([
          "listChanged" => true,
          "showMessage" => $message
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(204);
      }
    } else {
      $this->model->redirect();
    }
  }

}