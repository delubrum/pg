<?php
require_once 'app/models/model.php';

class ClientsController{
  private $model;
  public function __CONSTRUCT(){
    $this->model = new Model();
  }

  public function Index(){
    require_once "lib/check.php";
    if (in_array(17, $permissions)) {
      $title = "Configuración / Clientes";
      $fields = array("compañia","ciudad","tambores","contactos","productos","turboExclusivo","turboRecorrido","camionetaExclusivo","camionetaRecorrido","acción");
      $url = '?c=Clients&a=Data';
      $new = '?c=Clients&a=New';
      $content = 'app/components/indexdt.php';
      $datatables = true;
      $jspreadsheet = true;
      require_once 'app/views/index.php';
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
        $result[$i]['compañia'] = $r->company;
        $result[$i]['ciudad'] = $r->city;
        $result[$i]['tambores'] = $r->drums;
        $contacts = '<table class="min-w-full divide-y divide-gray-200 border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-2 text-left text-xs text-gray-500 tracking-wider border border-gray-300">Nombre</th>
                <th class="px-2 text-left text-xs text-gray-500 tracking-wider border border-gray-300">Email</th>
                <th class="px-2 text-left text-xs text-gray-500 tracking-wider border border-gray-300">Teléfono</th>
                <th class="px-2 text-left text-xs text-gray-500 tracking-wider border border-gray-300">Área</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">';
    
    foreach (json_decode($r->contacts) as $c) {
        $contacts .= '<tr>
            <td class="px-2 text-xs whitespace-nowrap border border-gray-300">' . htmlspecialchars($c[0]) . '</td>
            <td class="px-2 text-xs whitespace-nowrap border border-gray-300">' . htmlspecialchars($c[1]) . '</td>
            <td class="px-2 text-xs whitespace-nowrap border border-gray-300">' . htmlspecialchars($c[2]) . '</td>
            <td class="px-2 text-xs whitespace-nowrap border border-gray-300">' . htmlspecialchars($c[3]) . '</td>
        </tr>';
    }
    
    $contacts .= '</tbody></table>';
    
        $result[$i]['contactos'] = $contacts;
        $products = '';
        foreach (json_decode($r->products) as $p) {
          $products .=  "• " . $this->model->get('name','products'," and id = $p")->name . "<br>";
        }

        $result[$i]['productos'] = $products;

        $result[$i]['turboExclusivo'] = $r->price1;
        $result[$i]['turboRecorrido'] = $r->price2;
        $result[$i]['camionetaExclusivo'] = $r->price3;
        $result[$i]['camionetaRecorrido'] = $r->price4;

        $edit = "<a hx-get='?c=Clients&a=New&id=$r->id' hx-target='#myModal' @click='showModal = true' class='block text-teal-900 hover:text-teal-700 cursor-pointer float-right mx-3'><i class='ri-edit-2-line '></i> Editar</a>";
        $result[$i]['acción'] = "$edit";
        $i++;
      }
      echo json_encode($result);
    } else {
      $this->model->redirect();
    }
  }

  public function New(){
    require_once "lib/check.php";
    if (in_array(17, $permissions)) {
      if (!empty($_REQUEST['id'])) {
        $filters = "and id = " . $_REQUEST['id'];
        $id = $this->model->get('*','clients', $filters);
      }
      require_once 'app/views/clients/new.php';
    } else {
      $this->model->redirect();
    }
  }

  public function Save(){
    require_once "lib/check.php";
    if (in_array(4, $permissions)) {
      header('Content-Type: application/json');
      $company = $_POST['company'];
      if (empty($_POST['id']) and $this->model->get('id','clients',"and company = '$company'")) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "El cliente ya existe", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }
      if ($_POST['id']) {
        $id = $_POST['id'];
        if ($this->model->get('id','clients',"and company = '$company' and id <> $id")) {
        $hxTriggerData = json_encode([
          "showMessage" => '{"type": "error", "message": "Ya existe un cliente con el nombre que intentas actualizar", "close" : ""}'
        ]);
        header('HX-Trigger: ' . $hxTriggerData);
        http_response_code(409);
        exit;
      }
      }
      $item = new stdClass();
      $table = 'clients';
      $item->company = $_REQUEST['company'];
      $item->city = $_REQUEST['city'];
      $item->drums = $_REQUEST['drums'];
      $item->contacts = $_REQUEST['contacts'];
      $item->price1 = $_REQUEST['price1'];
      $item->price2 = $_REQUEST['price2'];
      $item->price3 = $_REQUEST['price3'];
      $item->price4 = $_REQUEST['price4'];
      $item->products = json_encode($_REQUEST['products']);
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