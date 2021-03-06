<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\State
 *
 * @property int $id
 * @property string $name
 * @property string $acronym
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\City[] $cities
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State whereAcronym($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class State extends Model
{
    protected $fillable = [
        'acronym',
        'name',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
