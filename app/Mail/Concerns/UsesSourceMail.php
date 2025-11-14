<?php

namespace App\Mail\Concerns;

use App\Models\Order;
use App\Services\SourceMailService;
use Illuminate\Support\Facades\Config;

trait UsesSourceMail
{
    protected ?\App\Models\Source $source = null;
    protected SourceMailService $sourceMailService;

    /**
     * Configure mail for source
     */
    protected function configureSourceMail(?Order $order = null): void
    {
        $this->sourceMailService = new SourceMailService();
        
        if ($order) {
            $this->source = $this->sourceMailService->getSource(null, $order);
            $this->sourceMailService->configureMailForSource($this->source);
        }
    }

    /**
     * Get source email variables
     */
    protected function getSourceEmailVariables(): array
    {
        if (!$this->sourceMailService) {
            $this->sourceMailService = new SourceMailService();
        }
        return $this->sourceMailService->getEmailVariables($this->source);
    }

    /**
     * Get source-specific from address
     */
    protected function getSourceFromAddress(): ?string
    {
        return $this->source?->smtp_from_address;
    }

    /**
     * Get source-specific from name
     */
    protected function getSourceFromName(): ?string
    {
        return $this->source?->smtp_from_name ?? $this->source?->company_name;
    }
}

