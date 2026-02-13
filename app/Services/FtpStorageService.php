<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FtpStorageService
{
    private $ftpConnection = null;
    private $ftpEnabled = false;

    public function __construct()
    {
        $this->ftpEnabled = !empty(env('FTP_HOST'));
    }

    /**
     * Store file to both local and FTP storage
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path Directory path (e.g., 'documents', 'versions')
     * @param string $filename Filename to save as
     * @return string The filename that was saved
     */
    public function storeFile($file, string $path, string $filename): string
    {
        // 1. Store to local storage (primary)
        $file->storeAs($path, $filename);

        // 2. Store to FTP (backup/mirror) - non-blocking
        if ($this->ftpEnabled) {
            $this->uploadToFtpNative($path . '/' . $filename);
        }

        return $filename;
    }

    /**
     * Delete file from both local and FTP storage
     *
     * @param string $path Full path including filename (e.g., 'documents/abc123.pdf')
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        $localDeleted = false;

        // Delete from local storage
        if (Storage::exists($path)) {
            $localDeleted = Storage::delete($path);
        }

        // Delete from FTP
        if ($this->ftpEnabled) {
            $this->deleteFromFtpNative($path);
        }

        return $localDeleted;
    }

    /**
     * Upload file to FTP server using native PHP FTP functions
     *
     * @param string $localPath Path in local storage
     * @return bool
     */
    private function uploadToFtpNative(string $localPath): bool
    {
        try {
            // Check if FTP is configured
            if (!env('FTP_HOST')) {
                Log::info("FTP not configured, skipping FTP upload");
                return false;
            }

            // Get file content from local storage
            if (!Storage::exists($localPath)) {
                Log::warning("Local file not found for FTP upload", ['path' => $localPath]);
                return false;
            }

            // Connect to FTP
            $conn = $this->connectFtp();
            if (!$conn) {
                return false;
            }

            // Create remote directory if needed
            $remotePath = '/' . $localPath;
            $remoteDir = dirname($remotePath);
            $this->createFtpDirectory($conn, $remoteDir);

            // Get file content from Storage (more reliable than reading from disk)
            $fileContent = Storage::get($localPath);

            // Create temporary stream from content
            $stream = fopen('php://temp', 'r+');
            fwrite($stream, $fileContent);
            rewind($stream);

            // Upload file using stream (more reliable than ftp_put with file path)
            $result = ftp_fput($conn, $remotePath, $stream, FTP_BINARY);

            // Close stream
            fclose($stream);

            if ($result) {
                Log::info("File uploaded to FTP successfully", [
                    'local_path' => $localPath,
                    'ftp_path' => $remotePath,
                    'file_size' => strlen($fileContent)
                ]);
            } else {
                Log::warning("FTP upload failed", [
                    'path' => $remotePath,
                    'file_size' => strlen($fileContent)
                ]);
            }

            // Close connection
            ftp_close($conn);

            return $result;

        } catch (\Exception $e) {
            // Log error but don't throw - FTP failure shouldn't break the application
            Log::error("FTP upload failed", [
                'local_path' => $localPath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Delete file from FTP server using native PHP FTP functions
     *
     * @param string $path Path to delete
     * @return bool
     */
    private function deleteFromFtpNative(string $path): bool
    {
        try {
            $conn = $this->connectFtp();
            if (!$conn) {
                return false;
            }

            $remotePath = '/' . $path;
            $result = @ftp_delete($conn, $remotePath);

            if ($result) {
                Log::info("FTP file deleted successfully", ['path' => $path]);
            }

            ftp_close($conn);
            return $result;

        } catch (\Exception $e) {
            Log::warning("Failed to delete file from FTP", [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Connect to FTP server
     *
     * @return resource|false
     */
    private function connectFtp()
    {
        try {
            $host = env('FTP_HOST');
            $username = env('FTP_USERNAME');
            $password = env('FTP_PASSWORD');
            $port = env('FTP_PORT', 21);
            $ssl = env('FTP_SSL', false);
            $passive = env('FTP_PASSIVE', true);

            // Connect
            if ($ssl) {
                $conn = @ftp_ssl_connect($host, $port, 30);
            } else {
                $conn = @ftp_connect($host, $port, 30);
            }

            if (!$conn) {
                Log::error("FTP connection failed", ['host' => $host, 'port' => $port]);
                return false;
            }

            // Login
            $login = @ftp_login($conn, $username, $password);
            if (!$login) {
                Log::error("FTP login failed", ['host' => $host, 'username' => $username]);
                ftp_close($conn);
                return false;
            }

            // Set passive mode
            ftp_pasv($conn, $passive);

            return $conn;

        } catch (\Exception $e) {
            Log::error("FTP connection error", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Create directory on FTP server recursively
     *
     * @param resource $conn FTP connection
     * @param string $dir Directory path
     * @return bool
     */
    private function createFtpDirectory($conn, string $dir): bool
    {
        if ($dir == '/' || $dir == '.') {
            return true;
        }

        // Check if directory exists
        if (@ftp_chdir($conn, $dir)) {
            ftp_chdir($conn, '/');
            return true;
        }

        // Create parent directory first
        $parent = dirname($dir);
        if ($parent != '/' && $parent != '.') {
            $this->createFtpDirectory($conn, $parent);
        }

        // Create this directory
        @ftp_mkdir($conn, $dir);
        @ftp_chmod($conn, 0755, $dir);

        return true;
    }

    /**
     * Check if FTP is available and configured
     *
     * @return bool
     */
    public function isFtpAvailable(): bool
    {
        if (!$this->ftpEnabled) {
            return false;
        }

        $conn = $this->connectFtp();
        if ($conn) {
            ftp_close($conn);
            return true;
        }

        return false;
    }
}
