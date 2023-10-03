<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogOld extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function descriptionType()
    {
        return $this->belongsTo(ActiviyDescriptionTypes::class, 'description_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id', 'id');
    }

    public function nameType()
    {
        return $this->belongsTo(ActiviyNameTypes::class, 'name_id', 'id');
    }

    public function causer()
    {
        return $this->morphTo();
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
// ActivityLog::where('subject_type', 'URL')->update(['subject_type' => 'page']);
// ActivityLog::where('id', 102)->select(['description_id', 'name_id','id', 'causer_id', 'causer_type', 'subject_id', 'subject_type', 'created_at'])->with(['subject:id,name_id', 'causer:id,name', 'causer.esi_tokens', 'descriptionType', 'nameType'])->get();
