<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FreeToolsController extends Controller
{
    public function carbonCalculator()
    {
        return view('public.tools.carbon_calculator');
    }

    public function calculateCarbon(Request $request)
    {
        $validated = $request->validate([
            'diesel_liters' => 'nullable|numeric|min:0',
            'natural_gas_m3' => 'nullable|numeric|min:0',
            'electricity_kwh' => 'nullable|numeric|min:0',
            'country' => 'nullable|string',
        ]);

        $dieselLiters = (float) ($validated['diesel_liters'] ?? 0);
        $gasM3 = (float) ($validated['natural_gas_m3'] ?? 0);
        $elecKwh = (float) ($validated['electricity_kwh'] ?? 0);

        // Standard emission factors
        $scope1 = ($dieselLiters * 0.00268) + ($gasM3 * 0.00203); // tCO2e
        $scope2 = $elecKwh * 0.000589; // Bangladesh/Asian average grid factor tCO2e/kWh
        $total = $scope1 + $scope2;

        return back()->with('carbonResult', [
            'scope1' => round($scope1, 3),
            'scope2' => round($scope2, 3),
            'total' => round($total, 3),
            'offset_trees' => (int) round($total * 45), // ~45 trees/tCO2e
        ])->withInput();
    }

    public function readinessQuiz()
    {
        return view('public.tools.readiness_quiz');
    }

    public function submitReadinessQuiz(Request $request)
    {
        $answers = $request->input('answers', []);
        $yesCount = count(array_filter($answers, fn ($val) => $val === 'yes'));
        $totalQuestions = 10;

        $score = round(($yesCount / $totalQuestions) * 100);

        $grade = match (true) {
            $score >= 80 => 'Leader (Advanced Readiness)',
            $score >= 50 => 'Progressing (Moderate Readiness)',
            default => 'Foundational (High Gap Exposure)',
        };

        return back()->with('quizResult', [
            'score' => $score,
            'grade' => $grade,
            'completed' => $yesCount,
            'missing' => $totalQuestions - $yesCount,
        ])->withInput();
    }

    public function materialityQuickCheck(Request $request)
    {
        $industry = $request->query('industry', 'manufacturing');

        $topicsByIndustry = [
            'manufacturing' => [
                'Wastewater Discharge & Effluent Treatment (GRI 303)',
                'Direct GHG Scope 1 Combustion & Energy Efficiency (GRI 302/305)',
                'Occupational Health & Worker Safety / LTIFR (GRI 403)',
                'Hazardous Chemical & Waste Management (GRI 306)',
                'Supply Chain Human Rights & Fair Living Wages (GRI 414)',
            ],
            'banking' => [
                'Financed Emissions (PCAF & GHG Protocol Scope 3 Cat 15)',
                'Climate Physical & Transition Risk in Loan Portfolios (TCFD/ISSB S2)',
                'Financial Inclusion, SME & Green Credit Programs (BSEC Guidelines)',
                'Information Security, Anti-Fraud & Cyber Resilience (ESRS G1)',
                'Anti-Money Laundering & Transparent Governance (GRI 205)',
            ],
            'textiles' => [
                'ZDHC Chemical Compliance & Water Effluent Quality (Higg Index FEM)',
                'Primary Scope 3 Cotton/Fabric Traceability (EUDR Compliance)',
                'Labor Rights, Working Hours & Overtime Compliance (Turnstile Synced)',
                'Thermal Energy Optimization (Biomass / Gas Turbines)',
                'Circular Fashion, Fabric Waste Diversion & Recycled Blends (DPP)',
            ],
            'technology' => [
                'Data Center Scope 2 Market PPA & Renewable Energy (GHG Protocol)',
                'E-Waste Recycling & Hardware Circularity (GRI 306)',
                'Data Privacy, GDPR & AI Ethics Oversight (ESRS G1)',
                'Diversity, Equity & Inclusion in Tech Workforce (GRI 405)',
                'Remote Workforce Wellness & Mental Health Support',
            ],
        ];

        $topics = $topicsByIndustry[$industry] ?? $topicsByIndustry['manufacturing'];

        return view('public.tools.materiality_check', compact('industry', 'topics', 'topicsByIndustry'));
    }
}
