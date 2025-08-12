<?php
// Inclure le header
require __DIR__ . '/../partials/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Créer une tâche</title>
    <link rel="stylesheet" href="/css/styles.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f8fa;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            transition: 0.3s;
        }

        .btn:hover {
            background: #0056b3;
        }

        .error {
            color: #d9534f;
            padding: 10px;
            background: #f2dede;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1>Créer une nouvelle tâche</h1>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="/?page=tasks">
        <input type="hidden" name="action" value="create" />

        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" placeholder="Nom de la tâche" required />

        <label for="description">Description :</label>
        <textarea id="description" name="description" rows="5" placeholder="Détaillez la tâche..."></textarea>

        <label for="tags">Tags :</label>
        <select id="tags" name="tags[]" multiple>
            <option value="urgent">Urgent</option>
            <option value="maison">Maison</option>
            <option value="travail">Travail</option>
            <option value="important">Important</option>
        </select>

        <label for="new-tag">Créer un nouveau tag :</label>
        <input type="text" id="new-tag" placeholder="Nouveau tag..." />

        <button type="submit" class="btn">Créer</button>
    </form>
</div>

<script>
    const tagSelect = document.getElementById('tags');
    const newTagInput = document.getElementById('new-tag');

    newTagInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const newTag = newTagInput.value.trim();
            if (newTag !== '') {
                const option = document.createElement('option');
                option.value = newTag;
                option.text = newTag;
                option.selected = true;
                tagSelect.add(option);
                newTagInput.value = '';
            }
        }
    });
</script>
</body>
</html>
