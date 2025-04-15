<?php include "includes/db.php"; session_start(); ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password_input = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM alumni WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $alumni = $stmt->get_result()->fetch_assoc();

    if ($alumni && password_verify($password_input, $alumni['password'])) {
        $_SESSION['alumni_id'] = $alumni['id'];
        $_SESSION['alumni_name'] = $alumni['full_name'];
        header("Location: welcome.php");
        exit();
    } else {
        $error_message = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Alumni System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1, #8b5cf6);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #f8fafc;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .gradient-text {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .input-group {
            position: relative;
            transition: all 0.3s ease;
        }

        .input-group:hover .input-label {
            transform: translateY(-100%) scale(0.9);
            color: #8b5cf6;
        }

        .input-group:focus-within .input-label {
            transform: translateY(-100%) scale(0.9);
            color: #8b5cf6;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
        }

        .action-button {
            background: var(--primary-gradient);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1));
            transition: transform 0.3s ease;
        }

        .action-button:hover::before {
            transform: translateX(100%);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .nav-link {
            position: relative;
            overflow: hidden;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-gradient);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #ef4444;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Animated Background -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/20 to-purple-900/20"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAzNGM0LjQxOCAwIDgtMy41ODIgOC04cy0zLjU4Mi04LTgtOC04IDMuNTgyLTggOCAzLjU4MiA4IDggOHoiIHN0cm9rZT0iIzRjMWQ3NSIgc3Ryb2tlLW9wYWNpdHk9Ii4xIi8+PC9nPjwvc3ZnPg==')] opacity-20"></div>
    </div>

    <!-- Navigation -->
    <nav class="glass-card fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="welcome.php" class="nav-link text-white hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">Home</a>
                    <a href="index.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">View All Alumni</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="signup.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8" data-aos="fade-up">
            <div class="glass-card rounded-2xl p-8 animate-float">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold gradient-text">Welcome Back</h2>
                    <p class="mt-2 text-white/60">Sign in to your account</p>
                </div>
                <?php if (isset($error_message)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                <form class="mt-8 space-y-6" method="POST">
                    <div class="rounded-md space-y-4">
                        <div class="input-group">
                            <label class="input-label absolute left-3 top-1/2 -translate-y-1/2 text-white/60 transition-all duration-300">Email</label>
                            <input type="email" name="email" required class="input-field w-full px-3 py-2 rounded-lg text-white" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div class="input-group">
                            <label class="input-label absolute left-3 top-1/2 -translate-y-1/2 text-white/60 transition-all duration-300">Password</label>
                            <input type="password" name="password" required class="input-field w-full px-3 py-2 rounded-lg text-white">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-white/60">
                                Remember me
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-400 hover:text-indigo-300">
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="action-button w-full flex justify-center py-2 px-4 rounded-lg text-white font-semibold">
                            Sign In
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Add input field focus effects
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('ring-2', 'ring-indigo-500');
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('ring-2', 'ring-indigo-500');
            });
        });
    </script>
</body>
</html>