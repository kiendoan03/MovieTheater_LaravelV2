#!/bin/bash

# Setup Realtime + PayOs Integration - Quick Start Guide

echo "=== Movie Theater Realtime Ticket Booking + PayOs ==="
echo ""

# 1. Install Laravel Reverb
echo "Step 1: Installing Laravel Reverb..."
composer require laravel/reverb

echo "Step 2: Running Reverb installation..."
php artisan reverb:install

# 2. Run Database Migration
echo "Step 3: Running database migration..."
php artisan migrate

# 3. Start services
echo ""
echo "=== Starting Services ==="
echo ""
echo "Please run these commands in separate terminal tabs:"
echo ""
echo "Tab 1 - Laravel Development Server:"
echo "  php artisan serve"
echo ""
echo "Tab 2 - Reverb WebSocket Server:"
echo "  php artisan reverb:start"
echo ""
echo "Tab 3 - NPM (if using build system):"
echo "  npm run dev"
echo ""

# 4. Display configuration
echo "=== Configuration Summary ==="
echo ""
echo "Reverb Configuration:"
echo "  - Host: 127.0.0.1"
echo "  - Port: 8080"
echo "  - Scheme: http"
echo ""
echo "PayOs Configuration:"
echo "  - API Endpoint: https://api-merchant.payos.vn/v1"
echo "  - Webhook URL: http://localhost/webhook/payos"
echo ""

echo "=== Testing Steps ==="
echo ""
echo "1. Open browser: http://localhost:8000/Admin/TicketBooking"
echo ""
echo "2. Test Realtime Seat Updates:"
echo "   - Open 2 browser windows side-by-side"
echo "   - In window 1: Select seats"
echo "   - In window 2: Watch seats update in realtime"
echo ""
echo "3. Test Cash Payment:"
echo "   - Select seats"
echo "   - Choose 'Thanh toán bằng tiền mặt'"
echo "   - Click 'Thanh toán'"
echo "   - Ticket should be created immediately"
echo ""
echo "4. Test PayOs Payment:"
echo "   - Select seats"
echo "   - Choose 'Chuyển khoản QR'"
echo "   - Click 'Thanh toán'"
echo "   - QR code modal should appear"
echo "   - System will poll PayOs every 3 seconds"
echo ""
echo "5. Simulate Webhook (for testing PayOs):"
echo "   - Use curl or Postman to POST to /webhook/payos"
echo "   - Include valid X-Signature header"
echo "   - Ticket status should update automatically"
echo ""

echo "=== Directory Structure ==="
ls -la app/Services/PayOsService.php
ls -la app/Events/PaymentCompleted.php
ls -la app/Events/SeatStatusChanged.php
ls -la app/Http/Controllers/PayOsWebhookController.php
ls -la config/payos.php
ls -la resources/views/admin/TicketBooking/payment-status.blade.php
echo ""

echo "=== Troubleshooting ==="
echo ""
echo "If Reverb won't start:"
echo "  - Check port 8080 is not in use"
echo "  - Verify .env has REVERB_* variables"
echo "  - Try: php artisan reverb:start --verbose"
echo ""
echo "If PayOs QR not showing:"
echo "  - Verify PayOs credentials in .env"
echo "  - Check API response in browser console"
echo "  - Verify PAYOS_WEBHOOK_URL is correct"
echo ""
echo "If realtime not updating:"
echo "  - Check Reverb server is running (tab 2)"
echo "  - Verify WebSocket connection in browser DevTools (Network tab)"
echo "  - Check Laravel logs: tail -f storage/logs/laravel.log"
echo ""

echo "Done! ✅"
