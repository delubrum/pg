<!DOCTYPE html>
<html lang="en">
  <head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Componenti</title>
		<script src="https://cdn.tailwindcss.com"></script>
		<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
		<link href="app/assets/css/styles.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
	</head>
  <body x-data='{ showModal: false, sidebar: false }'>
		<div id="loading"></div>
		<?php require_once "sidebar.php" ?>
		<main class="w-full bg-gray-100 min-h-screen transition-all main active" style="background-image: url('app/assets/img/bubbles.png');background-repeat: no-repeat;background-size:450px;">
			<?php require_once "navbar.php" ?>
			<div id="content">
				<?php require_once "app/components/page.php" ?>
			</div>
		</main>
		<?php require_once 'app/components/modal.php' ?>
		<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
		<script src="app/assets/js/script.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
		<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
		<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
		<script src="https://unpkg.com/htmx.org@1.9.6" integrity="sha384-FhXw7b6AlE/jyjlZH5iHa/tTe9EpJ1Y55RjcgPbjeWMskSxZt1v9qkxLJWNJaGni" crossorigin="anonymous"></script>
  </body>
</html>