<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Software App</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">Software App</h2>
            <p class="text-center">Login Form</p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 border border-gray-200 shadow sm:rounded-lg sm:px-10">
                <form id="login-form" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" value="admin@example.com" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" value="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Log in
                        </button>
                    </div>
                </form>

                <div class="mt-12 mb-4 text-center">
                    <button id="sso-login-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Login with Website App
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="sso-popup" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <h3 class="text-lg font-medium mb-4">Select Email to Login</h3>
            <div id="email-options" class="space-y-2 mb-4">
                <!-- Email options will be populated here -->
                <div class="flex items-center p-2 hover:bg-gray-100 rounded cursor-pointer email-option"
                    data-email="admin@example.com">
                    <span class="ml-2">admin@example.com</span>
                </div>
            </div>
            <div class="flex justify-end space-x-2">
                <button id="cancel-sso" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Cancel</button>
                <button id="confirm-sso"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Continue</button>
            </div>
        </div>
    </div>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedEmail = null;

            // Handle email selection
            $(document).on('click', '.email-option', function() {
                $('.email-option').removeClass('bg-blue-300 hover:bg-gray-100');
                $(this).addClass('bg-blue-300');
                selectedEmail = $(this).data('email');
            });

            // Cancel SSO
            $('#cancel-sso').click(function() {
                $('#sso-popup').addClass('hidden');
                $('#sso-login-btn').prop('disabled', false).text('Login with Website App');
            });

            // Confirm SSO with selected email
            $('#confirm-sso').click(async function() {
                if (!selectedEmail) {
                    alert('Please select an email');
                    return;
                }

                $('#sso-popup').addClass('hidden');
                await initiateSSO(selectedEmail);
            });

            $('#sso-login-btn').click(async function() {
                const token = localStorage.getItem('access_token');
                const $btn = $(this);
                $btn.prop('disabled', true).text('Redirecting...');

                if (!token) {
                    // Show popup to select email
                    $('#sso-popup').removeClass('hidden');
                    return;
                }

                await initiateSSO();
            });

            async function initiateSSO(email = null) {
                const $btn = $('#sso-login-btn');

                // Step 1: Initiate SSO from website-app
                const response = await fetch(`http://website-app.test/sso-initiate/${email}`, {
                    credentials: 'include'
                });

                if (!response.ok) {
                    toastr.error("SSO Initiate Failed");
                } else {
                    const {
                        token
                    } = await response.json();
                    const res = await fetch("http://website-app.test/api/user", {
                        headers: {
                            "Authorization": "Bearer " + token,
                        }
                    });
                    if (res.ok) {
                        toastr.success('Login successfully.');
                        localStorage.setItem('access_token', token);
                        setTimeout(function() {
                            window.location.href = '/dashboard';
                        }, 1500);
                    } else {
                        toastr.error("Login Failed.");
                        $('#sso-login-btn').prop('disabled', false).text('Login with Website App');
                    }
                }
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Check if token exists and is valid
            const token = localStorage.getItem('access_token');
            if (token) {
                verifyToken(token);
            }

            $(document).on('submit', '#login-form', async function(e) {
                e.preventDefault();
                const email = $('#email').val();
                const password = $('#password').val();

                const response = await fetch("/api/login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        email,
                        password
                    })
                });

                const data = await response.json();
                if (response.ok) {
                    localStorage.setItem('access_token', data.token);

                    const res = await fetch("/api/user", {
                        headers: {
                            "Authorization": "Bearer " + data.token,
                        }
                    });
                    if (res.ok) {
                        toastr.success('Login successfully.');

                        setTimeout(function() {
                            window.location.href = '/dashboard';
                        }, 1500);
                    }
                } else {
                    alert(data.message || "Login failed");
                }
            });
        });

        async function verifyToken(token) {
            const response = await fetch("/api/user", {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });
            if (response.ok) {
                window.location.href = '/dashboard';
            }
        }
    </script>

</body>

</html>
