<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\AdminDisbursementVerificationController;
use PHPUnit\Framework\Attributes\Test;

class AdminDisbursementVerificationTest extends \Tests\TestCase
{
    #[Test]
    public function admin_disbursement_verification_controller_exists(): void
    {
        $this->assertTrue(class_exists(AdminDisbursementVerificationController::class));
    }
}
