<!DOCTYPE html>
<html lang="en">
  <head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>SIPEC</title>
    <link rel="icon" sizes="192x192" href="app/assets/img/logo.png" />
    <script src="https://unpkg.com/htmx.org@1.9.6" integrity="sha384-FhXw7b6AlE/jyjlZH5iHa/tTe9EpJ1Y55RjcgPbjeWMskSxZt1v9qkxLJWNJaGni" crossorigin="anonymous"></script>
		<script src="https://cdn.tailwindcss.com"></script>
		<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
		<link href="app/assets/css/styles.css?v=1.0.0" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <?php if(isset($jspreadsheet)) { ?>
      <link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" />
      <script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js"></script>
      <script src="https://jsuites.net/v4/jsuites.js"></script>
      <link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />
    <?php } ?>
    <?php if(isset($datatables)) { ?>
      <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.dataTables.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/5.0.2/js/dataTables.fixedColumns.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/5.0.2/js/fixedColumns.dataTables.js"></script>

      


      <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.css">
    <?php } ?>
	</head>
  <body x-data='{ showModal: false, nestedModal: false, sidebar: false, showFilters : false }'>
		<?php require_once "app/components/sidebar.php" ?>
		<main class="w-full bg-gray-100 min-h-screen transition-all main active custombg">
			<?php require_once "app/components/navbar.php" ?>
      <?php require_once "app/components/filters.php" ?>
			<div id="content">
        <div id="loading" class="htmx-indicator pointer-events-none absolute z-[80] h-full w-full top-0 left-0 align-middle bg-gray-50">
            <div class="h-full w-full flex flex-col justify-center place-items-center my-auto">
              <div class="w-24 h-24 bg-no-repeat bg-center bg-[url('app/assets/img/loader.gif')] bg-contain opacity-90"></div>
            </div>
        </div>
        
				<?php require_once $content ?>
			</div>
		</main>
		<?php require_once 'app/components/modal.php' ?>
		<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
      const notyf = new Notyf({
        duration: 2000,
        position: {
          x: 'right',
          y: 'top',
        }
      });

      htmx.on("showMessage", (e) => {
        if(JSON.parse(e.detail.value).close != ""){
          let trigger = JSON.parse(e.detail.value).close;
          document.getElementById(trigger).click();
        };
        notyf.success(JSON.parse(e.detail.value));
      });

      htmx.on('listChanged', function(event) {
        table.ajax.reload(null, false);
      });
    </script>
  </body>
</html>