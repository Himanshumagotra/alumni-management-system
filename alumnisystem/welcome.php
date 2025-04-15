<?php 
session_start(); 
if (!isset($_SESSION['alumni_id'])) { 
    header("Location: login.php"); 
    exit(); 
} 

include "includes/db.php";

// Fetch logged-in alumni details
$stmt = $conn->prepare("SELECT * FROM alumni WHERE id = ?");
$stmt->bind_param("i", $_SESSION['alumni_id']);
$stmt->execute();
$result = $stmt->get_result();
$alumni = $result->fetch_assoc();

// Fetch all alumni for the table
$all_alumni_query = "SELECT * FROM alumni ORDER BY full_name ASC";
$all_alumni_result = $conn->query($all_alumni_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome</title>
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

        .alumni-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
        }

        .alumni-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border-color: rgba(99, 102, 241, 0.5);
        }

        .profile-avatar {
            background: var(--primary-gradient);
            transition: transform 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.1) rotate(5deg);
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

        .glass-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
        }

        .stats-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.5);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            margin: auto;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .more-info-btn {
            background: var(--primary-gradient);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .more-info-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1));
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .more-info-btn:hover::before {
            transform: translateX(100%);
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .close-modal:hover {
            opacity: 1;
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
                    <a href="faq.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">FAQ</a>
                    <a href="privacy.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">Privacy Policy</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="signup.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">Add New Alumni</a>
                    <button onclick="showDeleteModal()" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-md transition-colors">
                        Delete Account
                    </button>
                    <a href="logout.php" class="action-button text-white font-semibold px-4 py-2 rounded-md">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Delete Account Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content relative max-w-md">
            <button class="close-modal" onclick="closeDeleteModal()">&times;</button>
            <div class="text-center p-6">
                <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-2xl font-bold text-white mb-4">Delete Account</h3>
                <p class="text-white/60 mb-6">
                    Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.
                </p>
                <div class="flex justify-center space-x-4">
                    <button onclick="closeDeleteModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <form action="delete_account.php" method="POST" class="inline">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                            Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8 pt-24">
        <!-- Website Title -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 gradient-text">
                Alumni Management System
            </h1>
            <p class="text-xl text-white/60">
                Connecting graduates, fostering relationships, and creating opportunities
            </p>
        </div>

        <!-- Welcome Section -->
        <div class="glass-card rounded-2xl p-8 mb-8 animate-float" data-aos="fade-up">
            <div class="flex items-center space-x-6">
                <div class="profile-avatar w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold text-white">
                    <?php echo strtoupper(substr($alumni['full_name'], 0, 1)); ?>
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold gradient-text">Welcome, <?php echo htmlspecialchars($alumni['full_name']); ?>!</h1>
                    <p class="text-white/60 mt-2">Last login: <?php echo date('F j, Y'); ?></p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-white/60 text-sm font-semibold">Total Alumni</h3>
                <p class="text-3xl font-bold text-white mt-2"><?php echo $all_alumni_result->num_rows; ?></p>
            </div>
            <div class="stats-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-white/60 text-sm font-semibold">Your Batch</h3>
                <p class="text-3xl font-bold text-white mt-2"><?php echo htmlspecialchars($alumni['graduation_year']); ?></p>
            </div>
            <div class="stats-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-white/60 text-sm font-semibold">Your Degree</h3>
                <p class="text-3xl font-bold text-white mt-2"><?php echo htmlspecialchars($alumni['degree']); ?></p>
            </div>
        </div>

        <!-- Profile and Actions Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glass-card rounded-xl p-6" data-aos="fade-right">
                <h2 class="text-xl font-semibold text-white mb-4">Your Profile</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 rounded-lg hover:bg-white/5 transition duration-300">
                        <span class="text-white/60">Email</span>
                        <span class="text-white"><?php echo htmlspecialchars($alumni['email']); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-lg hover:bg-white/5 transition duration-300">
                        <span class="text-white/60">Phone</span>
                        <span class="text-white"><?php echo htmlspecialchars($alumni['phone']); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-lg hover:bg-white/5 transition duration-300">
                        <span class="text-white/60">Occupation</span>
                        <span class="text-white"><?php echo htmlspecialchars($alumni['occupation']); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-lg hover:bg-white/5 transition duration-300">
                        <span class="text-white/60">Company</span>
                        <span class="text-white"><?php echo htmlspecialchars($alumni['company']); ?></span>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-xl p-6" data-aos="fade-left">
                <h2 class="text-xl font-semibold text-white mb-4">Quick Actions</h2>
                <div class="space-y-4">
                    <a href="edit_profile.php" class="action-button block w-full text-center text-white font-semibold py-3 px-4 rounded-lg relative group">
                        <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        <span class="relative z-10 flex items-center justify-center">
                            Edit Profile
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </span>
                    </a>
                    <a href="index.php" class="action-button block w-full text-center text-white font-semibold py-3 px-4 rounded-lg relative group">
                        <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        <span class="relative z-10 flex items-center justify-center">
                            View All Alumni
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </span>
                    </a>
                    <a href="signup.php" class="action-button block w-full text-center text-white font-semibold py-3 px-4 rounded-lg relative group">
                        <span class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        <span class="relative z-10 flex items-center justify-center">
                            Add New Alumni
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Alumni Network Section -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <h2 class="text-3xl font-bold text-white mb-8">Alumni Network</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php while($alumni = $all_alumni_result->fetch_assoc()): ?>
                <div class="alumni-card rounded-xl p-6" data-aos="fade-up">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="profile-avatar w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            <?php echo strtoupper(substr($alumni['full_name'], 0, 1)); ?>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($alumni['full_name']); ?></h3>
                            <p class="text-indigo-300"><?php echo htmlspecialchars($alumni['degree']); ?></p>
                        </div>
                    </div>
                    <button onclick="showAlumniInfo(<?php echo htmlspecialchars(json_encode($alumni)); ?>)" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        View Profile
                    </button>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Upcoming Events Section -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <h2 class="text-3xl font-bold text-white mb-8">Upcoming Events</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Event 1 -->
                <div class="event-card glass-card rounded-xl p-6" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-indigo-500/20 text-indigo-300 rounded-full text-sm">Networking</span>
                        <span class="text-white/60">May 15, 2025</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Annual Alumni Meet</h3>
                    <p class="text-white/60 mb-4">Join us for our annual alumni gathering. Network with fellow graduates, share experiences, and reconnect with old friends.</p>
                    <div class="flex items-center text-white/60 mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        University Auditorium
                    </div>
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        Register Now
                    </button>
                </div>

                <!-- Event 2 -->
                <div class="event-card glass-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm">Workshop</span>
                        <span class="text-white/60">June 5, 2025</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Career Development Workshop</h3>
                    <p class="text-white/60 mb-4">Enhance your professional skills with our career development workshop. Learn from industry experts and alumni mentors.</p>
                    <div class="flex items-center text-white/60 mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Business School Conference Room
                    </div>
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        Register Now
                    </button>
                </div>

                <!-- Event 3 -->
                <div class="event-card glass-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-pink-500/20 text-pink-300 rounded-full text-sm">Social</span>
                        <span class="text-white/60">July 20, 2025</span>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Summer Networking Mixer</h3>
                    <p class="text-white/60 mb-4">Join us for an evening of networking and socializing. Meet fellow alumni in a relaxed atmosphere with refreshments.</p>
                    <div class="flex items-center text-white/60 mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Alumni Garden
                    </div>
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        Register Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Template -->
    <div class="modal" id="alumniModal">
        <div class="modal-content relative">
            <button class="close-modal" onclick="closeModal()">&times;</button>
            <div class="flex items-center space-x-4 mb-6">
                <div class="profile-avatar w-16 h-16 rounded-full flex items-center justify-center text-2xl">
                    <span id="modalInitial" class="text-white font-bold"></span>
                </div>
                <div>
                    <h3 id="modalName" class="text-2xl font-bold text-white"></h3>
                    <p id="modalDegree" class="text-white/60"></p>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 rounded-lg bg-white/5">
                    <span class="text-white/60">Email</span>
                    <span id="modalEmail" class="text-white"></span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg bg-white/5">
                    <span class="text-white/60">Phone</span>
                    <span id="modalPhone" class="text-white"></span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg bg-white/5">
                    <span class="text-white/60">Graduation Year</span>
                    <span id="modalGraduation" class="text-white"></span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg bg-white/5">
                    <span class="text-white/60">Occupation</span>
                    <span id="modalOccupation" class="text-white"></span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg bg-white/5">
                    <span class="text-white/60">Company</span>
                    <span id="modalCompany" class="text-white"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900/80 backdrop-blur-md text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About Us -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">About Us</h3>
                    <p class="text-gray-400">
                        The Alumni System is a platform designed to connect graduates, foster professional relationships, and create opportunities for networking and collaboration within our alumni community.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="welcome.php" class="text-gray-400 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="index.php" class="text-gray-400 hover:text-white transition-colors">
                                View All Alumni
                            </a>
                        </li>
                        <li>
                            <a href="faq.php" class="text-gray-400 hover:text-white transition-colors">
                                FAQ
                            </a>
                        </li>
                        <li>
                            <a href="privacy.php" class="text-gray-400 hover:text-white transition-colors">
                                Privacy Policy
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            alumni@example.com
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +1 (555) 123-4567
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            123 University Ave, City, State
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> Alumni System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        function showAlumniInfo(alumni) {
            const modal = document.getElementById('alumniModal');
            document.getElementById('modalInitial').textContent = alumni.full_name.charAt(0).toUpperCase();
            document.getElementById('modalName').textContent = alumni.full_name;
            document.getElementById('modalDegree').textContent = alumni.degree;
            document.getElementById('modalEmail').textContent = alumni.email;
            document.getElementById('modalPhone').textContent = alumni.phone;
            document.getElementById('modalGraduation').textContent = alumni.graduation_year;
            document.getElementById('modalOccupation').textContent = alumni.occupation;
            document.getElementById('modalCompany').textContent = alumni.company;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('alumniModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.getElementById('alumniModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        function showDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close delete modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>