<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Uid\Uuid;

class Totp extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_name',
        'secret',
        'issuer'
    ];
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public static function updateInfo(string $uuid, string $accountName): int
    {
        return self::where('id', '=', $uuid)->update(['account_name' => $accountName]);
    }

    public static function deleteInfo(string $uuid): int
    {
        return self::where('id', '=', $uuid)->delete();
    }

    public static function insertInfo(?string $accountName, string $secret, string $issuer): Totp
    {
        return self::create([
            'account_name' => $accountName,
            'secret' => $secret,
            'issuer' => $issuer
        ]);
    }

    public static function getInfoById(string $uuid, array $columns): Totp
    {
        return self::select($columns)->where('id', '=', $uuid)->first();
    }

    public static function getAllInfo(string $search = ''): LengthAwarePaginator
    {
        return self::where('account_name', 'like', '%' . $search . '%')->paginate(10);
    }
}
