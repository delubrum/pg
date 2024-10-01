<div> Total Plasticos Prestados: <span class="text-xl text-teal-900 px-2 font-bold"><?php echo $prestados = ceil($this->model->get('sum(mpClient) as total','rm',$filters)->total/170) ?></span> </div>
<div> Total Plasticos Devueltos Por Clientes: <span class="text-xl text-teal-900 px-2 font-bold"><?php echo $devueltos = $this->model->get('sum(drumsReturned) as total','rm',$filters)->total ?></span> </div>
<br>
<div class="text-xl text-teal-900 font-bold"> Total Plasticos en Inventario: <span class="text-2xl px-4"><?php echo $prestados-$devueltos ?> </span> </div>
<br>
<div> Total Metalicos En inventario: <span class="text-xl text-teal-900 px-4 py-2 font-bold"><?php echo $this->model->get('sum(drums) as total','rm'," $filters and returnToClient = 0")->total ?></span> </div>
<div> Total Cuñetes En inventario: <span class="text-xl text-teal-900 px-4 py-2 font-bold"><?php echo $this->model->get('sum(barrels) as total','rm'," $filters and returnToClient = 0")->total ?></span> </div>