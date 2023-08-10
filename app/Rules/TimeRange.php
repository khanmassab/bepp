<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TimeRange implements ValidationRule
{
    
    public function passes($attribute, $value)
    {
        $timeParts = explode('-', $value);

        if (count($timeParts) !== 2) {
            return false;
        }

        $startTime = trim($timeParts[0]);
        $endTime = trim($timeParts[1]);

        $validFormat = 'H:i';

        // Check if the start time is before the end time
        if (!empty($startTime) && !empty($endTime) && $startTime !== $endTime) {
            return \Illuminate\Validation\Rule::unique('users')->where(function ($query) use ($startTime, $endTime) {
                return $query->whereBetween('from_time', [$startTime, $endTime]);
            });
        }

        return false;
    }

    public function message()
    {
        return 'Invalid time range.';
    }
}
