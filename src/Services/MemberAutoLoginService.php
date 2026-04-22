<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;

final class MemberAutoLoginService
{
    public function __construct(
        private readonly LeadRepository $leads,
        private readonly PurchaseRepository $purchases,
    ) {}

    public function resolveAuthorizedLeadId(string $email): ?int
    {
        $normalizedEmail = strtolower(trim($email));
        if (!filter_var($normalizedEmail, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $leadId = $this->leads->findIdByEmail($normalizedEmail);
        if ($leadId === null) {
            return null;
        }

        return $this->purchases->buyerHasAnyPurchase($leadId) ? $leadId : null;
    }
}
