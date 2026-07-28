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
            'api.twilio.com/*' => Http::response([
                'sid' => 'SM1234567890abcdef',
                'status' => 'queued',
                'error_code' => null
            ], 201)
        ]);

        config([
            'services.twilio.sid' => 'test_twilio_sid',
            'services.twilio.token' => 'test_twilio_token',
            'services.twilio.from' => 'whatsapp:+14155238886',
        ]);

        $service = new WhatsAppNotificationService();
        $result = $service->sendNotification('081234567890', 'Test Message');

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.twilio.com/2010-04-01/Accounts/test_twilio_sid/Messages.json') &&
                $request['From'] === 'whatsapp:+14155238886' &&
                $request['To'] === 'whatsapp:+6281234567890' &&
                str_contains($request['Body'], 'Test Message');
        });
    }
}
