/**
 * Admin System - Integration Example
 * Shows how to integrate the admin system into your Express app
 */

import express from 'express';
import mysql from 'mysql2/promise';
import cors from 'cors';
import dotenv from 'dotenv';

// Import admin system modules
import { bootstrapSecurity } from './config/bootstrap.js';
import { createAuthMiddleware } from './middleware/authorize.js';
import * as permissionsLib from './lib/permissions.js';
import { createAdminRouter } from './routes/admin.js';
import { createPermissionsRouter } from './routes/permissions.js';
import { createSettingsRouter } from './routes/settings.js';

dotenv.config();

const app = express();
const PORT = process.env.PORT || 3001;

// Middleware
app.use(cors());
app.use(express.json({ limit: '50mb' }));
app.use(express.urlencoded({ extended: true, limit: '50mb' }));

// ============================================
// Database Connection
// ============================================

const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'your_database',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Test database connection
pool.getConnection()
  .then(connection => {
    console.log('✅ Database connected successfully');
    connection.release();
  })
  .catch(err => {
    console.error('❌ Database connection failed:', err.message);
    process.exit(1);
  });

// ============================================
// Initialize Admin System
// ============================================

// Create auth middleware
const authMiddleware = createAuthMiddleware(pool);

// Create routers
const adminRouter = createAdminRouter(pool, authMiddleware, permissionsLib);
const permissionsRouter = createPermissionsRouter(pool, authMiddleware, permissionsLib);
const settingsRouter = createSettingsRouter(pool, authMiddleware);

// Mount routes
app.use('/api/admin', adminRouter);
app.use('/api/permissions', permissionsRouter);
app.use('/api/settings', settingsRouter);

// ============================================
// Your Application Routes
// ============================================

app.get('/api/health', (req, res) => {
  res.json({ 
    status: 'ok', 
    timestamp: new Date().toISOString(),
    admin_system: 'active'
  });
});

// Add your other routes here...
// app.use('/api/your-feature', yourFeatureRouter);

// ============================================
// Error Handler
// ============================================

app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ 
    error: err.message || 'Something went wrong!' 
  });
});

// ============================================
// Start Server
// ============================================

app.listen(PORT, async () => {
  console.log(`🚀 Server running on http://localhost:${PORT}`);
  console.log(`📊 API available at http://localhost:${PORT}/api`);
  
  // Bootstrap security (create tables and default data)
  try {
    await bootstrapSecurity(pool);
    console.log('✅ Admin system initialized successfully');
    console.log('');
    console.log('📝 Default Super Admin:');
    console.log('   Username: admin');
    console.log('   Password: admin123');
    console.log('   ⚠️  Please change the password after first login!');
  } catch (error) {
    console.error('❌ Admin system initialization failed:', error);
  }
});

// Graceful shutdown
process.on('SIGTERM', async () => {
  console.log('SIGTERM signal received: closing HTTP server');
  await pool.end();
  process.exit(0);
});

process.on('SIGINT', async () => {
  console.log('SIGINT signal received: closing HTTP server');
  await pool.end();
  process.exit(0);
});
