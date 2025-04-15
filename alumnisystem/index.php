<?php
session_start();
if (!isset($_SESSION['alumni_id'])) {
    header("Location: login.php");
    exit();
}

include "includes/db.php";

// Get all unique branches
$branches_query = "SELECT DISTINCT degree FROM alumni ORDER BY degree";
$branches_result = $conn->query($branches_query);

// Get selected branch from filter
$selected_branch = isset($_GET['branch']) ? $_GET['branch'] : 'all';

// Build the query based on selected branch
$query = "SELECT * FROM alumni";
if ($selected_branch !== 'all') {
    $query .= " WHERE degree = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selected_branch);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Alumni Directory</title>
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

        .branch-filter {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .branch-filter:hover {
            background: var(--primary-gradient);
            transform: translateY(-2px);
        }

        .branch-filter.active {
            background: var(--primary-gradient);
            transform: translateY(-2px);
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
            max-width: 400px;
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

        .delete-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 1rem;
        }

        .delete-btn::before {
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

        .delete-btn:hover::before {
            transform: translateX(100%);
        }

        .confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 1001;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .confirmation-modal.active {
            display: flex;
            opacity: 1;
        }

        .confirmation-content {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            margin: auto;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .confirmation-modal.active .confirmation-content {
            transform: translateY(0);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .cancel-btn {
            background: var(--glass-bg);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            flex: 1;
        }

        .cancel-btn:hover {
            background: rgba(255, 255, 255, 0.1);
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
                    <a href="signup.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">Add New Alumni</a>
                    <a href="logout.php" class="action-button text-white font-semibold px-4 py-2 rounded-md">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8 pt-24">
        <!-- Branch Filter Section -->
        <div class="glass-card rounded-2xl p-6 mb-8" data-aos="fade-up">
            <h2 class="text-2xl font-bold text-white mb-6">Filter by Branch</h2>
            <div class="flex flex-wrap gap-4">
                <a href="?branch=all" class="branch-filter px-4 py-2 rounded-lg <?php echo $selected_branch === 'all' ? 'active' : ''; ?>">
                    All Branches
                </a>
                <?php while($branch = $branches_result->fetch_assoc()): ?>
                    <a href="?branch=<?php echo urlencode($branch['degree']); ?>" 
                       class="branch-filter px-4 py-2 rounded-lg <?php echo $selected_branch === $branch['degree'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($branch['degree']); ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Alumni Grid Section -->
        <div class="glass-card rounded-2xl p-8" data-aos="fade-up">
            <h2 class="text-2xl font-bold text-white mb-6">Alumni Network</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="alumni-card rounded-xl p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="profile-avatar w-12 h-12 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold"><?php echo strtoupper(substr($row['full_name'], 0, 1)); ?></span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white"><?php echo htmlspecialchars($row['full_name']); ?></h3>
                                <p class="text-sm text-white/60"><?php echo htmlspecialchars($row['degree']); ?></p>
                            </div>
                        </div>
                        <button class="more-info-btn w-full mt-4" onclick="showAlumniInfo(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                            More Info
                        </button>
                    </div>
                <?php endwhile; ?>
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
            </div>
            <button id="deleteButton" class="delete-btn w-full mt-4 hidden" onclick="confirmDelete()">
                Delete Profile
            </button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <h3 class="text-xl font-bold text-white mb-4">Confirm Deletion</h3>
            <p class="text-white/60 mb-4">Are you sure you want to delete this alumni profile? This action cannot be undone.</p>
            <div class="action-buttons">
                <button class="cancel-btn" onclick="closeConfirmationModal()">Cancel</button>
                <button class="delete-btn" onclick="deleteProfile()">Delete</button>
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

        let currentAlumniId = null;
        const currentUserId = <?php echo $_SESSION['alumni_id']; ?>;

        function showAlumniInfo(alumni) {
            currentAlumniId = alumni.id;
            const modal = document.getElementById('alumniModal');
            const deleteButton = document.getElementById('deleteButton');
            
            // Show delete button only if it's the current user's profile
            if (alumni.id == currentUserId) {
                deleteButton.classList.remove('hidden');
            } else {
                deleteButton.classList.add('hidden');
            }

            document.getElementById('modalInitial').textContent = alumni.full_name.charAt(0).toUpperCase();
            document.getElementById('modalName').textContent = alumni.full_name;
            document.getElementById('modalDegree').textContent = alumni.degree;
            document.getElementById('modalEmail').textContent = alumni.email;
            document.getElementById('modalPhone').textContent = alumni.phone;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function confirmDelete() {
            const confirmationModal = document.getElementById('confirmationModal');
            confirmationModal.classList.add('active');
        }

        function closeConfirmationModal() {
            const confirmationModal = document.getElementById('confirmationModal');
            confirmationModal.classList.remove('active');
        }

        function deleteProfile() {
            if (!currentAlumniId) return;

            fetch('delete_alumni.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: currentAlumniId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error deleting profile: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the profile');
            });

            closeConfirmationModal();
            closeModal();
        }

        function closeModal() {
            const modal = document.getElementById('alumniModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modals when clicking outside
        document.getElementById('alumniModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmationModal();
            }
        });
    </script>
</body>
</html>