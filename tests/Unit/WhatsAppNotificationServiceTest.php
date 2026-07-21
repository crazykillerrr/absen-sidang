<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\Http;

class WhatsAppNotificationServiceTest extends TestCase
{
    public function test_send_notification_successfully(): void
    {
        Http::fake([
            'api.fonnte.com/*' => Http::response([
                'status' => true,
                'message' => 'success'
            ], 200)
        ]);

        config(['services.fonnte.token' => 'test_fonnte_token']);
        config(['services.fonnte.url' => 'https://api.fonnte.com/send']);

        $service = new WhatsAppNotificationService();
        $result = $service->sendNotification('081234567890', 'Test Message');

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.fonnte.com/send' &&
                $request->hasHeader('Authorization', 'test_fonnte_token') &&
                $request['target'] === '6281234567890' &&
                str_contains($request['message'], 'Test Message');
        });
    }
}
