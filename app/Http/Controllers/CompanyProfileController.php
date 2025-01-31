<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompanyProfileController extends Controller
{
    public function index()
    {
        $companyData = Company::first();
        return view('admin.company-details.index', compact('companyData'));
    }

    public function update(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $company = Company::find(1) ?? new Company();

        $rules = [
            'name' => 'required',
            'art' => 'required',
            'address' => 'required',
            'managing_director' => 'required',
            'bank_name' => 'required',
            'iban_number' => 'required',
            'bic_number' => 'required',
            'ust_id' => 'required',
            'trade_register' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'website_url' => 'required',
            'company_presentation_word' => 'nullable|mimes:doc,docx',
            'company_presentation_pdf' => 'nullable|mimes:pdf',
            'agile_framework_word' => 'nullable|mimes:doc,docx',
            'agile_framework_pdf' => 'nullable|mimes:pdf',
        ];

        if (!$company->exists) {
            $rules = array_merge($rules, [
                'company_presentation_word' => 'required|mimes:doc,docx',
                'company_presentation_pdf' => 'required|mimes:pdf',
                'agile_framework_word' => 'required|mimes:doc,docx',
                'agile_framework_pdf' => 'required|mimes:pdf',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $result = ['status' => false, 'error' => $validator->errors(), 'data' => ''];
            return response()->json($result);
        }

        $company->fill([
            'name' => $request->input('name'),
            'type' => $request->input('art'),
            'address' => $request->input('address'),
            'managing_director' => $request->input('managing_director'),
            'bank_name' => $request->input('bank_name'),
            'iban_number' => $request->input('iban_number'),
            'bic_number' => $request->input('bic_number'),
            'vat_id' => $request->input('ust_id'),
            'trade_register' => $request->input('trade_register'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'website_url' => $request->input('website_url'),
        ]);

        $files = [
            'company_presentation_word' => ['name' => 'company-presentation', 'filedName' => 'company_presentation_word_original_file_name', 'preview' => 'company_presentation_docx_preview'],
            'company_presentation_pdf' => ['name' => 'company-presentation', 'filedName' => 'company_presentation_pdf_original_file_name', 'preview' => ''],
            'agile_framework_word' => ['name' => 'agile-framework', 'filedName' => 'agile_framework_word_original_file_name', 'preview' => 'agile_framework_docx_preview'],
            'agile_framework_pdf' => ['name' => 'agile-framework', 'filedName' => 'agile_framework_pdf_original_file_name', 'preview' => ''],
        ];

        foreach ($files as $field => $config) {
            if ($request->hasFile($field)) {
                $oldFile = $company->{$field};

                $fileData = uploadFile($request->file($field), 'company-documents', $config['name'], $oldFile);

                // Update the company fields with the uploaded file name and the first page PDF path if it's a docx
                $company->{$field} = $fileData['filename'];

                $originalFileName = getOriginaFileName($request->file($field));
                $company->{$config['filedName']} = $originalFileName;

                if ($fileData['firstPagePdfPath']) {
                    $company->{$config['preview']} = $fileData['firstPagePdfPath'];
                }

                // $company->{$field} = $this->uploadFile($request->file($field), $config['folder'], $config['name'], $oldFile);
                // $company->{$config['fieldName']} = $request->file($field)->getClientOriginalName();
            }
        }

        if ($company->save()) {
            $message = 'Company Details updated successfully.';
            return response()->json(['status' => true, 'message' => $message, 'data' => []]);
        }

        // Handle failure in saving data
        return response()->json(['status' => false, 'message' => 'Failed to update company details.', 'data' => []], 500);
    }

    // private function uploadFile($file, $folder, $fileName, $oldFile = null)
    // {
    //     // Delete old file if exists
    //     if ($oldFile) {
    //         Storage::delete("public/{$folder}/{$oldFile}");
    //     }

    //     $dir = "public/{$folder}/";
    //     $extension = $file->getClientOriginalExtension();
    //     $filename = "{$fileName}.{$extension}";

    //     $dirPath = storage_path("app/{$dir}");
    //     if (!is_dir($dirPath)) {
    //         mkdir($dirPath, 0755, true); // Create directory with 0755 permissions
    //     }

    //     Storage::disk('local')->put($dir . $filename, File::get($file));

    //     $filePath = storage_path("app/{$dir}{$filename}");

    //     if ($extension === 'doc') {
    //         $convertedFilePath = convertDocToDocx($filePath);
    //         if ($convertedFilePath) {
    //             $filePath = $convertedFilePath;
    //             $extension = 'docx'; // Update extension after conversion
    //             $filename = "{$fileName}.{$extension}";
    //         } else {
    //             throw new \Exception("Failed to convert .doc to .docx for file: {$filePath}");
    //         }
    //     }
    //     return $filename;
    // }

    public function detail(Request $request)
    {
        $company = Company::find(1);
        if (!$company) {
            return response()->json(['status' => false, 'message' => 'Company not found', 'data' => []], 404);
        }
        return response()->json($company);
    }
}
