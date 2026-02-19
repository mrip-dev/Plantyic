<?php
use App\Models\InspectionField;

if (!function_exists('format_currency')) {
    function format_currency($amount, $currency = 'AED')
    {
        return $currency . ' ' . number_format($amount, 2);
    }
}
if (!function_exists('getYears')) {
    function getYears()
    {
        $years = range(now()->year+5, 1980);
        return $years;
    }
}


if (!function_exists('set_active_route')) {
    function set_active_route($routeName, $activeClass = 'active')
    {
        return request()->routeIs($routeName) ? $activeClass : '';
    }
}

if (!function_exists('getStatusInfo')) {
    /**
     * Determines the CSS class and icon for a given status value.
     *
     * @param string|null $status
     * @return array
     */
    function getStatusInfo($value)
    {
        if (is_array($value)) {
            return ['class' => 'item-value', 'icon' => 'fas fa-list'];
        }

        $value_lower = is_string($value) ? strtolower(trim($value)) : '';

        // Excellent conditions
        $excellent_keywords = ['excellent', 'perfect', 'like new'];
        foreach ($excellent_keywords as $keyword) {
            if (strpos($value_lower, $keyword) !== false) {
                return ['class' => 'status-excellent', 'icon' => 'fas fa-star'];
            }
        }

        // Good conditions
        $good_keywords = ['no visible fault', 'no leak', 'no error', 'no smoke', 'available', 'good', 'operational', 'working', 'functional', 'ok', 'normal', 'passed', 'yes'];
        foreach ($good_keywords as $keyword) {
            if (strpos($value_lower, $keyword) !== false) {
                return ['class' => 'status-good', 'icon' => 'fas fa-check-circle'];
            }
        }

        // Warning conditions
        $warning_keywords = ['minor leak', 'judder', 'cranking noise', 'white', 'minor error', 'stuck', 'worn', 'noisy', 'dirty', 'warning light on', 'fair', 'average', 'minor'];
        foreach ($warning_keywords as $keyword) {
            if (strpos($value_lower, $keyword) !== false) {
                return ['class' => 'status-warning', 'icon' => 'fas fa-exclamation-triangle'];
            }
        }

        // Danger conditions
        $danger_keywords = ['major leak', 'hard', 'tappet noise', 'abnormal noise', 'black', 'major error', 'not engaging', 'damaged', 'not working', 'not cooling', 'alignment out', 'worn out', 'arms-bushes crack', 'rusty', 'poor', 'bad', 'broken', 'failed'];
        foreach ($danger_keywords as $keyword) {
            if (strpos($value_lower, $keyword) !== false) {
                return ['class' => 'status-danger', 'icon' => 'fas fa-times-circle'];
            }
        }

        // N/A or empty
        if (empty($value_lower) || $value_lower === 'n/a' || $value_lower === 'not available') {
            return ['class' => 'status-na', 'icon' => 'fas fa-minus-circle'];
        }

        // Default info status
        return ['class' => 'status-info', 'icon' => 'fas fa-info-circle'];
    }
}

if (! function_exists('inspectionFieldFileCount')) {
    /**
     * Count the number of files for a given inspection field
     *
     * @param int $reportId
     * @param string $fieldName
     * @return int
     */
    function inspectionFieldFileCount(int $reportId, string $fieldName): int
    {
        $field = InspectionField::with('files')
            ->where('vehicle_inspection_report_id', $reportId)
            ->where('name', $fieldName)
            ->first();

        return $field ? $field->files->count() : 0;
    }
}
