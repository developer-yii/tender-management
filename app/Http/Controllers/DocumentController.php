<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index()
    {
        $categoriesWithDocuments = Document::with('parameters')->get()
                    ->groupBy('category_name');
        $categories = Document::categories;
        return view('admin.documents.index', compact('categoriesWithDocuments', 'categories'));
    }

    public function addupdate(Request $request)
    {
        // pre($request->all());
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $availableCategories = Document::categories;
        $rules = [
            'category' => 'required|in:' . implode(',', $availableCategories),
            'title' => 'required|unique:documents,title,' . $request->document_id . ',id,deleted_at,NULL',
            'document_pdf' => 'nullable|mimes:pdf|max:15360',
        ];

        if (!$request->document_id) {
            $rules = array_merge($rules, [
                'document_pdf' => 'required|mimes:pdf|max:15360',
            ]);
        }

        $paramNames = $request->input('param_name', []);
        $paramValues = $request->input('param_value', []);

        // Iterate over param_name and param_value for custom validation
        foreach ($paramNames as $index => $paramName) {
            $paramValue = $paramValues[$index] ?? null;

            // Add custom validation rules for pairs
            $rules["param_name.$index"] = function ($attribute, $value, $fail) use ($paramValue, $index) {
                if (!empty($value) && empty($paramValue)) {
                    $fail("The parameter name is required");
                }
            };

            $rules["param_value.$index"] = function ($attribute, $value, $fail) use ($paramName, $index) {
                if (!empty($value) && empty($paramName)) {
                    $fail("The parameter value is required.");
                }
            };
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors(), 'data' => '']);
        }

        DB::beginTransaction();
        try {
            $document = $request->document_id ? Document::find($request->document_id) : new Document();
            if (!$document) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Certificate Not Found', 'data' => []]);
            }

            $document->category_name = $request->input('category');
            $document->title = $request->input('title');
            $document->save();

            $param_names = $request->input('param_name');
            $param_values = $request->input('param_value');
            $parameterData = [];
            $existingParameters = $document->parameters->pluck('id')->toArray();

            if (!empty($param_names)) {
                // Build an array of parameters from the input
                foreach ($param_names as $key => $param_name) {
                    $param_value = $param_values[$key] ?? null;

                    // Skip if both name and value are empty
                    if (empty($param_name) && empty($param_value)) {
                        continue;
                    }

                    // Check if the parameter already exists for this document
                    $existingParameter = $document->parameters()
                        ->where('param_name', $param_name)
                        ->first();

                    if ($existingParameter) {
                        // Update the existing parameter value
                        $existingParameter->update(['param_value' => $param_value]);
                    } else {
                        // Add a new parameter
                        $parameterData[] = [
                            'param_name' => $param_name,
                            'param_value' => $param_value,
                        ];
                    }
                }

                // Remove parameters that were not submitted in the current request
                $submittedParameterNames = array_filter($param_names);
                $parametersToRemove = $document->parameters()
                    ->whereNotIn('param_name', $submittedParameterNames)
                    ->pluck('id')
                    ->toArray();

                $document->parameters()->whereIn('id', $parametersToRemove)->delete();

                // Insert the new parameters
                if (!empty($parameterData)) {
                    $document->parameters()->createMany($parameterData);
                }
            } else {
                // If no parameters are submitted, remove all existing parameters
                $document->parameters()->delete();
            }

            // $subFolderName = "document" . $document->id;

            $files = [
                'document_pdf' => ['folder' => 'documents', 'fileName' => 'document'],
            ];

            if ($document->save()) {
                $document = fileUploadWithId($request, $document, $files);
                DB::commit();
                $message = $request->document_id ? 'Document updated successfully.' : 'Document added successfully.';
                $isNew = $request->document_id ? false : true;
                return response()->json(['status' => true, 'message' => $message, 'isNew' => $isNew, 'data' => []]);
            }else {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Error in saving data', 'data' => []]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage(), 'data' => []]);
        }

    }

    public function documentDetails(Request $request)
    {
        $categories = Document::categories;
        $document = Document::with('parameters')->find($request->id);
        if(!$document){
            return response()->json(['message' => 'Document not found.'], 404);
        }
        $this->getFilePath($document);
        return view('admin.documents.details', compact('document', 'categories'));
    }

    public function detail(Request $request)
    {
        $document = Document::with('parameters')->find($request->id);
        if(!$document){
            return response()->json(['message' => 'Document not found.'], 404);
        }
        $this->getFilePath($document);
        return response()->json($document);
    }

    private function getFilePath($document)
    {
        $document->document_pdf_url = $document->document_pdf
            ? $document->getDocumentPdfUrl()
            : null;
    }

    // public function previewWordFile($fileName)
    // {
    //     // Path to the Word file
    //     $filePath = storage_path("app/public/documents/{$fileName}");
    //     \Log::info($filePath);
    //     // Check if file exists
    //     if (!file_exists($filePath)) {
    //         abort(404, 'File not found.');
    //     }

    //     // Load the Word file
    //     $phpWord = IOFactory::load($filePath);

    //     // Save as HTML
    //     $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
    //     $previewPath = storage_path('app/public/preview.html');
    //     $htmlWriter->save($previewPath);

    //     // Display the HTML file in a view
    //     $htmlContent = file_get_contents($previewPath);
    //     return view('admin.documents.index', ['htmlContent' => $htmlContent]);
    // }

}
