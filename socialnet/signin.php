<?php
// ============================================================
// SocialNet - Sign In Page
// ============================================================
//
// URL: /socialnet/signin.php
//
// Purpose:
// - Display login form
// - Authenticate users against database
// - Redirect to home page on successful login
// - Show error messages on failed login
//
// Security Features:
// - Prepared statements (prevent SQL injection)
// - Password verification with hashing
// - Session management
// - Input validation
//
// ============================================================

// Start session for storing login information
session_start();

// If user is already logged in, redirect to home page
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Include database configuration
include '../config.php';

// ============================================================
// Initialize variables
// ============================================================

$message = "";           // For displaying error/success messages
$message_type = "";      // "error" or "success"

// ============================================================
// Handle Form Submission
// ============================================================
// Check if form was submitted via POST method

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ============================================================
    // Get form input and sanitize
    // ============================================================
    
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // ============================================================
    // Validation
    // ============================================================
    
    if (empty($username) || empty($password)) {
        $message = "Please enter both username and password.";
        $message_type = "error";
    } else {
        
        // ============================================================
        // Query database for user
        // ============================================================
        // Use prepared statement to prevent SQL injection
        
        $stmt = $conn->prepare(
            "SELECT id, username, password FROM account WHERE username = ?"
        );
        
        if ($stmt === false) {
            // Prepare failed
            $message = "Database error: " . $conn->error;
            $message_type = "error";
        } else {
            
            // Bind parameter (s = string)
            $stmt->bind_param("s", $username);
            
            // Execute query
            $stmt->execute();
            
            // Get result
            $result = $stmt->get_result();
            
            // ============================================================
            // Check if user exists
            // ============================================================
            
            if ($result->num_rows == 1) {
                // User found - fetch user data
                $row = $result->fetch_assoc();
                
                // ============================================================
                // Verify password
                // ============================================================
                // Use password_verify() to compare input with hashed password
                
                if (password_verify($password, $row['password'])) {
                    // Password is correct - set session and redirect
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['user_id'] = $row['id'];
                    
                    // Redirect to home page
                    header("Location: index.php");
                    exit();
                } else {
                    // Password is incorrect
                    $message = "❌ Invalid password. Please try again.";
                    $message_type = "error";
                }
            } else {
                // User not found
                $message = "❌ Username not found. Please check and try again.";
                $message_type = "error";
            }
            
            // Close statement
            $stmt->close();
        }
    }
}

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - SocialNet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #667eea;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 0.95em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 0.95em;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #5568d3;
        }

        .form-group button:active {
            background-color: #4a57b8;
        }

        .message {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.95em;
        }

        .message.error {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .message.success {
            background-color: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.95em;
            margin: 0 5px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .divider {
            color: #999;
            margin: 0 5px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
                margin: 20px;
            }

            .login-header h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Header -->
    <div class="login-header">
        <h1>🔐 SocialNet</h1>
        <p>Welcome Back</p>
    </div>

    <!-- Message Display -->
    <?php
    if (!empty($message)) {
        echo "<div class='message " . htmlspecialchars($message_type) . "'>";
        echo htmlspecialchars($message);
        echo "</div>";
    }
    ?>

    <!-- Login Form -->
    <form method="POST" action="">
        
        <!-- Username Field -->
        <div class="form-group">
            <label for="username">Username</label>
            <input 
                type="text" 
                id="username"
                name="username" 
                placeholder="Enter your username"
                required
            >
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password">Password</label>
            <input 
                type="password" 
                id="password"
                name="password" 
                placeholder="Enter your password"
                required
            >
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button type="submit">Sign In</button>
        </div>

    </form>

    <!-- Links -->
    <div class="links">
        <a href="about.php">About</a>
        <span class="divider">|</span>
        <a href="../admin/newuser.php">Create Account</a>
    </div>

</div>

<?php
// ============================================================
// Code Explanation
// ============================================================
//
// 1. Session Management
//    - session_start(): Initialize session for storing login info
//    - Check if already logged in, redirect if yes
//    - $_SESSION['username']: Stores username after login
//
// 2. Form Handling
//    - $_SERVER["REQUEST_METHOD"] == "POST": Check if form submitted
//    - trim(): Remove whitespace from input
//    - Empty validation: Check both fields have values
//
// 3. Database Query
//    - Prepared statement: $conn->prepare()
//    - bind_param("s", $username): Bind string parameter
//    - Prevents SQL injection attacks
//    - num_rows: Check if user exists
//
// 4. Password Verification
//    - password_verify(): Compare plain password with hash
//    - Returns true/false
//    - More secure than plain text comparison
//
// 5. Error Handling
//    - Specific messages for different errors
//    - User not found vs wrong password
//    - Database errors handled gracefully
//
// 6. Styling
//    - Modern gradient background
//    - Responsive design for mobile
//    - Professional appearance
//    - Clear visual feedback
//
// ============================================================
?>

</body>
</html>
