<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class ImageWatermarkService
{
    /**
     * Add a logo watermark at the center of the image with low opacity
     *
     * @param string $imagePath Path of the original image
     * @param string $logoPath Path of the watermark logo
     * @param string $savePath Path to save the watermarked image
     * @param int $opacity Opacity of the watermark (0-100)
     */
    public function addLogoWatermark(string $imagePath, string $logoPath, string $savePath, int $opacity = 30)
    {
        // Ensure save directory exists
        $dir = dirname($savePath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        // Load main image
        $img = imagecreatefromstring(file_get_contents($imagePath));

        // Load watermark logo
        $logo = imagecreatefromstring(file_get_contents($logoPath));

        // Get dimensions
        $imgWidth = imagesx($img);
        $imgHeight = imagesy($img);

        // -------------------------------
        // 🔥 Resize Logo (Increase Size)
        // -------------------------------
        $scale = 0.50; // = 40% of image width (increase this to make bigger)
        $newLogoWidth  = intval($imgWidth * $scale);
        $newLogoHeight = intval($newLogoWidth * (imagesy($logo) / imagesx($logo)));

        // Create resized logo
        $resizedLogo = imagecreatetruecolor($newLogoWidth, $newLogoHeight);
        imagesavealpha($resizedLogo, true);
        $transColour = imagecolorallocatealpha($resizedLogo, 0, 0, 0, 127);
        imagefill($resizedLogo, 0, 0, $transColour);

        imagecopyresampled(
            $resizedLogo,
            $logo,
            0,
            0,
            0,
            0,
            $newLogoWidth,
            $newLogoHeight,
            imagesx($logo),
            imagesy($logo)
        );

        imagedestroy($logo);
        $logo = $resizedLogo;
        // -------------------------------

        $logoWidth = imagesx($logo);
        $logoHeight = imagesy($logo);

        // Center position
        $x = ($imgWidth - $logoWidth) / 2;
        $y = ($imgHeight - $logoHeight) / 2;

        // Merge logo with opacity
        $this->imagecopymerge_alpha($img, $logo, $x, $y, 0, 0, $logoWidth, $logoHeight, $opacity);

        // Save final
        imagejpeg($img, $savePath, 90);

        // Free memory
        imagedestroy($img);
        imagedestroy($logo);
    }


    /**
     * Copy merge with alpha support
     */
    private function imagecopymerge_alpha($dstImg, $srcImg, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH, $opacity)
    {
        $opacity /= 100;

        // Create a cut resource
        $cut = imagecreatetruecolor($srcW, $srcH);

        // Copy relevant section
        imagecopy($cut, $dstImg, 0, 0, $dstX, $dstY, $srcW, $srcH);

        // Copy source logo
        imagecopy($cut, $srcImg, 0, 0, $srcX, $srcY, $srcW, $srcH);

        // Merge with opacity
        imagecopymerge($dstImg, $cut, $dstX, $dstY, 0, 0, $srcW, $srcH, $opacity * 100);

        imagedestroy($cut);
    }
}
