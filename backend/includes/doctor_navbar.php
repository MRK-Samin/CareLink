<div class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-heartbeat"></i>
        <span>CareLink HMS - Doctor</span>
    </div>
    <div class="navbar-menu">
        <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="my_patients.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'my_patients.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-injured"></i> My Patients
        </a>
        <a href="add_prescription.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'add_prescription.php' ? 'active' : ''; ?>">
            <i class="fas fa-prescription"></i> Prescriptions
        </a>
        <a href="add_lab_test.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'add_lab_test.php' ? 'active' : ''; ?>">
            <i class="fas fa-flask"></i> Lab Tests
        </a>
        <a href="my_schedule.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'my_schedule.php' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt"></i> My Schedule
        </a>
        <a href="patient_vitals.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'patient_vitals.php' ? 'active' : ''; ?>">
            <i class="fas fa-heartbeat"></i> Vitals
        </a>
    </div>
    <div class="navbar-user">
        <span>Dr. <?php echo $_SESSION['doctor_name']; ?></span>
        <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>