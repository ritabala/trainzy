<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\BelongsToGym;

class Message extends Model
{
    use HasFactory;
    use BelongsToGym;

    protected $fillable = [
        'subject',
        'body',
        'created_by',
        'recipient_type',
        'recipient_ids',
        'activity_class_id',
        'status',
    ];

    protected $casts = [
        'recipient_ids' => 'array',
    ];

    public function recipients()
    {
        return $this->hasMany(MessageRecipient::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activityClass()
    {
        return $this->belongsTo(ActivityClass::class);
    }

    public function getRecipientUsersAttribute()
    {
        $query = User::query();
        
        if ($this->recipient_type === 'all_members') {
            $query->role('member-' . gym()->id);
        } elseif ($this->recipient_type === 'all_staff') {
            $query->role(['staff-' . gym()->id, 'admin-' . gym()->id]);
        } elseif ($this->recipient_type === 'selected_members') {
            $query->role('member-' . gym()->id)->whereIn('id', $this->recipient_ids);
        } elseif ($this->recipient_type === 'selected_staff') {
            $query->role(['staff-' . gym()->id, 'admin-' . gym()->id])->whereIn('id', $this->recipient_ids);
        } else {
            return collect();
        }
        
        return $query->get();
    }
}
