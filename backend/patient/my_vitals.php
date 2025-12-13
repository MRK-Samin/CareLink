<?php
$page_title = "My Vitals";
require_once '../includes/patient_header.php';
require_once '../includes/patient_navbar.php';

$patient_number = $_SESSION['patient_number'];

$vitals = mysqli_query($conn, "SELECT * FROM his_vitals 
                               WHERE vit_pat_number = '$patient_number' 
                               ORDER BY vit_daterec DESC");

// Get vital statistics
$total = mysqli_num_rows($vitals);

// Get latest vitals
$latest_vitals = mysqli_query($conn, "SELECT * FROM his_vitals 
                                      WHERE vit_pat_number = '$patient_number' 
                                      ORDER BY vit_daterec DESC LIMIT 1");
$latest = mysqli_fetch_assoc($latest_vitals);
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-heartbeat"></i> My Vital Signs</h1>
            <p>Track your health metrics and vital signs over time</p>
        </div>
    </div>

    <?php if($latest): ?>
    <!-- Latest Vitals Display -->
    <div class="content-card" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1)); border: 2px solid #667eea; margin-bottom: 30px;">
        <div class="card-header" style="border-bottom: 2px solid #667eea;">
            <h2><i class="fas fa-chart-line"></i> Latest Vital Signs</h2>
            <span style="color: #667eea; font-weight: 600;">
                <i class="fas fa-clock"></i> <?php echo date('M d, Y - h:i A', strtotime($latest['vit_daterec'])); ?>
            </span>
        </div>
        <div style="padding: 30px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <?php 
                $temp = floatval($latest['vit_bodytemp']);
                $heart_rate = intval($latest['vit_heartpulse']);
                $resp_rate = intval($latest['vit_resprate']);
                $bp_parts = explode('/', $latest['vit_bloodpress']);
                $systolic = isset($bp_parts[0]) ? intval($bp_parts[0]) : 120;
                
                // Temperature status
                $temp_status = 'success';
                $temp_icon = 'check-circle';
                if($temp > 100.4) {
                    $temp_status = 'danger';
                    $temp_icon = 'exclamation-triangle';
                } elseif($temp > 99) {
                    $temp_status = 'warning';
                    $temp_icon = 'exclamation-circle';
                }
                
                // Heart rate status
                $hr_status = 'success';
                $hr_icon = 'check-circle';
                if($heart_rate > 100) {
                    $hr_status = 'danger';
                    $hr_icon = 'exclamation-triangle';
                } elseif($heart_rate > 90) {
                    $hr_status = 'warning';
                    $hr_icon = 'exclamation-circle';
                }
                
                // Blood pressure status
                $bp_status = 'success';
                $bp_icon = 'check-circle';
                if($systolic > 140) {
                    $bp_status = 'danger';
                    $bp_icon = 'exclamation-triangle';
                } elseif($systolic > 130) {
                    $bp_status = 'warning';
                    $bp_icon = 'exclamation-circle';
                }
                ?>
                
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-thermometer-half" style="font-size: 40px; color: #667eea; margin-bottom: 15px;"></i>
                    <div style="font-size: 32px; font-weight: bold; color: #333; margin-bottom: 5px;">
                        <?php echo $latest['vit_bodytemp']; ?>°F
                    </div>
                    <div style="color: #666; margin-bottom: 10px;">Body Temperature</div>
                    <span class="badge badge-<?php echo $temp_status; ?>">
                        <i class="fas fa-<?php echo $temp_icon; ?>"></i>
                        <?php echo $temp_status == 'success' ? 'Normal' : ($temp_status == 'warning' ? 'Elevated' : 'High'); ?>
                    </span>
                </div>
                
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-heartbeat" style="font-size: 40px; color: #f5576c; margin-bottom: 15px;"></i>
                    <div style="font-size: 32px; font-weight: bold; color: #333; margin-bottom: 5px;">
                        <?php echo $latest['vit_heartpulse']; ?> bpm
                    </div>
                    <div style="color: #666; margin-bottom: 10px;">Heart Rate</div>
                    <span class="badge badge-<?php echo $hr_status; ?>">
                        <i class="fas fa-<?php echo $hr_icon; ?>"></i>
                        <?php echo $hr_status == 'success' ? 'Normal' : ($hr_status == 'warning' ? 'Elevated' : 'High'); ?>
                    </span>
                </div>
                
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-lungs" style="font-size: 40px; color: #43e97b; margin-bottom: 15px;"></i>
                    <div style="font-size: 32px; font-weight: bold; color: #333; margin-bottom: 5px;">
                        <?php echo $latest['vit_resprate']; ?>/min
                    </div>
                    <div style="color: #666; margin-bottom: 10px;">Respiratory Rate</div>
                    <span class="badge badge-<?php echo ($resp_rate >= 12 && $resp_rate <= 20) ? 'success' : 'warning'; ?>">
                        <i class="fas fa-<?php echo ($resp_rate >= 12 && $resp_rate <= 20) ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                        <?php echo ($resp_rate >= 12 && $resp_rate <= 20) ? 'Normal' : 'Abnormal'; ?>
                    </span>
                </div>
                
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-tint" style="font-size: 40px; color: #4facfe; margin-bottom: 15px;"></i>
                    <div style="font-size: 32px; font-weight: bold; color: #333; margin-bottom: 5px;">
                        <?php echo $latest['vit_bloodpress']; ?>
                    </div>
                    <div style="color: #666; margin-bottom: 10px;">Blood Pressure (mmHg)</div>
                    <span class="badge badge-<?php echo $bp_status; ?>">
                        <i class="fas fa-<?php echo $bp_icon; ?>"></i>
                        <?php echo $bp_status == 'success' ? 'Normal' : ($bp_status == 'warning' ? 'Elevated' : 'High'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Vitals History -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-history"></i> Vitals History (<?php echo $total; ?> Records)</h2>
        </div>
        <?php if($total > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Temperature</th>
                        <th>Heart Rate</th>
                        <th>Resp. Rate</th>
                        <th>Blood Pressure</th>
                        <th>Overall Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    mysqli_data_seek($vitals, 0); // Reset pointer
                    while ($vital = mysqli_fetch_assoc($vitals)): 
                        $temp = floatval($vital['vit_bodytemp']);
                        $heart_rate = intval($vital['vit_heartpulse']);
                        $bp_parts = explode('/', $vital['vit_bloodpress']);
                        $systolic = isset($bp_parts[0]) ? intval($bp_parts[0]) : 120;
                        
                        $overall_status = 'success';
                        if($temp > 100 || $systolic > 140 || $heart_rate > 100) {
                            $overall_status = 'danger';
                        } elseif($temp > 99 || $systolic > 130 || $heart_rate > 90) {
                            $overall_status = 'warning';
                        }
                    ?>
                    <tr>
                        <td>
                            <strong><?php echo date('M d, Y', strtotime($vital['vit_daterec'])); ?></strong><br>
                            <small style="color: #999;"><?php echo date('h:i A', strtotime($vital['vit_daterec'])); ?></small>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $temp > 100 ? 'danger' : ($temp > 99 ? 'warning' : 'info'); ?>">
                                <?php echo $vital['vit_bodytemp']; ?>°F
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $heart_rate > 100 ? 'danger' : ($heart_rate > 90 ? 'warning' : 'info'); ?>">
                                <?php echo $vital['vit_heartpulse']; ?> bpm
                            </span>
                        </td>
                        <td><?php echo $vital['vit_resprate']; ?>/min</td>
                        <td>
                            <span class="badge badge-<?php echo $systolic > 140 ? 'danger' : ($systolic > 130 ? 'warning' : 'info'); ?>">
                                <?php echo $vital['vit_bloodpress']; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $overall_status; ?>">
                                <i class="fas fa-<?php echo $overall_status == 'danger' ? 'exclamation-triangle' : ($overall_status == 'warning' ? 'exclamation-circle' : 'check-circle'); ?>"></i>
                                <?php echo $overall_status == 'danger' ? 'Critical' : ($overall_status == 'warning' ? 'Warning' : 'Normal'); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 60px 20px; color: #999;">
            <i class="fas fa-heartbeat" style="font-size: 64px; margin-bottom: 20px; display: block; opacity: 0.5;"></i>
            <h3 style="margin-bottom: 10px; color: #666;">No Vital Records Yet</h3>
            <p>Your vital signs will be recorded during your visits to the hospital.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Vital Signs Reference Guide -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-book-medical"></i> Understanding Your Vital Signs</h2>
        </div>
        <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid #667eea;">
                    <h3 style="color: #667eea; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-thermometer-half"></i> Body Temperature
                    </h3>
                    <p style="margin: 5px 0; font-size: 14px;"><strong>Normal:</strong> 97.8°F - 99.1°F</p>
                    <p style="margin: 5px 0; font-size: 14px;"><strong>Fever:</strong> Above 100.4°F</p>
                    <p style="margin: 10px 0 0 0; font-size: 13px; color: #666;">
                        Temperature varies slightly throughout the day. It's usually lowest in the morning and highest in late afternoon.
                    </p>
                </div>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid #f5576c;">
                    <h3 style="color: #f5576c; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-heartbeat"></i> Heart Rate
                    </h3>
                    <p style="margin: 5px 0; font-size: 14px;"><strong>Normal:</strong> 60-100 beats/min</p>
                    <p style="margin: 5px 0; font-size: 14px;"><strong>Tachycardia:</strong> Above 100 bpm</p>
                    <p style="margin: 10px 0 0 0; font-size: 13px; color: #666;">
                        Heart rate increases with activity, stress, or illness. Athletes may have lower resting rates.
                    </p>
                </div>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid #43e97b;">
                    <h3 style="color: #43e97b; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-lungs"></i> Respiratory Rate
                    </h3>
                    <p style="margin: 5px 0; font-size: 14px;"><strong>Normal:</strong> 12-20 breaths/min</p>
                    <p style="margin: 5px 0; font-size: 14px;"><strong>Abnormal:</strong> Below 12 or above 20</p>
                    <p style="margin: 10px 0 0 0; font-size: 13px; color: #666;">
                        Breathing rate increases with exercise, fever, or respiratory conditions.
                    </p>
                </div>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid #4facfe;">
                    <h3 style="color: #4facfe; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-tint"></i> Blood Pressure
                    </h3>
                    <p style="margin: 5px 0; font-size: 14px;"><strong>Normal:</strong> 120/80 mmHg</p>
                    <p style="margin: 5px 0; font-size: 14px;"><strong>High:</strong> Above 140/90 mmHg</p>
                    <p style="margin: 10px 0 0 0; font-size: 13px; color: #666;">
                        First number (systolic) is pressure when heart beats. Second (diastolic) is pressure between beats.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php if($total > 0): ?>
    <!-- Health Tips -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-lightbulb"></i> Tips for Healthy Vitals</h2>
        </div>
        <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div style="background: #e8f4fd; padding: 20px; border-radius: 12px;">
                    <h3 style="color: #4facfe; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-apple-alt"></i> Healthy Diet
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                        <li>Reduce salt intake for blood pressure</li>
                        <li>Eat heart-healthy foods</li>
                        <li>Stay hydrated</li>
                        <li>Limit caffeine and alcohol</li>
                    </ul>
                </div>
                
                <div style="background: #e8f8f5; padding: 20px; border-radius: 12px;">
                    <h3 style="color: #43e97b; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-running"></i> Regular Exercise
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                        <li>30 minutes daily activity</li>
                        <li>Walking, swimming, or cycling</li>
                        <li>Helps control weight</li>
                        <li>Improves heart health</li>
                    </ul>
                </div>
                
                <div style="background: #fff4e6; padding: 20px; border-radius: 12px;">
                    <h3 style="color: #fa709a; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-bed"></i> Quality Sleep
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                        <li>7-9 hours per night</li>
                        <li>Maintain sleep schedule</li>
                        <li>Avoid screens before bed</li>
                        <li>Keep bedroom cool and dark</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>