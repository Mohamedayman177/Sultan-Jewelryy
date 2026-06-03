<?php

namespace App\Support;

class CustomerFormHelper
{
    /**
     * @return array<string, string>
     */
    public static function optionsFor(string $category, string $group): array
    {
        $key = $category === 'gemstone' ? 'gemstone' : 'jewelry';

        return config("customer-form.{$key}.{$group}", []);
    }

    public static function label(string $category, string $group, ?string $value, string $locale = 'ar'): string
    {
        if (! filled($value)) {
            return '—';
        }

        $options = self::optionsFor($category, $group);

        return $options[$value][$locale] ?? $options[$value]['ar'] ?? $value;
    }

    /**
     * @param  array<int, string>|string|null  $values
     * @return list<string>
     */
    public static function labelsList(string $category, string $group, array|string|null $values, string $locale = 'ar'): array
    {
        $list = is_array($values) ? $values : (filled($values) ? [(string) $values] : []);

        return array_map(
            fn (string $v) => self::label($category, $group, $v, $locale),
            $list
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function jewelryDetailsFromRequest(array $input): array
    {
        return array_filter([
            'piece_type' => $input['piece_type'] ?? null,
            'metal_type' => $input['metal_type'] ?? null,
            'pieces_count' => filled($input['pieces_count'] ?? null) ? (string) $input['pieces_count'] : null,
            'approximate_weight' => filled($input['approximate_weight'] ?? null) ? trim((string) $input['approximate_weight']) : null,
            'karat' => filled($input['karat'] ?? null) ? trim((string) $input['karat']) : null,
            'brand' => filled($input['brand'] ?? null) ? trim((string) $input['brand']) : null,
            'has_hallmark' => $input['has_hallmark'] ?? null,
            'has_invoice' => $input['has_invoice'] ?? null,
            'has_certificate' => $input['has_certificate'] ?? null,
            'piece_condition' => $input['piece_condition'] ?? null,
            'evaluation_purpose' => array_values(array_filter((array) ($input['evaluation_purpose'] ?? []))),
            'brief_description' => filled($input['brief_description'] ?? null) ? trim((string) $input['brief_description']) : null,
            'additional_notes' => filled($input['additional_notes'] ?? null) ? trim((string) $input['additional_notes']) : null,
            'referral_source' => $input['referral_source'] ?? null,
        ], fn ($v) => $v !== null && $v !== [] && $v !== '');
    }

    /**
     * @return array<string, mixed>
     */
    public static function gemstoneDetailsFromRequest(array $input): array
    {
        return array_filter([
            'gemstone_type' => $input['gemstone_type'] ?? null,
            'stones_count' => filled($input['stones_count'] ?? null) ? (string) $input['stones_count'] : null,
            'approximate_weight_carat' => filled($input['approximate_weight_carat'] ?? null) ? trim((string) $input['approximate_weight_carat']) : null,
            'shape' => $input['shape'] ?? null,
            'color' => filled($input['color'] ?? null) ? trim((string) $input['color']) : null,
            'clarity_grade' => filled($input['clarity_grade'] ?? null) ? trim((string) $input['clarity_grade']) : null,
            'origin_type' => $input['origin_type'] ?? null,
            'treated' => $input['treated'] ?? null,
            'country_of_origin' => filled($input['country_of_origin'] ?? null) ? trim((string) $input['country_of_origin']) : null,
            'has_certificate' => $input['has_certificate'] ?? null,
            'stone_condition' => $input['stone_condition'] ?? null,
            'evaluation_purpose' => array_values(array_filter((array) ($input['evaluation_purpose'] ?? []))),
            'brief_description' => filled($input['brief_description'] ?? null) ? trim((string) $input['brief_description']) : null,
            'additional_notes' => filled($input['additional_notes'] ?? null) ? trim((string) $input['additional_notes']) : null,
            'referral_source' => $input['referral_source'] ?? null,
        ], fn ($v) => $v !== null && $v !== [] && $v !== '');
    }
}
