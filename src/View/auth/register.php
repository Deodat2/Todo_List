<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Inscription - Todo List</title>
        <link rel="stylesheet" href="/css/styles.css" />
    </head>

    <body>
        <main class="auth-container">

            <h1>Inscription</h1>

            <form action="/?page=register" method="POST" class="auth-form">

                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="username" />

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="exemple@mail.com" />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="*******" />

                <button type="submit">Signup</button>
                
            </form>

            <p class="auth-footer">
                Already have an account? <a href="/?page=login">Login</a>
            </p>
        </main>
    </body>
</html>
