<?php
// Views/tasks/create.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Créer une tâche</title>
        <link rel="stylesheet" href="/css/styles.css" />
    </head>
    <body>
        <h1>Créer une nouvelle tâche</h1>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/?page=tasks">
            <input type="hidden" name="action" value="create" />
            <label for="title">Titre :</label><br />
            <input type="text" id="title" name="title" required /><br /><br />

            <label for="description">Description :</label><br />
            <textarea id="description" name="description" rows="5"></textarea><br /><br />

            <label for="tags">Tags (séparés par des virgules) :</label><br />
            <input type="text" id="tags" name="tags" placeholder="ex: urgent, maison" /><br /><br />

            <button type="submit">Créer</button>
        </form>

        <p><a href="/?page=tasks">Retour à la liste</a></p>
    </body>
</html>
