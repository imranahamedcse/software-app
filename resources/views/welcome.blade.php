<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software App</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <header class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Software App</h1>
            <button id="login-btn"
                class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded hover:bg-indigo-50 transition">Login</button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center border border-gray-200 rounded-lg p-8 bg-white">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome to Software App</h2>
            <p class="text-gray-600">Please login to access your dashboard</p>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('#login-btn').click(function() {
                window.location.href = '/login';
            });
        });
    </script>
</body>

</html>
