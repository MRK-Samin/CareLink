<div class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-heartbeat"></i>
        <span>CareLink HMS - Patient</span>
    </div>
    <div class="navbar-menu">
        <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="my_prescriptions.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'my_prescriptions.php' ? 'active' : ''; ?>">
            <i class="fas fa-prescription"></i> Prescriptions
        </a>
        <a href="my_lab_tests.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'my_lab_tests.php' ? 'active' : ''; ?>">
            <i class="fas fa-flask"></i> Lab Tests
        </a>
        <a href="my_vitals.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'my_vitals.php' ? 'active' : ''; ?>">
            <i class="fas fa-heartbeat"></i> Vitals
        </a>
        <a href="my_medical_records.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'my_medical_records.php' ? 'active' : ''; ?>">
            <i class="fas fa-file-medical"></i> Medical Records
        </a>
        <a href="my_profile.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'my_profile.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-circle"></i> My Profile
        </a>
    </div>
    <div class="navbar-user">
        <span><?php echo $_SESSION['patient_name']; ?> (<?php echo $_SESSION['patient_number']; ?>)</span>
        <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>