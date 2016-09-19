<?php

namespace App\Entities;

use App\Entities\Observers\StatusObserver;

use App\Entities\TraitRelations\BelongsToStatusTrait;

/**
 * Used for Status Models
 * 
 * @author cmooy
 */
class Status extends BaseModel
{
	/**
	 * Relationship Traits
	 *
	 */
	use BelongsToStatusTrait;
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table				= 'status';

	/**
	 * Date will be returned as carbon
	 *
	 * @var array
	 */
	protected $dates				=	['created_at', 'updated_at', 'deleted_at'];

	/**
	 * The appends attributes from mutator and accessor
	 *
	 * @var array
	 */
	protected $appends				=	[];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden 				= [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable				=	[
											'ref_id'						,
											'prev_id'						,
											'next_id'						,
											'status'						,
											'position'						,
										];
										
	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'ref_id'						=> 'required',
											'status'						=> 'required|max:"255"',
										];


	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
		
	/**
	 * boot
	 * observing model
	 *
	 */
	public static function boot() 
	{
        parent::boot();

		Status::observe(new StatusObserver);
    }

	/* ---------------------------------------------------------------------------- SCOPES ----------------------------------------------------------------------------*/

	/**
	 * scope to get condition where ref id
	 *
	 * @param string of entity' ref id
	 **/
	public function scopeRefID($query, $variable)
	{
		return 	$query->where($query->getModel()->table.'.ref_id', $variable);
	}

	/**
	 * scope to get condition where position
	 *
	 * @param string of entity' position
	 **/
	public function scopePosition($query, $variable)
	{
		return 	$query->where($query->getModel()->table.'.position', $variable);
	}

	/**
	 * scope to get condition where status
	 *
	 * @param string or array of entity' status
	 **/
	public function scopeStatus($query, $variable)
	{
		if(is_array($variable))
		{
			$query = $query->whereIn($query->getModel()->table.'.status', $variable);

			return $query;
		}

		return 	$query->where($query->getModel()->table.'.status', $variable);
	}
}
