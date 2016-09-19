<?php 

namespace App\Entities\Observers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

use App\Entities\Status as Model; 

/**
 * Used in CLient model
 *
 * @author cmooy
 */
class StatusObserver 
{
	public function saving($model)
	{
		//1. validate prev_id
		if($model->prev_id!=0 && $model->prev->ref_id != $model->ref_id)
		{
			$model['errors']	= ['List tidak berasal dari dokumen yang sama.'];

			return false;
		}

		//2. validate next id
		if($model->next_id!=0 && $model->next->ref_id != $model->ref_id)
		{
			$model['errors']	= ['List tidak berasal dari dokumen yang sama.'];

			return false;
		}

		//3. set position
		if($model->prev_id==0)
		{
			$model->position 	= 1;
		}
		else
		{
			$model->position 	= $model->prev->position + 1;
		}

		return true;
	}

	public function saved($model)
	{
		//1. check previous
		$same_post 				= Model::refid($model->ref_id)->notid($model->id)->position($model->position)->first();

		if($same_post)
		{
			$same_post->prev_id 	= $model->id;

			if(!$same_post->save())
			{
				$model['errors']	= $same_post->getError();

				return false;
			}
		}

		if($model->position!=1)
		{
			$model->prev->next_id 	= $model->id;

			if(!$model->prev->save())
			{
				$model['errors']	= $model->prev->getError();

				return false;
			}
		}

		return true;
	}


	public function deleted($model)
	{
		//1. check previous
		if($model->prev)
		{
			$model->prev->next_id 	= $model->next_id;

			if(!$model->prev->save())
			{
				$model['errors']	= $model->prev->getError();

				return false;
			}
		}

		//2. check next
		if($model->next)
		{
			$model->next->prev_id 	= $model->prev_id;

			if(!$model->next->save())
			{
				$model['errors']	= $model->next->getError();

				return false;
			}
		}

		return true;
	}
}
