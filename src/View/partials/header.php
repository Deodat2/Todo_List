<?php
// Assurer que l'utilisateur est connecté et son nom est disponible
$username = $_SESSION['username'] ?? 'Utilisateur';
?>
<header class="main-header">

    <div class="container">

        <h1 class="logo">TodoApp</h1>

        <nav class="nav-links">
            <a href="/?page=dashboard" class="btn-nav">Board</a>
            <a href="/?page=tasks&subpage=create" class="btn-nav primary">+ New</a>

            <!-- Menu utilisateur -->
            <div class="user-menu">
                <button class="user-icon" id="userMenuBtn">
                    <img src="/public/user-icon.svg" alt="User">
                </button>
                <div class="user-dropdown" id="userDropdown">
                    <p class="username"><?php echo htmlspecialchars($username); ?></p>
                    <a href="/?page=logout" class="btn-logout">Logout</a>
                </div>
            </div>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    userMenuBtn.addEventListener('click', () => {
        userDropdown.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
        if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('show');
        }
    });
});
</script>