<?php

namespace App\Http\Controllers;

use App\Models\AssuranceEngagement;
use App\Models\Organization;
use App\Models\ReportProject;
use App\Models\Supplier;
use App\Models\SupplierAssessment;
use App\Models\WhistleblowerCase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicPortalController extends Controller
{
    public function supplierPortal(Request $request)
    {
        $code = $request->query('code');
        $supplier = $code ? Supplier::where('code', $code)->first() : null;

        return view('public.portals.supplier', compact('supplier', 'code'));
    }

    public function submitSupplierAssessment(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'questionnaire_name' => 'required|string',
            'score_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $supplier = Supplier::findOrFail($validated['supplier_id']);

        $assessment = SupplierAssessment::create([
            'organization_id' => $supplier->organization_id,
            'supplier_id' => $supplier->id,
            'questionnaire_name' => $validated['questionnaire_name'],
            'score_percentage' => $validated['score_percentage'],
            'status' => 'submitted',
        ]);

        return back()->with('success', "Assessment submitted successfully! Score: {$assessment->score_percentage}%");
    }

    public function investorPortal()
    {
        $publishedReports = ReportProject::whereIn('status', ['published', 'locked'])
            ->with('organization')
            ->latest('published_at')
            ->get();

        return view('public.portals.investor', compact('publishedReports'));
    }

    public function auditorDataRoom(string $token)
    {
        $engagement = AssuranceEngagement::with(['organization', 'workpapers'])->first();

        return view('public.portals.auditor_dataroom', compact('engagement', 'token'));
    }

    public function whistleblowerForm()
    {
        $organizations = Organization::all();

        return view('public.portals.whistleblower', compact('organizations'));
    }

    public function submitWhistleblowerReport(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'allegation_type' => 'required|string',
            'details' => 'required|string|min:20',
        ]);

        $token = 'ANON-'.strtoupper(Str::random(12));

        $case = WhistleblowerCase::create([
            'organization_id' => $validated['organization_id'],
            'case_token' => $token,
            'category' => $validated['allegation_type'],
            'subject' => 'Confidential Allegation Report',
            'report_details' => $validated['details'],
            'status' => 'submitted',
            'priority' => 'high',
        ]);

        return back()->with('token', $token);
    }
}
