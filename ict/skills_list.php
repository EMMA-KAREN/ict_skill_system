<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_role(['admin', 'ict']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skill_name = trim($_POST['skill_name']);
    $description = trim($_POST['description']);
    $required_level = $_POST['required_level'];

    $stmt = $pdo->prepare("INSERT INTO skills (skill_name, description, required_level) VALUES (?, ?, ?)");
    $stmt->execute([$skill_name, $description, $required_level]);
    header('Location: skills_list.php');
    exit;
}

$skills = $pdo->query("SELECT * FROM skills ORDER BY skill_name")->fetchAll();

include '../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-kstvet-brown">ICT Skills Catalogue</h2>
    <button class="btn btn-kstvet-brown" data-bs-toggle="modal" data-bs-target="#addSkillModal"><i class="bi bi-plus-lg"></i> Add New Skill</button>
</div>

<div class="card p-3 shadow-sm">
    <table class="table table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Skill Name</th>
                <th>Description</th>
                <th>Required Competency Level</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($skills as $skill): ?>
            <tr>
                <td><strong><?= htmlspecialchars($skill['skill_name']) ?></strong></td>
                <td><?= htmlspecialchars($skill['description']) ?></td>
                <td>
                    <span class="badge bg-kstvet-brown text-warning">
                        <?= $skill['required_level'] == 3 ? 'Advanced' : ($skill['required_level'] == 2 ? 'Intermediate' : 'Basic') ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="addSkillModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-kstvet-brown text-white">
                <h5 class="modal-title">Add Skill to Catalogue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Skill Name</label>
                    <input type="text" name="skill_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Required Competency Level</label>
                    <select name="required_level" class="form-select" required>
                        <option value="1">Basic</option>
                        <option value="2" selected>Intermediate</option>
                        <option value="3">Advanced</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-kstvet-brown">Add Skill</button>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>