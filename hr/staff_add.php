<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_role(['admin', 'hr']);

$departments = $pdo->query("SELECT * FROM departments")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_no = trim($_POST['staff_no']);
    $full_name = trim($_POST['full_name']);
    $department_id = $_POST['department_id'];
    $position = trim($_POST['position']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    try {
        $stmt = $pdo->prepare("INSERT INTO staff (staff_no, full_name, department_id, position, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$staff_no, $full_name, $department_id, $position, $email, $phone]);
        header('Location: staff_list.php');
        exit;
    } catch (PDOException $e) {
        $error = 'Error: Staff number may already exist.';
    }
}
include '../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm">
            <h3 class="mb-3 text-kstvet-brown fw-bold">Register Staff Member</h3>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Staff Number</label>
                    <input type="text" name="staff_no" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select" required>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['department_id'] ?>"><?= htmlspecialchars($dept['department_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Position</label>
                    <input type="text" name="position" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <button type="submit" class="btn btn-kstvet-brown w-100 py-2">Save Staff Record</button>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>