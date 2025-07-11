<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Software App</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <header class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Software App</h1>
            <button id="logout-btn"
                class="px-4 py-2 border border-red-600 text-red-600 rounded hover:bg-red-50 transition">Logout</button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="border border-gray-200 rounded-lg p-8 bg-white">
            <h2 class="text-3xl font-bold text-gray-800 text-center">Dashboard</h2>
        </div>
    </main>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Check if token exists and is valid
            const token = localStorage.getItem('access_token');
            if (token) {
                verifyToken(token);
            } else {
                window.location.href = '/login';
            }

            $(document).on('click', '#logout-btn', async function() {
                const res = await fetch("/api/logout", {
                    method: "POST",
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    }
                });
                if (res.ok) {
                    localStorage.removeItem('access_token');
                    toastr.success('Logout successfully.');

                    setTimeout(function() {
                        window.location.href = '/login';
                    }, 1500);
                } else if (res.status == 401) {
                    localStorage.removeItem('access_token');
                    window.location.href = '/login';
                }
            });
        });

        async function verifyToken(token) {
            const response = await fetch("/api/user", {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });
            if (!response.ok) {
                window.location.href = '/login';
            }
        }
    </script>
</body>

</html>
