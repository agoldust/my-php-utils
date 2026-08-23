<?php
namespace Goldoust\Utils\WebPageCheckers;

/**
 * ETerminChecker is a utility class for checking available time slots from the eTermin API.
 * API URL: https://www.etermin.net/
 */
class ETerminChecker {
    private string $baseUrl = "https://www.etermin.net/api/timeslots";
    
    /**
     *  Checks available time slots for a given service and web ID.
     *  @param string $serviceId The service ID to check for available slots (default is "110023").
     *  @param string $webId The web ID associated with the service (default is "qterminstadtduisburgstva").
     *  @return array An associative array containing the available time slots.
     */
    public function checkAvailableSlots(string $serviceId = "110023", string $webId = "qterminstadtduisburgstva"): array {
        $today = date('Y-m-d');

        $params = [
            "date"                 => $today,
            "serviceid"            => $serviceId,
            "rangesearch"          => "1",
            "caching"              => "false",
            "capacity"             => "1",
            "duration"             => "0",
            "cluster"              => "false",
            "slottype"             => "0",
            "fillcalendarstrategy" => "0",
            "showavcap"            => "false",
            "appfuture"            => "14",
            "appdeadline"          => "15",
            "appdeadlinewm"        => "0",
            "oneoff"               => "null",
            "msdcm"                => "0",
            "calendarid"           => ""
        ];

        $headers = [
            "Accept: application/json, text/plain",
            "Accept-Language: en-US,en;q=0.9,de-DE;q=0.8,de;q=0.7,fa;q=0.6",
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Pragma: no-cache",
            "Referer: https://www.etermin.net/{$webId}",
            'Sec-Ch-Ua: "Not=A?Brand";v="99", "Google Chrome";v="151", "Chromium";v="151"',
            "Sec-Ch-Ua-Mobile: ?0",
            'Sec-Ch-Ua-Platform: "Windows"',
            "Sec-Fetch-Dest: empty",
            "Sec-Fetch-Mode: cors",
            "Sec-Fetch-Site: same-origin",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36",
            "Webid: {$webId}"
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->baseUrl . '?' . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_ENCODING       => '',
            CURLOPT_TIMEOUT        => 15
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($response, true) ?? [];
        }

        throw new \Exception("Connection Error with HTTP status: " . $httpCode);
    }
}