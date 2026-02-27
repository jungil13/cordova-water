-- Cordova Water System Inc. - PostgreSQL Schema

-- Users table (OAuth + email/password)
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    picture VARCHAR(500),
    provider VARCHAR(20) DEFAULT 'local',
    provider_id VARCHAR(255),
    role VARCHAR(20) DEFAULT 'user',
    address VARCHAR(500),
    phone VARCHAR(50),
    password_hash VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_provider ON users (provider, provider_id);
CREATE INDEX IF NOT EXISTS idx_email ON users (email);

-- Service requests
CREATE TABLE IF NOT EXISTS service_requests (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    fullname VARCHAR(255) NOT NULL,
    address VARCHAR(500) NOT NULL,
    contact VARCHAR(50) NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    details TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    assigned_to INTEGER REFERENCES users(id) ON DELETE SET NULL,
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_status ON service_requests (status);
CREATE INDEX IF NOT EXISTS idx_created_sr ON service_requests (created_at);

-- Billing records (water consumption & charges)
CREATE TABLE IF NOT EXISTS billing (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    account_number VARCHAR(50),
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    consumption_cbm NUMERIC(10,2) NOT NULL DEFAULT 0,
    amount NUMERIC(12,2) NOT NULL DEFAULT 0,
    status VARCHAR(20) DEFAULT 'unpaid',
    due_date DATE,
    paid_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_user_status ON billing (user_id, status);
CREATE INDEX IF NOT EXISTS idx_due ON billing (due_date);

-- Payments
CREATE TABLE IF NOT EXISTS payments (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    billing_id INTEGER REFERENCES billing(id) ON DELETE SET NULL,
    amount NUMERIC(12,2) NOT NULL,
    method VARCHAR(20) DEFAULT 'cash',
    reference VARCHAR(255),
    proof_of_payment VARCHAR(500),
    status VARCHAR(20) DEFAULT 'pending',
    notes TEXT,
    confirmed_by INTEGER REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_payment_status ON payments (status);
CREATE INDEX IF NOT EXISTS idx_payment_created ON payments (created_at);

-- Water rates (reference)
CREATE TABLE IF NOT EXISTS water_rates (
    id SERIAL PRIMARY KEY,
    min_cbm INTEGER NOT NULL,
    max_cbm INTEGER NOT NULL,
    rate_per_cbm NUMERIC(10,2) NOT NULL,
    description VARCHAR(255)
);

INSERT INTO water_rates (min_cbm, max_cbm, rate_per_cbm, description) VALUES
  (0, 5, 220, 'Minimum charge (0-5 m³)'),
  (6, 10, 48, '6-10 m³ per cubic meter'),
  (11, 20, 54, '11-20 m³ per cubic meter'),
  (21, 30, 65, '21-30 m³ per cubic meter'),
  (31, 9999, 92, '31+ m³ per cubic meter')
ON CONFLICT DO NOTHING;

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    message TEXT NOT NULL,
    type VARCHAR(20) DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_notifications_user_read ON notifications (user_id, is_read);
CREATE INDEX IF NOT EXISTS idx_notifications_created ON notifications (created_at);

