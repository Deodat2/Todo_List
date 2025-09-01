<!-- Flash messages -->
<?php if(isset($_SESSION['success'])): ?>
    <div class="flash-message success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if(isset($_SESSION['error'])): ?>
    <div class="flash-message error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="task-container">

    <!-- Header tâche principale -->
    <div class="task-header">

        <div class="task-header-left">
            <h1 class="task-title-sub"><?= htmlspecialchars($task['title']) ?></h1>
            <p class="task-desc-sub"><?= nl2br(htmlspecialchars($task['description'])) ?></p>
        </div>

        <div class="task-header-right">

            <!-- Status de la tâche principale -->
            <form method="post" action="/?page=tasks" class="main-task-status-form">
                <input type="hidden" name="action" value="task_status">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">

                <label for="task-status-select">Status: </label>
                <select name="status" id="task-status-select" class="status-select <?= "badge-".$task['status'] ?>" onchange="this.form.submit()">
                    <?php
                        $statuses = [
                            'created'=>'Created',
                            'in_progress'=>'In Progress',
                            'paused'=>'Paused',
                            'suspended'=>'Suspended',
                            'abandoned'=>'Abandoned',
                            'complete'=>'Complete'
                        ];
                        foreach($statuses as $key => $label):
                    ?>
                        <option value="<?= $key ?>" <?= $task['status'] === $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </form>

            <hr>

            <span>Created : <strong><?= date('d/m/Y H:i', strtotime($task['created_at'])) ?></strong></span>
            <?php if(!empty($task['deadline'])): ?>
                <span>⏳ Deadline : <strong><?= date('d/m/Y H:i', strtotime($task['deadline'])) ?></strong></span>
            <?php endif; ?>

        </div>
    </div>

    <hr class="divider">

    <!-- Résumé des sous-tâches -->
    <div class="subtasks-summary">
        <?php
            $statusCounts = ['created'=>0, 'in_progress'=>0, 'paused'=>0, 'suspended'=>0, 'abandoned'=>0, 'complete'=>0];
            foreach($subtasks as $s) { 
                if(isset($statusCounts[$s['status']])) $statusCounts[$s['status']]++;
            }
        ?>
        <?php foreach($statusCounts as $status => $count): ?>
            <span class="summary-badge <?= "badge-$status" ?>"><?= ucfirst(str_replace('_',' ',$status)) ?>: <?= $count ?></span>
        <?php endforeach; ?>
    </div>

    <!-- Sous-tâches -->
    <div class="subtasks-header">
        <h2>Sous-tâches</h2>
        <button id="openModalBtn" class="btn-primary">+ Add Subtask</button>
    </div>

    <?php if(!empty($subtasks)): ?>
        <div class="table-wrapper">
            <table class="subtasks-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Deadline / Duration</th>
                        <th class="actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($subtasks as $subtask): ?>
                        <?php
                            // Calcul affichage deadline/duration (en jours)
                            $deadlineDisplay = '-';
                            if($subtask['status'] === 'created' && !empty($subtask['duration'])) {
                                $deadlineDisplay = $subtask['duration'].' day(s)';
                            } elseif($subtask['status'] === 'in_progress' && !empty($subtask['created_at']) && !empty($subtask['duration'])) {
                                $deadlineTime = strtotime($subtask['created_at']) + $subtask['duration'] * 86400; // jours -> secondes
                                $deadlineDisplay = date('d/m/Y H:i', $deadlineTime);
                            } elseif(!empty($subtask['deadline'])) {
                                $deadlineDisplay = date('d/m/Y H:i', strtotime($subtask['deadline']));
                            }

                            // Classe badge status
                            $statusColors = [
                                'created'=>'badge-created',
                                'in_progress'=>'badge-in-progress',
                                'paused'=>'badge-paused',
                                'suspended'=>'badge-suspended',
                                'abandoned'=>'badge-abandoned',
                                'complete'=>'badge-complete'
                            ];

                            // Ligne en retard
                            $isOverdue = ($subtask['status'] !== 'complete') && !empty($subtask['deadline']) && strtotime($subtask['deadline']) < time();
                        ?>
                        <tr class="<?= $isOverdue ? 'row-overdue' : '' ?>">
                            <td><?= htmlspecialchars($subtask['title']) ?></td>
                            <td><?= nl2br(htmlspecialchars($subtask['description'])) ?></td>
                            <td>
                                <form method="post" action="/?page=tasks">
                                    <input type="hidden" name="action" value="subtask_status">
                                    <input type="hidden" name="id" value="<?= $subtask['id'] ?>">
                                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">

                                    <select name="status" class="status-select <?= $statusColors[$subtask['status']] ?>" onchange="this.form.submit()">
                                        <?php
                                        $statuses = [
                                            'created'=>'Created',
                                            'in_progress'=>'In Progress',
                                            'paused'=>'Paused',
                                            'suspended'=>'Suspended',
                                            'abandoned'=>'Abandoned',
                                            'complete'=>'Complete'
                                        ];
                                        foreach($statuses as $key => $label):
                                        ?>
                                            <option value="<?= $key ?>" <?= $subtask['status']===$key?'selected':'' ?>><?= $label ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                            <td><?= $deadlineDisplay ?></td>
                            <td class="actions-cell">
                                <form method="post" action="/?page=tasks" class="inline-form">
                                    <input type="hidden" name="action" value="subtask_delete">
                                    <input type="hidden" name="id" value="<?= $subtask['id'] ?>">
                                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                    <button type="submit" class="btn-danger" onclick="return confirm('Delete this subtask?')">🗑</button>
                                </form>
                                <form method="get" action="/?page=tasks&subpage=edit" class="inline-form">
                                    <input type="hidden" name="id" value="<?= $subtask['id'] ?>">
                                    <button type="submit" class="btn-secondary">✏️ Edit</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="no-subtasks">🚫 No subtasks yet.</p>
    <?php endif; ?>
</div>

<!-- Modal Add Subtask -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-panel" id="modalPanel">
        <span class="modal-close" id="closeModal">&times;</span>
        <h3>Add Subtask</h3>
        <form method="post" action="/?page=tasks" class="modal-form">
            <input type="hidden" name="action" value="subtask_create">
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">

            <input type="text" name="title" placeholder="Subtask Title" required>
            <textarea name="description" placeholder="Subtask Description"></textarea>
            <input type="number" name="duration" placeholder="Duration (days)" min="1" required>

            <button type="submit" class="btn-primary">Add</button>
        </form>
    </div>
</div>

<!-- Script Modal -->
<script>
const openBtn = document.getElementById('openModalBtn');
const modalOverlay = document.getElementById('modalOverlay');
const modalPanel = document.getElementById('modalPanel');
const closeBtn = document.getElementById('closeModal');

openBtn.addEventListener('click', () => {
    modalOverlay.style.display = 'flex';
    setTimeout(() => modalPanel.classList.add('show'), 10);
});

closeBtn.addEventListener('click', () => {
    modalPanel.classList.remove('show');
    setTimeout(() => modalOverlay.style.display = 'none', 300);
});

modalOverlay.addEventListener('click', (e) => {
    if(e.target === modalOverlay){
        modalPanel.classList.remove('show');
        setTimeout(() => modalOverlay.style.display = 'none', 300);
    }
});
</script>
