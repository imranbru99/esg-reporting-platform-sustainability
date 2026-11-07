<?php

namespace App\Services\ESG;

class UnitConverter
{
    /**
     * Conversion multipliers to standard base unit for each physical dimension:
     * - Mass: base is 'kg'
     * - Energy: base is 'kWh'
     * - Volume: base is 'L'
     * - Distance: base is 'km'
     * - Area: base is 'sqm'
     */
    protected static array $rates = [
        'mass' => [
            'kg' => 1.0,
            'g' => 0.001,
            't' => 1000.0,
            'tonne' => 1000.0,
            'tco2e' => 1000.0,
            'kgco2e' => 1.0,
            'lb' => 0.45359237,
            'lbs' => 0.45359237,
            'ton_us' => 907.18474,
        ],
        'energy' => [
            'kwh' => 1.0,
            'mwh' => 1000.0,
            'gwh' => 1000000.0,
            'gj' => 277.777778,
            'mj' => 0.277777778,
            'btu' => 0.000293071,
            'mmbtu' => 293.071,
            'therm' => 29.3001,
        ],
        'volume' => [
            'l' => 1.0,
            'litre' => 1.0,
            'liter' => 1.0,
            'm3' => 1000.0,
            'kl' => 1000.0,
            'gal' => 3.78541,
            'gal_us' => 3.78541,
            'gal_uk' => 4.54609,
            'bbl' => 158.987, // Standard oil barrel
        ],
        'distance' => [
            'km' => 1.0,
            'm' => 0.001,
            'mile' => 1.609344,
            'miles' => 1.609344,
            'pkm' => 1.0, // Passenger-kilometer
            'tkm' => 1.0, // Tonne-kilometer
        ],
        'area' => [
            'sqm' => 1.0,
            'm2' => 1.0,
            'sqft' => 0.092903,
            'ft2' => 0.092903,
            'hectare' => 10000.0,
            'ha' => 10000.0,
            'acre' => 4046.86,
        ],
    ];

    /**
     * Standard baseline units for reporting.
     */
    protected static array $standardUnits = [
        'mass' => 't',
        'energy' => 'MWh',
        'volume' => 'm3',
        'distance' => 'km',
        'area' => 'sqm',
    ];

    /**
     * Determine dimension category of a unit.
     */
    public static function detectDimension(string $unit): ?string
    {
        $normalized = strtolower(trim($unit));

        foreach (static::$rates as $dimension => $units) {
            if (array_key_exists($normalized, $units)) {
                return $dimension;
            }
        }

        return null;
    }

    /**
     * Convert a quantity from one unit to another.
     *
     * @throws \InvalidArgumentException
     */
    public static function convert(float $value, string $fromUnit, string $toUnit): float
    {
        $from = strtolower(trim($fromUnit));
        $to = strtolower(trim($toUnit));

        if ($from === $to) {
            return $value;
        }

        $fromDim = static::detectDimension($from);
        $toDim = static::detectDimension($to);

        if (! $fromDim || ! $toDim) {
            throw new \InvalidArgumentException("Unsupported or unrecognized unit: [{$fromUnit}] or [{$toUnit}].");
        }

        if ($fromDim !== $toDim) {
            throw new \InvalidArgumentException("Incompatible unit conversion from dimension [{$fromDim}] to [{$toDim}].");
        }

        // Convert from source to base unit
        $baseValue = $value * static::$rates[$fromDim][$from];

        // Convert from base unit to target unit
        return $baseValue / static::$rates[$toDim][$to];
    }

    /**
     * Normalize a value to its standard reporting unit.
     */
    public static function normalize(float $value, string $unit, ?string $dimension = null): array
    {
        $dim = $dimension ?? static::detectDimension($unit);

        if (! $dim || ! isset(static::$standardUnits[$dim])) {
            return [
                'value' => $value,
                'unit' => $unit,
                'dimension' => 'other',
            ];
        }

        $targetUnit = static::$standardUnits[$dim];
        $convertedValue = static::convert($value, $unit, $targetUnit);

        return [
            'value' => round($convertedValue, 4),
            'unit' => $targetUnit,
            'dimension' => $dim,
        ];
    }

    /**
     * List all supported units for a dimension.
     */
    public static function getUnits(string $dimension): array
    {
        return array_keys(static::$rates[$dimension] ?? []);
    }
}
