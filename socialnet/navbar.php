<?php
// ============================================================
// SocialNet - Smart Navigation Bar Component
// ============================================================
//
// This navbar is included in multiple pages to provide
// consistent navigation throughout the application.
//
// Features:
// - Shows different content based on login status
// - Displays username if logged in
// - Provides links to main pages
// - Styled for professional appearance
//
// Include this file in your page:
// <?php include 'navbar.php'; ?>
//
// ============================================================

// Safety check: Make sure session is started
if (!isset($_SESSION)) {
    session_start();
}

?>

<!-- Navbar Styling -->
<style>
    .navbar {
        background-color: #2c3e50;
        padding: 15px 20px;
        margin-bottom: 20px;
        border-bottom: 3px solid #4CAF50;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .navbar-content {
        max-width: 800px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .navbar-title {
        color: #4CAF50;
        font-weight: bold;
        font-size: 1.2em;
    }

    .navbar-links {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
    }

    .navbar a {
        color: #ecf0f1;
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .navbar a:hover {
        background-color: #4CAF50;
        color: white;
    }

    .navbar-username {
        color: #4CAF50;
        font-weight: bold;
        padding: 8px 12px;
    }

    .navbar-status {
        color: #95a5a6;
        font-size: 0.9em;
    }

    @media (max-width: 600px) {
        .navbar-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .navbar-links {
            width: 100%;
        }
    }
</style>

<!-- Navbar HTML -->
<nav class="navbar">
    <div class="navbar-content">
        <!-- Logo/Title -->
        <div class="navbar-title">🔗 SocialNet</div>

        <!-- Navigation Links -->
        <div class="navbar-links">

            <?php
            // ============================================================
            // Check if user is logged in
            // ============================================================
            // If $_SESSION['username'] is set, user is logged in
            if (isset($_SESSION['username'])) {
                // ============================================================
                // User is logged in - show logged-in menu
                // ============================================================
                ?>

                <!-- Welcome message with username -->
                <span class="navbar-username">
                    👤 <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>

                <!-- Navigation links for logged-in users -->
                <a href="index.php">🏠 Home</a>
                <a href="profile.php">👥 Profile</a>
                <a href="setting.php">⚙️ Settings</a>
                <a href="about.php">ℹ️ About</a>
                <a href="signout.php" style="background-color: #e74c3c; color: white;">🚪 Sign Out</a>

                <?php
            } else {
                // ============================================================
                // User is NOT logged in - show guest menu
                // ============================================================
                ?>

                <!-- Status for non-logged-in users -->
                <span class="navbar-status">Not logged in</span>

                <!-- Navigation links for guests -->
                <a href="about.php">ℹ️ About</a>
                <a href="signin.php" style="background-color: #4CAF50; color: white;">🔓 Sign In</a>

                <?php
            }
            ?>

        </div>
    </div>
</nav>

<?php
// ============================================================
// Navbar Explanation
// ============================================================
//
// 1. Session Check
//    - if (isset($_SESSION['username']))
//    - Determines if user is logged in
//    - Sets username in session during login
//
// 2. Conditional Display
//    - Logged-in users see: Home, Profile, Settings, About, Sign Out
//    - Guests see: About, Sign In
//
// 3. Security - htmlspecialchars()
//    - Escapes special HTML characters
//    - Prevents XSS (Cross-Site Scripting) attacks
//    - Safe to display user-supplied data
//
// 4. Styling
//    - Responsive design (works on mobile)
//    - Professional color scheme
//    - Smooth transitions and hover effects
//    - Flexbox for flexible layout
//
// 5. Reusability
//    - Include on any page: <?php include 'navbar.php'; ?>
//    - Works without modification
//    - Session data automatically available
//
// ============================================================
?>
