<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * App\User
 *  @method static \Illuminate\Database\Query\Builder|\App findOrFail($id, $columns = ['*'])
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';


    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'confirmed',
        'confirmation_code',
        'type',
        'facebook_id',
        'status',
        'pending_data',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'avatar',
        'role',
		'customer_id',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_NOT_CONFIRMED = 2;
    const STATUS_SUSPENDED = 0;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public static function register($data, $facebook = NULL)
    {
        if($facebook)
            $save = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'confirmation_code' => '',
                'status' => self::STATUS_ACTIVE,
                'type' => (in_array($data['type'], ['web', 'ios', 'android'])) ? $data['type'] : '',
                'facebook_id' => $facebook,
                'avatar' => ''
            ];
        else
            $save = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'confirmation_code' => $data['confirmation_code'],
                'status' => self::STATUS_NOT_CONFIRMED,
                'type' => (in_array($data['type'], ['web', 'ios', 'android'])) ? $data['type'] : '',
                'avatar' => ''
            ];


        $user = User::create($save);


//        if($facebook)
//            \Mail::send('email.user_password', ['password' => $data['password']], function($message) use($data){
//                $message
//                    ->from('srdjan.mitrovic@gmail.com')
//                    ->to( $data['email'], $data['first_name'] . ' ' . $data['last_name'])
//                    ->subject('Your login password');
//            });
//        else
//            \Mail::send('email.verify', ['confirmation_code' => $confirmation_code], function($message) use($data){
//                $message
//                    ->from('srdjan.mitrovic@gmail.com')
//                    ->to( $data['email'], $data['first_name'] . ' ' . $data['last_name'])
//                    ->subject('Verify you email address');
//            });

        return $user;
    }

    public static function registerGuest( $data )
    {

        $user = User::create([
            'first_name' => "Guest user",
            'status' => 3,
            'installation_id' => $data['installation_id'],
            'role' => 'guest'
        ]);

        return $user;


    }

    public function verify($confirmation_code)
    {
        $user = User::where('confirmation_code', '=', $confirmation_code)->first();

        if(!$user)
            return FALSE;


        $user->confirmation_code = '';
        $user->status = 1;
        $user->update();

        return $user;
    }


    public function verifyPending($confirmation_code)
    {
        $user = User::where('confirmation_code', '=', $confirmation_code)->first();

        if(!$user)
            return FALSE;


        if($user->pending_data)
        {
            $pending = json_decode($user->pending_data);

            foreach($pending as $k => $v)
            {
                $user->{$k} = $v;
            }

        }
        $user->pending_data = '[]';
        $user->confirmation_code = '';

        $user->update();

        return TRUE;

    }


    public static function getByFacebookId($facebook_id)
    {

        $user = User::where('facebook_id', '=', $facebook_id)->first();

        if(!$user)
            return FALSE;

        return $user;

    }

    public function getByIdWithPackage($user_id) {
        $query = \DB::table($this->table)
        ->select([
            'users.user_id',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.status',
            'billing_packages.name',
            'billing_packages.discount',
            
        ])->where('users.user_id', '=', $user_id)
        ->leftJoin('billing_history', function($join){
            $join->on('users.user_id', '=', 'billing_history.user_id')->where('billing_history.status', '=', 1);
        })
        ->leftJoin('billing_packages', function($join){
            $join->on('billing_history.package_id', '=', 'billing_packages.package_id');
        });
        $result = $query->first();
        return $result;
    }
    public static function getById($user_id)
    {
        $user = User::where('user_id', '=', $user_id)->first();

        if(!$user)
            return FALSE;

        return $user;
    }

    public static function getByEmail($email)
    {
        $user = User::where('email', '=', $email)->first();
        if(!$user)
            return FALSE;

        return $user;
    }


    public static function updateProfile($user_id, $data, $email_change)
    {

        $user = User::where('user_id', '=', $user_id);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'company' => $data['company'],
            'city' => $data['hometown']
        ]);

        if($email_change)
        {
            $user->update( [
                'pending_data'      => json_encode( [ 'email' => $data['email'] ] ),
                'confirmation_code' => $data['confirmation_code']
            ] );
        }


        return TRUE;


    }

    public static function updatePassword($user_id, $data)
    {

        $user = User::where('user_id', '=', $user_id);

        $user->update([
            'password' => \Hash::make($data['password'])
        ]);

        return TRUE;

    }

    public static function getEmailFromToken($token)
    {
        return \DB::table('users_password_resets')->where('token', '=', $token)->first();
    }


    public function getAll($filter)
    {

        $query = \DB::table($this->table)
            ->select([
                'users.user_id',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.status',
                'billing_packages.name as package_name'
            ])
            ->leftJoin('billing_history', function($join){
                $join->on('users.user_id', '=', 'billing_history.user_id')->where('billing_history.status', '=', 1);
            })
            ->leftJoin('billing_packages', function($join){
                $join->on('billing_history.package_id', '=', 'billing_packages.package_id');
            });

        if(isset($filter['status']))
        {
            $query->where('users.status','=', $filter['status']);
        }

        $users = $query->paginate(5);

        $return = [];

        foreach($users->items() as $user)
        {

            $return['data'][] = [
                'user_id' => $user->user_id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'status' => $user->status,
                'package' => $user->package_name
            ];

            
        }

        $return['pagination'] = [
            'total' => $users->total(),
            'last_page' => $users->lastPage(),
            'next_page' => str_replace(url('api') . '/', '', $users->nextPageUrl()),
            'prev_page' => str_replace(url('api') . '/', '', $users->previousPageUrl()),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage()
        ];

        return $return;

    }


    public function search( $string )
    {

        $users = \DB::table('users')
            ->where('first_name', 'LIKE', "%$string%")
            ->orWhere('last_name', 'LIKE', "%$string%")
            ->orWhere('email', 'LIKE', "%$string%")
            ->where('status', '=', 1)
            ->get([
                'user_id',
                'first_name',
                'last_name',
                'email'
            ]);

        return $users;

    }


}
