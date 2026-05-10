<?php

namespace App\Service;

use App\Constants\PrStatusConstant;
use App\Models\Item;

class PrStatusResolverService
{
    public function resolveItemStatus(Item $item): string
    {
        return $this->resolveItemStatusFromValues(
            quantity: $item->quantity,
            quantityApprove: $item->quantity_approve,
            isTrashed: $item->trashed(),
        );
    }

    public function resolveItemStatusFromValues(mixed $quantity, mixed $quantityApprove, bool $isTrashed = false): string
    {
        if ($isTrashed) {
            return PrStatusConstant::REJECTED;
        }

        if ($quantityApprove === null || $quantityApprove === '') {
            return PrStatusConstant::WAITING_APPROVAL;
        }

        return ((float) $quantity > (float) $quantityApprove)
            ? PrStatusConstant::PARTIALLY_APPROVED
            : PrStatusConstant::APPROVED;
    }

    public function resolvePrStatusFromItems(iterable $items): string
    {
        $total = 0;
        $rejectedCount = 0;
        $waitingCount = 0;
        $approvedCount = 0;

        foreach ($items as $item) {
            if (! $item instanceof Item) {
                continue;
            }

            $total++;
            $status = $this->resolveItemStatus($item);

            if ($status === PrStatusConstant::REJECTED) {
                $rejectedCount++;
                continue;
            }

            if ($status === PrStatusConstant::WAITING_APPROVAL) {
                $waitingCount++;
                continue;
            }

            if ($status === PrStatusConstant::APPROVED) {
                $approvedCount++;
            }
        }

        if ($total === 0) {
            return PrStatusConstant::REJECTED;
        }

        if ($rejectedCount === $total) {
            return PrStatusConstant::REJECTED;
        }

        if ($waitingCount === $total) {
            return PrStatusConstant::WAITING_APPROVAL;
        }

        if ($approvedCount === $total) {
            return PrStatusConstant::APPROVED;
        }

        return PrStatusConstant::PARTIALLY_APPROVED;
    }
}