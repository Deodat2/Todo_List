<!-- Views/auth/login.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Todo List</title>
    <link rel="stylesheet" href="/css/styles.css" />
</head>
<body>

<div class="login-container">
    <h2>Welcome Back</h2>
    <form method="POST" action="/?page=login">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" required />
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="********" required />
        </div>

        <button type="submit" class="btn">Login</button>

        <div class="register-link">
            Don't have an account? <a href="/?page=register">Sign up</a>
        </div>
    </form>
</div>

</body>
</html>
