<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_role(['admin', 'procurement']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = trim($_POST['item_name']);
    $category = trim($_POST['category']);
    $quantity = (int)$_POST['quantity'];
    $estimated_cost = (float)$_POST['estimated_cost'];

    $stmt = $pdo->prepare("INSERT INTO procurement_resources (item_name, category, quantity, estimated_cost, requested_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$item_name, $category, $quantity, $estimated_cost, $_SESSION['username']]);
    header('Location: resources.php');
    exit;
}

$resources = $pdo->query("SELECT * FROM procurement_resources ORDER BY created_at DESC")->fetchAll();

include '../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-kstvet-brown"><i class="bi bi-cart-check text-warning me-2"></i> Procurement Training Resources</h2>
    <button class="btn btn-kstvet-brown" data-bs-toggle="modal" data-bs-target="#addResourceModal"><i class="bi bi-plus-lg"></i> Request New Resource</button>
</div>

<div class="card p-3 shadow-sm">
    <table class="table table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Resource / Equipment Item</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Estimated Cost (KES)</th>
                <th>Fulfillment Status</th>
                <th>Requested By</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resources as $res): ?>
            <tr>
                <td><strong><?= htmlspecialchars($res['item_name']) ?></strong></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($res['category']) ?></span></td>
                <td><?= $res['quantity'] ?></td>
                <td>KES <?= number_format($res['estimated_cost'], 2) ?></td>
                <td><span class="badge bg-<?= $res['status'] == 'procured' ? 'success' : ($res['status'] == 'approved' ? 'info' : 'warning') ?>"><?= strtoupper($res['status']) ?></span></td>
                <td><?= htmlspecialchars($res['requested_by']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="addResourceModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-kstvet-brown text-white">
                <h5 class="modal-title">Request Training Equipment / Resource</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Item / Equipment Description</label>
                    <input type="text" name="item_name" class="form-control" placeholder="e.g. Projector or Laptops" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Resource Category</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g. ICT Hardware / Training Books" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Quantity Required</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estimated Cost (KES)</label>
                    <input type="number" step="0.01" name="estimated_cost" class="form-control" placeholder="0.00" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-kstvet-brown">Submit Request</button>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>