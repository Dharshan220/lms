<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();

        $certificates = Certificate::where('user_id', $student->id)
            ->with(['course', 'teacher'])
            ->latest('issued_at')
            ->paginate(15);

        return view('student.certificates.index', compact('certificates'));
    }

    public function download(Request $request, Certificate $certificate)
    {
        $student = $request->user();
        abort_unless($certificate->user_id === $student->id, 403);

        if ($certificate->pdf_path && \Storage::disk('public')->exists($certificate->pdf_path)) {
            return \Storage::disk('public')->download($certificate->pdf_path, "certificate-{$certificate->certificate_number}.pdf");
        }

        return redirect()->route('student.certificates.index')
            ->with('error', 'Certificate PDF is not available for download yet.');
    }
}
