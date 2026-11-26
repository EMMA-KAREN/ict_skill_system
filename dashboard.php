<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
check_auth();

$total_staff = $pdo->query("SELECT COUNT(*) FROM staff")->fetchColumn();
$total_assessed = $pdo->query("SELECT COUNT(DISTINCT staff_id) FROM assessments")->fetchColumn();
$total_gaps = $pdo->query("SELECT COUNT(*) FROM assessments a JOIN skills s ON a.skill_id = s.skill_id WHERE a.current_level < s.required_level")->fetchColumn();
$total_needs = $pdo->query("SELECT COUNT(*) FROM training_needs WHERE status = 'pending'")->fetchColumn();

include 'includes/header.php';
?>
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-kstvet-brown">Institutional Overview Dashboard</h2>
        <p class="text-muted">Welcome back, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! Here is your role-based system summary.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-kstvet-brown text-white p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-warning fw-semibold">Total Staff</h6>
                    <h2 class="fw-bold mb-0"><?= $total_staff ?></h2>
                </div>
                <i class="bi bi-people-fill fs-1 text-warning opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-light fw-semibold">Assessed Staff</h6>
                    <h2 class="fw-bold mb-0"><?= $total_assessed ?></h2>
                </div>
                <i class="bi bi-check-circle-fill fs-1 text-light opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-kstvet-brown fw-semibold">Skills Gaps</h6>
                    <h2 class="fw-bold mb-0"><?= $total_gaps ?></h2>
                </div>
                <i class="bi bi-exclamation-triangle-fill fs-1 text-kstvet-brown opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-light fw-semibold">Pending Needs</h6>
                    <h2 class="fw-bold mb-0"><?= $total_needs ?></h2>
                </div>
                <i class="bi bi-clock-history fs-1 text-light opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card p-4 shadow-sm">
            <h5 class="fw-bold text-kstvet-brown mb-3"><i class="bi bi-shield-lock text-warning me-2"></i> Role Access Summary (Logged in as: <?= strtoupper($_SESSION['role']) ?>)</h5>
            <p>You have been authenticated with <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> privileges. Available modules for your role:</p>
            <ul>
                <?php if (in_array($_SESSION['role'], ['admin', 'hr'])): ?>
                <li><strong>HR Management:</strong> View/register staff members, maintain departmental records, and record training needs.</li>
                <?php endif; ?>

                <?php if (in_array($_SESSION['role'], ['admin', 'ict'])): ?>
                <li><strong>ICT Skills Catalogue:</strong> Add ICT skill requirements, conduct staff competency evaluations, and analyze skills gaps.</li>
                <?php endif; ?>

                <?php if (in_array($_SESSION['role'], ['admin', 'procurement'])): ?>
                <li><strong>Procurement Module:</strong> Request training resources, equipment specifications, and track fulfillment status.</li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'staff'): ?>
                <li><strong>Staff Portal:</strong> View personal assessment evaluations and institutional training programs.</li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><strong class="text-danger">Admin User & Credential Management:</strong> Create new user accounts, assign login credentials, set permissions, and <strong>Reset User Passwords</strong>.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>