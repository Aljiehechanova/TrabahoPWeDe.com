<?php
require_once __DIR__ . '/vendor/Client_API/vendor/autoload.php';

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

/**
 * Extracts full detected text from an image using Google Cloud Vision API.
 *
 * @param string $imagePath
 * @return string|false
 */
function extractTextFromImage($imagePath) {
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/../vendor/Client_API/vision_key.json');

    $imageAnnotator = new ImageAnnotatorClient();

    try {
        $image = @file_get_contents($imagePath);
        if ($image === false) {
            return "Could not read image file.";
        }

        $response = $imageAnnotator->textDetection($image);
        $texts = $response->getTextAnnotations();

        if (!empty($texts)) {
            $fullText = $texts[0]->getDescription();
            // Normalize the text for easier parsing (remove excess line breaks)
            return preg_replace('/[\r\n]+/', ' ', $fullText);
        } else {
            return false;
        }

    } catch (Exception $e) {
        return "API Error: " . $e->getMessage();
    } finally {
        $imageAnnotator->close();
    }
}
