<?php
// Views/tasks/edit.php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>Modifier la tâche</title>
        <link rel="stylesheet" href="/css/style.css" />
    </head>
    <body>
        <h1>Modifier la tâche</h1>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/?page=tasks">
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']) ?>" />

            <label for="title">Titre :</label><br />
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required /><br /><br />

            <label for="description">Description :</label><br />
            <textarea id="description" name="description" rows="5"><?= htmlspecialchars($task['description']) ?></textarea><br /><br />

            <label for="tags">Tags (séparés par des virgules) :</label><br />
            <input type="text" id="tags" name="tags" value="<?php
                echo !empty($task['tags']) ? htmlspecialchars(implode(', ', $task['tags'])) : '';
            ?>" /><br /><br />

            <button type="submit">Mettre à jour</button>
        </form>

        <p><a href="/?page=tasks">Retour à la liste</a></p>
    </body>
</html>
