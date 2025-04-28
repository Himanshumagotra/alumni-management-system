<?php
session_start();
if (!isset($_SESSION['alumni_id'])) {
    header("Location: login.php");
    exit();
}

include "includes/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Alumni Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-black to-indigo-900 min-h-screen text-white">
    <!-- Header Navigation -->
    <header class="glass-card sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="welcome.php" class="text-xl font-bold text-white">Alumni Portal</a>
                </div>
                
                <div class="hidden md:block">
                    <div class="flex items-center space-x-4">
                        <a href="welcome.php" class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md">Home</a>
                        <a href="welcome.php" class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md">Events</a>
                        <a href="faq.php" class="bg-indigo-700 text-white px-3 py-2 rounded-md">FAQ</a>
                        
                        <a href="logout.php" class="text-white hover:bg-red-600 px-3 py-2 rounded-md">Logout</a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-white hover:bg-indigo-700 px-2 py-1 rounded-md" 
                            onclick="toggleMobileMenu()">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden hidden" id="mobileMenu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="welcome.php" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md">Home</a>
                    <a href="events.php" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md">Events</a>
                    <a href="faq.php" class="bg-indigo-700 text-white block px-3 py-2 rounded-md">FAQ</a>
                    <a href="profile.php" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md">Profile</a>
                    <a href="logout.php" class="text-white hover:bg-red-600 block px-3 py-2 rounded-md">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="max-w-5xl mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">
                Frequently Asked Questions
            </h1>
            <p class="text-indigo-200">
                Have a question? Find answers or ask the community
            </p>
        </div>

        <!-- Ask Question Section -->
        <div class="mb-12">
            <div class="glass-card rounded-xl p-6 shadow-xl">
                <h2 class="text-xl font-semibold mb-4 text-indigo-300">Ask a Question</h2>
                <form id="questionForm" class="space-y-4">
                    <div>
                        <textarea 
                            id="questionInput"
                            class="w-full bg-black/30 border border-indigo-500/30 rounded-xl p-4 text-white placeholder-indigo-300/50 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 resize-none"
                            rows="3"
                            placeholder="What would you like to know?"
                            required
                        ></textarea>
                    </div>
                    <button 
                        type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-xl transition duration-200 transform hover:scale-105"
                    >
                        Submit Question
                    </button>
                </form>
            </div>
        </div>

        <!-- FAQ List Section -->
        <div id="faqList" class="space-y-6">
            <!-- Questions will be added here dynamically -->
        </div>
    </div>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }

        let questions = [
            {
                id: 1,
                question: "What is the alumni association?",
                answer: "The alumni association is a network of former students who maintain connections with their alma mater.",
                status: "answered",
                timestamp: new Date()
            },
            {
                id: 2,
                question: "How can I update my contact information?",
                answer: "You can update your contact information through your profile settings.",
                status: "answered",
                timestamp: new Date()
            }
        ];

        function createQuestionCard(question) {
            const card = document.createElement('div');
            card.className = `fade-in glass-card rounded-xl p-6 ${
                question.status === 'pending' ? 'border-yellow-500/30' : 'border-indigo-500/30'
            } shadow-xl transition duration-300 hover:bg-white/5`;
            
            card.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium ${
                        question.status === 'pending' 
                            ? 'bg-yellow-500/20 text-yellow-300' 
                            : 'bg-indigo-500/20 text-indigo-300'
                    }">
                        ${question.status.charAt(0).toUpperCase() + question.status.slice(1)}
                    </span>
                    <span class="text-sm text-indigo-200/60">
                        ${question.timestamp.toLocaleString()}
                    </span>
                </div>
                <h3 class="text-lg font-medium text-white mb-3">
                    ${question.question}
                </h3>
                ${question.answer ? `
                    <div class="bg-black/30 rounded-xl p-4 mt-4">
                        <p class="text-indigo-100">${question.answer}</p>
                    </div>
                ` : ''}
                ${question.status === 'pending' ? `
                    <div class="mt-4 space-y-3">
                        <button 
                            onclick="showAnswerForm(${question.id})"
                            class="text-indigo-400 hover:text-indigo-300 transition duration-200"
                        >
                            Provide Answer
                        </button>
                        <div id="answerForm-${question.id}" class="hidden space-y-3">
                            <textarea 
                                class="w-full bg-black/30 border border-indigo-500/30 rounded-xl p-4 text-white placeholder-indigo-300/50 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 resize-none"
                                rows="2"
                                placeholder="Type your answer..."
                            ></textarea>
                            <button 
                                onclick="submitAnswer(${question.id})"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-xl transition duration-200 transform hover:scale-105"
                            >
                                Submit Answer
                            </button>
                        </div>
                    </div>
                ` : ''}
            `;
            return card;
        }

        function renderQuestions() {
            const faqList = document.getElementById('faqList');
            faqList.innerHTML = '';
            questions
                .sort((a, b) => b.timestamp - a.timestamp)
                .forEach(question => {
                    faqList.appendChild(createQuestionCard(question));
                });
        }

        function showAnswerForm(questionId) {
            const form = document.getElementById(`answerForm-${questionId}`);
            form.classList.toggle('hidden');
        }

        function submitAnswer(questionId) {
            const form = document.getElementById(`answerForm-${questionId}`);
            const answer = form.querySelector('textarea').value;
            
            if (answer.trim()) {
                const questionIndex = questions.findIndex(q => q.id === questionId);
                if (questionIndex !== -1) {
                    questions[questionIndex].answer = answer;
                    questions[questionIndex].status = 'answered';
                    renderQuestions();
                }
            }
        }

        document.getElementById('questionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const questionInput = document.getElementById('questionInput');
            const questionText = questionInput.value.trim();
            
            if (questionText) {
                const newQuestion = {
                    id: questions.length + 1,
                    question: questionText,
                    status: 'pending',
                    timestamp: new Date()
                };
                
                questions.push(newQuestion);
                questionInput.value = '';
                renderQuestions();
            }
        });

        renderQuestions();
    </script>
</body>
</html> 