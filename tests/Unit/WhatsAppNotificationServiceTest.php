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
            'api.twilio.com/*' => Http::response(['sid' => 'SMxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'], 201)
        ]);

        config(['services.twilio.sid' => 'test_sid']);
        config(['services.twilio.token' => 'test_token']);
        config(['services.twilio.from' => 'whatsapp:+14155238886']);

        $service = new WhatsAppNotificationService();
        $result = $service->sendNotification('081234567890', 'Test Message');

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/test_sid/Messages.json' &&
                $request['To'] === 'whatsapp:+6281234567890' &&
                $request['From'] === 'whatsapp:+14155238886' &&
                str_contains($request['Body'], 'Test Message');
        });
    }
}
