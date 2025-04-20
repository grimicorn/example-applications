<?php

namespace App;

use App\BaseModel;
use App\ExchangeSpaceMember;
use Illuminate\Database\Eloquent\Model;
use App\Support\ExchangeSpace\MemberRole;
use App\Support\Notification\ReportsAbuse;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends BaseModel
{
    use SoftDeletes,
        ReportsAbuse;

    protected $fillable = [
      'body'
    ];

    protected $casts = [
        'body' => 'string',
        'user_id' => 'integer',
        'conversation_id' => 'integer'
    ];

    protected $appends = [
        'creator_member',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['space'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['space', 'conversation'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    /**
     * Get the user that owns the message.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the space that owns the conversation that owns the message.
     */
    public function space()
    {
        return $this->conversation->space();
    }

    public function scopeWithDeletedByAdmin($query)
    {
        return $query->withTrashed()->where('deleted_at', null)->orWhereNotNull('deleted_by_id');
    }

    /**
     * Get the user that deleted the message.
     */
    public function deletedBy()
    {
        return $this->belongsTo('App\User', 'deleted_by_id');
    }

    public function getBodyAttribute($value)
    {
        if ($this->deletedByAdmin()) {
            return 'This message has been removed by an administrator.';
        }

        return $value;
    }

    public function deletedByAdmin()
    {
        return !is_null($this->deleted_by_id);
    }

    /**
     * Gets the message creator member attribute.
     *
     * @return App\Member|null
     */
    protected function getCreatorMemberAttribute()
    {
        // Lets try to first find the actual member
        if ($member = $this->getCreatorMember()) {
            return $member;
        }

        // Ok then lets check if the user is a developer
        // if not then we will just want to stop here
        if (!optional($this->user)->isDeveloper()) {
            return null;
        }

        // Setup
        $conversation = optional(Conversation::find($this->conversation_id));
        $member = (new ExchangeSpaceMember)->fillAdministrator(
            $this->user->id,
            optional($conversation->space)->id
        );

        return $member;
    }

    public function getCreatorMember()
    {
        $conversation = optional($this->conversation()->get()->first());
        return ExchangeSpaceMember::where('user_id', $this->user_id)
        ->where('exchange_space_id', optional($conversation->space)->id)
        ->withTrashed()
        ->with('user')
        ->first();
    }

    public function documents()
    {
        if ($this->deletedByAdmin()) {
            return collect([]);
        }

        return $this->conversation->space
        ->getMedia()
        ->filter(function ($media) {
            return $media->getCustomProperty('message_id') === $this->id;
        });
    }

    public function getDocumentsAttribute()
    {
        return $this->documents();
    }

    public function reportAbuseUrl($reporter = null, $reason = null, $reason_details = null)
    {
        return $this->reportMessageAbuseLink(
            $this,
            $reporter ? $reporter->id : auth()->id(),
            array_filter([
                'reason' => $reason,
                'reason_details' => $reason_details,
            ])
        );
    }
}
