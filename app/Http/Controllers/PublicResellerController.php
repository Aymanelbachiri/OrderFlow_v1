<?php

namespace App\Http\Controllers;

use App\Models\ResellerCreditPack;
use Illuminate\Support\Facades\Log;

class PublicResellerController extends Controller
{
    /**
     * Display public reseller credit packs page
     */
    public function creditPacks()
    {
        try {
            $creditPacks = ResellerCreditPack::active()
                ->whereNotNull('name')
                ->whereNotNull('price')
                ->whereNotNull('credits_amount')
                ->get();

            // Additional validation to ensure all required fields are present
            $creditPacks = $creditPacks->filter(function ($pack) {
                return !empty($pack->name) &&
                       !is_null($pack->price) &&
                       !is_null($pack->credits_amount) &&
                       $pack->credits_amount > 0;
            });

            return view('public.reseller-plans', compact('creditPacks'));

        } catch (\Exception $e) {
            Log::error('Error loading reseller credit packs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return view with empty collection to prevent crashes
            $creditPacks = collect();
            return view('public.reseller-plans', compact('creditPacks'));
        }
    }
}
