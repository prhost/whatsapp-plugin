<?php

namespace GetByte\Whatsapp\Classes;

use GetByte\Whatsapp\Classes\Helpers\Phone;
use GetByte\Whatsapp\Models\Account;
use GetByte\Whatsapp\Models\MessageLog;

class WhatsAppService
{
    public static function connect(Account $account)
    {
        return $account->getClassProvider()::connect($account);
    }

    public static function getStatus(Account $account): StatusConnectionResponse
    {
        $statusResponse = $account->getClassProvider()::getStatus($account);

        $status = $statusResponse->getStatus();
        $account->connected_at = ($status == 'CONNECTED' && $account->status != $status) ? now() : null;
        $account->status = $status ?: 'DISCONNECTED';
        $account->save();

        return $statusResponse;
    }

    public static function logout(Account $account)
    {
        $provider = $account->getClassProvider();
        return $provider::logout($account);
    }

    public static function send(Account $account, string $messageType, string $phoneNumber, string $content, $document_filename = null, $caption = null)
    {
        $phoneNumber = Phone::justnumber($phoneNumber);

        if ($phoneNumber && Phone::validate($phoneNumber)) {

            try {
                $provider = $account->getClassProvider();

                if ($messageType == 'image' || $messageType == 'document') {
                    $provider::sendMedia($messageType, $phoneNumber, $content, $account, $caption, $document_filename);
                } else {
                    $provider::sendText($phoneNumber, $content, $account);
                }

                MessageLog::create([
                    'to_phone_number' => $phoneNumber,
                    'account_id'      => $account->id,
                    'message_type'    => $messageType,
                    'content'         => [
                        'content'           => $content,
                        'document_filename' => $document_filename ?? '',
                        'caption'           => $caption ?? '',
                    ],
                ]);

            } catch (ApiException $e) {
                MessageLog::create([
                    'to_phone_number' => $phoneNumber,
                    'account_id'      => $account->id,
                    'message_type'    => $messageType,
                    'error'           => $e->getContent(),
                    'content'         => [
                        'content'           => $content,
                        'document_filename' => $document_filename ?? '',
                        'caption'           => $caption ?? '',
                    ],
                ]);
            }
        } else {
            MessageLog::create([
                'to_phone_number' => $phoneNumber ?? '',
                'account_id'      => $account->id,
                'message_type'    => $messageType,
                'error'           => 'Invalid phone number',
                'content'         => [
                    'content'           => $content,
                    'document_filename' => $document_filename ?? '',
                    'caption'           => $caption ?? '',
                ],
            ]);
        }
    }
}
