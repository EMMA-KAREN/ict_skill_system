<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_role(['admin']);

$message = '';
$error = '';

// Password Reset Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_password') {
    $reset_user_id = (int)$_POST['user_id'];
    $new_password = trim($_POST['new_password']);

    if (!empty($new_password)) {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $stmt->execute([$password_hash, $reset_user_id]);
        $message = "Password successfully reset for User ID #{$reset_user_id}.";
    } else {
        $error = "New password cannot be empty.";
    }
}

// Delete User Action
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if ($delete_id === $_SESSION['user_id']) {
        $error = "Security Restriction: You cannot delete your own logged-in admin account.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$delete_id]);
        $message = "User account successfully deleted.";
    }
}

// Create User Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_user') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];
    $staff_id = $_POST['staff_id'] ?: null;

    if (!empty($username) && !empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, staff_id, status) VALUES (?, ?, ?, ?, 'active')");
            $stmt->execute([$username, $password_hash, $role, $staff_id]);
            $message = "User account '{$username}' created with assigned credentials successfully!";
        } catch (PDOException $e) {
            $error = "Error: Username '{$username}' already exists.";
        }
    } else {
        $error = "Username and Password cannot be empty.";
    }
}

$users = $pdo->query("SELECT u.*, s.full_name FROM users u LEFT JOIN staff s ON u.staff_id = s.staff_id ORDER BY u.created_at DESC")->fetchAll();
$staff_members = $pdo->query("SELECT staff_id, full_name, staff_no FROM staff")->fetchAll();

include '../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-kstvet-brown"><i class="bi bi-person-gear text-warning"></i> Admin User & Password Management</h2>
    <button class="btn btn-kstvet-brown" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-person-plus-fill me-1"></i> Create User Account</button>
</div>

<?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="card p-3 shadow-sm">
    <table class="table table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Linked Staff Member</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['user_id'] ?></td>
                <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                <td><span class="badge bg-kstvet-brown text-warning"><?= strtoupper($u['role']) ?></span></td>
                <td><?= htmlspecialchars($u['full_name'] ?? 'System User') ?></td>
                <td><span class="badge bg-success"><?= ucfirst($u['status']) ?></span></td>
                <td><?= $u['created_at'] ?></td>
                <td>
                    <!-- Reset Password Button -->
                    <button class="btn btn-sm btn-outline-warning text-dark me-1" data-bs-toggle="modal" data-bs-target="#resetModal<?= $u['user_id'] ?>">
                        <i class="bi bi-key-fill"></i> Reset Password
                    </button>

                    <?php if ($u['user_id'] !== $_SESSION['user_id']): ?>
                        <a href="users.php?delete_id=<?= $u['user_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user account?');">
                            <i class="bi bi-trash-fill"></i> Delete
                        </a>
                    <?php else: ?>
                        <span class="badge bg-secondary">Current Active Admin</span>
                    <?php endif; ?>

                    <!-- Reset Password Modal -->
                    <div class="modal fade" id="resetModal<?= $u['user_id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <input type="hidden" name="action" value="reset_password">
                                <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                                <div class="modal-header bg-kstvet-brown text-white">
                                    <h5 class="modal-title"><i class="bi bi-key-fill text-warning me-2"></i> Reset Password for '<?= htmlspecialchars($u['username']) ?>'</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Enter New Password</label>
                                        <input type="password" name="new_password" class="form-control" placeholder="Type new password" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-kstvet-brown">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal to Create User & Assign Credentials -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="create_user">
            <div class="modal-header bg-kstvet-brown text-white">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill text-warning me-2"></i> Create User & Assign Credentials</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Assign Username</label>
                    <input type="text" name="username" class="form-control" placeholder="e.g. hr_officer2" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Assign Password</label>
                    <input type="password" name="password" class="form-control" placeholder="e.g. Pass123!" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select System Role</label>
                    <select name="role" class="form-select" required>
                        <option value="hr">HR Officer (hr)</option>
                        <option value="ict">ICT Officer (ict)</option>
                        <option value="procurement">Procurement Officer (procurement)</option>
                        <option value="admin">System Administrator (admin)</option>
                        <option value="staff">Staff User (staff)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Link to Staff Profile (Optional)</label>
                    <select name="staff_id" class="form-select">
                        <option value="">-- None / Standalone Account --</option>
                        <?php foreach ($staff_members as $sm): ?>
                            <option value="<?= $sm['staff_id'] ?>"><?= htmlspecialchars($sm['staff_no']) ?> - <?= htmlspecialchars($sm['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-kstvet-brown">Create User Account</button>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>