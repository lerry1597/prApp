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

    /**
     * Parse localized Indonesian date string to Carbon object.
     * Example: "03 Mei 2026, 02:23 WIB"
     *
     * @param string|null $dateString
     * @return Carbon|null
     */
    public function parseLocalizedDate(?string $dateString): ?Carbon
    {
        if (!$dateString) {
            return null;
        }

        try {
            $cleanDate = $dateString;

            // Mapping zona waktu Indonesia ke Offset/Identifier standar
            $timezones = [
                'WIB' => '+07:00',
                'WITA' => '+08:00',
                'WIT' => '+09:00',
            ];

            $foundTz = 'UTC'; // Default jika tidak ditemukan
            foreach ($timezones as $id => $offset) {
                if (str_contains($dateString, $id)) {
                    $foundTz = $offset;
                    $cleanDate = str_replace($id, '', $cleanDate);
                    break;
                }
            }

            // Mapping bulan Indonesia ke Inggris
            $months = [
                'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
                'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
                'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
                'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December',
            ];

            foreach ($months as $id => $en) {
                $cleanDate = str_ireplace($id, $en, $cleanDate);
            }

            // Gabungkan tanggal yang sudah dibersihkan dengan offset yang ditemukan
            // Format yang diharapkan: "d F Y, H:i +07:00"
            $finalString = trim(str_replace(',', '', $cleanDate)) . ' ' . $foundTz;

            return Carbon::parse($finalString);
        } catch (\Exception $e) {
            \Log::warning("Failed to parse localized date: " . $dateString);
            return null;
        }
    }
}
