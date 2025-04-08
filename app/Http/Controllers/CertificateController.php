<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use Spatie\Browsershot\Browsershot;
use Imagick;
use ImagickPixel;
use ImagickDraw;

class CertificateController extends Controller
{
    public function index()
    {
        $categoriesWithCertificates = Certificate::all()
                    ->groupBy('category_name');
        $categories = Certificate::categories;
        return view('admin.certificates.index', compact('categories', 'categoriesWithCertificates'));
    }

    public function addupdate(Request $request)
    {
        if(!$request->ajax()){
            return response()->json(['status' => 400, 'message' => trans('message.invalid-request'), 'data' => []]);
        }

        $availableCategories = Certificate::categories;
        $rules = [
            'category' => 'required|in:' . implode(',', $availableCategories),
            'title' => 'required|unique:certificates,title,' . $request->certificate_id . ',id,deleted_at,NULL',
            'description' => 'required',
            'certificate_word' => 'nullable|mimes:doc,docx|max:15360',
            'certificate_pdf' => 'nullable|mimes:pdf|max:15360',
        ];

        if (!$request->certificate_id) {
            $rules = array_merge($rules, [
                'logo' => 'required|image|max:2048',
                'certificate_word' => 'required|mimes:doc,docx|max:15360',
                'certificate_pdf' => 'required|mimes:pdf|max:15360',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {
            // Check if both start_date and end_date are blank
            if (empty($request->start_date) && empty($request->end_date)) {
                $validator->errors()->add('start_date', 'Startdatum und Enddatum sind erforderlich.');
                $validator->errors()->add('end_date', 'Startdatum und Enddatum sind erforderlich.');
            }
            // Check if start_date is blank
            elseif (empty($request->start_date)) {
                $validator->errors()->add('start_date', 'Startdatum ist erforderlich.');
            }
            // Check if end_date is blank
            elseif (empty($request->end_date)) {
                $validator->errors()->add('end_date', 'Enddatum ist erforderlich.');
            }
            // Check if end_date is after or equal to start_date
            elseif (!empty($request->start_date) && strtotime($request->end_date) < strtotime($request->start_date)) {
                $validator->errors()->add('end_date', 'Das Enddatum muss nach oder gleich dem Startdatum liegen.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors(), 'data' => '']);
        }

        DB::beginTransaction();
        try {
            $certificate = $request->certificate_id ? Certificate::find($request->certificate_id) : new Certificate();
            if (!$certificate) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => trans('message.certificate-not-found'), 'data' => []]);
            }

            $certificate->category_name = $request->input('category');
            $certificate->title = $request->input('title');
            $certificate->description = $request->input('description');
            $certificate->valid_from_date = $request->input('start_date');
            $certificate->valid_to_date = $request->input('end_date');
            $certificate->save();

            $subFolderName = "certificate" . $certificate->id;

            $files = [
                'logo' => ['multiple' => false, 'folder' => 'certificates', 'subFolder' => $subFolderName],
                'certificate_pdf' => ['multiple' => false, 'folder' => 'certificates', 'subFolder' => $subFolderName],
                'certificate_word' => ['multiple' => false, 'folder' => 'certificates','subFolder' => $subFolderName],
            ];

            $certificate = handleFileUploads($request, $certificate, $files);
            $certificate->save();

            DB::commit();

            $message = $request->certificate_id
                        ? trans('message.Certificate updated successfully')
                        : trans('message.Certificate added successfully');

            $isNew = $request->certificate_id ? false : true;
            return response()->json(['status' => true, 'message' => $message, 'isNew' => $isNew, 'data' => []]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage(), 'data' => []]);
        }

    }

    public function certificateDetails(Request $request)
    {
        $categories = Certificate::categories;
        $certificate = Certificate::find($request->id);
        if(!$certificate){
            abort(404);
        }
        $this->getFilePath($certificate);
        return view('admin.certificates.details', compact('certificate', 'categories'));
    }

    public function detail(Request $request)
    {
        $certificate = Certificate::find($request->id);
        if(!$certificate){
            return response()->json(['message' => trans('message.certificate-not-found')], 404);
        }

        $this->getFilePath($certificate);
        return response()->json($certificate);
    }

    private function getFilePath($certificate)
    {
        $certificate->logo_url = $certificate->logo
            ? $certificate->getLogoUrl()
            : null;

        $certificate->certificate_pdf_url = $certificate->certificate_pdf
            ? $certificate->getCertificatePdfUrl()
            : null;

        $certificate->certificate_word_url = $certificate->certificate_word
            ? $certificate->getCertificateWordUrl()
            : null;
    }
}
