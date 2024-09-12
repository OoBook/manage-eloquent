<?php

namespace Oobook\Database\Eloquent\Tests\TestModels;

use Illuminate\Database\Eloquent\Model;
use Oobook\Database\Eloquent\Concerns\ManageEloquent;

class Company extends Model
{
    use ManageEloquent;

    protected $fillable = ['name'];

    protected $table = 'companies';

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }
}
