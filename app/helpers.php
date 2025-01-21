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

if (!function_exists('getDocumentPath')) {
    function getDocumentPath($fileName)
    {
        if (!$fileName) {
            return '#';
        }

        return asset('storage/company-documents/' . $fileName);
    }
}

function fileUploadWithId($request, $model, $filesConfig) {
    foreach ($filesConfig as $field => $config) {
        if ($request->hasFile($field)) {
            // $oldFile = $model->{$field};
            // if ($oldFile) {
            //     Storage::delete("public/{$config['folder']}/{$oldFile}");
            // }

            $dir = "public/{$config['folder']}/";
            $extension = $request->file($field)->getClientOriginalExtension();
            $filename = $config['fileName'] .'_'. $model->id . '.' . $extension;

            $model->{$field} = $filename;
            Storage::disk('local')->put($dir . $filename, File::get($request->file($field)));
            $model->save();
        }
    }
    return $model;
}

function handleFileUploads($request, $model, $filesConfig) {
    foreach ($filesConfig as $field => $config) {
        // \Log::info($request->hasFile($field));
        if ($request->hasFile($field)) {
            // Check if multiple files are being uploaded
            if ($config['multiple']) {

                foreach ($request->file($field) as $file) {
                    // Process each file
                    $fileData = uploadFile($file, $config['folder'], $config['subFolder']);

                    $extension = $file->getClientOriginalExtension();
                    $original_file_name = $file->getClientOriginalName();

                    // Check if the extension is '.doc' and modify the file name
                    if ($extension === 'doc') {
                        // You can modify the name here as needed. For example, adding '_doc' suffix to the original name
                        $original_file_name = pathinfo($original_file_name, PATHINFO_FILENAME) . ".docx";
                    }

                    $model->files()->create([
                        'type' => $config['type'],
                        'folder_name' => $config['folderName'] ?? null,
                        'original_file_name' => $original_file_name,
                        'file_path' => $fileData['filename'],
                        'docx_preview' => $fileData['firstPagePdfPath'] ?? null,
                    ]);
                }
            } else {
                // Handle a single file upload
                $oldFile = $model->{$field};
                $fileData = uploadFile($request->file($field), $config['folder'], $config['subFolder'], $oldFile);

                // Save the file name or path
                $model->{$field} = $fileData['filename'];

                // Assign the first-page PDF preview if available
                if (isset($fileData['firstPagePdfPath'])) {
                    $model->docx_preview = $fileData['firstPagePdfPath'];
                }
            }
        }
    }
    return $model;
}


// function handleFileUploads($request, $model, $filesConfig) {
//     foreach ($filesConfig as $field => $config) {
//         if ($request->hasFile($field)) {
//             $oldFile = $model->{$field};
//             $fileData = uploadFile($request->file($field), $config['folder'], $config['subFolder'], $oldFile);

//             // Assign the uploaded file name
//             $model->{$field} = $fileData['filename'];

//             // Assign the first-page PDF preview if available
//             if (isset($fileData['firstPagePdfPath'])) {
//                 $model->docx_preview = $fileData['firstPagePdfPath'];
//             }
//         }
//     }
//     return $model;
// }

if (!function_exists('uploadFile')) {
    function uploadFile($file, $mainFolder, $subFolder = null, $oldFile = null)
    {
        // Delete old file if exists
        if ($oldFile) {
            Storage::delete("public/{$mainFolder}/{$subFolder}/{$oldFile}");
        }

        $dir = "public/{$mainFolder}/{$subFolder}/";
        $outputDir = storage_path("app/{$dir}");

        // Ensure the directory exists and set permissions to 755
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true); // Create directory with 755 permission
        }

        $dir = "public/{$mainFolder}/{$subFolder}/";
        $extension = $file->getClientOriginalExtension();
        $uniqueName = uniqid() . "_" . time();
        $filename = $uniqueName . '.' . $extension;

        Storage::disk('local')->put($dir . $filename, File::get($file));
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

if (!function_exists('convertDocToDocx')) {
    function convertDocToDocx($docPath)
    {
        $docxPath = str_replace('.doc', '.docx', $docPath);

        $sofficePath = (PHP_OS_FAMILY === 'Windows')
                ? '"C:\\Program Files\\LibreOffice\\program\\soffice.exe"'
                : 'soffice';

        $command = $sofficePath . ' --headless --convert-to docx ' . escapeshellarg($docPath) . ' --outdir ' . escapeshellarg(dirname($docPath));

        // $command = 'libreoffice --headless --convert-to docx ' . escapeshellarg($docPath) . ' --outdir ' . escapeshellarg(dirname($docPath));

        // $command = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe" --headless --convert-to docx ' . escapeshellarg($docPath) . ' --outdir ' . escapeshellarg(dirname($docPath));
        putenv('HOME=/tmp');
        putenv('DISPLAY=:0');
        exec($command . ' 2>&1', $output, $resultCode);
        if ($resultCode !== 0) {
            \Log::error("LibreOffice conversion failed for {$docPath}: " . implode("\n", $output));
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

        // $command = $sofficePath . ' --headless --convert-to pdf ' . escapeshellarg($docxPath) . ' --outdir ' . escapeshellarg(dirname($outputImagePath));
        // $command = 'libreoffice --headless --convert-to pdf ' . escapeshellarg($docxPath) . ' --outdir ' . escapeshellarg(dirname($outputImagePath));

        $command = $sofficePath . ' --headless --convert-to pdf ' . escapeshellarg($docxPath) . ' --outdir ' . escapeshellarg($outputImagePath);
        exec($command, $output, $resultCode);

        // Check if the conversion was successful
        putenv('HOME=/tmp');
        putenv('DISPLAY=:0');
        // exec($command, $output, $resultCode);
        exec($command . ' 2>&1', $output, $resultCode);
        \Log::info("pdf command : ".$command);
        \Log::info("pdf output : ".json_encode($output));
        \Log::info("pdf resultCode : ".json_encode($resultCode));

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
        // $gsCommand = 'gs -sDEVICE=pdfwrite -dFirstPage=1 -dLastPage=1 -o ' . escapeshellarg($firstPagePdfPath) . ' ' . escapeshellarg($fullPdfPath);

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

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'd.m.Y | h:i')
    {
        return $date ? \Carbon\Carbon::parse($date)->format($format) : null;
    }
}

if (!function_exists('getTenderFiles')) {
    function getTenderFiles($tender, $type, $groupBy = null)
    {
        $query = $tender->files()->where('type', $type);

        if ($groupBy) {
            return $query->get()->groupBy($groupBy);
        }

        return $query->get();
    }
}

if (!function_exists('getRemainingDays')) {
    function getRemainingDays($date)
    {
        $targetDate = Carbon::parse($date);
        $today = Carbon::now();
        return $today->diffInDays($targetDate, false);
    }
}

if (!function_exists('getRemainingDaysMessage')) {
    function getRemainingDaysMessage($date)
    {
        $remainingDays = getRemainingDays($date);

        if ($remainingDays >= 0) {
            return "noch {$remainingDays} Tage";
        } else {
            return 'Ablaufdatum überschritten';
        }
    }
}

if (!function_exists('getTenderMainImage')) {
    function getTenderMainImage($tender)
    {
        if ($tender->files && $tender->files->isNotEmpty()) {
            $mainFile = $tender->files->first(); // You might want to adjust if there's a 'main' file type to prioritize
            if ($mainFile && $mainFile->file_path) {
                $filePath = 'storage/tenders/tender' . $tender->id . '/' . $mainFile->file_path;
                return asset($filePath);
            }
        }

        return asset('assest/images/image_not_available.png');
    }
}




