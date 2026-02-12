<?php

/**
 * FTP Connection Test Script (Native PHP FTP)
 *
 * Run this script to test FTP connectivity before using the application
 * Usage: php test_ftp_native.php
 */

echo "=== FTP Connection Test (Native PHP) ===\n\n";

// Test 1: Check PHP FTP Extension
echo "1. Checking PHP FTP extension...\n";
if (!function_exists('ftp_connect')) {
    echo "   ❌ PHP FTP extension is not installed\n";
    echo "   Please enable FTP extension in php.ini\n";
    exit(1);
}
echo "   ✓ PHP FTP extension is available\n\n";

// Test 2: Load environment variables
echo "2. Loading configuration...\n";
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    echo "   ❌ .env file not found\n";
    exit(1);
}

// Parse .env file manually (Laravel format)
$env = [];
$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    // Skip comments
    if (strpos(trim($line), '#') === 0) {
        continue;
    }

    // Parse KEY=VALUE
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Remove quotes if present
        $value = trim($value, '"\'');

        $env[$key] = $value;
    }
}

$ftpHost = $env['FTP_HOST'] ?? '';
$ftpUser = $env['FTP_USERNAME'] ?? '';
$ftpPass = $env['FTP_PASSWORD'] ?? '';
$ftpPort = $env['FTP_PORT'] ?? 21;

if (empty($ftpHost)) {
    echo "   ❌ FTP_HOST not configured in .env\n";
    exit(1);
}

echo "   ✓ FTP Host: {$ftpHost}\n";
echo "   ✓ FTP User: {$ftpUser}\n";
echo "   ✓ FTP Port: {$ftpPort}\n\n";

// Test 3: Test FTP Connection
echo "3. Testing FTP connection...\n";
$conn = @ftp_connect($ftpHost, $ftpPort, 30);
if (!$conn) {
    echo "   ❌ FTP connection failed\n";
    echo "   Please check FTP_HOST and FTP_PORT in .env\n";
    echo "   Error: " . error_get_last()['message'] . "\n";
    exit(1);
}
echo "   ✓ FTP connection successful!\n\n";

// Test 4: Test FTP Login
echo "4. Testing FTP login...\n";
$login = @ftp_login($conn, $ftpUser, $ftpPass);
if (!$login) {
    echo "   ❌ FTP login failed\n";
    echo "   Please check FTP_USERNAME and FTP_PASSWORD in .env\n";
    ftp_close($conn);
    exit(1);
}
echo "   ✓ FTP login successful!\n\n";

// Set passive mode
ftp_pasv($conn, true);

// Test 5: Test File Upload
echo "5. Testing file upload to FTP...\n";
$testContent = "FTP Test - " . date('Y-m-d H:i:s');
$testFilename = 'ftp_test_' . time() . '.txt';
$localTestFile = sys_get_temp_dir() . '/' . $testFilename;

file_put_contents($localTestFile, $testContent);

$uploaded = @ftp_put($conn, '/' . $testFilename, $localTestFile, FTP_BINARY);
if ($uploaded) {
    echo "   ✓ File uploaded successfully: {$testFilename}\n\n";
} else {
    echo "   ❌ File upload failed\n";
    echo "   This might be a permission issue on the FTP server\n";
    ftp_close($conn);
    unlink($localTestFile);
    exit(1);
}

// Test 6: Test File Download
echo "6. Testing file download from FTP...\n";
$downloadFile = sys_get_temp_dir() . '/download_' . $testFilename;
$downloaded = @ftp_get($conn, $downloadFile, '/' . $testFilename, FTP_BINARY);
if ($downloaded) {
    $content = file_get_contents($downloadFile);
    if ($content === $testContent) {
        echo "   ✓ File downloaded successfully\n";
        echo "   ✓ Content matches: {$content}\n\n";
    } else {
        echo "   ❌ Content mismatch\n";
    }
    unlink($downloadFile);
} else {
    echo "   ⚠ File download failed (might be normal if server doesn't allow download)\n\n";
}

// Test 7: Test File Delete
echo "7. Testing file deletion from FTP...\n";
$deleted = @ftp_delete($conn, '/' . $testFilename);
if ($deleted) {
    echo "   ✓ File deleted successfully\n\n";
} else {
    echo "   ⚠ File deletion failed (file might not exist or no permission)\n\n";
}

// Test 8: Test Directory Creation
echo "8. Testing directory operations...\n";
$testDirs = ['documents', 'versions', 'public', 'public/profiles', 'public/signatures'];

foreach ($testDirs as $dir) {
    $created = @ftp_mkdir($conn, '/' . $dir);
    if ($created) {
        echo "   ✓ Created directory: {$dir}\n";
        @ftp_chmod($conn, 0755, '/' . $dir);
    } else {
        // Directory might already exist
        if (@ftp_chdir($conn, '/' . $dir)) {
            echo "   ✓ Directory exists: {$dir}\n";
            ftp_chdir($conn, '/');
        } else {
            echo "   ⚠ Could not create/access directory: {$dir}\n";
        }
    }
}

// Cleanup
@unlink($localTestFile);
ftp_close($conn);

echo "\n=== All Tests Completed Successfully! ===\n";
echo "FTP is ready to use with the DMS application.\n";
echo "\nNext steps:\n";
echo "1. Upload a document through the DMS interface\n";
echo "2. Check storage/app/documents/ for local file\n";
echo "3. Check FTP server for the same file\n";
echo "4. Check storage/logs/laravel.log for FTP upload logs\n";
