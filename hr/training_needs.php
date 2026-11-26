<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_role(['admin', 'hr']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $skill_id = $_POST['skill_id'] ?: null;
    $training_title = trim($_POST['training_title']);
    $priority = $_POST['priority'];
    $recommendation = trim($_POST['recommendation']);

    $stmt = $pdo->prepare("INSERT INTO training_needs (staff_id, skill_id, training_title, priority, recommendation, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$staff_id, $skill_id, $training_title, $priority, $recommendation, $_SESSION['username']]);
    header('Location: training_needs.php');
    exit;
}

$needs = $pdo->query("
    SELECT tn.*, s.full_name, sk.skill_name 
    FROM training_needs tn 
    JOIN staff s ON tn.staff_id = s.staff_id 
    LEFT JOIN skills sk ON tn.skill_id = sk.skill_id 
    ORDER BY tn.created_at DESC
")->fetchAll();

$staff_list = $pdo->query("SELECT staff_id, full_name FROM staff")->fetchAll();
$skills_list = $pdo->query("SELECT skill_id, skill_name FROM skills")->fetchAll();

include '../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-kstvet-brown">Training Needs Management</h2>
    <button class="btn btn-kstvet-brown" data-bs-toggle="modal" data-bs-target="#addNeedModal"><i class="bi bi-plus-lg"></i> Record Training Need</button>
</div>

<div class="card p-3 shadow-sm">
    <table class="table table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Staff Member</th>
                <th>Training Title</th>
                <th>Skill Associated</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Recommendation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($needs as $need): ?>
            <tr>
                <td><strong><?= htmlspecialchars($need['full_name']) ?></strong></td>
                <td><?= htmlspecialchars($need['training_title']) ?></td>
                <td><?= htmlspecialchars($need['skill_name'] ?? 'General') ?></td>
                <td>
                    <span class="badge bg-<?= $need['priority'] == 'high' ? 'danger' : ($need['priority'] == 'medium' ? 'warning text-dark' : 'secondary') ?>">
                        <?= ucfirst($need['priority']) ?>
                    </span>
                </td>
                <td><span class="badge bg-info text-dark"><?= ucfirst($need['status']) ?></span></td>
                <td><?= htmlspecialchars($need['recommendation']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="addNeedModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-kstvet-brown text-white">
                <h5 class="modal-title">Record Training Need</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Staff Member</label>
                    <select name="staff_id" class="form-select" required>
                        <?php foreach ($staff_list as $s): ?>
                            <option value="<?= $s['staff_id'] ?>"><?= htmlspecialchars($s['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Related Skill (Optional)</label>
                    <select name="skill_id" class="form-select">
                        <option value="">-- None --</option>
                        <?php foreach ($skills_list as $sk): ?>
                            <option value="<?= $sk['skill_id'] ?>"><?= htmlspecialchars($sk['skill_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Training Title</label>
                    <input type="text" name="training_title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Recommendation / Justification</label>
                    <textarea name="recommendation" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-kstvet-brown">Save Training Need</button>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>