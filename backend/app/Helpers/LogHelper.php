<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LogHelper
{
    public static function pretty_log($message, array $context = [])
    {
        $separator = str_repeat('=', 50);
        $timestamp = Carbon::now('Asia/Tokyo')->format('Y-m-d H:i:s');

        $formattedMessage = "\n{$separator}\n";
        $formattedMessage .= "⏰ 時間: {$timestamp}\n";
        $formattedMessage .= "📝 メッセージ: {$message}\n";

        if (!empty($context)) {
            $formattedMessage .= "📋 データ:\n";
            foreach ($context as $key => $value) {
                $jsonValue = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                $formattedMessage .= "  ├ {$key}:\n";
                $formattedMessage .= "  │ {$jsonValue}\n";
            }
        }

        $formattedMessage .= "{$separator}\n";

        Log::info($formattedMessage);
    }
}