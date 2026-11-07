<?php

namespace App\Http\Controllers;

use App\Models\Framework;
use App\Models\Module;
use App\Models\Plan;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PublicSiteController extends Controller
{
    public function home()
    {
        $totalModules = Module::count();
        $totalFrameworks = Framework::count();
        $plans = Plan::where('is_active', true)->get();
        $presets = Preset::all();

        return view('public.home', compact('totalModules', 'totalFrameworks', 'plans', 'presets'));
    }

    public function features()
    {
        $modulesByCategory = Module::all()->groupBy('category');

        return view('public.features', compact('modulesByCategory'));
    }

    public function frameworks()
    {
        $frameworks = Framework::withCount('disclosures')->get();

        return view('public.frameworks', compact('frameworks'));
    }

    public function frameworkDetail(string $slug)
    {
        $framework = Framework::where('slug', $slug)->with('disclosures')->firstOrFail();

        return view('public.framework_detail', compact('framework'));
    }

    public function industries(?string $industry = null)
    {
        $presets = Preset::all();
        $selectedPreset = $industry ? Preset::where('key', $industry)->first() : $presets->first();

        return view('public.industries', compact('presets', 'selectedPreset'));
    }

    public function roles(?string $role = null)
    {
        $rolesList = [
            'esg_manager' => ['title' => 'For ESG Managers & Sustainability Directors', 'desc' => 'Automate end-to-end data collection, Scope 1-3 calculations, and AI-written GRI/CSRD reports with zero hallucination.'],
            'external_auditor' => ['title' => 'For External Assurance & Audit Firms', 'desc' => 'Independent audit workspace, cryptographic hash logs, sampling workpapers, and ISAE 3000 sign-off.'],
            'finance_cfo' => ['title' => 'For CFOs & Finance Teams', 'desc' => 'Link sustainability to Capex/Opex, track green financing eligibility, carbon pricing, and automated investor disclosures.'],
            'procurement' => ['title' => 'For Supply Chain & Procurement Officers', 'desc' => 'Primary Scope 3 supplier data ingestion, Higg Index assessments, and automated EUDR compliance.'],
            'board_member' => ['title' => 'For Board Members & Executive Leadership', 'desc' => 'Executive level oversight, real-time ESG risk heatmaps, peer benchmarking, and one-click report approval.'],
        ];

        $currentRole = $role && isset($rolesList[$role]) ? $role : 'esg_manager';

        return view('public.roles', compact('rolesList', 'currentRole'));
    }

    public function modulesExplorer()
    {
        $modules = Module::all();
        $presets = Preset::all();
        $plans = Plan::where('is_active', true)->get();

        return view('public.modules_explorer', compact('modules', 'presets', 'plans'));
    }

    public function pricing()
    {
        $plans = Plan::where('is_active', true)->get();

        return view('public.pricing', compact('plans'));
    }

    public function demoCenter()
    {
        $demoUsers = User::with('roles')->get();

        return view('public.demo_center', compact('demoUsers'));
    }

    public function demoLogin(string $roleSlug)
    {
        $user = User::role($roleSlug)->first();

        if (! $user) {
            $user = User::first();
        }

        if ($user) {
            Auth::login($user);

            return redirect('/admin');
        }

        return redirect()->route('demo.center')->with('error', 'Demo user not found.');
    }
}
