CREATE TABLE PROJECT (
  project_id INT AUTO_INCREMENT PRIMARY KEY,
  project_name VARCHAR(150) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NULL,
  budget DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status VARCHAR(30) NOT NULL,
  INDEX idx_project_status (status),
  INDEX idx_project_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE SITE (
  site_id INT AUTO_INCREMENT PRIMARY KEY,
  site_name VARCHAR(150) NOT NULL,
  location VARCHAR(255) NOT NULL,
  project_id INT NOT NULL,
  CONSTRAINT fk_site_project FOREIGN KEY (project_id)
    REFERENCES PROJECT(project_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_site_project (project_id),
  INDEX idx_site_name (site_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE MILESTONE (
  milestone_id INT AUTO_INCREMENT PRIMARY KEY,
  milestone_name VARCHAR(150) NOT NULL,
  target_date DATE NOT NULL,
  status VARCHAR(30) NOT NULL,
  project_id INT NOT NULL,
  CONSTRAINT fk_milestone_project FOREIGN KEY (project_id)
    REFERENCES PROJECT(project_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  INDEX idx_milestone_project (project_id),
  INDEX idx_milestone_status (status),
  INDEX idx_milestone_target (target_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE DAILY_REPORT (
  report_id INT AUTO_INCREMENT PRIMARY KEY,
  report_date DATE NOT NULL,
  progress_percentage DECIMAL(5,2) NOT NULL,
  site_id INT NOT NULL,
  CONSTRAINT fk_report_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  UNIQUE KEY uq_site_reportdate (site_id, report_date),
  INDEX idx_report_date (report_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE RISK (
  risk_id INT AUTO_INCREMENT PRIMARY KEY,
  risk_type VARCHAR(100) NOT NULL,
  severity VARCHAR(30) NOT NULL,
  site_id INT NOT NULL,
  CONSTRAINT fk_risk_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  INDEX idx_risk_site (site_id),
  INDEX idx_risk_type_severity (risk_type, severity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE MATERIAL (
  material_id INT AUTO_INCREMENT PRIMARY KEY,
  material_name VARCHAR(150) NOT NULL,
  material_type VARCHAR(100) NOT NULL,
  unit_of_measure VARCHAR(30) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  INDEX idx_material_type (material_type),
  INDEX idx_material_name (material_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE SUPPLIER (
  supplier_id INT AUTO_INCREMENT PRIMARY KEY,
  supplier_name VARCHAR(150) NOT NULL,
  contact_person VARCHAR(100),
  phone VARCHAR(20),
  email VARCHAR(100),
  address VARCHAR(255),
  rating DECIMAL(3,2),
  INDEX idx_supplier_name (supplier_name),
  INDEX idx_supplier_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PURCHASE_ORDER (
  po_id INT AUTO_INCREMENT PRIMARY KEY,
  po_number VARCHAR(50) NOT NULL UNIQUE,
  po_date DATE NOT NULL,
  delivery_date DATE,
  total_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  status VARCHAR(30) NOT NULL,
  supplier_id INT NOT NULL,
  project_id INT NOT NULL,
  CONSTRAINT fk_po_supplier FOREIGN KEY (supplier_id)
    REFERENCES SUPPLIER(supplier_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_po_project FOREIGN KEY (project_id)
    REFERENCES PROJECT(project_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_po_status (status),
  INDEX idx_po_date (po_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PO_LINE_ITEM (
  po_line_id INT AUTO_INCREMENT PRIMARY KEY,
  quantity DECIMAL(10,2) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  line_total DECIMAL(12,2) NOT NULL,
  po_id INT NOT NULL,
  material_id INT NOT NULL,
  CONSTRAINT fk_poline_po FOREIGN KEY (po_id)
    REFERENCES PURCHASE_ORDER(po_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_poline_material FOREIGN KEY (material_id)
    REFERENCES MATERIAL(material_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE INVENTORY (
  inventory_id INT AUTO_INCREMENT PRIMARY KEY,
  quantity_in_stock DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  reorder_level DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  site_id INT NOT NULL,
  material_id INT NOT NULL,
  CONSTRAINT fk_inventory_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_inventory_material FOREIGN KEY (material_id)
    REFERENCES MATERIAL(material_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  UNIQUE KEY uq_site_material (site_id, material_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE MATERIAL_USAGE (
  usage_id INT AUTO_INCREMENT PRIMARY KEY,
  usage_date DATE NOT NULL,
  quantity_used DECIMAL(10,2) NOT NULL,
  site_id INT NOT NULL,
  material_id INT NOT NULL,
  CONSTRAINT fk_usage_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_usage_material FOREIGN KEY (material_id)
    REFERENCES MATERIAL(material_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE EMPLOYEE (
  employee_id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE,
  phone VARCHAR(20),
  hire_date DATE NOT NULL,
  job_title VARCHAR(100) NOT NULL,
  salary DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ATTENDANCE (
  attendance_id INT AUTO_INCREMENT PRIMARY KEY,
  attendance_date DATE NOT NULL,
  clock_in TIME,
  clock_out TIME,
  hours_worked DECIMAL(5,2),
  status VARCHAR(30) NOT NULL,
  employee_id INT NOT NULL,
  site_id INT NOT NULL,
  CONSTRAINT fk_attendance_employee FOREIGN KEY (employee_id)
    REFERENCES EMPLOYEE(employee_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_attendance_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  UNIQUE KEY uq_employee_date (employee_id, attendance_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE PAYROLL (
  payroll_id INT AUTO_INCREMENT PRIMARY KEY,
  pay_period_start DATE NOT NULL,
  pay_period_end DATE NOT NULL,
  gross_pay DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  deductions DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  net_pay DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  payment_date DATE,
  employee_id INT NOT NULL,
  CONSTRAINT fk_payroll_employee FOREIGN KEY (employee_id)
    REFERENCES EMPLOYEE(employee_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE EQUIPMENT (
  equipment_id INT AUTO_INCREMENT PRIMARY KEY,
  equipment_name VARCHAR(150) NOT NULL,
  equipment_type VARCHAR(100) NOT NULL,
  model VARCHAR(100),
  serial_number VARCHAR(100) UNIQUE,
  purchase_date DATE,
  purchase_cost DECIMAL(12,2) DEFAULT 0.00,
  status VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE EQUIPMENT_ASSIGNMENT (
  assignment_id INT AUTO_INCREMENT PRIMARY KEY,
  assignment_date DATE NOT NULL,
  return_date DATE,
  equipment_id INT NOT NULL,
  site_id INT NOT NULL,
  CONSTRAINT fk_assignment_equipment FOREIGN KEY (equipment_id)
    REFERENCES EQUIPMENT(equipment_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_assignment_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE MAINTENANCE (
  maintenance_id INT AUTO_INCREMENT PRIMARY KEY,
  maintenance_type VARCHAR(50) NOT NULL,
  maintenance_date DATE NOT NULL,
  cost DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  equipment_id INT NOT NULL,
  CONSTRAINT fk_maintenance_equipment FOREIGN KEY (equipment_id)
    REFERENCES EQUIPMENT(equipment_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// 18-02-2024
//modified the table employee by adding role column and password column
ALTER TABLE EMPLOYEE 
ADD COLUMN password VARCHAR(255) NOT NULL,
ADD COLUMN role ENUM('admin','project_manager') NOT NULL;


INSERT INTO EMPLOYEE 
(first_name, last_name, email, phone, hire_date, job_title, salary, status, password, role)
VALUES
('Amol', 'Jadhav', 'amol@gmail.com', '9876543210', CURDATE(), 
'Administrator', 50000.00, 'Active',
'$2y$10$wH8sQ4t9x8lZ5b7R8g6Z6e6t8wH8sQ4t9x8lZ5b7R8g6Z6e6t8wH8',
'admin');


INSERT INTO EMPLOYEE 
(first_name, last_name, email, phone, hire_date, job_title, salary, status, password, role)
VALUES
('Shubham', 'Patil', 'shubham@gmail.com', '9123456780', CURDATE(), 
'Project Manager', 40000.00, 'Active',
'$2y$10$wH8sQ4t9x8lZ5b7R8g6Z6e6t8wH8sQ4t9x8lZ5b7R8g6Z6e6t8wH8',
'project_manager');


23/2/2026

CREATE TABLE PHASE (
  phase_id INT AUTO_INCREMENT PRIMARY KEY,

  site_id INT NOT NULL,   -- Phase belongs to a site

  phase_name VARCHAR(150) NOT NULL,
  description TEXT NULL,

  start_date DATE NOT NULL,
  end_date DATE NULL,

  progress_percentage DECIMAL(5,2) DEFAULT 0.00,

  status ENUM('Not Started','In Progress','On Hold','Completed','Delayed')
         NOT NULL DEFAULT 'Not Started',

  priority ENUM('Low','Medium','High') DEFAULT 'Medium',

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
             ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_phase_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id)
    ON UPDATE CASCADE ON DELETE CASCADE,

  INDEX idx_phase_site (site_id),
  INDEX idx_phase_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



ALTER TABLE DAILY_REPORT
ADD COLUMN phase_id INT NOT NULL AFTER site_id,
ADD CONSTRAINT fk_report_phase
FOREIGN KEY (phase_id)
REFERENCES PHASE(phase_id)
ON UPDATE CASCADE ON DELETE CASCADE;





Date -  2/26/2026

material and Inventory managment 




USE construction_db;

-- 1) Material Requirement Planning
CREATE TABLE IF NOT EXISTS MATERIAL_PLAN (
  plan_id INT AUTO_INCREMENT PRIMARY KEY,
  plan_date DATE NOT NULL,
  required_by_date DATE NULL,
  status ENUM('Draft','Submitted','Approved','Rejected') NOT NULL DEFAULT 'Draft',
  remarks VARCHAR(255) NULL,
  site_id INT NOT NULL,
  phase_id INT NULL,
  created_by INT NULL,
  approved_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_plan_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_plan_phase FOREIGN KEY (phase_id)
    REFERENCES PHASE(phase_id) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_plan_created_by FOREIGN KEY (created_by)
    REFERENCES EMPLOYEE(employee_id) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_plan_approved_by FOREIGN KEY (approved_by)
    REFERENCES EMPLOYEE(employee_id) ON UPDATE CASCADE ON DELETE SET NULL,

  INDEX idx_plan_site (site_id),
  INDEX idx_plan_phase (phase_id),
  INDEX idx_plan_status (status),
  INDEX idx_plan_date (plan_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS MATERIAL_PLAN_ITEM (
  plan_item_id INT AUTO_INCREMENT PRIMARY KEY,
  plan_id INT NOT NULL,
  material_id INT NOT NULL,
  planned_qty DECIMAL(10,2) NOT NULL,
  estimated_unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  notes VARCHAR(255) NULL,

  CONSTRAINT fk_plan_item_plan FOREIGN KEY (plan_id)
    REFERENCES MATERIAL_PLAN(plan_id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_plan_item_material FOREIGN KEY (material_id)
    REFERENCES MATERIAL(material_id) ON UPDATE CASCADE ON DELETE RESTRICT,

  UNIQUE KEY uq_plan_material (plan_id, material_id),
  INDEX idx_plan_item_material (material_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2) Material Indent Request
CREATE TABLE IF NOT EXISTS MATERIAL_INDENT (
  indent_id INT AUTO_INCREMENT PRIMARY KEY,
  indent_number VARCHAR(50) NOT NULL UNIQUE,
  indent_date DATE NOT NULL,
  need_by_date DATE NULL,
  status ENUM('Pending','Approved','Rejected','Fulfilled') NOT NULL DEFAULT 'Pending',
  priority ENUM('Low','Medium','High') NOT NULL DEFAULT 'Medium',
  reason VARCHAR(255) NULL,
  site_id INT NOT NULL,
  requested_by INT NULL,
  approved_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_indent_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_indent_requested_by FOREIGN KEY (requested_by)
    REFERENCES EMPLOYEE(employee_id) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_indent_approved_by FOREIGN KEY (approved_by)
    REFERENCES EMPLOYEE(employee_id) ON UPDATE CASCADE ON DELETE SET NULL,

  INDEX idx_indent_site (site_id),
  INDEX idx_indent_status (status),
  INDEX idx_indent_date (indent_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS MATERIAL_INDENT_ITEM (
  indent_item_id INT AUTO_INCREMENT PRIMARY KEY,
  indent_id INT NOT NULL,
  material_id INT NOT NULL,
  requested_qty DECIMAL(10,2) NOT NULL,
  approved_qty DECIMAL(10,2) NULL,
  remarks VARCHAR(255) NULL,

  CONSTRAINT fk_indent_item_indent FOREIGN KEY (indent_id)
    REFERENCES MATERIAL_INDENT(indent_id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_indent_item_material FOREIGN KEY (material_id)
    REFERENCES MATERIAL(material_id) ON UPDATE CASCADE ON DELETE RESTRICT,

  UNIQUE KEY uq_indent_material (indent_id, material_id),
  INDEX idx_indent_item_material (material_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3) Purchase Approval Workflow
CREATE TABLE IF NOT EXISTS PURCHASE_APPROVAL (
  approval_id INT AUTO_INCREMENT PRIMARY KEY,
  po_id INT NOT NULL,
  indent_id INT NULL,
  requested_by INT NULL,
  approver_id INT NULL,
  approval_status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  approval_date DATE NULL,
  remarks VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_approval_po FOREIGN KEY (po_id)
    REFERENCES PURCHASE_ORDER(po_id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_approval_indent FOREIGN KEY (indent_id)
    REFERENCES MATERIAL_INDENT(indent_id) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_approval_requested_by FOREIGN KEY (requested_by)
    REFERENCES EMPLOYEE(employee_id) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_approval_approver FOREIGN KEY (approver_id)
    REFERENCES EMPLOYEE(employee_id) ON UPDATE CASCADE ON DELETE SET NULL,

  UNIQUE KEY uq_approval_po (po_id),
  INDEX idx_approval_status (approval_status),
  INDEX idx_approval_date (approval_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4) GRN
CREATE TABLE IF NOT EXISTS GRN (
  grn_id INT AUTO_INCREMENT PRIMARY KEY,
  grn_number VARCHAR(50) NOT NULL UNIQUE,
  grn_date DATE NOT NULL,
  po_id INT NOT NULL,
  supplier_id INT NOT NULL,
  site_id INT NOT NULL,
  received_by INT NULL,
  status ENUM('Draft','Posted','Cancelled') NOT NULL DEFAULT 'Draft',
  remarks VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_grn_po FOREIGN KEY (po_id)
    REFERENCES PURCHASE_ORDER(po_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_grn_supplier FOREIGN KEY (supplier_id)
    REFERENCES SUPPLIER(supplier_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_grn_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_grn_received_by FOREIGN KEY (received_by)
    REFERENCES EMPLOYEE(employee_id) ON UPDATE CASCADE ON DELETE SET NULL,

  INDEX idx_grn_date (grn_date),
  INDEX idx_grn_po (po_id),
  INDEX idx_grn_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS GRN_ITEM (
  grn_item_id INT AUTO_INCREMENT PRIMARY KEY,
  grn_id INT NOT NULL,
  material_id INT NOT NULL,
  received_qty DECIMAL(10,2) NOT NULL,
  accepted_qty DECIMAL(10,2) NOT NULL,
  rejected_qty DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,

  CONSTRAINT fk_grn_item_grn FOREIGN KEY (grn_id)
    REFERENCES GRN(grn_id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_grn_item_material FOREIGN KEY (material_id)
    REFERENCES MATERIAL(material_id) ON UPDATE CASCADE ON DELETE RESTRICT,

  INDEX idx_grn_item_grn (grn_id),
  INDEX idx_grn_item_material (material_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5) Stock In / Stock Out
CREATE TABLE IF NOT EXISTS STOCK_TRANSACTION (
  stock_txn_id INT AUTO_INCREMENT PRIMARY KEY,
  txn_date DATE NOT NULL,
  txn_type ENUM('IN','OUT','ADJUSTMENT') NOT NULL,
  reference_type ENUM('GRN','USAGE','MANUAL','RETURN','TRANSFER') NOT NULL DEFAULT 'MANUAL',
  reference_id INT NULL,
  site_id INT NOT NULL,
  material_id INT NOT NULL,
  quantity DECIMAL(10,2) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  remarks VARCHAR(255) NULL,
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_stock_txn_site FOREIGN KEY (site_id)
    REFERENCES SITE(site_id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_stock_txn_material FOREIGN KEY (material_id)
    REFERENCES MATERIAL(material_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_stock_txn_created_by FOREIGN KEY (created_by)
    REFERENCES EMPLOYEE(employee_id) ON UPDATE CASCADE ON DELETE SET NULL,

  INDEX idx_stock_txn_date (txn_date),
  INDEX idx_stock_txn_site_material (site_id, material_id),
  INDEX idx_stock_txn_type (txn_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
