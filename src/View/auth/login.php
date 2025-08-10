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

        <main class="auth-container">

            <h1>Welcome Back</h1>

            <form method="POST" action="/?page=login" class="auth-form">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="********" required />

                <button type="submit">Login</button>

            </form>

            <p class="auth-footer">
                Don't have an account? <a href="/?page=register">Sign up</a>
            </p>

        </main>

    </body>
    
</html>
