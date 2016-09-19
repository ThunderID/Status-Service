<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Entities\Status;

/**
 * Status resource representation.
 *
 * @Resource("Statuses", uri="/status")
 */
class StatusController extends Controller
{
	public function __construct(Request $request)
	{
		$this->request 				= $request;
	}

	/**
	 * Show all status
	 *
	 * @Get("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"search":{"id":"string","reference":"string","tail":"boolean","head":"boolean","status":"string"},"sort":{"newest":"asc|desc","position":"desc|asc","reference":"desc|asc"}, "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status":"success","data":{"data":{"id":"string","ref_id":"string","next_id":"string","status":"string","position","string"},"count":"integer"} })
	 * })
	 */
	public function index()
	{
		$result						= new Status;

		if(Input::has('search'))
		{
			$search					= Input::get('search');

			foreach ($search as $key => $value) 
			{
				switch (strtolower($key)) 
				{
					case 'id':
						$result		= $result->id($value);
						break;
					case 'reference':
						$result		= $result->refid($value);
						break;
					case 'tail':
						$result		= $result->nextid(0);
						break;
					case 'head':
						$result		= $result->previd(0);
						break;
					case 'status':
						$result		= $result->status($value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		if(Input::has('sort'))
		{
			$sort					= Input::get('sort');

			foreach ($sort as $key => $value) 
			{
				if(!in_array($value, ['asc', 'desc']))
				{
					return response()->json( JSend::error([$key.' harus bernilai asc atau desc.'])->asArray());
				}
				switch (strtolower($key)) 
				{
					case 'newest':
						$result		= $result->orderby('created_at', $value);
						break;
					case 'position':
						$result		= $result->orderby('position', $value);
						break;
					case 'reference':
						$result		= $result->orderby('ref_id', $value);
						break;
					default:
						# code...
						break;
				}
			}
		}
		else
		{
			$result					= $result->orderby('ref_id', 'asc')->orderby('position', 'asc');
		}

		$count						= count($result->get());

		if(Input::has('skip'))
		{
			$skip					= Input::get('skip');
			$result					= $result->skip($skip);
		}

		if(Input::has('take'))
		{
			$take					= Input::get('take');
			$result					= $result->take($take);
		}

		$result 					= $result->with(['prev', 'next'])->get();
		
		return response()->json( JSend::success(['data' => $result->toArray(), 'count' => $count])->asArray())
				->setCallback($this->request->input('callback'));
	}

	/**
	 * Store Status
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":"string","ref_id":"string","next_id":"string","status":"string","position","string"}),
	 *      @Response(200, body={"status": "success", "data":{"id":"string","ref_id":"string","next_id":"string","status":"string","position","string"}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function post()
	{
		$id 			= Input::get('id');

		if(!is_null($id) && !empty($id))
		{
			$result		= Status::id($id)->first();
		}
		else
		{
			$result 	= new Status;
		}
		
		$result->fill(Input::only('ref_id', 'prev_id', 'next_id', 'status'));

		if($result->save())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error($result->getError())->asArray());
	}

	/**
	 * Delete Status
	 *
	 * @Delete("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null}),
	 *      @Response(200, body={"status": "success", "data": {"id":"string","ref_id":"string","next_id":"string","status":"string","position","string"}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function delete()
	{
		$status			= Status::id(Input::get('id'))->with(['prev', 'next'])->first();

		$result 		= $status;

		if($status && $status->delete())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		elseif(!$status)
		{
			return response()->json( JSend::error(['ID tidak valid'])->asArray());
		}

		return response()->json( JSend::error($status->getError())->asArray());
	}
}