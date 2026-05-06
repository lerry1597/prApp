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

    //Status tambahan untuk kebutuhan internal, logistik, status barang, dll. yang tidak memengaruhi flow approval. 
    public const PICKED_UP = 'picked_up';
    public const DELIVERED = 'delivered';
    public const ONBOARD = 'onboard';

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
            self::PICKED_UP => 'Diambil',
            self::DELIVERED => 'Dikirim',
            self::ONBOARD => 'Di Kapal',
        ];
    }

    /**
     * Kembalikan warna badge Filament berdasarkan status PR.
     * Definisikan di satu tempat agar konsisten di seluruh aplikasi.
     */
    public static function getColor(string $status): string
    {
        return match ($status) {
            self::WAITING_APPROVAL, self::CONVERTED_TO_PO   => 'warning',
            self::REJECTED                                  => 'danger',
            self::APPROVED                                  => 'success',
            self::SUBMITTED                                 => 'info',
            self::PICKED_UP, self::DELIVERED, self::ONBOARD => 'info',
            default                                         => 'gray',
        };
    }
}
