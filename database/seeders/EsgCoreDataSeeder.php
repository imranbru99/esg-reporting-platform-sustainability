<?php

namespace Database\Seeders;

use App\Models\DisclosureMetricMap;
use App\Models\EmissionFactor;
use App\Models\EmissionFactorLibrary;
use App\Models\Framework;
use App\Models\FrameworkDisclosure;
use App\Models\Metric;
use App\Models\RegulationWatch;
use Illuminate\Database\Seeder;

class EsgCoreDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Frameworks
        $frameworks = [
            [
                'slug' => 'gri',
                'name' => 'Global Reporting Initiative (GRI Standards)',
                'version' => '2021 Universal',
                'publisher' => 'Global Sustainability Standards Board (GSSB)',
                'description' => 'Global best practice for impact reporting on economy, environment, and people.',
                'documentation_url' => 'https://www.globalreporting.org/standards/',
            ],
            [
                'slug' => 'issb',
                'name' => 'ISSB (IFRS S1 & S2)',
                'version' => '2023 Standard',
                'publisher' => 'International Sustainability Standards Board (IFRS Foundation)',
                'description' => 'Investor-focused baseline disclosures on sustainability-related risks and opportunities.',
                'documentation_url' => 'https://www.ifrs.org/issued-standards/sustainability-standards/',
            ],
            [
                'slug' => 'csrd_esrs',
                'name' => 'CSRD / ESRS (European Sustainability Reporting Standards)',
                'version' => '2024 Commission Delegated Act',
                'publisher' => 'EFRAG / European Commission',
                'description' => 'Mandatory double materiality disclosures across 12 sector-agnostic standards.',
                'documentation_url' => 'https://www.efrag.org/lab6',
            ],
            [
                'slug' => 'tcfd',
                'name' => 'Task Force on Climate-related Financial Disclosures (TCFD)',
                'version' => '2021 Annex',
                'publisher' => 'Financial Stability Board',
                'description' => 'Climate governance, strategy, risk management, and metrics/targets.',
                'documentation_url' => 'https://www.fsb-tcfd.org/',
            ],
            [
                'slug' => 'tnfd',
                'name' => 'Taskforce on Nature-related Financial Disclosures (TNFD)',
                'version' => 'v1.0 (LEAP)',
                'publisher' => 'TNFD Forum',
                'description' => 'Nature and biodiversity dependency, impact, risk, and opportunity disclosures.',
                'documentation_url' => 'https://tnfd.global/',
            ],
            [
                'slug' => 'cdp',
                'name' => 'CDP (Climate, Forests, Water Security)',
                'version' => '2024 Integrated Questionnaire',
                'publisher' => 'CDP Worldwide',
                'description' => 'Global environmental disclosure system for investors and purchasers.',
                'documentation_url' => 'https://www.cdp.net/',
            ],
            [
                'slug' => 'un_sdgs',
                'name' => 'UN Sustainable Development Goals (SDGs 1-17)',
                'version' => '2030 Agenda',
                'publisher' => 'United Nations',
                'description' => 'Universal call to action to end poverty, protect the planet, and ensure peace.',
                'documentation_url' => 'https://sdgs.un.org/goals',
            ],
            [
                'slug' => 'ghg_protocol',
                'name' => 'GHG Protocol (Corporate Standard & Scope 3)',
                'version' => 'Revised Edition',
                'publisher' => 'WRI & WBCSD',
                'description' => 'The world’s most widely used greenhouse gas accounting standards.',
                'documentation_url' => 'https://ghgprotocol.org/',
            ],
            [
                'slug' => 'bsec_bb',
                'name' => 'BSEC & Bangladesh Bank Sustainable Finance Policy',
                'version' => 'SFD Circular No. 05/2020',
                'publisher' => 'Bangladesh Bank & BSEC',
                'description' => 'Sustainability rating, green taxonomy, and ESG reporting for listed entities in Bangladesh.',
                'documentation_url' => 'https://www.bb.org.bd/',
            ],
            [
                'slug' => 'brsr_india',
                'name' => 'Business Responsibility and Sustainability Reporting (BRSR)',
                'version' => 'SEBI 2023 Master Circular',
                'publisher' => 'Securities and Exchange Board of India (SEBI)',
                'description' => 'Mandatory ESG disclosure framework for the top 1,000 listed companies in India.',
                'documentation_url' => 'https://www.sebi.gov.in/',
            ],
        ];

        $frameworkModels = [];
        foreach ($frameworks as $fw) {
            $frameworkModels[$fw['slug']] = Framework::updateOrCreate(['slug' => $fw['slug']], $fw);
        }

        // 2. Framework Disclosures
        $disclosures = [
            // GRI
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 305-1', 'title' => 'Direct (Scope 1) GHG emissions', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 305-2', 'title' => 'Energy indirect (Scope 2) GHG emissions', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 305-3', 'title' => 'Other indirect (Scope 3) GHG emissions', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => false],
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 302-1', 'title' => 'Energy consumption within the organization', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 303-3', 'title' => 'Water withdrawal by source', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 306-3', 'title' => 'Waste generated and diverted from disposal', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 401-1', 'title' => 'New employee hires and employee turnover', 'category' => 'social', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 403-9', 'title' => 'Work-related injuries and LTIFR rate', 'category' => 'social', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 405-1', 'title' => 'Diversity of governance bodies and employees', 'category' => 'social', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['gri']->id, 'code' => 'GRI 205-1', 'title' => 'Operations assessed for risks related to corruption', 'category' => 'governance', 'requirement_type' => 'mixed', 'is_mandatory' => true],

            // ISSB
            ['framework_id' => $frameworkModels['issb']->id, 'code' => 'IFRS S2 para 29a', 'title' => 'Gross Scope 1 greenhouse gas emissions', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['issb']->id, 'code' => 'IFRS S2 para 29b', 'title' => 'Gross Scope 2 location and market-based emissions', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['issb']->id, 'code' => 'IFRS S2 para 29c', 'title' => 'Gross Scope 3 GHG emissions across categories', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],

            // CSRD
            ['framework_id' => $frameworkModels['csrd_esrs']->id, 'code' => 'ESRS E1-6', 'title' => 'Gross Scopes 1, 2, 3 and Total GHG emissions', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['csrd_esrs']->id, 'code' => 'ESRS E1-5', 'title' => 'Energy consumption and mix', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['csrd_esrs']->id, 'code' => 'ESRS S1-9', 'title' => 'Diversity indicators and gender balance', 'category' => 'social', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['csrd_esrs']->id, 'code' => 'ESRS G1-1', 'title' => 'Corporate culture and business conduct policies', 'category' => 'governance', 'requirement_type' => 'qualitative', 'is_mandatory' => true],

            // Bangladesh Bank / BSEC
            ['framework_id' => $frameworkModels['bsec_bb']->id, 'code' => 'BSEC-ENV-01', 'title' => 'Direct fuel and energy consumption disclosures', 'category' => 'environmental', 'requirement_type' => 'quantitative', 'is_mandatory' => true],
            ['framework_id' => $frameworkModels['bsec_bb']->id, 'code' => 'BSEC-SOC-01', 'title' => 'Workforce health, safety and living wage compliance', 'category' => 'social', 'requirement_type' => 'mixed', 'is_mandatory' => true],
        ];

        $disclosureModels = [];
        foreach ($disclosures as $disc) {
            $disclosureModels[$disc['code']] = FrameworkDisclosure::updateOrCreate(
                ['framework_id' => $disc['framework_id'], 'code' => $disc['code']],
                $disc
            );
        }

        // 3. Metric Definition Library
        $metrics = [
            [
                'slug' => 'scope1_direct_ghg',
                'code' => 'GHG-S1',
                'name' => 'Scope 1 Direct GHG Emissions',
                'category' => 'environmental',
                'subcategory' => 'ghg_scope1',
                'unit_type' => 'mass',
                'default_unit' => 'tCO2e',
                'aggregation_method' => 'sum',
                'description' => 'Direct emissions from owned or controlled sources (stationary combustion, mobile combustion, process, fugitives).',
            ],
            [
                'slug' => 'scope2_location_ghg',
                'code' => 'GHG-S2-LOC',
                'name' => 'Scope 2 Location-Based Indirect GHG Emissions',
                'category' => 'environmental',
                'subcategory' => 'ghg_scope2',
                'unit_type' => 'mass',
                'default_unit' => 'tCO2e',
                'aggregation_method' => 'sum',
                'description' => 'Indirect emissions from the generation of purchased electricity using regional grid average emission factors.',
            ],
            [
                'slug' => 'scope2_market_ghg',
                'code' => 'GHG-S2-MKT',
                'name' => 'Scope 2 Market-Based Indirect GHG Emissions',
                'category' => 'environmental',
                'subcategory' => 'ghg_scope2',
                'unit_type' => 'mass',
                'default_unit' => 'tCO2e',
                'aggregation_method' => 'sum',
                'description' => 'Emissions from purchased electricity reflecting contractual instruments such as PPAs, RECs, or supplier tariffs.',
            ],
            [
                'slug' => 'scope3_value_chain_ghg',
                'code' => 'GHG-S3',
                'name' => 'Scope 3 Value Chain GHG Emissions (All Categories)',
                'category' => 'environmental',
                'subcategory' => 'ghg_scope3',
                'unit_type' => 'mass',
                'default_unit' => 'tCO2e',
                'aggregation_method' => 'sum',
                'description' => 'All indirect emissions (not included in scope 2) that occur in the value chain of the reporting company.',
            ],
            [
                'slug' => 'electricity_consumption',
                'code' => 'NRG-ELEC',
                'name' => 'Total Purchased Electricity Consumption',
                'category' => 'environmental',
                'subcategory' => 'energy',
                'unit_type' => 'energy',
                'default_unit' => 'MWh',
                'aggregation_method' => 'sum',
                'description' => 'Gross electricity consumed across all facilities from public utility grids and private networks.',
            ],
            [
                'slug' => 'diesel_fuel_consumption',
                'code' => 'NRG-DSL',
                'name' => 'Diesel Fuel Consumption',
                'category' => 'environmental',
                'subcategory' => 'energy',
                'unit_type' => 'volume',
                'default_unit' => 'L',
                'aggregation_method' => 'sum',
                'description' => 'Diesel fuel consumed in backup generators, boilers, and company-owned vehicle fleets.',
            ],
            [
                'slug' => 'natural_gas_consumption',
                'code' => 'NRG-GAS',
                'name' => 'Natural Gas Consumption',
                'category' => 'environmental',
                'subcategory' => 'energy',
                'unit_type' => 'volume',
                'default_unit' => 'm3',
                'aggregation_method' => 'sum',
                'description' => 'Piped natural gas used in industrial heating, steam production, or heating.',
            ],
            [
                'slug' => 'water_withdrawal_total',
                'code' => 'WTR-WTH',
                'name' => 'Total Water Withdrawal',
                'category' => 'environmental',
                'subcategory' => 'water',
                'unit_type' => 'volume',
                'default_unit' => 'm3',
                'aggregation_method' => 'sum',
                'description' => 'Sum of all water drawn from surface water, groundwater, seawater, or municipal third-party sources.',
            ],
            [
                'slug' => 'waste_generated_total',
                'code' => 'WST-TOT',
                'name' => 'Total Waste Generated',
                'category' => 'environmental',
                'subcategory' => 'waste',
                'unit_type' => 'mass',
                'default_unit' => 't',
                'aggregation_method' => 'sum',
                'description' => 'Total hazardous and non-hazardous solid waste produced by operations.',
            ],
            [
                'slug' => 'workforce_headcount_total',
                'code' => 'SOC-HC',
                'name' => 'Total Workforce Headcount',
                'category' => 'social',
                'subcategory' => 'workforce',
                'unit_type' => 'count',
                'default_unit' => 'headcount',
                'aggregation_method' => 'latest',
                'description' => 'Total number of permanent and temporary active employees across all sites.',
            ],
            [
                'slug' => 'workforce_female_percentage',
                'code' => 'SOC-FEM',
                'name' => 'Female Workforce Participation Ratio',
                'category' => 'social',
                'subcategory' => 'diversity',
                'unit_type' => 'percentage',
                'default_unit' => '%',
                'aggregation_method' => 'average',
                'description' => 'Percentage of women across total workforce, executive leadership, and board.',
            ],
            [
                'slug' => 'lost_time_injury_rate',
                'code' => 'OHS-LTIFR',
                'name' => 'Lost Time Injury Frequency Rate (LTIFR)',
                'category' => 'social',
                'subcategory' => 'ohs',
                'unit_type' => 'ratio',
                'default_unit' => 'rate',
                'aggregation_method' => 'average',
                'description' => 'Number of lost-time injuries per million hours worked.',
            ],
            [
                'slug' => 'training_hours_per_employee',
                'code' => 'SOC-TRN',
                'name' => 'Average Training Hours per Employee',
                'category' => 'social',
                'subcategory' => 'training',
                'unit_type' => 'count',
                'default_unit' => 'hours',
                'aggregation_method' => 'average',
                'description' => 'Total training hours logged divided by total headcount.',
            ],
            [
                'slug' => 'board_independence_ratio',
                'code' => 'GOV-IND',
                'name' => 'Board Independent Directors Ratio',
                'category' => 'governance',
                'subcategory' => 'board',
                'unit_type' => 'percentage',
                'default_unit' => '%',
                'aggregation_method' => 'latest',
                'description' => 'Percentage of independent non-executive directors serving on the corporate board.',
            ],
        ];

        $metricModels = [];
        foreach ($metrics as $m) {
            $metricModels[$m['slug']] = Metric::updateOrCreate(['slug' => $m['slug']], $m);
        }

        // 4. Cross-Framework Mapping (One metric satisfies multiple framework disclosures)
        $mappings = [
            ['disc' => 'GRI 305-1', 'metric' => 'scope1_direct_ghg', 'weight' => 1.0],
            ['disc' => 'IFRS S2 para 29a', 'metric' => 'scope1_direct_ghg', 'weight' => 1.0],
            ['disc' => 'ESRS E1-6', 'metric' => 'scope1_direct_ghg', 'weight' => 1.0],

            ['disc' => 'GRI 305-2', 'metric' => 'scope2_location_ghg', 'weight' => 1.0],
            ['disc' => 'GRI 305-2', 'metric' => 'scope2_market_ghg', 'weight' => 1.0],
            ['disc' => 'IFRS S2 para 29b', 'metric' => 'scope2_location_ghg', 'weight' => 1.0],
            ['disc' => 'IFRS S2 para 29b', 'metric' => 'scope2_market_ghg', 'weight' => 1.0],

            ['disc' => 'GRI 305-3', 'metric' => 'scope3_value_chain_ghg', 'weight' => 1.0],
            ['disc' => 'IFRS S2 para 29c', 'metric' => 'scope3_value_chain_ghg', 'weight' => 1.0],

            ['disc' => 'GRI 302-1', 'metric' => 'electricity_consumption', 'weight' => 1.0],
            ['disc' => 'ESRS E1-5', 'metric' => 'electricity_consumption', 'weight' => 1.0],
            ['disc' => 'BSEC-ENV-01', 'metric' => 'electricity_consumption', 'weight' => 1.0],

            ['disc' => 'GRI 303-3', 'metric' => 'water_withdrawal_total', 'weight' => 1.0],
            ['disc' => 'GRI 306-3', 'metric' => 'waste_generated_total', 'weight' => 1.0],
            ['disc' => 'GRI 405-1', 'metric' => 'workforce_female_percentage', 'weight' => 1.0],
            ['disc' => 'ESRS S1-9', 'metric' => 'workforce_female_percentage', 'weight' => 1.0],
            ['disc' => 'GRI 403-9', 'metric' => 'lost_time_injury_rate', 'weight' => 1.0],
        ];

        foreach ($mappings as $map) {
            if (isset($disclosureModels[$map['disc']], $metricModels[$map['metric']])) {
                DisclosureMetricMap::updateOrCreate([
                    'framework_disclosure_id' => $disclosureModels[$map['disc']]->id,
                    'metric_id' => $metricModels[$map['metric']]->id,
                ], [
                    'formula_weight' => $map['weight'],
                    'notes' => 'Standard cross-framework alignment.',
                ]);
            }
        }

        // 5. Emission Factor Libraries
        $defraLib = EmissionFactorLibrary::updateOrCreate(
            ['name' => 'UK DESNZ / DEFRA 2024'],
            ['publisher' => 'UK Department for Energy Security and Net Zero', 'year' => '2024', 'version' => '2.1']
        );

        $epaLib = EmissionFactorLibrary::updateOrCreate(
            ['name' => 'US EPA GHG Emission Factors Hub 2024'],
            ['publisher' => 'United States Environmental Protection Agency', 'year' => '2024', 'version' => '1.0']
        );

        $bgdLib = EmissionFactorLibrary::updateOrCreate(
            ['name' => 'Bangladesh Grid & National Fuel Library 2024'],
            ['publisher' => 'Department of Environment (DoE) & SREDA', 'year' => '2024', 'version' => '2024.1']
        );

        // 6. Emission Factors
        $factors = [
            // Scope 1: Diesel
            [
                'library_id' => $defraLib->id,
                'category' => 'fuels',
                'activity_name' => 'Diesel (100% mineral diesel)',
                'scope' => 'scope1',
                'factor_value' => 2.68787000,
                'factor_unit' => 'kgCO2e/L',
                'co2_factor' => 2.66312,
                'ch4_factor' => 0.00310,
                'n2o_factor' => 0.02165,
                'source_reference' => 'DEFRA 2024 Fuels Table 1.1',
                'uncertainty_percentage' => 3.00,
                'applicable_year' => '2024',
                'region_code' => 'GLOBAL',
            ],
            // Scope 1: Natural Gas
            [
                'library_id' => $defraLib->id,
                'category' => 'fuels',
                'activity_name' => 'Natural Gas (piped gaseous)',
                'scope' => 'scope1',
                'factor_value' => 2.02880000,
                'factor_unit' => 'kgCO2e/m3',
                'co2_factor' => 2.01500,
                'ch4_factor' => 0.00800,
                'n2o_factor' => 0.00580,
                'source_reference' => 'DEFRA 2024 Natural Gas Volumetric',
                'uncertainty_percentage' => 4.00,
                'applicable_year' => '2024',
                'region_code' => 'GLOBAL',
            ],
            // Scope 1: Petrol / Gasoline
            [
                'library_id' => $epaLib->id,
                'category' => 'fuels',
                'activity_name' => 'Motor Gasoline / Petrol',
                'scope' => 'scope1',
                'factor_value' => 2.31400000,
                'factor_unit' => 'kgCO2e/L',
                'co2_factor' => 2.29800,
                'ch4_factor' => 0.00600,
                'n2o_factor' => 0.01000,
                'source_reference' => 'EPA Hub Table 2',
                'uncertainty_percentage' => 3.50,
                'applicable_year' => '2024',
                'region_code' => 'GLOBAL',
            ],
            // Scope 2: Bangladesh Grid Electricity
            [
                'library_id' => $bgdLib->id,
                'category' => 'electricity',
                'activity_name' => 'Electricity Grid (Bangladesh National Mix)',
                'scope' => 'scope2_location',
                'factor_value' => 0.58420000,
                'factor_unit' => 'kgCO2e/kWh',
                'source_reference' => 'DoE Bangladesh National GHG Inventory 2024',
                'uncertainty_percentage' => 5.00,
                'applicable_year' => '2024',
                'region_code' => 'BGD',
            ],
            // Scope 2: US National Average Grid Electricity
            [
                'library_id' => $epaLib->id,
                'category' => 'electricity',
                'activity_name' => 'Electricity Grid (US eGRID Subregion Average)',
                'scope' => 'scope2_location',
                'factor_value' => 0.37210000,
                'factor_unit' => 'kgCO2e/kWh',
                'source_reference' => 'EPA eGRID 2024 Summary Tables',
                'uncertainty_percentage' => 3.00,
                'applicable_year' => '2024',
                'region_code' => 'USA',
            ],
            // Scope 2: UK National Grid
            [
                'library_id' => $defraLib->id,
                'category' => 'electricity',
                'activity_name' => 'Electricity Grid (UK Average Generation)',
                'scope' => 'scope2_location',
                'factor_value' => 0.20705000,
                'factor_unit' => 'kgCO2e/kWh',
                'source_reference' => 'DEFRA 2024 Electricity Conversion Factors',
                'uncertainty_percentage' => 2.50,
                'applicable_year' => '2024',
                'region_code' => 'GBR',
            ],
            // Scope 3: Air Travel Long-haul Economy
            [
                'library_id' => $defraLib->id,
                'category' => 'travel',
                'activity_name' => 'Business Travel: Flight Long-Haul Economy (>3700km)',
                'scope' => 'scope3_cat6',
                'factor_value' => 0.10240000,
                'factor_unit' => 'kgCO2e/pkm',
                'source_reference' => 'DEFRA 2024 Passenger Travel with Radiative Forcing',
                'uncertainty_percentage' => 8.00,
                'applicable_year' => '2024',
                'region_code' => 'GLOBAL',
            ],
            // Scope 3: Freight Diesel Truck
            [
                'library_id' => $defraLib->id,
                'category' => 'freight',
                'activity_name' => 'Upstream Freight: Diesel Heavy Goods Vehicle (HGV)',
                'scope' => 'scope3_cat4',
                'factor_value' => 0.10920000,
                'factor_unit' => 'kgCO2e/tkm',
                'source_reference' => 'DEFRA 2024 Freight Table 3.2',
                'uncertainty_percentage' => 5.00,
                'applicable_year' => '2024',
                'region_code' => 'GLOBAL',
            ],
        ];

        foreach ($factors as $f) {
            EmissionFactor::updateOrCreate(
                ['activity_name' => $f['activity_name'], 'applicable_year' => $f['applicable_year']],
                $f
            );
        }

        // 7. Regulation Watch Updates
        $regulationFeeds = [
            [
                'framework_slug' => 'csrd_esrs',
                'source_url' => 'https://www.efrag.org/News/Project-754/EFRAG-publishes-first-set-of-ESRS-QAs',
                'headline' => 'EFRAG Issues First Implementation Guidance on Double Materiality for Value Chains',
                'raw_text' => 'EFRAG published its definitive implementation guide clarifying how companies must assess upstream and downstream scope 3 impacts under ESRS 1 and ESRS E1.',
                'ai_summary' => 'Organizations reporting under CSRD must expand double materiality boundaries to include Tier 1 and high-risk Tier 2 suppliers starting from reporting year 2025.',
                'impact_level' => 'high',
                'action_recommendations' => [
                    'Review supplier risk matrices in Module: Supplier ESG Assessments',
                    'Initiate Scope 3 category 1 primary data collection for key suppliers',
                ],
                'status' => 'pending_review',
                'published_at' => now()->subDays(5),
            ],
            [
                'framework_slug' => 'issb',
                'source_url' => 'https://www.ifrs.org/news-and-events/news/2024/05/issb-consultation-biodiversity/',
                'headline' => 'ISSB Commences Standards Harmonization on Biodiversity and Human Capital',
                'raw_text' => 'The International Sustainability Standards Board announced research projects aligning with TNFD guidelines and human capital workforce metrics.',
                'ai_summary' => 'Future IFRS S3 and S4 standards will integrate TNFD nature reporting and GRI workforce indicators into baseline investor disclosures.',
                'impact_level' => 'medium',
                'action_recommendations' => [
                    'Audit readiness for TNFD LEAP workflow in Module: Biodiversity & Land Use',
                    'Ensure workforce headcount and safety metrics meet IFRS general criteria',
                ],
                'status' => 'acknowledged',
                'published_at' => now()->subDays(12),
            ],
            [
                'framework_slug' => 'bsec_bb',
                'source_url' => 'https://www.bb.org.bd/mediaroom/circulars/sfd/jan2024sfd01.pdf',
                'headline' => 'Bangladesh Bank Mandates 5% Green Finance Allocation for All Scheduled Banks',
                'raw_text' => 'Sustainable Finance Department Circular mandates all commercial banks to achieve minimum 5% total term loan disbursement towards verified green taxonomy projects.',
                'ai_summary' => 'Commercial banks and borrowing manufacturing plants must furnish verified carbon and energy audit certificates to qualify for green refinancing lines.',
                'impact_level' => 'critical',
                'action_recommendations' => [
                    'Generate Energy Management and Carbon Scope 1/2 audit pack via Unicode PDF',
                    'Submit verified facility efficiency scores to green loan consortium',
                ],
                'status' => 'action_created',
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($regulationFeeds as $rf) {
            RegulationWatch::updateOrCreate(['headline' => $rf['headline']], $rf);
        }
    }
}
