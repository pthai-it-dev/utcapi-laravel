<?php

namespace App\Repositories;

use App\Repositories\Contracts\NotificationRepositoryContract;
use App\Models\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NotificationRepository implements NotificationRepositoryContract
{
    public function insertGetID ($data) : int
    {
        return Notification::create($data)->id_notification;
    }

    public function setDelete ($id_notification_list)
    {
        Notification::whereIn('id_notification', $id_notification_list)
                    ->update(['is_delete' => 1]);
    }

    public function getNotifications ($id_sender, $num) : Collection
    {
        return Notification::where('id_sender', '=', $id_sender)
                           ->where('is_delete', '=', 0)
                           ->orderBy('id_notification', 'desc')
                           ->offset($num)
                           ->limit(15)
                           ->select('id_notification', 'title', 'content',
                                    'time_create', 'time_start', 'time_end')
                           ->get();
    }

    public function getDeletedNotifications ()
    {
        return Notification::where('is_delete', '=', true)
                           ->where('time_create', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 3 WEEK)'))
                           ->pluck('id_notification')
                           ->toArray();
    }
}