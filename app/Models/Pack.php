<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
	protected $table = 'pack';


	protected $primaryKey = 'pack_id';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'user_id',
		'status'
	];

	public function profileImage( $hash )
	{
		if(file_exists(base_path() . '/public/data/images/profile/' . $hash . '.jpg'))
			$avatar = url('data/images/profile/' . $hash . '.jpg');
		else
			$avatar = url('images/user-avatar.png');

		return  $avatar;
	}
	
	public function get($user_id)
	{
		$return = [
			'owner' => [],
			'member' => []
		];

		$packs = PackUserRelations::where('user_id', '=', $user_id)->get(['pack_id', 'owner'])->toArray();

		$my_pack_id = NULL;
		$member_packs_ids = [];

		foreach($packs as $pack)
		{
			if($pack['owner'])
				$my_pack_id = $pack['pack_id'];
			else
				$member_packs_ids[] = $pack['pack_id'];
		}

		$owner_pack = \DB::table($this->table)
			->select([
				$this->table . '.pack_id',
				$this->table . '.name AS pack_name',
				'pack_user_rel.owner',
				'users.user_id',
				'users.avatar',
				'users.first_name',
				'users.last_name'
			])
			->leftJoin('pack_user_rel', function($join){
				$join->on('pack_user_rel.pack_id', '=', $this->table . '.pack_id');
			})
			->leftJoin('users', function($join){
				$join->on('users.user_id', '=', 'pack_user_rel.user_id');
			})
			->where('pack.pack_id', '=', $my_pack_id)
			->where('pack_user_rel.status', '=', 1)
			->get();


		$return['owner'] = [
			'pack_id' => $owner_pack[0]->pack_id,
			'pack_name' => $owner_pack[0]->pack_name,
			'members' => $this->getMembers($owner_pack, TRUE)
		];


//		$member_packs = \DB::table($this->table)
//		            ->select([
//			            $this->table . '.pack_id',
//			            $this->table . '.name AS pack_name',
//			            'users.user_id',
//			            'users.first_name',
//			            'users.last_name'
//		            ])
//		            ->leftJoin('pack_user_rel', function($join){
//			            $join->on('pack_user_rel.pack_id', '=', $this->table . '.pack_id');
//		            })
//		            ->leftJoin('users', function($join){
//			            $join->on('users.user_id', '=', 'pack_user_rel.user_id');
//		            })
//					->orderBy('pack_user_rel.owner', 'DESC')
//					->whereIn('pack.pack_id', $member_packs_ids)
//		            ->get();
//
//
//		foreach($member_packs as $member)
//		{
//
//			$return['member'][$member->pack_id] = [
//				'pack_id' => $member->pack_id,
//				'pack_name' => $member->pack_name,
//				'members' => $this->getMembers($member_packs),
//			];
//		}


		return $return;

	}

	private function getMembers($data, $not_me = FALSE)
	{

		$return = [];

		foreach($data as $item)
		{

			if($not_me)
				if($item->user_id == \Auth::getUser()->user_id)
					continue;
			

			$return[] = [
				'user_id' => $item->user_id,
				'first_name' => $item->first_name,
				'last_name' => $item->last_name,
				'avatar' => $this->profileImage($item->avatar)
			];

		}

		return $return;

	}


	public static function newPack( $name, $user_id )
	{

		$create = Pack::create([
			'name' => $name,
			'user_id' => $user_id,
			'status' => 1
		]);


		if($create)
			return $create->pack_id;

		return FALSE;

	}
	
	
}
