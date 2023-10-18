<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Componenti</title>
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded shadow-md max-w-md w-full">
        <img src="app/assets/img/logo.png" class="mb-4 mx-auto w-60"></h2>
        <form name="login_form">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-medium">Email</label>
                <input type="text" name="email" class="mt-1 p-2 w-full border border-gray-300 rounded focus:ring focus:ring-blue-200" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-medium">Password</label>
                <input type="password" name="pass" class="mt-1 p-2 w-full border border-gray-300 rounded focus:ring focus:ring-blue-200" required>
            </div>
            <div class="text-center">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-200">Login</button>
            </div>
        </form>
    </div>
</body>
</html>

<script>
document.addEventListener('submit', async (e) => {
    e.preventDefault();
    const loginForm = e.target;
    if (loginForm.checkValidity()) {
        try {
            const formData = new FormData(loginForm);
            const response = await fetch("?c=Home&a=Index", {
                method: "POST",
                body: formData,
            });
            if (response.ok) {
                const data = await response.text();
                if (data.trim() !== 'ok') {
                    toastr.error('Error');
                    console.log('Error');
                } else {
                    window.location.href = '/componenti';
                }
            }
        } catch (error) {
            console.error("failed");
        }
    }
});
</script>
