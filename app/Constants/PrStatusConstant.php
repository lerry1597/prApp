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
            self::DRAFT => 'Draft',
            self::SUBMITTED => 'Submitted',
            self::WAITING_APPROVAL => 'Waiting Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::CONVERTED_TO_PO => 'Converted to PO',
            self::CLOSED => 'Closed',
            self::PENDING => 'Pending',
        ];
    }
}
