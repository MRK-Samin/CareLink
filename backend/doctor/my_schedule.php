<?php
$page_title = "My Schedule";
require_once '../includes/doctor_header.php';
require_once '../includes/doctor_navbar.php';

$doctor_number = $_SESSION['doctor_number'];
$doctor_name = $_SESSION['doctor_name'];
$doctor_dept = $_SESSION['doctor_dept'];

// Get today's date
$today = date('Y-m-d');
$current_day = date('l'); // Day name (Monday, Tuesday, etc.)

// Get upcoming surgeries for this doctor
$upcoming_surgeries = mysqli_query($conn, "SELECT s.*, p.pat_phone, p.pat_type 
                                            FROM his_surgery s 
                                            LEFT JOIN his_patients p ON s.s_pat_number = p.pat_number 
                                            WHERE s.s_doc LIKE '%{$_SESSION['doctor_name']}%' 
                                            AND DATE(s.s_pat_date) >= '$today'
                                            ORDER BY s.s_pat_date ASC");

// Get recent prescriptions written by this doctor
$recent_prescriptions = mysqli_query($conn, "SELECT * FROM his_prescriptions 
                                             ORDER BY pres_date DESC LIMIT 5");

// Get pending lab tests
$pending_labs = mysqli_query($conn, "SELECT * FROM his_laboratory 
                                     WHERE (lab_pat_results IS NULL OR lab_pat_results = '') 
                                     ORDER BY lab_date_rec DESC LIMIT 5");

// Get statistics for today
$today_appointments = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_patients WHERE DATE(pat_date_joined) = '$today'");
$today_count = mysqli_fetch_assoc($today_appointments)['count'];

$today_surgeries = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_surgery 
                                        WHERE s_doc LIKE '%{$_SESSION['doctor_name']}%' 
                                        AND DATE(s_pat_date) = '$today'");
$surgery_count = mysqli_fetch_assoc($today_surgeries)['count'];
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-calendar-alt"></i> My Schedule</h1>
            <p><?php echo date('l, F d, Y'); ?> - Department: <?php echo $doctor_dept; ?></p>
        </div>
    </div>

    <!-- Today's Statistics -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-value"><?php echo $today_count; ?></div>
            <div class="stat-label">Today's Patients</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                <i class="fas fa-syringe"></i>
            </div>
            <div class="stat-value"><?php echo $surgery_count; ?></div>
            <div class="stat-label">Today's Surgeries</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <i class="fas fa-flask"></i>
            </div>
            <div class="stat-value"><?php echo mysqli_num_rows($pending_labs); ?></div>
            <div class="stat-label">Pending Lab Results</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value"><?php echo date('h:i A'); ?></div>
            <div class="stat-label">Current Time</div>
        </div>
    </div>

    <!-- Weekly Schedule -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-calendar-week"></i> Weekly Schedule</h2>
        </div>
        <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                <?php 
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $times = [
                    'Monday' => '9:00 AM - 5:00 PM',
                    'Tuesday' => '9:00 AM - 5:00 PM',
                    'Wednesday' => '9:00 AM - 5:00 PM',
                    'Thursday' => '9:00 AM - 5:00 PM',
                    'Friday' => '9:00 AM - 2:00 PM',
                    'Saturday' => 'Emergency Only',
                    'Sunday' => 'Off'
                ];
                
                foreach($days as $day): 
                    $is_today = ($day == $current_day);
                ?>
                <div style="background: <?php echo $is_today ? 'linear-gradient(135deg, #667eea, #764ba2)' : '#f8f9fa'; ?>; 
                            padding: 20px; border-radius: 12px; text-align: center;
                            color: <?php echo $is_today ? 'white' : '#333'; ?>; 
                            box-shadow: <?php echo $is_today ? '0 8px 20px rgba(102, 126, 234, 0.3)' : 'none'; ?>;">
                    <div style="font-weight: bold; margin-bottom: 8px; font-size: 16px;">
                        <?php echo $day; ?>
                        <?php if($is_today): ?>
                            <i class="fas fa-star" style="font-size: 12px;"></i>
                        <?php endif; ?>
                    </div>
                    <div style="font-size: 13px; opacity: 0.9;">
                        <?php echo $times[$day]; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Upcoming Surgeries -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-syringe"></i> Upcoming Surgeries</h2>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Surgery #</th>
                        <th>Patient Name</th>
                        <th>Patient #</th>
                        <th>Procedure</th>
                        <th>Scheduled Date/Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($upcoming_surgeries) > 0): ?>
                        <?php while ($surgery = mysqli_fetch_assoc($upcoming_surgeries)): ?>
                        <tr>
                            <td><strong><?php echo $surgery['s_number']; ?></strong></td>
                            <td><?php echo $surgery['s_pat_name']; ?></td>
                            <td><?php echo $surgery['s_pat_number']; ?></td>
                            <td><?php echo $surgery['s_pat_ailment']; ?></td>
                            <td>
                                <strong><?php echo date('M d, Y', strtotime($surgery['s_pat_date'])); ?></strong><br>
                                <small><?php echo date('h:i A', strtotime($surgery['s_pat_date'])); ?></small>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $surgery['s_pat_status'] == 'Successful' ? 'success' : 'warning'; ?>">
                                    <?php echo $surgery['s_pat_status'] ? $surgery['s_pat_status'] : 'Scheduled'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                                <i class="fas fa-calendar-check" style="font-size: 36px; margin-bottom: 10px; display: block;"></i>
                                No upcoming surgeries scheduled
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 30px;">
        <!-- Recent Prescriptions -->
        <div class="content-card">
            <div class="card-header">
                <h2><i class="fas fa-prescription"></i> Recent Prescriptions</h2>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Prescription #</th>
                            <th>Patient</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($recent_prescriptions) > 0): ?>
                            <?php while ($pres = mysqli_fetch_assoc($recent_prescriptions)): ?>
                            <tr>
                                <td><strong><?php echo $pres['pres_number']; ?></strong></td>
                                <td><?php echo $pres['pres_pat_name']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($pres['pres_date'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px; color: #999;">
                                    No recent prescriptions
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Lab Tests -->
        <div class="content-card">
            <div class="card-header">
                <h2><i class="fas fa-flask"></i> Pending Lab Results</h2>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Lab #</th>
                            <th>Patient</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($pending_labs) > 0): ?>
                            <?php while ($lab = mysqli_fetch_assoc($pending_labs)): ?>
                            <tr>
                                <td><strong><?php echo $lab['lab_number']; ?></strong></td>
                                <td><?php echo $lab['lab_pat_name']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($lab['lab_date_rec'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 20px; color: #999;">
                                    No pending lab tests
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>