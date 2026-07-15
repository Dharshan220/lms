<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['user', 'course', 'teacher']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('course', function ($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $certificates = $query->latest('issued_at')->paginate(15);

        return view('admin.certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['user', 'course', 'teacher']);

        return view('admin.certificates.show', compact('certificate'));
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }
}
