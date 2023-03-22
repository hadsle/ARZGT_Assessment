<?php

namespace App\Listeners;

use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;

class LogUserActivity
{
    private $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    public function handle($event)
    {
        $user = $this->auth->user();

        if ($user) {
            $activityType = 'update';
            $activityDate = now();
            $activityDetails = [];

            if ($event instanceof Model) {
                $changes = $event->getChanges();

                foreach ($changes as $fieldName => [$oldValue, $newValue]) {
                    if ($this->isLoggableField($event, $fieldName)) {
                        $activityDetails[$fieldName] = [
                            'old_value' => $oldValue,
                            'new_value' => $newValue,
                        ];
                    }
                }
            }

            if (!empty($activityDetails)) {
                $user->userActivities()->create([
                    'activity_type' => $activityType,
                    'activity_date' => $activityDate,
                    'activity_details' => $activityDetails,
                ]);
            }
        }
    }

    private function isLoggableField($model, $fieldName)
    {
        if (isset($model->loggable) && in_array($fieldName, $model->loggable)) {
            return true;
        }

        return false;
    }
}
