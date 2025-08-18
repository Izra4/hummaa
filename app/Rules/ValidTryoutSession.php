<?php

namespace App\Rules;

use App\Models\TryoutResult;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTryoutSession implements ValidationRule
{
    public function __construct(
        private ?int $userId = null
    ) {
        $this->userId = $userId ?? auth()->id();
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $session = TryoutResult::find($value);

        if (!$session) {
            $fail('The selected session does not exist.');
            return;
        }

        if ($session->user_id !== $this->userId) {
            $fail('You are not authorized to access this session.');
            return;
        }

        if ($session->status === 'selesai') {
            $fail('This session is already completed.');
            return;
        }

        if ($session->isExpired()) {
            $fail('This session has expired.');
            return;
        }
    }
}