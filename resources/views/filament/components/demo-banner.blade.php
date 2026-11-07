@php
    $currentUser = auth()->user();
    $currentRole = $currentUser?->roles->first()?->name ?? 'super_admin';
    $rolesList = [
        'super_admin' => 'Super Admin',
        'esg_manager' => 'ESG Manager (Samantha)',
        'facility_manager' => 'Facility Lead (Rafiq)',
        'finance_officer' => 'Bank CFO (Tanveer)',
        'hr_officer' => 'HR Compliance (Farzana)',
        'procurement_manager' => 'Procurement (Tariq)',
        'external_auditor' => 'KPMG Auditor (David)',
        'executive' => 'Board Director (Amina)',
        'supplier_portal' => 'Cotton Supplier (Rahim)',
        'consultant' => 'Veritas Consultant (Marcus)',
    ];
@endphp

@if($currentUser)
    <div style="background: linear-gradient(90deg, #064e3b 0%, #065f46 50%, #047857 100%); color: #ecfdf5; padding: 6px 16px; font-size: 12px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #10b981; z-index: 9999;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-weight: 800; background: #059669; padding: 2px 6px; border-radius: 4px; font-size: 10px; letter-spacing: 0.5px;">LIVE DEMO</span>
            <span>Active Persona: <strong>{{ $currentUser->name }}</strong> (<span style="color: #6ee7b7;">{{ $currentRole }}</span>)</span>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <label style="font-size: 11px; color: #a7f3d0;">Switch Persona:</label>
            <select onchange="window.location.href = '/demo-login/' + this.value" style="background: #064e3b; color: #ffffff; border: 1px solid #10b981; border-radius: 4px; padding: 2px 8px; font-size: 11px; cursor: pointer;">
                @foreach($rolesList as $key => $label)
                    <option value="{{ $key }}" {{ $currentRole === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <a href="{{ route('demo.center') }}" style="background: #10b981; color: #022c22; font-weight: bold; padding: 3px 8px; border-radius: 4px; text-decoration: none; font-size: 11px;">
                All 22 Roles &rarr;
            </a>
        </div>
    </div>
@endif

