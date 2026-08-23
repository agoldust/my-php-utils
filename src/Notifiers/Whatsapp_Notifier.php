<?php
namespace Goldoust\Utils\Notifiers;

/**
 * A simple WhatsApp notifier for sending messages via the UltraMsg API.
 * API URL: https://ultramsg.com/
 */
class Whatsapp_Notifier {
    private string $instanceId;
    private string $token;

    /**
     * Constructor for initializing the WhatsApp notifier.
     * @param string $instanceId The instance ID for the UltraMsg API.
     * @param string $token The API token for authentication.
     */
    public function __construct(string $instanceId = 'instance189285', string $token = '2xgd708819jtesy1') {
        $this->instanceId = $instanceId;
        $this->token = $token;
    }


    /**
     * Sending Text Message
     * @param string $to The recipient's phone number in international format (e.g., '1234567890').
     * @param string $message The message content to be sent.
     */
    public function send_text(string $to, string $message): bool {
        $params = [
            'token' => $this->token,
            'to'    => $to,
            'body'  => $message
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => "https://api.ultramsg.com/{$this->instanceId}/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => http_build_query($params),
            CURLOPT_HTTPHEADER     => ["content-type: application/x-www-form-urlencoded"]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpCode === 200 && $response !== false);
    }
}