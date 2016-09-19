<?php 

namespace App\Entities\TraitRelations;

/**
 * Trait for Entities belongs to Status.
 *
 * @author cmooy
 */
trait BelongsToStatusTrait 
{
	/**
	 * boot
	 *
	 * @return void
	 **/
	function BelongsToStatusTraitConstructor()
	{
		//
	}
	
	/**
	 * call belongsto relationship with previous status
	 *
	 **/
	public function Prev()
	{
		return $this->belongsTo('App\Entities\Status', 'prev_id', 'id');
	}
	
	/**
	 * call belongsto relationship with next status
	 *
	 **/
	public function Next()
	{
		return $this->belongsTo('App\Entities\Status', 'next_id', 'id');
	}

	/**
	 * check if model has prev status in certain id
	 *
	 * @var singular id
	 **/
	public function scopePrevID($query, $variable)
	{
		return $query->where('prev_id', $variable);
	}

	/**
	 * check if model has next status in certain id
	 *
	 * @var singular id
	 **/
	public function scopeNextID($query, $variable)
	{
		return $query->where('next_id', $variable);
	}
}