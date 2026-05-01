<?php

namespace App\Constants;

class PrStatusConstant
{
    public const DRAFT = 'draft';
    public const SUBMITTED = 'submitted';
    public const WAITING_APPROVAL = 'waiting_approval';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const CONVERTED_TO_PO = 'converted_to_po';
    public const CLOSED = 'closed';
    public const PENDING = 'pending';

    public static function getStatuses(): array
    {
        return [
            self::DRAFT => 'Draf',
            self::SUBMITTED => 'Diajukan',
            self::WAITING_APPROVAL => 'Menunggu Persetujuan',
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Ditolak',
            self::CONVERTED_TO_PO => 'Dikonversi ke PO',
            self::CLOSED => 'Ditutup',
            self::PENDING => 'Menunggu',
        ];
    }
}
