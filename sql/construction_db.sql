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
