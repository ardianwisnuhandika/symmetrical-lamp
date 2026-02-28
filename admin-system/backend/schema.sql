-- ============================================
-- Admin System - Database Schema
-- ============================================
-- Version: 1.0.0
-- Description: Complete schema for admin management system
-- with role-based access control (RBAC)
-- ============================================

-- Create database (optional - comment out if not needed)
-- CREATE DATABASE IF NOT EXISTS your_database_name;
-- USE your_database_name;

-- ============================================
-- Core Tables
-- ============================================

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'badan_gizi', 'sppg_admin') NOT NULL,
    email VARCHAR(255),
    no_hp VARCHAR(50),
    kecamatan VARCHAR(100),
    kode_kecamatan VARCHAR(10),
    desa VARCHAR(100),
    kode_desa VARCHAR(20),
    kode_unik_sppg VARCHAR(40),
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- RBAC Tables
-- ============================================

-- Roles table
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permissions table
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(100) NOT NULL UNIQUE,
    label VARCHAR(150) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Role Permissions junction table
CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Permissions override table
CREATE TABLE IF NOT EXISTS admin_permissions (
    admin_id INT NOT NULL,
    permission_id INT NOT NULL,
    allowed TINYINT(1) DEFAULT 1,
    PRIMARY KEY (admin_id, permission_id),
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Activity & Logging Tables
-- ============================================

-- Activity Logs table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    admin_name VARCHAR(100),
    action VARCHAR(100),
    description TEXT,
    ip_address VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_id (admin_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Application-Specific Tables (Optional)
-- ============================================
-- These tables are specific to SPPG application
-- Remove or modify based on your needs

-- SPPG Units table (example)
CREATE TABLE IF NOT EXISTS sppg_units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NULL,
    admin_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nama VARCHAR(255) NOT NULL,
    yayasan VARCHAR(255),
    kepala VARCHAR(255),
    noHp VARCHAR(50),
    status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif',
    kecamatan VARCHAR(100),
    desa VARCHAR(100),
    kode_kec INT,
    kode_kel INT,
    kode_unik VARCHAR(20),
    alamat TEXT,
    koordinat JSON,
    data JSON,
    images JSON,
    image VARCHAR(500),
    jumlah INT DEFAULT 0,
    tanggalOperasional VARCHAR(100),
    INDEX idx_admin_id (admin_id),
    INDEX idx_kecamatan (kecamatan),
    INDEX idx_desa (desa),
    INDEX idx_status (status),
    INDEX idx_kode_unik (kode_unik),
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Laporan (Reports) table
CREATE TABLE IF NOT EXISTS laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sppg_id INT,
    admin_id INT,
    judul VARCHAR(255),
    deskripsi TEXT,
    status ENUM('pending', 'processed', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_admin_id (admin_id),
    FOREIGN KEY (sppg_id) REFERENCES sppg_units(id) ON DELETE SET NULL,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Request Data table
CREATE TABLE IF NOT EXISTS request_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sppg_id INT,
    admin_id INT,
    type VARCHAR(100),
    data_lama JSON,
    data_baru JSON,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_admin_id (admin_id),
    FOREIGN KEY (sppg_id) REFERENCES sppg_units(id) ON DELETE SET NULL,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Default Data
-- ============================================

-- Default Super Admin
-- Password: admin123 (hashed with custom algorithm)
-- Hash formula: 'hash_' + Math.abs(hash).toString(16)
-- where hash is calculated using bit shifting
INSERT IGNORE INTO admins (username, password, nama, role, status) VALUES 
('admin', 'hash_39c43b7d', 'Super Admin', 'super_admin', 'aktif');

-- Default Settings
INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('maintenance_mode', 'false'),
('max_login_attempts', '5'),
('lock_duration_minutes', '15'),
('session_timeout_minutes', '60'),
('password_min_length', '8'),
('password_require_upper', 'true'),
('password_require_lower', 'true'),
('password_require_number', 'true'),
('password_require_symbol', 'true'),
('login_rate_limit_per_minute', '20'),
('login_rate_limit_block_minutes', '2'),
('global_announcement', ''),
('superadmin_announcement', ''),
('sppg_announcement', ''),
('visitor_count', '0');

-- ============================================
-- Notes
-- ============================================
-- 1. Roles and permissions are auto-populated by bootstrapSecurity()
-- 2. Default super admin password should be changed after first login
-- 3. Adjust ENUM values in admins.role based on your needs
-- 4. Remove sppg_units, laporan, request_data if not needed
-- 5. Indexes are optimized for common queries
-- ============================================
