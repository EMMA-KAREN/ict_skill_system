<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_role(['admin', 'ict']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $skill_id = $_POST['skill_id'];
    $current_level = $_POST['current_level'];
    $assessment_date = $_POST['assessment_date'];
    $notes = trim($_POST['notes']);

    $stmt = $pdo->prepare("INSERT INTO assessments (staff_id, skill_id, current_level, assessed_by, assessment_date, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$staff_id, $skill_id, $current_level, $_SESSION['username'], $assessment_date, $notes]);
    header('Location: gap_analysis.php');
    exit;
}

$staff_members = $pdo->query("SELECT staff_id, full_name, staff_no FROM staff")->fetchAll();
$skills = $pdo->query("SELECT skill_id, skill_name FROM skills")->fetchAll();

include '../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm">
            <h3 class="mb-3 text-kstvet-brown fw-bold">Record ICT Skill Assessment</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Staff Member</label>
                    <select name="staff_id" class="form-select" required>
                        <?php foreach ($staff_members as $s): ?>
                            <option value="<?= $s['staff_id'] ?>"><?= htmlspecialchars($s['staff_no']) ?> - <?= htmlspecialchars($s['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">ICT Skill</label>
                    <select name="skill_id" class="form-select" required>
                        <?php foreach ($skills as $sk): ?>
                            <option value="<?= $sk['skill_id'] ?>"><?= htmlspecialchars($sk['skill_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Assessed Competency Level</label>
                    <select name="current_level" class="form-select" required>
                        <option value="1">Basic</option>
                        <option value="2">Intermediate</option>
                        <option value="3">Advanced</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Assessment Date</label>
                    <input type="date" name="assessment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Assessor Notes / Remarks</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-kstvet-brown w-100 py-2">Save Assessment</button>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>