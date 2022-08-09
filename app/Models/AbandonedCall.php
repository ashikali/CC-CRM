<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbandonedCall extends Model
{
    use HasFactory;

    public const C_STATUS_SELECT = [
        'Open'   => 'Open',
        'Closed' => 'Closed',
    ];

    public const C_ACTION_SELECT = [
        'Not Called' => 'Not Called',
        'Called'     => 'Called',
        'No Answer'  => 'No Answer',
        'Invalid'    => 'Invalid',
    ];

    public $table = 'call_entry';
    public $timestamps = false;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'c_status',
        'c_action',
        'c_reason',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
