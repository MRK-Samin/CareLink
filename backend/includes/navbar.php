<div class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-heartbeat"></i>
        <span>CareLink HMS</span>
    </div>
    <div class="navbar-menu">
        <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="patients.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'patients.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-injured"></i> Patients
        </a>
        <a href="doctors.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'doctors.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-md"></i> Doctors
        </a>
        <a href="laboratory.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'laboratory.php' ? 'active' : ''; ?>">
            <i class="fas fa-flask"></i> Laboratory
        </a>
        <a href="pharmacy.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'pharmacy.php' ? 'active' : ''; ?>">
            <i class="fas fa-pills"></i> Pharmacy
        </a>
        <a href="prescriptions.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'prescriptions.php' ? 'active' : ''; ?>">
            <i class="fas fa-prescription"></i> Prescriptions
        </a>
        <a href="vitals.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'vitals.php' ? 'active' : ''; ?>">
            <i class="fas fa-heartbeat"></i> Vitals
        </a>
        <a href="surgery.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'surgery.php' ? 'active' : ''; ?>">
            <i class="fas fa-syringe"></i> Surgery
        </a>
    </div>
    <div class="navbar-user">
        <span><?php echo $_SESSION['admin_name']; ?></span>
        <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>