<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_role(['admin', 'hr']);

$stmt = $pdo->query("SELECT s.*, d.department_name FROM staff s JOIN departments d ON s.department_id = d.department_id ORDER BY s.full_name");
$staff_members = $stmt->fetchAll();

include '../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-kstvet-brown">Staff Records Management</h2>
    <a href="staff_add.php" class="btn btn-kstvet-brown"><i class="bi bi-plus-lg"></i> Register New Staff</a>
</div>

<div class="card p-3 shadow-sm">
    <table class="table table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Staff No</th>
                <th>Full Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staff_members as $staff): ?>
            <tr>
                <td><strong><?= htmlspecialchars($staff['staff_no']) ?></strong></td>
                <td><?= htmlspecialchars($staff['full_name']) ?></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($staff['department_name']) ?></span></td>
                <td><?= htmlspecialchars($staff['position']) ?></td>
                <td><?= htmlspecialchars($staff['email']) ?></td>
                <td><?= htmlspecialchars($staff['phone']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>