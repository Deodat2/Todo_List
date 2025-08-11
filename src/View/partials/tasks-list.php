<?php if (!empty($_SESSION['success'])): ?>

    <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>

    <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>

<div class="tasks-header">

    <h2>Mes tâches</h2>

    <a href="/?page=tasks&subpage=create" class="btn btn-primary">+ Nouvelle tâche</a>

</div>

<div class="task-cards">

    <?php if (empty($tasks)): ?>

        <p>Aucune tâche trouvée.</p>

    <?php else: ?>

        <?php foreach ($tasks as $task): ?>

            <div class="task-card" onclick="window.location.href='/?page=tasks&subpage=edit&id=<?= $task['id'] ?>'">

                <?php if (!empty($task['tags'])): ?>

                    <span class="task-badge"><?= htmlspecialchars($task['tags'][0]) ?></span>

                <?php endif; ?>

                <h3 class="task-title"><?= htmlspecialchars($task['title']) ?></h3>

                <p class="task-desc">

                    <?= htmlspecialchars(mb_strimwidth($task['description'], 0, 80, '...')) ?>
                
                </p>

                <form method="POST" action="/?page=tasks" class="delete-form" onsubmit="event.stopPropagation(); return confirm('Supprimer cette tâche ?');">
                    
                    <input type="hidden" name="id" value="<?= $task['id'] ?>" />

                    <input type="hidden" name="action" value="delete" />

                    <button type="submit" class="delete-btn">🗑</button>

                </form>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>
    
</div>
