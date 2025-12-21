<?php
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    header("Location: prescriptions.php");
    exit();
}

$pres_id = mysqli_real_escape_string($conn, $_GET['id']);
$prescription_query = mysqli_query($conn, "SELECT * FROM his_prescriptions WHERE pres_id = '$pres_id'");

if (mysqli_num_rows($prescription_query) == 0) {
    header("Location: prescriptions.php");
    exit();
}

$prescription = mysqli_fetch_assoc($prescription_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - <?php echo $prescription['pres_number']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 40px; line-height: 1.6; }
        .prescription-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        .prescription-header h1 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .prescription-header p {
            color: #666;
        }
        .prescription-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-item strong {
            display: inline-block;
            width: 150px;
            color: #333;
        }
        .prescription-content {
            margin: 30px 0;
            padding: 25px;
            border: 2px solid #667eea;
            border-radius: 10px;
        }
        .prescription-content h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .prescription-content ul {
            padding-left: 20px;
        }
        .prescription-content li {
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
        }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Print
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    <div class="prescription-header">
        <h1><i class="fas fa-hospital"></i> CareLink Hospital</h1>
        <p>Excellence in Healthcare Management</p>
        <p style="margin-top: 5px; font-size: 14px;">Dhaka, Bangladesh | Tel: +880 1234-567890</p>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #667eea; font-size: 28px;">PRESCRIPTION</h2>
        <p style="color: #666; margin-top: 5px;">Prescription #: <?php echo $prescription['pres_number']; ?></p>
        <p style="color: #666;">Date: <?php echo date('F d, Y', strtotime($prescription['pres_date'])); ?></p>
    </div>

    <div class="prescription-info">
        <div>
            <div class="info-item">
                <strong>Patient Name:</strong> <?php echo $prescription['pres_pat_name']; ?>
            </div>
            <div class="info-item">
                <strong>Patient Number:</strong> <?php echo $prescription['pres_pat_number']; ?>
            </div>
            <div class="info-item">
                <strong>Age:</strong> <?php echo $prescription['pres_pat_age']; ?> years
            </div>
        </div>
        <div>
            <div class="info-item">
                <strong>Patient Type:</strong> <?php echo $prescription['pres_pat_type']; ?>
            </div>
            <div class="info-item">
                <strong>Diagnosis:</strong> <?php echo $prescription['pres_pat_ailment']; ?>
            </div>
            <div class="info-item">
                <strong>Address:</strong> <?php echo $prescription['pres_pat_addr']; ?>
            </div>
        </div>
    </div>

    <div class="prescription-content">
        <h2><i class="fas fa-pills"></i> Prescription Instructions</h2>
        <?php echo $prescription['pres_ins']; ?>
    </div>

    <div style="margin-top: 80px; text-align: right;">
        <div style="border-top: 2px solid #333; width: 200px; margin-left: auto; padding-top: 10px;">
            <strong>Doctor's Signature</strong>
        </div>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> CareLink Hospital Management System</p>
        <p style="font-size: 12px; margin-top: 5px;">This is a computer-generated prescription</p>
    </div>
</body>
</html>
?>