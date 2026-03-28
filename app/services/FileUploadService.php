<?php

class FileUploadService
{
    private int $maxFileSize = 5 * 1024 * 1024; // 5MB

    private array $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    /**
     * Validate file upload
     */
    public function validateFile(array $file): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return $this->error('File upload không hợp lệ');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $this->error($this->getUploadErrorMessage($file['error']));
        }

        if ($file['size'] > $this->maxFileSize) {
            return $this->error('File vượt quá 5MB');
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            return $this->error('File upload không hợp lệ');
        }

        // Kiểm tra MIME thật
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);

        if (!array_key_exists($mime, $this->allowedMimes)) {
            return $this->error('Chỉ chấp nhận file hình ảnh (jpg, png, gif, webp)');
        }

        return [
            'valid' => true,
            'mime'  => $mime,
            'ext'   => $this->allowedMimes[$mime]
        ];
    }

    /**
     * Upload file
     */
    public function uploadFile(array $file, string $uploadDir, ?string $oldFileName = null): array
    {
        $validation = $this->validateFile($file);

        if (!$validation['valid']) {
            return [
                'success'  => false,
                'fileName' => null,
                'error'    => $validation['error']
            ];
        }

        if (!$this->createDirectory($uploadDir)) {
            return $this->errorResponse('Không thể tạo thư mục upload');
        }

        // Random tên file an toàn
        $newFileName = bin2hex(random_bytes(16)) . '.' . $validation['ext'];
        $targetPath  = rtrim($uploadDir, '/') . '/' . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $this->errorResponse('Không thể upload file');
        }

        // Xóa file cũ nếu có
        if ($oldFileName) {
            $oldPath = rtrim($uploadDir, '/') . '/' . $oldFileName;
            $this->deleteFile($oldPath);
        }

        return [
            'success'  => true,
            'fileName' => $newFileName,
            'error'    => null
        ];
    }

    /**
     * Xóa file
     */
    public function deleteFile(string $filePath): bool
    {
        return is_file($filePath) ? unlink($filePath) : false;
    }

    /**
     * Xóa thư mục (recursive nếu cần)
     */
    public function deleteDirectory(string $dirPath, bool $force = false): bool
    {
        if (!is_dir($dirPath)) {
            return false;
        }

        $files = array_diff(scandir($dirPath), ['.', '..']);

        if (!$force && !empty($files)) {
            return false;
        }

        foreach ($files as $file) {
            $path = $dirPath . DIRECTORY_SEPARATOR . $file;
            is_dir($path)
                ? $this->deleteDirectory($path, true)
                : unlink($path);
        }

        return rmdir($dirPath);
    }

    /**
     * Tạo thư mục an toàn
     */
    private function createDirectory(string $dirPath): bool
    {
        if (!file_exists($dirPath)) {
            return mkdir($dirPath, 0755, true);
        }

        return true;
    }

    /**
     * Chuẩn hóa error response
     */
    private function error(string $message): array
    {
        return [
            'valid' => false,
            'error' => $message
        ];
    }

    private function errorResponse(string $message): array
    {
        return [
            'success'  => false,
            'fileName' => null,
            'error'    => $message
        ];
    }

    /**
     * Mapping lỗi upload PHP
     */
    private function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE   => 'File vượt quá cấu hình server',
            UPLOAD_ERR_FORM_SIZE  => 'File vượt quá giới hạn form',
            UPLOAD_ERR_PARTIAL    => 'File upload chưa hoàn tất',
            UPLOAD_ERR_NO_FILE    => 'Không có file được chọn',
            UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file',
            UPLOAD_ERR_EXTENSION  => 'Upload bị chặn bởi extension',
            default               => 'Lỗi upload không xác định'
        };
    }
}
