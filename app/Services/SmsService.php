<?php

namespace App\Services;

use App\Models\SmsNotification;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private $client;
    private $apiKey;
    private $username;
    private $senderId;
    private $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.egosms.api_key');
        $this->username = config('services.egosms.username');
        $this->senderId = config('services.egosms.sender_id', 'EgoSMS');

        $useSandbox = (bool) config('services.egosms.sandbox', false);
        $defaultUrl = $useSandbox
            ? config('services.egosms.sandbox_url', 'https://comms-test.pahappa.net/api/v1/json')
            : config('services.egosms.live_url', 'https://comms.egosms.co/api/v1/json/');

        $this->baseUrl = rtrim((string) config('services.egosms.base_url', $defaultUrl), '/') . '/';
    }

    /**
     * Legacy method for compatibility
     */
    public function send(string $phone, string $message): bool
    {
        $result = $this->sendSms($phone, $message);
        return $result['success'] ?? false;
    }

    /**
     * Send SMS via EgoSMS API
     */
    public function sendSms($phoneNumber, $message, $from = null)
    {
        // If no API key configured, use development mode
        if (empty($this->apiKey) || empty($this->username)) {
            Log::info("SMS [DEV] to {$phoneNumber}: {$message}");
            return [
                'success' => true,
                'data' => ['development_mode' => true],
                'external_id' => 'dev_' . uniqid()
            ];
        }

        try {
            // Format phone number for international use
            $formattedPhone = SmsNotification::formatUgandaPhone($phoneNumber);

            $payload = [
                'method' => 'SendSms',
                'userdata' => [
                    'username' => $this->username,
                    'password' => $this->apiKey,
                ],
                'msgdata' => [
                    [
                        'number' => $formattedPhone,
                        'message' => $message,
                        'senderid' => $from ?? $this->senderId,
                        'priority' => '0',
                    ],
                ],
            ];

            $response = $this->client->post($this->baseUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true) ?? [];
            $isSuccess = ($responseData['Status'] ?? null) === 'OK';

            if (!$isSuccess) {
                Log::warning('SMS sending failed', [
                    'phone' => $formattedPhone,
                    'response' => $responseData,
                ]);

                return [
                    'success' => false,
                    'error' => $responseData['Message'] ?? 'EgoSMS request failed',
                    'response' => $responseData,
                ];
            }

            Log::info('SMS sent successfully', [
                'phone' => $formattedPhone,
                'response' => $responseData,
            ]);

            return [
                'success' => true,
                'data' => $responseData,
                'external_id' => $responseData['MsgFollowUpUniqueCode'] ?? null,
            ];

        } catch (RequestException $e) {
            $errorMessage = $e->getMessage();
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null;
            
            Log::error('SMS sending failed', [
                'phone' => $phoneNumber,
                'error' => $errorMessage,
                'response' => $responseBody
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'response' => $responseBody ? json_decode($responseBody, true) : null,
            ];
        } catch (\Exception $e) {
            Log::error('SMS service error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process SMS notification from database
     */
    public function processSmsNotification(SmsNotification $notification)
    {
        $result = $this->sendSms(
            $notification->phone_number, 
            $notification->message
        );

        if ($result['success']) {
            $notification->markAsSent(
                $result['external_id'] ?? null,
                $result['data'] ?? null
            );
            return true;
        } else {
            $notification->markAsFailed(
                $result['error'],
                $result['response'] ?? null
            );
            return false;
        }
    }

    /**
     * Send appointment reminder
     */
    public function sendAppointmentReminder($appointment, $scheduleTime = null)
    {
        // Create notification record
        $notification = SmsNotification::createAppointmentReminder($appointment, $scheduleTime);
        
        // If no schedule time, send immediately
        if (!$scheduleTime) {
            return $this->processSmsNotification($notification);
        }
        
        return $notification;
    }

    /**
     * Process pending SMS notifications
     */
    public function processPendingNotifications($limit = 100)
    {
        $notifications = SmsNotification::readyToSend()
            ->orderBy('scheduled_at')
            ->limit($limit)
            ->get();

        $successCount = 0;
        $failureCount = 0;

        foreach ($notifications as $notification) {
            if ($this->processSmsNotification($notification)) {
                $successCount++;
            } else {
                $failureCount++;
            }

            // Small delay to avoid rate limiting
            usleep(100000); // 100ms delay
        }

        Log::info('Processed SMS notifications', [
            'total' => $notifications->count(),
            'success' => $successCount,
            'failed' => $failureCount
        ]);

        return [
            'total' => $notifications->count(),
            'success' => $successCount,
            'failed' => $failureCount,
        ];
    }

    /**
     * Retry failed SMS notifications
     */
    public function retryFailedNotifications($limit = 50)
    {
        $notifications = SmsNotification::retryable()
            ->orderBy('updated_at')
            ->limit($limit)
            ->get();

        $successCount = 0;
        $failureCount = 0;

        foreach ($notifications as $notification) {
            if ($this->processSmsNotification($notification)) {
                $successCount++;
            } else {
                $failureCount++;
            }

            usleep(100000); // 100ms delay
        }

        Log::info('Retried failed SMS notifications', [
            'total' => $notifications->count(),
            'success' => $successCount,
            'failed' => $failureCount,
        ]);

        return [
            'total' => $notifications->count(),
            'success' => $successCount,
            'failed' => $failureCount,
        ];
    }

    /**
     * Check service configuration
     */
    public function checkConfiguration()
    {
        return [
            'api_key_set' => !empty($this->apiKey),
            'username_set' => !empty($this->username),
            'base_url' => $this->baseUrl,
            'sender_id' => $this->senderId,
            'configured' => !empty($this->apiKey) && !empty($this->username),
        ];
    }
}
