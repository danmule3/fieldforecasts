<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

/**
 * Central write path for the security/audit trail. Every module that
 * needs to record a security-relevant event (auth, role changes,
 * subscription changes, admin actions) should depend on this service
 * rather than writing to ActivityLog directly, so the "what gets
 * captured on every event" logic lives in one place.
 */
class ActivityLogger
{
    public function log(string $event, ?User $user = null, ?Model $subject = null, array $properties = []): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $user?->id,
            'event' => $event,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 255),
            'created_at' => now(),
        ]);
    }
}
