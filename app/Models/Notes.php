<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * App\Models
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Notes extends Model
{


	protected $table = 'notes';

	protected $primaryKey = 'note_id';

	protected $fillable = ['user_id', 'note', 'parent_type', 'parent_id'];

	const NOTE_APPROVED = 1;
	const NOTE_DECLINED = 2;

	public static function add( $note, $parent_type = 0, $parent_id = 0 )
	{

		$user_id = \Auth::getUser()->user_id;

		Notes::create([
			'user_id' => $user_id,
			'note' => $note,
			'parent_type' => $parent_type,
			'parent_id' => $parent_id
		]);

	}
}