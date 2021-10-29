<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseModel
 *
 * @method static Builder|static newModelQuery()
 * @method static Builder|static newQuery()
 * @method static Builder|static query()
 * @method static Builder|static whereselfTypeId($value)
 * @method static Builder|static whereCreatedAt($value)
 * @method static Builder|static whereCurrentUsage($value)
 * @method static Builder|static whereId($value)
 * @method static Builder|static whereMoisture($value)
 * @method static Builder|static whereName($value)
 * @method static Builder|static whereProductGradeId($value)
 * @method static Builder|static whereProductTypeId($value)
 * @method static Builder|static whereStorageId($value)
 * @method static Builder|static whereTotCapacity($value)
 * @method static Builder|static whereUpdatedAt($value)
 */
class BaseModel extends Model
{
    public static function getUserLikedTypeQuery(string $table, string $model, User $user): \Illuminate\Database\Query\Expression
    {
        return DB::raw("type FROM likeables l WHERE likeable_id={$table}.id AND likeable_type LIKE '%{$model}%' AND user_id={$user->id} LIMIT 1");
    }
}
