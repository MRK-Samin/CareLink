<?php
$page_title = "Pharmacy";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$pharmaceuticals = mysqli_query($conn, "SELECT * FROM his_pharmaceuticals ORDER BY phar_id DESC");

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $pharmaceuticals = mysqli_query($conn, "SELECT * FROM his_pharmaceuticals WHERE 
        phar_name LIKE '%$search%' OR 
        phar_bcode LIKE '%$search%' OR 
        phar_cat LIKE '%$search%' OR 
        phar_vendor LIKE '%$search%'
        ORDER BY phar_id DESC");
}

// Get low stock items
$low_stock = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_pharmaceuticals WHERE CAST(phar_qty AS UNSIGNED) < 1000");
$low_stock_count = mysqli_fetch_assoc($low_stock)['total'];
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-pills"></i> Pharmacy Management</h1>
            <p>Manage pharmaceutical inventory and medicines</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <?php if($low_stock_count > 0): ?>
                <a href="pharmacy.php?filter=low_stock" class="btn btn-warning">
                    <i class="fas fa-exclamation-triangle"></i> Low Stock (<?php echo $low_stock_count; ?>)
                </a>
            <?php endif; ?>
            <a href="add_pharmaceutical.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Medicine
            </a>
        </div>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by medicine name, barcode, category, vendor..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(isset($_GET['search'])): ?>
                <a href="pharmacy.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
    </div>
  
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> All Pharmaceuticals (<?php echo mysqli_num_rows($pharmaceuticals); ?>)</h2>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Barcode</th>
                        <th>Category</th>
                        <th>Vendor</th>
                        <th>Quantity</th>
                        <th>Stock Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($pharmaceuticals) > 0): ?>
                        <?php while ($pharma = mysqli_fetch_assoc($pharmaceuticals)): 
                            $qty = intval($pharma['phar_qty']);
                            $stock_status = $qty < 500 ? 'danger' : ($qty < 1000 ? 'warning' : 'success');
                            $stock_text = $qty < 500 ? 'Critical' : ($qty < 1000 ? 'Low' : 'Available');
                        ?>
                        <tr>
                            <td><strong><?php echo $pharma['phar_name']; ?></strong></td>
                            <td><?php echo $pharma['phar_bcode']; ?></td>
                            <td><span class="badge badge-info"><?php echo $pharma['phar_cat']; ?></span></td>
                            <td><?php echo $pharma['phar_vendor']; ?></td>
                            <td><strong><?php echo number_format($qty); ?></strong> units</td>
                            <td>
                                <span class="badge badge-<?php echo $stock_status; ?>">
                                    <i class="fas fa-<?php echo $qty < 500 ? 'exclamation-triangle' : ($qty < 1000 ? 'exclamation-circle' : 'check-circle'); ?>"></i>
                                    <?php echo $stock_text; ?>
                                </span>
                            </td>
                            <td>
                                <a href="view_pharmaceutical.php?id=<?php echo $pharma['phar_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_pharmaceutical.php?id=<?php echo $pharma['phar_id']; ?>" class="btn btn-sm btn-success" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_pharmaceutical.php?id=<?php echo $pharma['phar_id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this medicine?');"
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-pills" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                No pharmaceuticals found. <?php if(isset($_GET['search'])): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>