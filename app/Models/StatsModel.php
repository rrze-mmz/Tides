<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatsModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (app()->environment('testing')) {
            $this->connection = 'sqlite';
        } else {
            $this->connection = 'pgsql_stats';
        }
    }
}
