<?php
session_start();

// Collect and store error messages before unsetting
$errors = [
    "login" => $_SESSION['login_error'] ?? '',
    "register" => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

// Unset only the specific session keys to preserve others
unset($_SESSION['login_error'], $_SESSION['register_error'], $_SESSION['active_form']);

// Function to safely display error messages
function showError($error) {
    return !empty($error) ? "<p class='error-message'>" . htmlspecialchars($error) . "</p>" : '';
}

// Function to determine active form
function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- Replace .container with .main-container and wrap both form-boxes inside .form-container -->
<div class="main-container <?= $activeForm === 'register' ? 'show-register' : '' ?>">
    <div class="form-container">
        <div class="form-box login">
            <form action="login_register.php" method="post">
                <h2>Login</h2>
                <?= showError($errors['login']); ?>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required autocomplete="email">
                </div>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
                </div>
                <button type="submit" name="login">Login</button>
                <p>Don't have an account? <a href="#" onclick="toggleForm('register')">Register</a></p>
            </form>
        </div>

        <div class="form-box register">
            <form action="login_register.php" method="post">
                <h2>Register</h2>
                <?= showError($errors['register']); ?>
                <input type="text" name="name" placeholder="Name" required autocomplete="name">
                <input type="email" name="email" placeholder="Email" required autocomplete="email">
                <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
                <select name="role" required>
                    <option value="">--Select Role--</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="register">Register</button>
                <p>Already have an account? <a href="#" onclick="toggleForm('login')">Login</a></p>
            </form>
        </div>
    </div>
</div>

    <!-- JavaScript to handle form switching -->
        <script>
function toggleForm(formType) {
    const container = document.querySelector('.main-container');
    if (formType === 'register') {
        container.classList.add('show-register');
    } else {
        container.classList.remove('show-register');
    }
}
    function showForm(formId) {
        document.getElementById('login-form').classList.remove('active');
        document.getElementById('register-form').classList.remove('active');
        document.getElementById(formId).classList.add('active');
    }
    </script>
</body>
</html>
