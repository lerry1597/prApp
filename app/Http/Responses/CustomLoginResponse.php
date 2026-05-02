<?php

namespace App\Http\Responses;

use App\Constants\RoleConstant;
use App\Filament\Pages\App\PurchaseRequisition;
use Filament\Auth\Http\Responses\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class CustomLoginResponse extends LoginResponse
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        if ($user && Filament::getCurrentPanel()?->getId() === 'app') {
            if ($user->roles()->where('name', RoleConstant::VESSEL_CREW_REQUESTER)->exists()) {
                return redirect('/purchase-requisition-form');
            }
            return redirect('/');
        }

        // Default redirect untuk panel lain (seperti Admin Panel)
        return redirect(Filament::getCurrentPanel()?->getUrl() ?? '/');
    }
}
