<?php
// Assurer que l'utilisateur est connecté et son nom est disponible
$username = $_SESSION['username'] ?? 'Utilisateur';
?>
<header class="main-header">

    <div class="container">

        <h1 class="logo">TodoApp</h1>

        <!-- Barre de recherche et filtre tags -->
        <form class="search-form" action="/?page=tasks" method="GET">
            <input type="hidden" name="page" value="tasks">

            <!-- Barre de recherche -->
            <input 
                type="text" 
                name="search" 
                placeholder="Rechercher une tâche..." 
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
            />

            <!-- Filtre par tag -->
            <select name="tag">
                <option value="">-- Filtrer par tag --</option>
                <option value="urgent" <?= (($_GET['tag'] ?? '') === 'urgent') ? 'selected' : '' ?>>Urgent</option>
                <option value="maison" <?= (($_GET['tag'] ?? '') === 'maison') ? 'selected' : '' ?>>Maison</option>
                <option value="travail" <?= (($_GET['tag'] ?? '') === 'travail') ? 'selected' : '' ?>>Travail</option>
                <option value="important" <?= (($_GET['tag'] ?? '') === 'important') ? 'selected' : '' ?>>Important</option>
            </select>

            <button type="submit">🔍</button>
        </form>

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

<style>
/* Centrage du formulaire dans le header */
.search-form {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-grow: 1;
    justify-content: center;
}

.search-form input[type="text"] {
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    min-width: 200px;
}

.search-form select {
    padding: 6px 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.search-form button {
    padding: 6px 10px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}

.search-form button:hover {
    background-color: #0056b3;
}
</style>
