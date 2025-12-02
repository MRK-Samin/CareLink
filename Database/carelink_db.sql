-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 02:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carelink_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `his_accounts`
--

CREATE TABLE `his_accounts` (
  `acc_id` int(11) NOT NULL,
  `acc_name` varchar(200) DEFAULT NULL,
  `acc_desc` text DEFAULT NULL,
  `acc_type` varchar(200) DEFAULT NULL,
  `acc_number` varchar(200) DEFAULT NULL,
  `acc_amount` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_accounts`
--

INSERT INTO `his_accounts` (`acc_id`, `acc_name`, `acc_desc`, `acc_type`, `acc_number`, `acc_amount`) VALUES
(1, 'Dutch Bangla Bank Limited', '<p>DBBL is one of the leading private commercial banks in Bangladesh, known for excellent customer service and innovative banking solutions.</p>', 'Receivable Account', '1234567890123', '2500000'),
(2, 'Islami Bank Bangladesh Limited', '<p>IBBL is the first Islamic bank in Southeast Asia, providing Shariah-based banking services with a wide network across Bangladesh.</p>', 'Payable Account', '9876543210987', '1800000'),
(3, 'Brac Bank Limited', '<p>A leading commercial bank focused on SME banking and retail services, serving millions of customers nationwide.</p>', 'Receivable Account', '4567891234567', '3200000');

-- --------------------------------------------------------

--
-- Table structure for table `his_admin`
--

CREATE TABLE `his_admin` (
  `ad_id` int(11) NOT NULL,
  `ad_fname` varchar(200) DEFAULT NULL,
  `ad_lname` varchar(200) DEFAULT NULL,
  `ad_email` varchar(200) DEFAULT NULL,
  `ad_pwd` varchar(200) DEFAULT NULL,
  `ad_dpic` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_admin`
--

INSERT INTO `his_admin` (`ad_id`, `ad_fname`, `ad_lname`, `ad_email`, `ad_pwd`, `ad_dpic`) VALUES
(1, 'Samin', 'Administrator', 'admin@ccbd.com', '5ac4e329a50ad57dc59acb814de0342021b76c67', 'doc-icon.png');

-- --------------------------------------------------------

--
-- Table structure for table `his_assets`
--

CREATE TABLE `his_assets` (
  `asst_id` int(11) NOT NULL,
  `asst_name` varchar(200) DEFAULT NULL,
  `asst_desc` longtext DEFAULT NULL,
  `asst_vendor` varchar(200) DEFAULT NULL,
  `asst_status` varchar(200) DEFAULT NULL,
  `asst_dept` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `his_docs`
--

CREATE TABLE `his_docs` (
  `doc_id` int(11) NOT NULL,
  `doc_fname` varchar(200) DEFAULT NULL,
  `doc_lname` varchar(200) DEFAULT NULL,
  `doc_email` varchar(200) DEFAULT NULL,
  `doc_pwd` varchar(200) DEFAULT NULL,
  `doc_dept` varchar(200) DEFAULT NULL,
  `doc_number` varchar(200) DEFAULT NULL,
  `doc_dpic` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_docs`
--

INSERT INTO `his_docs` (`doc_id`, `doc_fname`, `doc_lname`, `doc_email`, `doc_pwd`, `doc_dept`, `doc_number`, `doc_dpic`) VALUES
(1, 'Dr. Tanvir', 'Rahman', 'tanvir.rahman@carelink.com.bd', 'dce0b27ba675df41e9cc07af80ec59c475810824', 'Cardiology', 'DOC001', 'defaultimg.jpg'),
(2, 'Dr. Nusrat', 'Jahan', 'nusrat.jahan@carelink.com.bd', 'dce0b27ba675df41e9cc07af80ec59c475810824', 'Pediatrics', 'DOC002', 'defaultimg.jpg'),
(3, 'Dr. Mahmud', 'Hasan', 'mahmud.hasan@carelink.com.bd', 'dce0b27ba675df41e9cc07af80ec59c475810824', 'Orthopedics', 'DOC003', 'defaultimg.jpg'),
(4, 'Dr. Farhana', 'Sultana', 'farhana.sultana@carelink.com.bd', 'dce0b27ba675df41e9cc07af80ec59c475810824', 'Gynecology', 'DOC004', 'defaultimg.jpg'),
(5, 'Dr. Rafiqul', 'Islam', 'rafiqul.islam@carelink.com.bd', 'dce0b27ba675df41e9cc07af80ec59c475810824', 'Surgery | Theatre', 'DOC005', 'defaultimg.jpg'),
(6, 'Dr. Ayesha', 'Akter', 'ayesha.akter@carelink.com.bd', 'dce0b27ba675df41e9cc07af80ec59c475810824', 'Laboratory', 'DOC006', 'defaultimg.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `his_equipments`
--

CREATE TABLE `his_equipments` (
  `eqp_id` int(11) NOT NULL,
  `eqp_code` varchar(200) DEFAULT NULL,
  `eqp_name` varchar(200) DEFAULT NULL,
  `eqp_vendor` varchar(200) DEFAULT NULL,
  `eqp_desc` longtext DEFAULT NULL,
  `eqp_dept` varchar(200) DEFAULT NULL,
  `eqp_status` varchar(200) DEFAULT NULL,
  `eqp_qty` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_equipments`
--

INSERT INTO `his_equipments` (`eqp_id`, `eqp_code`, `eqp_name`, `eqp_vendor`, `eqp_desc`, `eqp_dept`, `eqp_status`, `eqp_qty`) VALUES
(1, 'EQP001', 'Digital Thermometer', 'Omron Bangladesh', '<p>Non-contact digital thermometers for fever screening, essential during dengue season.</p>', 'Emergency', 'Functioning', '50'),
(2, 'EQP002', 'Blood Pressure Monitor', 'Omron Bangladesh', '<p>Digital BP monitors for hypertension management, widely used in Bangladesh.</p>', 'Cardiology', 'Functioning', '30'),
(3, 'EQP003', 'Nebulizer Machine', 'Philips Bangladesh', '<p>For respiratory treatments, especially important during monsoon season.</p>', 'Pediatrics', 'Functioning', '20'),
(4, 'EQP004', 'ECG Machine', 'Fukuda Denshi', '<p>12-lead ECG machine for cardiac monitoring and diagnosis.</p>', 'Cardiology', 'Functioning', '5'),
(5, 'EQP005', 'Ultrasound Machine', 'GE Healthcare', '<p>Portable ultrasound for prenatal care and general diagnostics.</p>', 'Gynecology', 'Functioning', '3');

-- --------------------------------------------------------

--
-- Table structure for table `his_laboratory`
--

CREATE TABLE `his_laboratory` (
  `lab_id` int(11) NOT NULL,
  `lab_pat_name` varchar(200) DEFAULT NULL,
  `lab_pat_ailment` varchar(200) DEFAULT NULL,
  `lab_pat_number` varchar(200) DEFAULT NULL,
  `lab_pat_tests` longtext DEFAULT NULL,
  `lab_pat_results` longtext DEFAULT NULL,
  `lab_number` varchar(200) DEFAULT NULL,
  `lab_date_rec` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_laboratory`
--

INSERT INTO `his_laboratory` (`lab_id`, `lab_pat_name`, `lab_pat_ailment`, `lab_pat_number`, `lab_pat_tests`, `lab_pat_results`, `lab_number`, `lab_date_rec`) VALUES
(1, 'Fatema Begum', 'Dengue Fever', 'PAT002', '<ul><li>Complete Blood Count (CBC)</li><li>Platelet Count</li><li>Dengue NS1 Antigen Test</li><li>Dengue IgM/IgG Antibodies</li></ul>', '<ul><li>CBC: WBC 3,500/cumm (Low)</li><li>Platelet Count: 85,000/cumm (Low - Critical)</li><li>NS1 Antigen: Positive</li><li>IgM: Positive (Acute Infection)</li><li><strong>Diagnosis: Dengue Fever Confirmed</strong></li></ul>', 'LAB001', '2025-12-02 01:48:13'),
(2, 'Abdul Karim', 'Diabetes Mellitus Type 2', 'PAT003', '<ul><li>Fasting Blood Sugar (FBS)</li><li>HbA1c</li><li>Lipid Profile</li><li>Kidney Function Test</li></ul>', '<ul><li>FBS: 156 mg/dL (High)</li><li>HbA1c: 8.2% (Poorly Controlled)</li><li>Total Cholesterol: 240 mg/dL (High)</li><li>Creatinine: 1.1 mg/dL (Normal)</li><li><strong>Diagnosis: Uncontrolled Diabetes</strong></li></ul>', 'LAB002', '2025-12-02 01:48:13'),
(3, 'Kamal Uddin', 'Hypertension', 'PAT001', '<ul><li>Lipid Profile</li><li>ECG</li><li>Kidney Function Test</li></ul>', '<ul><li>Blood Pressure: 150/95 mmHg (High)</li><li>Cholesterol: 220 mg/dL (Borderline High)</li><li>ECG: Normal Sinus Rhythm</li><li>Creatinine: Normal</li><li><strong>Diagnosis: Stage 1 Hypertension</strong></li></ul>', 'LAB003', '2025-12-02 01:48:13');

-- --------------------------------------------------------

--
-- Table structure for table `his_medical_records`
--

CREATE TABLE `his_medical_records` (
  `mdr_id` int(11) NOT NULL,
  `mdr_number` varchar(200) DEFAULT NULL,
  `mdr_pat_name` varchar(200) DEFAULT NULL,
  `mdr_pat_adr` varchar(200) DEFAULT NULL,
  `mdr_pat_age` varchar(200) DEFAULT NULL,
  `mdr_pat_ailment` varchar(200) DEFAULT NULL,
  `mdr_pat_number` varchar(200) DEFAULT NULL,
  `mdr_pat_prescr` longtext DEFAULT NULL,
  `mdr_date_rec` timestamp(4) NOT NULL DEFAULT current_timestamp(4) ON UPDATE current_timestamp(4)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_medical_records`
--

INSERT INTO `his_medical_records` (`mdr_id`, `mdr_number`, `mdr_pat_name`, `mdr_pat_adr`, `mdr_pat_age`, `mdr_pat_ailment`, `mdr_pat_number`, `mdr_pat_prescr`, `mdr_date_rec`) VALUES
(1, 'MDR001', 'Fatema Begum', 'Village: Rampur, Upazila: Savar, Dhaka', '32', 'Dengue Fever', 'PAT002', '<ul><li>Admitted with high fever and body pain</li><li>Dengue NS1 test positive</li><li>Platelet count critically low at 85,000</li><li>IV fluid therapy initiated</li><li>Daily monitoring of vitals and platelet count</li><li>Patient advised strict bed rest</li></ul>', '2025-12-02 01:48:13.6496'),
(2, 'MDR002', 'Nasrin Sultana', 'Banani, Dhaka-1213', '36', 'Appendicitis', 'PAT006', '<ul><li>Emergency appendectomy performed</li><li>Surgery successful without complications</li><li>Post-operative recovery normal</li><li>Antibiotics administered</li><li>Discharge planned after 3 days</li></ul>', '2025-12-02 01:48:13.6496');

-- --------------------------------------------------------

--
-- Table structure for table `his_patients`
--

CREATE TABLE `his_patients` (
  `pat_id` int(11) NOT NULL,
  `pat_fname` varchar(200) DEFAULT NULL,
  `pat_lname` varchar(200) DEFAULT NULL,
  `pat_dob` varchar(200) DEFAULT NULL,
  `pat_age` varchar(200) DEFAULT NULL,
  `pat_number` varchar(200) DEFAULT NULL,
  `pat_addr` varchar(200) DEFAULT NULL,
  `pat_phone` varchar(200) DEFAULT NULL,
  `pat_type` varchar(200) DEFAULT NULL,
  `pat_date_joined` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `pat_ailment` varchar(200) DEFAULT NULL,
  `pat_discharge_status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_patients`
--

INSERT INTO `his_patients` (`pat_id`, `pat_fname`, `pat_lname`, `pat_dob`, `pat_age`, `pat_number`, `pat_addr`, `pat_phone`, `pat_type`, `pat_date_joined`, `pat_ailment`, `pat_discharge_status`) VALUES
(1, 'Kamal', 'Uddin', '15/03/1985', '39', 'PAT001', 'House 45, Road 12, Dhanmondi, Dhaka-1209', '01712345678', 'OutPatient', '2025-12-02 01:48:13.593098', 'Hypertension', NULL),
(2, 'Fatema', 'Begum', '22/07/1992', '32', 'PAT002', 'Village: Rampur, Upazila: Savar, Dhaka', '01823456789', 'InPatient', '2025-12-02 01:48:13.593098', 'Dengue Fever', NULL),
(3, 'Abdul', 'Karim', '10/11/1978', '46', 'PAT003', 'Flat 3B, Uttara Sector 7, Dhaka-1230', '01934567890', 'OutPatient', '2025-12-02 01:48:13.593098', 'Diabetes Mellitus Type 2', NULL),
(4, 'Shamima', 'Akter', '05/05/2000', '24', 'PAT004', 'House 12, Mohammadpur, Dhaka-1207', '01645678901', 'InPatient', '2025-12-02 01:48:13.593098', 'Pregnancy - 8 months', NULL),
(5, 'Rahim', 'Mia', '18/09/1995', '29', 'PAT005', 'Mirpur-10, Dhaka-1216', '01756789012', 'OutPatient', '2025-12-02 01:48:13.593098', 'Respiratory Infection', NULL),
(6, 'Nasrin', 'Sultana', '30/12/1988', '36', 'PAT006', 'Banani, Dhaka-1213', '01867890123', 'InPatient', '2025-12-02 01:48:13.593098', 'Appendicitis', NULL),
(7, 'Habibur', 'Rahman', '14/02/2010', '14', 'PAT007', 'Chittagong, Bangladesh', '01978901234', 'OutPatient', '2025-12-02 01:48:13.593098', 'Asthma', NULL),
(8, 'Roksana', 'Khatun', '25/08/1982', '42', 'PAT008', 'Sylhet, Bangladesh', '01589012345', 'InPatient', '2025-12-02 01:48:13.593098', 'Kidney Stone', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `his_patient_transfers`
--

CREATE TABLE `his_patient_transfers` (
  `t_id` int(11) NOT NULL,
  `t_hospital` varchar(200) DEFAULT NULL,
  `t_date` varchar(200) DEFAULT NULL,
  `t_pat_name` varchar(200) DEFAULT NULL,
  `t_pat_number` varchar(200) DEFAULT NULL,
  `t_status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_patient_transfers`
--

INSERT INTO `his_patient_transfers` (`t_id`, `t_hospital`, `t_date`, `t_pat_name`, `t_pat_number`, `t_status`) VALUES
(1, 'Dhaka Medical College Hospital', '2025-11-28', 'Roksana Khatun', 'PAT008', 'Success');

-- --------------------------------------------------------

--
-- Table structure for table `his_payrolls`
--

CREATE TABLE `his_payrolls` (
  `pay_id` int(11) NOT NULL,
  `pay_number` varchar(200) DEFAULT NULL,
  `pay_doc_name` varchar(200) DEFAULT NULL,
  `pay_doc_number` varchar(200) DEFAULT NULL,
  `pay_doc_email` varchar(200) DEFAULT NULL,
  `pay_emp_salary` varchar(200) DEFAULT NULL,
  `pay_date_generated` timestamp(4) NOT NULL DEFAULT current_timestamp(4) ON UPDATE current_timestamp(4),
  `pay_status` varchar(200) DEFAULT NULL,
  `pay_descr` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_payrolls`
--

INSERT INTO `his_payrolls` (`pay_id`, `pay_number`, `pay_doc_name`, `pay_doc_number`, `pay_doc_email`, `pay_emp_salary`, `pay_date_generated`, `pay_status`, `pay_descr`) VALUES
(1, 'PAY001', 'Dr. Tanvir Rahman', 'DOC001', 'tanvir.rahman@carelink.com.bd', '85000', '2025-12-02 01:48:13.6742', 'Paid', '<p>Monthly salary for December 2025. Cardiology specialist with 10 years experience.</p>'),
(2, 'PAY002', 'Dr. Nusrat Jahan', 'DOC002', 'nusrat.jahan@carelink.com.bd', '75000', '2025-12-02 01:48:13.6742', 'Paid', '<p>Monthly salary for December 2025. Pediatrics specialist.</p>'),
(3, 'PAY003', 'Dr. Mahmud Hasan', 'DOC003', 'mahmud.hasan@carelink.com.bd', '80000', '2025-12-02 01:48:13.6742', NULL, '<p>Monthly salary for December 2025. Orthopedic surgeon.</p>'),
(4, 'PAY004', 'Dr. Farhana Sultana', 'DOC004', 'farhana.sultana@carelink.com.bd', '78000', '2025-12-02 01:48:13.6742', NULL, '<p>Monthly salary for December 2025. Gynecology specialist.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `his_pharmaceuticals`
--

CREATE TABLE `his_pharmaceuticals` (
  `phar_id` int(11) NOT NULL,
  `phar_name` varchar(200) DEFAULT NULL,
  `phar_bcode` varchar(200) DEFAULT NULL,
  `phar_desc` longtext DEFAULT NULL,
  `phar_qty` varchar(200) DEFAULT NULL,
  `phar_cat` varchar(200) DEFAULT NULL,
  `phar_vendor` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_pharmaceuticals`
--

INSERT INTO `his_pharmaceuticals` (`phar_id`, `phar_name`, `phar_bcode`, `phar_desc`, `phar_qty`, `phar_cat`, `phar_vendor`) VALUES
(1, 'Napa (Paracetamol 500mg)', '8941100501238', '<p>Napa is the most popular paracetamol brand in Bangladesh, manufactured by Beximco Pharmaceuticals. Used for fever and pain relief.</p>', '5000', 'Antipyretics', 'Beximco Pharmaceuticals Ltd'),
(2, 'Ace (Paracetamol 500mg)', '8941100502456', '<p>Ace is Square Pharmaceuticals popular paracetamol brand. Widely trusted for fever and mild to moderate pain.</p>', '4500', 'Antipyretics', 'Square Pharmaceuticals Ltd'),
(3, 'Seclo (Omeprazole 20mg)', '8941100503789', '<p>Seclo is used to treat gastric acidity and ulcers. Very commonly prescribed in Bangladesh.</p>', '3000', 'Antibiotics', 'Square Pharmaceuticals Ltd'),
(4, 'Fexo (Fexofenadine 120mg)', '8941100504012', '<p>Antihistamine for allergies. Popular brand in Bangladesh for seasonal allergies.</p>', '2500', 'Antipyretics', 'Square Pharmaceuticals Ltd'),
(5, 'Zimax (Azithromycin 500mg)', '8941100505345', '<p>Broad-spectrum antibiotic commonly prescribed for respiratory and other bacterial infections.</p>', '2000', 'Antibiotics', 'Beximco Pharmaceuticals Ltd'),
(6, 'Novorapid (Insulin)', '8941100506678', '<p>Insulin for diabetes management. Essential medication for Type 1 diabetes patients.</p>', '800', 'Antidiabetics', 'Incepta Pharmaceuticals Ltd');

-- --------------------------------------------------------

--
-- Table structure for table `his_pharmaceuticals_categories`
--

CREATE TABLE `his_pharmaceuticals_categories` (
  `pharm_cat_id` int(11) NOT NULL,
  `pharm_cat_name` varchar(200) DEFAULT NULL,
  `pharm_cat_vendor` varchar(200) DEFAULT NULL,
  `pharm_cat_desc` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_pharmaceuticals_categories`
--

INSERT INTO `his_pharmaceuticals_categories` (`pharm_cat_id`, `pharm_cat_name`, `pharm_cat_vendor`, `pharm_cat_desc`) VALUES
(1, 'Antipyretics', 'Square Pharmaceuticals Ltd', '<p>Antipyretics are medications that reduce fever. Common in Bangladesh for treating viral fevers, dengue, and other conditions.</p>'),
(2, 'Antibiotics', 'Beximco Pharmaceuticals Ltd', '<p>Antibiotics fight bacterial infections. Widely prescribed in Bangladesh for various infections.</p>'),
(3, 'Antidiabetics', 'Incepta Pharmaceuticals Ltd', '<p>Medications for managing diabetes, a growing health concern in Bangladesh.</p>'),
(4, 'Antimalarials', 'Square Pharmaceuticals Ltd', '<p>Used to treat and prevent malaria, still prevalent in some regions of Bangladesh.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `his_prescriptions`
--

CREATE TABLE `his_prescriptions` (
  `pres_id` int(11) NOT NULL,
  `pres_pat_name` varchar(200) DEFAULT NULL,
  `pres_pat_age` varchar(200) DEFAULT NULL,
  `pres_pat_number` varchar(200) DEFAULT NULL,
  `pres_number` varchar(200) DEFAULT NULL,
  `pres_pat_addr` varchar(200) DEFAULT NULL,
  `pres_pat_type` varchar(200) DEFAULT NULL,
  `pres_date` timestamp(4) NOT NULL DEFAULT current_timestamp(4) ON UPDATE current_timestamp(4),
  `pres_pat_ailment` varchar(200) DEFAULT NULL,
  `pres_ins` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_prescriptions`
--

INSERT INTO `his_prescriptions` (`pres_id`, `pres_pat_name`, `pres_pat_age`, `pres_pat_number`, `pres_number`, `pres_pat_addr`, `pres_pat_type`, `pres_date`, `pres_pat_ailment`, `pres_ins`) VALUES
(1, 'Fatema Begum', '32', 'PAT002', 'PRE001', 'Village: Rampur, Upazila: Savar, Dhaka', 'InPatient', '2025-12-02 01:48:13.6288', 'Dengue Fever', '<ul><li>Napa (Paracetamol) 500mg - 1 tablet 3 times daily</li><li>Complete bed rest</li><li>Drink plenty of fluids (ORS, coconut water)</li><li>Monitor platelet count daily</li><li>Avoid aspirin and NSAIDs</li></ul>'),
(2, 'Abdul Karim', '46', 'PAT003', 'PRE002', 'Flat 3B, Uttara Sector 7, Dhaka-1230', 'OutPatient', '2025-12-02 01:48:13.6288', 'Diabetes Mellitus Type 2', '<ul><li>Metformin 500mg - 1 tablet twice daily after meals</li><li>Follow diabetic diet plan</li><li>Exercise 30 minutes daily</li><li>Check blood sugar weekly</li><li>Avoid sugary foods and drinks</li></ul>'),
(3, 'Rahim Mia', '29', 'PAT005', 'PRE003', 'Mirpur-10, Dhaka-1216', 'OutPatient', '2025-12-02 01:48:13.6288', 'Respiratory Infection', '<ul><li>Zimax (Azithromycin) 500mg - 1 tablet daily for 3 days</li><li>Napa 500mg - for fever if needed</li><li>Take steam inhalation twice daily</li><li>Drink warm water</li><li>Complete full course of antibiotics</li></ul>');

-- --------------------------------------------------------

--
-- Table structure for table `his_pwdresets`
--

CREATE TABLE `his_pwdresets` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `his_surgery`
--

CREATE TABLE `his_surgery` (
  `s_id` int(11) NOT NULL,
  `s_number` varchar(200) DEFAULT NULL,
  `s_doc` varchar(200) DEFAULT NULL,
  `s_pat_number` varchar(200) DEFAULT NULL,
  `s_pat_name` varchar(200) DEFAULT NULL,
  `s_pat_ailment` varchar(200) DEFAULT NULL,
  `s_pat_date` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `s_pat_status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_surgery`
--

INSERT INTO `his_surgery` (`s_id`, `s_number`, `s_doc`, `s_pat_number`, `s_pat_name`, `s_pat_ailment`, `s_pat_date`, `s_pat_status`) VALUES
(1, 'SUR001', 'Dr. Rafiqul Islam', 'PAT006', 'Nasrin Sultana', 'Appendicitis', '2025-12-02 01:48:13.657843', 'Successful'),
(2, 'SUR002', 'Dr. Mahmud Hasan', 'PAT008', 'Roksana Khatun', 'Kidney Stone', '2025-12-02 01:48:13.657843', 'Successful');

-- --------------------------------------------------------

--
-- Table structure for table `his_vendor`
--

CREATE TABLE `his_vendor` (
  `v_id` int(11) NOT NULL,
  `v_number` varchar(200) DEFAULT NULL,
  `v_name` varchar(200) DEFAULT NULL,
  `v_adr` varchar(200) DEFAULT NULL,
  `v_mobile` varchar(200) DEFAULT NULL,
  `v_email` varchar(200) DEFAULT NULL,
  `v_phone` varchar(200) DEFAULT NULL,
  `v_desc` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_vendor`
--

INSERT INTO `his_vendor` (`v_id`, `v_number`, `v_name`, `v_adr`, `v_mobile`, `v_email`, `v_phone`, `v_desc`) VALUES
(1, 'BD001', 'Square Pharmaceuticals Ltd', 'Tejgaon I/A, Dhaka-1208, Bangladesh', '01711234567', 'info@squarepharma.com.bd', '02-8878750', '<p>Square Pharmaceuticals Ltd. is the largest pharmaceutical company in Bangladesh with a strong presence in export markets. They manufacture a wide range of medicines including antibiotics, cardiovascular drugs, and more.</p>'),
(2, 'BD002', 'Beximco Pharmaceuticals Ltd', 'Plot 10, Rokeya Sarani, Sher-e-Bangla Nagar, Dhaka-1207', '01819876543', 'contact@beximcopharma.com', '02-9183111', '<p>Beximco Pharmaceuticals is one of the leading pharmaceutical manufacturers in Bangladesh, exporting to over 50 countries worldwide.</p>'),
(3, 'BD003', 'Incepta Pharmaceuticals Ltd', 'Savar, Dhaka-1340, Bangladesh', '01678954321', 'info@inceptapharma.com', '02-7791743', '<p>Incepta Pharmaceuticals is a leading manufacturer of generic medicines with state-of-the-art manufacturing facilities.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `his_vitals`
--

CREATE TABLE `his_vitals` (
  `vit_id` int(11) NOT NULL,
  `vit_number` varchar(200) DEFAULT NULL,
  `vit_pat_number` varchar(200) DEFAULT NULL,
  `vit_bodytemp` varchar(200) DEFAULT NULL,
  `vit_heartpulse` varchar(200) DEFAULT NULL,
  `vit_resprate` varchar(200) DEFAULT NULL,
  `vit_bloodpress` varchar(200) DEFAULT NULL,
  `vit_daterec` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_vitals`
--

INSERT INTO `his_vitals` (`vit_id`, `vit_number`, `vit_pat_number`, `vit_bodytemp`, `vit_heartpulse`, `vit_resprate`, `vit_bloodpress`, `vit_daterec`) VALUES
(1, 'VIT001', 'PAT002', '103', '98', '22', '100/70', '2025-12-02 01:48:13.666272'),
(2, 'VIT002', 'PAT003', '98', '78', '16', '150/95', '2025-12-02 01:48:13.666272'),
(3, 'VIT003', 'PAT001', '98.6', '82', '18', '148/92', '2025-12-02 01:48:13.666272'),
(4, 'VIT004', 'PAT004', '98.4', '88', '20', '115/75', '2025-12-02 01:48:13.666272'),
(5, 'VIT005', 'PAT006', '99', '90', '20', '120/80', '2025-12-02 01:48:13.666272');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `his_accounts`
--
ALTER TABLE `his_accounts`
  ADD PRIMARY KEY (`acc_id`);

--
-- Indexes for table `his_admin`
--
ALTER TABLE `his_admin`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `his_assets`
--
ALTER TABLE `his_assets`
  ADD PRIMARY KEY (`asst_id`);

--
-- Indexes for table `his_docs`
--
ALTER TABLE `his_docs`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `his_equipments`
--
ALTER TABLE `his_equipments`
  ADD PRIMARY KEY (`eqp_id`);

--
-- Indexes for table `his_laboratory`
--
ALTER TABLE `his_laboratory`
  ADD PRIMARY KEY (`lab_id`);

--
-- Indexes for table `his_medical_records`
--
ALTER TABLE `his_medical_records`
  ADD PRIMARY KEY (`mdr_id`);

--
-- Indexes for table `his_patients`
--
ALTER TABLE `his_patients`
  ADD PRIMARY KEY (`pat_id`);

--
-- Indexes for table `his_patient_transfers`
--
ALTER TABLE `his_patient_transfers`
  ADD PRIMARY KEY (`t_id`);

--
-- Indexes for table `his_payrolls`
--
ALTER TABLE `his_payrolls`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `his_pharmaceuticals`
--
ALTER TABLE `his_pharmaceuticals`
  ADD PRIMARY KEY (`phar_id`);

--
-- Indexes for table `his_pharmaceuticals_categories`
--
ALTER TABLE `his_pharmaceuticals_categories`
  ADD PRIMARY KEY (`pharm_cat_id`);

--
-- Indexes for table `his_prescriptions`
--
ALTER TABLE `his_prescriptions`
  ADD PRIMARY KEY (`pres_id`);

--
-- Indexes for table `his_pwdresets`
--
ALTER TABLE `his_pwdresets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `his_surgery`
--
ALTER TABLE `his_surgery`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `his_vendor`
--
ALTER TABLE `his_vendor`
  ADD PRIMARY KEY (`v_id`);

--
-- Indexes for table `his_vitals`
--
ALTER TABLE `his_vitals`
  ADD PRIMARY KEY (`vit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `his_accounts`
--
ALTER TABLE `his_accounts`
  MODIFY `acc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `his_admin`
--
ALTER TABLE `his_admin`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `his_assets`
--
ALTER TABLE `his_assets`
  MODIFY `asst_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `his_docs`
--
ALTER TABLE `his_docs`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `his_equipments`
--
ALTER TABLE `his_equipments`
  MODIFY `eqp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `his_laboratory`
--
ALTER TABLE `his_laboratory`
  MODIFY `lab_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `his_medical_records`
--
ALTER TABLE `his_medical_records`
  MODIFY `mdr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `his_patients`
--
ALTER TABLE `his_patients`
  MODIFY `pat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `his_patient_transfers`
--
ALTER TABLE `his_patient_transfers`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `his_payrolls`
--
ALTER TABLE `his_payrolls`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `his_pharmaceuticals`
--
ALTER TABLE `his_pharmaceuticals`
  MODIFY `phar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `his_pharmaceuticals_categories`
--
ALTER TABLE `his_pharmaceuticals_categories`
  MODIFY `pharm_cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `his_prescriptions`
--
ALTER TABLE `his_prescriptions`
  MODIFY `pres_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `his_pwdresets`
--
ALTER TABLE `his_pwdresets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `his_surgery`
--
ALTER TABLE `his_surgery`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `his_vendor`
--
ALTER TABLE `his_vendor`
  MODIFY `v_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `his_vitals`
--
ALTER TABLE `his_vitals`
  MODIFY `vit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
