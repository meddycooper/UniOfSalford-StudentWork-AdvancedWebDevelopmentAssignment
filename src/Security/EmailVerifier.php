<?php

namespace App\Security;

use App\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * Safe-to-share version of EmailVerifier for public repositories.
 * No actual email sending or database persistence is performed.
 */
class EmailVerifier
{
    public function __construct()
    {
        // Dependencies removed for safety
    }

    /**
     * Mock sending email confirmation.
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        // Mock logic: just set a dummy signed URL in the email context
        $context = $email->getContext();
        $context['signedUrl'] = 'https://example.com/verify?token=dummy';
        $context['expiresAtMessageKey'] = 'dummy_expiry_key';
        $context['expiresAtMessageData'] = ['expiry' => 'never'];

        $email->context($context);

        // No actual email sending
    }

    /**
     * Mock handling email confirmation.
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        // Mock validation: assume email is always successfully verified
        $user->setVerified(true);

        // No database persistence
    }
}
