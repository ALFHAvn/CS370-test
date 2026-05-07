<?php
// ============================================================
// SocialNet - About Page
// ============================================================
//
// This page displays project information and student details.
// This page is accessible to everyone (no login required).
//
// URL: /socialnet/about.php
//
// Features:
// - Shows project overview
// - Lists all student names and IDs
// - Displays technology stack
// - Includes navigation bar
// - Professional styling
//
// ============================================================

// Start session (needed for navbar)
session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - SocialNet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2.5em;
        }

        h2 {
            color: #4CAF50;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        .section {
            margin: 20px 0;
            line-height: 1.6;
        }

        .student-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .student-card {
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .student-name {
            font-weight: bold;
            color: #333;
            font-size: 1.1em;
            margin-bottom: 5px;
        }

        .student-id {
            color: #666;
            font-size: 0.95em;
        }

        .tech-stack {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .tech-item {
            background-color: #f0f8ff;
            border: 1px solid #4CAF50;
            padding: 12px;
            border-radius: 4px;
            text-align: center;
            font-weight: 500;
        }

        .tech-item .label {
            color: #666;
            font-size: 0.85em;
            display: block;
            margin-bottom: 5px;
        }

        .tech-item .value {
            color: #4CAF50;
            font-weight: bold;
        }

        p {
            margin: 10px 0;
            text-align: justify;
        }

        .features {
            list-style-type: none;
            margin: 20px 0;
        }

        .features li {
            background-color: #f9f9f9;
            padding: 10px 15px;
            margin: 8px 0;
            border-radius: 4px;
            border-left: 3px solid #4CAF50;
        }

        .features li:before {
            content: "✓ ";
            color: #4CAF50;
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<?php
// ============================================================
// Include the navbar component
// ============================================================
// This provides consistent navigation across all pages
include 'navbar.php';
?>

<div class="container">

    <!-- Page Title -->
    <h1>About SocialNet</h1>

    <!-- Project Overview Section -->
    <div class="section">
        <h2>📱 Project Overview</h2>
        <p>
            <strong>SocialNet</strong> is a social networking web application built as a course assignment 
            for CS370. It demonstrates the implementation of a full-stack web application with user 
            authentication, profile management, and real-time user interactions.
        </p>
        <p>
            The application allows users to create accounts, log in securely, manage their profiles, 
            discover other users in the system, and share information through profile descriptions.
        </p>
    </div>

    <!-- Students Section -->
    <div class="section">
        <h2>👥 Development Team</h2>
        <div class="student-list">
            <div class="student-card">
                <div class="student-name">Nguyen Dinh Toan Thang</div>
                <div class="student-id">Student ID: 1694559</div>
            </div>
            <div class="student-card">
                <div class="student-name">Do Dang Khoa</div>
                <div class="student-id">Student ID: 1693636</div>
            </div>
            <div class="student-card">
                <div class="student-name">Dao Hai An</div>
                <div class="student-id">Student ID: 1696969</div>
            </div>
        </div>
    </div>

    <!-- Technology Stack Section -->
    <div class="section">
        <h2>🔧 Technology Stack</h2>
        <div class="tech-stack">
            <div class="tech-item">
                <span class="label">Backend</span>
                <span class="value">PHP 7.4+</span>
            </div>
            <div class="tech-item">
                <span class="label">Database</span>
                <span class="value">MySQL 5.7+</span>
            </div>
            <div class="tech-item">
                <span class="label">Web Server</span>
                <span class="value">Nginx</span>
            </div>
            <div class="tech-item">
                <span class="label">OS</span>
                <span class="value">Linux (Ubuntu)</span>
            </div>
            <div class="tech-item">
                <span class="label">Frontend</span>
                <span class="value">HTML5 & CSS3</span>
            </div>
            <div class="tech-item">
                <span class="label">Version Control</span>
                <span class="value">Git / GitHub</span>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="section">
        <h2>✨ Application Features</h2>
        <ul class="features">
            <li>Secure user registration and authentication</li>
            <li>Password hashing using bcrypt algorithm</li>
            <li>Session-based user management</li>
            <li>User profile creation and editing</li>
            <li>View other users' profiles</li>
            <li>Share profile descriptions and information</li>
            <li>SQL injection prevention with prepared statements</li>
            <li>XSS (Cross-Site Scripting) protection</li>
            <li>Responsive web interface</li>
            <li>Clean and intuitive navigation</li>
        </ul>
    </div>

    <!-- Course Information Section -->
    <div class="section">
        <h2>📚 Course Information</h2>
        <p>
            <strong>Course:</strong> CS370 - Web Application Development
        </p>
        <p>
            This project demonstrates fundamental concepts of web development including:
            backend programming, database design, user authentication, and secure coding practices.
        </p>
    </div>

    <!-- Footer -->
    <div class="section" style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #999; font-size: 0.9em;">
        <p>
            SocialNet © 2026 | CS370 Course Assignment | All Rights Reserved
        </p>
    </div>

</div>

<?php
// ============================================================
// Page Explanation
// ============================================================
//
// 1. Session Start
//    session_start();
//    - Initializes the session
//    - Needed for navbar to check login status
//
// 2. Responsive Design
//    - Uses CSS Grid for layout
//    - Adapts to different screen sizes
//    - Mobile-friendly
//
// 3. CSS Styling
//    - Professional color scheme (green theme)
//    - Clear visual hierarchy
//    - Good readability with proper spacing
//
// 4. Security
//    - No sensitive data displayed
//    - Safe for public access
//    - No database queries on this page
//
// 5. Accessibility
//    - Proper heading hierarchy (h1, h2)
//    - Semantic HTML
//    - Clear navigation
//
// ============================================================
?>

</body>
</html>
