<?php

namespace Oobook\Database\Eloquent\Tests\TestModels;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Oobook\Database\Eloquent\Concerns\ManageEloquent;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable,Authorizable, ManageEloquent, SoftDeletes;

    protected $fillable = ['company_id', 'email'];

    public $timestamps = false;

    protected $table = 'users';

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(File::class, 'packageable');
    }
}
