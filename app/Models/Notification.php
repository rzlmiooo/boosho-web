<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'type', 'url', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kirim notifikasi ke user tertentu.
     */
    public static function sendToUser($userId, $title, $message, $url = null, $type = 'info')
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'type' => $type,
        ]);
    }

    /**
     * Kirim notifikasi ke semua admin.
     */
    public static function sendToAdmins($title, $message, $url = null, $type = 'info')
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            self::create([
                'user_id' => $admin->id,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'type' => $type,
            ]);
        }
    }

    /**
     * Kirim notifikasi ke semua user non-admin.
     */
    public static function sendToAllUsers($title, $message, $url = null, $type = 'promo')
    {
        $users = User::where('role', '!=', 'admin')->get();
        foreach ($users as $user) {
            self::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'type' => $type,
            ]);
        }
    }
}
