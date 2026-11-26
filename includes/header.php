<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSTVET - Staff ICT Skills & Training Needs Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-light">
<?php if (isset($_SESSION['user_id'])): ?>
<!-- KSTVET Official Brand Header Bar (Brown & White) -->
<div class="bg-kstvet-brown text-white py-2 px-3 border-bottom border-warning border-3 shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white text-kstvet-brown fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; font-size: 14px; border: 2px solid #D4AF37;">
                KSTVET
            </div>
            <div>
                <span class="fw-bold tracking-wide d-block leading-tight text-uppercase">KENYA SCHOOL OF TVET</span>
                <small class="text-warning small-text fw-semibold">The Home of Technical Education</small>
            </div>
        </div>
        <div class="text-end small d-none d-md-block">
            <span class="text-light opacity-90"><i class="bi bi-geo-alt-fill text-warning"></i> Gigiri, Nairobi, Kenya | Tel: +254 707444222</span>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar Navigation -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-white sidebar shadow-sm min-vh-100 p-0 border-end">
            <div class="position-sticky pt-3">
                <div class="px-3 pb-3 border-bottom mb-3 text-center">
                    <div class="mb-2">
                        <i class="bi bi-person-circle fs-1 text-kstvet-brown"></i>
                    </div>
                    <h6 class="fw-bold text-kstvet-brown mb-0"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></h6>
                    <span class="badge bg-kstvet-brown text-white px-2 py-1 mt-1"><?= strtoupper($_SESSION['role']) ?> ROLE</span>
                </div>
                
                <ul class="nav flex-column px-2">
                    <li class="nav-item mb-1">
                        <a class="nav-link text-kstvet-brown rounded active-nav" href="/dashboard.php">
                            <i class="bi bi-speedometer2 me-2 text-warning"></i> Dashboard
                        </a>
                    </li>
                    
                    <?php if (in_array($_SESSION['role'], ['admin', 'hr'])): ?>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-kstvet-brown rounded" href="/hr/staff_list.php">
                            <i class="bi bi-people-fill me-2 text-warning"></i> Staff Records
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-kstvet-brown rounded" href="/hr/training_needs.php">
                            <i class="bi bi-card-checklist me-2 text-warning"></i> Training Needs
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (in_array($_SESSION['role'], ['admin', 'ict'])): ?>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-kstvet-brown rounded" href="/ict/skills_list.php">
                            <i class="bi bi-journal-code me-2 text-warning"></i> ICT Skills Catalogue
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-kstvet-brown rounded" href="/ict/assessment_add.php">
                            <i class="bi bi-clipboard-check-fill me-2 text-warning"></i> Record Assessment
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (in_array($_SESSION['role'], ['admin', 'procurement'])): ?>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-kstvet-brown rounded" href="/procurement/resources.php">
                            <i class="bi bi-cart-check-fill me-2 text-warning"></i> Procurement Resources
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item mb-1">
                        <a class="nav-link text-kstvet-brown rounded" href="/ict/gap_analysis.php">
                            <i class="bi bi-bar-chart-line-fill me-2 text-warning"></i> Skills Gap Analysis
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link text-kstvet-brown rounded" href="/reports/summary.php">
                            <i class="bi bi-file-earmark-pdf-fill me-2 text-warning"></i> Management Reports
                        </a>
                    </li>

                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item mb-1 mt-2 border-top pt-2">
                        <span class="px-3 text-muted small fw-bold text-uppercase">Admin Controls</span>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-kstvet-brown rounded" href="/admin/users.php">
                            <i class="bi bi-person-gear me-2 text-danger"></i> User & Password Admin
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item mt-4 border-top pt-2">
                        <a class="nav-link text-danger rounded" href="/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
<?php endif; ?>