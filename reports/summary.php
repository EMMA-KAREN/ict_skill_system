<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
check_auth();

$dept_gaps = $pdo->query("
    SELECT d.department_name, COUNT(*) as gap_count
    FROM assessments a
    JOIN staff s ON a.staff_id = s.staff_id
    JOIN departments d ON s.department_id = d.department_id
    JOIN skills k ON a.skill_id = k.skill_id
    WHERE a.current_level < k.required_level
    GROUP BY d.department_id
")->fetchAll();

$skill_gaps = $pdo->query("
    SELECT k.skill_name, COUNT(*) as gap_count
    FROM assessments a
    JOIN skills k ON a.skill_id = k.skill_id
    WHERE a.current_level < k.required_level
    GROUP BY k.skill_id
")->fetchAll();

include '../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-kstvet-brown">Management Summary Reports</h2>
    <button onclick="window.print()" class="btn btn-kstvet-brown"><i class="bi bi-printer me-1"></i> Print / Export PDF</button>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm">
            <h4 class="mb-3 text-kstvet-brown fw-bold">Competency Gaps by Department</h4>
            <table class="table table-striped">
                <thead>
                    <tr><th>Department</th><th>Total Gaps</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($dept_gaps as $dg): ?>
                    <tr><td><?= htmlspecialchars($dg['department_name']) ?></td><td><span class="badge bg-warning text-dark"><?= $dg['gap_count'] ?></span></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 shadow-sm">
            <h4 class="mb-3 text-kstvet-brown fw-bold">Competency Gaps by ICT Skill</h4>
            <table class="table table-striped">
                <thead>
                    <tr><th>Skill</th><th>Total Gaps</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($skill_gaps as $sg): ?>
                    <tr><td><?= htmlspecialchars($sg['skill_name']) ?></td><td><span class="badge bg-danger"><?= $sg['gap_count'] ?></span></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>