<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
check_auth();

$stmt = $pdo->query("
    SELECT s.staff_no, s.full_name, d.department_name, k.skill_name,
           k.required_level, a.current_level,
           (k.required_level - a.current_level) AS gap_size
    FROM assessments a
    JOIN staff s ON s.staff_id = a.staff_id
    JOIN departments d ON d.department_id = s.department_id
    JOIN skills k ON k.skill_id = a.skill_id
    WHERE a.current_level < k.required_level
    ORDER BY gap_size DESC, s.full_name
");
$gaps = $stmt->fetchAll();

include '../includes/header.php';
?>
<h2 class="mb-4 fw-bold text-kstvet-brown">ICT Skills Gap Analysis</h2>
<div class="card p-3 shadow-sm">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Staff No</th>
                <th>Staff Name</th>
                <th>Department</th>
                <th>ICT Skill</th>
                <th>Required Level</th>
                <th>Current Level</th>
                <th>Gap Size</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($gaps) > 0): foreach ($gaps as $gap): ?>
            <tr>
                <td><strong><?= htmlspecialchars($gap['staff_no']) ?></strong></td>
                <td><?= htmlspecialchars($gap['full_name']) ?></td>
                <td><?= htmlspecialchars($gap['department_name']) ?></td>
                <td><?= htmlspecialchars($gap['skill_name']) ?></td>
                <td><?= $gap['required_level'] == 3 ? 'Advanced' : ($gap['required_level'] == 2 ? 'Intermediate' : 'Basic') ?></td>
                <td><?= $gap['current_level'] == 3 ? 'Advanced' : ($gap['current_level'] == 2 ? 'Intermediate' : 'Basic') ?></td>
                <td><span class="badge bg-danger"><?= $gap['gap_size'] ?> Level(s)</span></td>
                <td><span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Gap Identified</span></td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="8" class="text-center py-4 text-muted">No competency gaps currently identified.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>