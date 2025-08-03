<?php

namespace App\Services;

use App\Models\User;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

class QrCodeService
{
    /**
     * Generate or retrieve a user's scan code
     */
    public function getOrCreateScanCode(User $user): string
    {
        if (!$user->scan_code) {
            $user->scan_code = $user->gym_id . '_' . $user->id . '_' . time();
            $user->save();
        }
        
        return $user->scan_code;
    }

    /**
     * Generate QR code for a user
     */
    public function generateQrCode(User $user, int $size = 300): string
    {
        $scanCode = $this->getOrCreateScanCode($user);
        $attendanceUrl = route('attendance.qr-codes.record', ['token' => $scanCode]);
        
        $qrCode = new QrCode(
            data: $attendanceUrl,
            size: $size,
            margin: 10,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getString();
    }

    /**
     * Generate QR code image as base64 string
     */
    public function generateQrCodeBase64(User $user, int $size = 300): string
    {
        return base64_encode($this->generateQrCode($user, $size));
    }
} 