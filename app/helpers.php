<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

if (!function_exists('pre')) {
    function pre($text)
    {
        print "<pre>";
        print_r($text);
        exit();
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        $user = Auth::user();
        return $user && ($user->role == 1);
    }
}

if (!function_exists('isEmployee')) {
    function isEmployee()
    {
        $user = Auth::user();
        return $user && ($user->role == 2);
    }
}

if (!function_exists('formatDateRange')) {
    function formatDateRange($startDate, $endDate)
    {
        return Carbon::parse($startDate)->translatedFormat('M. Y') . '–' . Carbon::parse($endDate)->translatedFormat('M. Y');
    }
}

function handleFileUploads($request, $model, $filesConfig) {
    foreach ($filesConfig as $field => $config) {
        if ($request->hasFile($field)) {
            $oldFile = $model->{$field};
            $fileData = uploadFile($request->file($field), $config['folder'], $config['subFolder'], $oldFile);

            // Assign the uploaded file name
            $model->{$field} = $fileData['filename'];

            // Assign the first-page PDF preview if available
            if (isset($fileData['firstPagePdfPath'])) {
                $model->docx_preview = $fileData['firstPagePdfPath'];
            }
        }
    }
    return $model;
}


// if (!function_exists('handleFileUploads')) {
//     function handleFileUploads($request, $model, $filesConfig) {
//         foreach ($filesConfig as $field => $config) {
//             if ($request->hasFile($field)) {
//                 $oldFile = $model->{$field};
//                 $model->{$field} = uploadFile($request->file($field), $config['folder'], $config['subFolder'], $oldFile);
//             }
//         }
//         return $model;
//     }
// }

if (!function_exists('uploadFile')) {
    function uploadFile($file, $mainFolder, $subFolder, $oldFile = null)
    {
        // Delete old file if exists
        if ($oldFile) {
            Storage::delete("public/{$mainFolder}/{$subFolder}/{$oldFile}");
        }

        $dir = "public/{$mainFolder}/{$subFolder}/";
        $extension = $file->getClientOriginalExtension();
        $uniqueName = uniqid() . "_" . time();
        $filename = $uniqueName . '.' . $extension;

        Storage::disk('local')->put($dir . $filename, File::get($file));

        $outputDir = storage_path("app/{$dir}");

        $filePath = storage_path("app/{$dir}{$filename}");

        if ($extension === 'doc') {
            $convertedFilePath = convertDocToDocx($filePath);
            if ($convertedFilePath) {
                $filePath = $convertedFilePath;
                $extension = 'docx'; // Update extension after conversion
                $filename = $uniqueName. '.' . $extension;
            } else {
                throw new \Exception("Failed to convert .doc to .docx for file: {$filePath}");
            }
        }

        $firstPagePdfPath = null;
        if ($extension === 'docx') {
            $firstPagePdfPath = convertDocxtoPdf($filePath, $outputDir, $uniqueName);
        }

        return [
            'filename' => $filename,
            'firstPagePdfPath' => $firstPagePdfPath,
        ];

        // return $filename;
    }
}

if (!function_exists('convertDocToDocx')) {
    function convertDocToDocx($docPath)
    {
        $docxPath = str_replace('.doc', '.docx', $docPath);

        $sofficePath = (PHP_OS_FAMILY === 'Windows')
                ? '"C:\\Program Files\\LibreOffice\\program\\soffice.exe"'
                : 'soffice';

        $command = $sofficePath . ' --headless --convert-to docx ' . escapeshellarg($docPath) . ' --outdir ' . escapeshellarg(dirname($docPath));

        // $command = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe" --headless --convert-to docx ' . escapeshellarg($docPath) . ' --outdir ' . escapeshellarg(dirname($docPath));
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            return false;
        }
        unlink($docPath);
        return $docxPath;
    }
}

if (!function_exists('convertDocxtoPdf')) {
    function convertDocxtoPdf($docxPath, $outputImagePath, $filenameonly)
    {
        // Convert DOCX to PDF using LibreOffice
        $sofficePath = (PHP_OS_FAMILY === 'Windows')
                ? '"C:\\Program Files\\LibreOffice\\program\\soffice.exe"'
                : 'soffice';

        $command = $sofficePath . ' --headless --convert-to docx ' . escapeshellarg($docxPath) . ' --outdir ' . escapeshellarg(dirname($docxPath));

        // $command = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe" --headless --convert-to pdf ' . escapeshellarg($docxPath) . ' --outdir ' . escapeshellarg($outputImagePath);
        exec($command, $output, $resultCode);

        // Check if the conversion was successful
        if ($resultCode !== 0) {
            throw new \Exception("Failed to convert DOCX to PDF. Command output: " . implode("\n", $output));
        }

        // Full PDF path
        $fullPdfPath = $outputImagePath . $filenameonly . '.pdf';

        // Check if the PDF was created
        if (!file_exists($fullPdfPath)) {
            throw new \Exception("PDF file not found: " . $fullPdfPath);
        }

        // Extract only the first page using pdftk
        $firstPagePdfPath = $outputImagePath . $filenameonly . '_first_page.pdf';
        $firstPagePdfFileName = $filenameonly . '_first_page.pdf';
        extractFirstPageWithGhostscript($fullPdfPath, $firstPagePdfPath);

        return $firstPagePdfFileName;
    }
}

if (!function_exists('extractFirstPageWithGhostscript')) {
    function extractFirstPageWithGhostscript($fullPdfPath, $firstPagePdfPath)
    {
        // Update this path if needed based on your installation
        $gsPath = (PHP_OS_FAMILY === 'Windows')
            ? '"C:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe"'
            : 'gs';

        $gsCommand = $gsPath . ' -sDEVICE=pdfwrite -dFirstPage=1 -dLastPage=1 -o ' . escapeshellarg($firstPagePdfPath) . ' ' . escapeshellarg($fullPdfPath);

        // $gsCommand = '"C:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe" -sDEVICE=pdfwrite -dFirstPage=1 -dLastPage=1 -o ' . escapeshellarg($firstPagePdfPath) . ' ' . escapeshellarg($fullPdfPath);

        exec($gsCommand, $output, $resultCode);

        // Log the result
        error_log("Ghostscript command output: " . implode("\n", $output));
        error_log("Ghostscript command result code: " . $resultCode);

        unlink($fullPdfPath);
        return $resultCode === 0;
    }
}


if (!function_exists('formatDateToGerman')) {
    function formatDateToGerman($date)
    {
        if (!$date) {
            return null;
        }
        return \Carbon\Carbon::parse($date)->format('d.m.Y');
    }
}


