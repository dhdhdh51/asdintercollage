<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title', 'message', 'type', 'target_role', 'user_id',
        'is_read', 'send_email', 'email_sent', 'created_by'
    ];
    protected $casts = ['is_read' => 'boolean', 'send_email' => 'boolean', 'email_sent' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
