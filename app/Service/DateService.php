<?php

declare(strict_types=1);

namespace App\Service;

use Illuminate\Support\Carbon;

class DateService
{
    /**
     * Get current date.
     *
     * @return Carbon
     */
    public function getCurrentDate(): Carbon
    {
        return Carbon::now('Asia/Jakarta');
    }

    /**
     * Get issue date formatted for display.
     *
     * @return string
     */
    public function getIssueDate(): string
    {
        return $this->getCurrentDate()->translatedFormat('d F Y');
    }

    /**
     * Get current date in string format.
     *
     * @param string $format
     * @return string
     */
    public function getFormattedDate(string $format = 'd-m-Y'): string
    {
        return $this->getCurrentDate()->format($format);
    }

    /**
     * Get current date and time formatted for display.
     *
     * @return string
     */
    public function getDateTimeDisplay(): string
    {
        return $this->getCurrentDate()->translatedFormat('d F Y, H:i') . ' WIB';
    }
}
