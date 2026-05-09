@echo off
REM Setup Realtime + PayOs Integration - Windows Quick Start

echo.
echo ======================================
echo Movie Theater Realtime Ticket Booking
echo ======================================
echo.

REM Step 1: Check if composer is installed
echo [Step 1] Checking Composer installation...
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Composer is not installed or not in PATH
    exit /b 1
)
echo OK: Composer found

REM Step 2: Install Laravel Reverb
echo.
echo [Step 2] Installing Laravel Reverb...
call composer require laravel/reverb
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to install Laravel Reverb
    exit /b 1
)
echo OK: Laravel Reverb installed

REM Step 3: Run Reverb installation
echo.
echo [Step 3] Running Reverb installation...
call php artisan reverb:install
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Reverb installation failed
    exit /b 1
)
echo OK: Reverb installation complete

REM Step 4: Run database migration
echo.
echo [Step 4] Running database migration...
call php artisan migrate
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Database migration failed
    exit /b 1
)
echo OK: Database migration complete

REM Step 5: Display configuration
echo.
echo ======================================
echo Configuration Summary
echo ======================================
echo.
echo Reverb Configuration:
echo   - Host: 127.0.0.1
echo   - Port: 8080
echo   - Scheme: http
echo.
echo PayOs Configuration:
echo   - API Endpoint: https://api-merchant.payos.vn/v1
echo   - Webhook URL: http://localhost/webhook/payos
echo.

REM Step 6: Display what to do next
echo.
echo ======================================
echo Next Steps - Start Services
echo ======================================
echo.
echo Open 3 command prompt windows and run:
echo.
echo Window 1 - Laravel Development Server:
echo   cd %CD%
echo   php artisan serve
echo.
echo Window 2 - Reverb WebSocket Server:
echo   cd %CD%
echo   php artisan reverb:start
echo.
echo Window 3 - Optional: NPM Development:
echo   cd %CD%
echo   npm run dev
echo.

echo ======================================
echo Testing
echo ======================================
echo.
echo 1. Open browser: http://localhost:8000/Admin/TicketBooking
echo 2. Open in 2 windows to test realtime seat updates
echo 3. Select seats in one window, watch them update in the other
echo.

echo ======================================
echo Files Created/Modified
echo ======================================
echo.
echo Application Files:
echo   - app/Services/PayOsService.php
echo   - app/Events/PaymentCompleted.php
echo   - app/Events/SeatStatusChanged.php
echo   - app/Http/Controllers/PayOsWebhookController.php
echo   - config/payos.php
echo.
echo Views:
echo   - resources/views/admin/TicketBooking/payment-status.blade.php
echo   - resources/views/admin/TicketBooking/seat-layout.blade.php
echo.
echo Database:
echo   - migrations/2026_04_20_add_payos_fields_to_tickets_table.php
echo.
echo Documentation:
echo   - COMPLETE_GUIDE.md
echo   - REALTIME_PAYOS_SETUP.md
echo.

echo Setup Complete! ✅
echo.
pause
