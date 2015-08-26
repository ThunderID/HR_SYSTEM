<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation_id 				: Foreign Key From Organisation, Integer, Required
 * 	created_by 						: Foreign Key From Person, Integer, Required
 * 	type 		 					: Required : max 255
 * 	value 		 					: Required
 * 	started_at 		 				: Require, datetime
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	2 Relationships belongsTo 
	{
		Organisation
		Person
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception, DB;

class Policy extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;
	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 'tmp_policies';

	protected 	$fillable			= 	[
											'created_by' 				,
											'type' 						,
											'value' 					,
											'started_at' 				,
										];

	protected 	$rules				= 	[
											'created_by'				=> 'exists:persons,id',
											'type'						=> 'required|max:255',
											'value'						=> 'required',
											'started_at'				=> 'required|date_format:"Y-m-d H:i:s"',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'organisationid' 			=> 'OrganisationID', 

											'ondate' 					=> 'OnDate', 
											'type' 						=> 'Type', 
											'newest' 					=> 'Newest', 

											'withattributes' 			=> 'WithAttributes'
										];
										
	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'organisationid' 			=> 'Could be array or integer', 

											'ondate' 					=> 'Could be array or string (date)', 
											'type' 						=> 'Must be string', 
											'newest' 					=> 'Must be true', 

											'withattributes' 			=> 'Must be array of relationship'
										];

	public $sortable 				= 	['created_at', 'updated_at', 'started_at'];

	/* ---------------------------------------------------------------------------- CONSTRUCT ----------------------------------------------------------------------------*/
	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/
	static function boot()
	{
		parent::boot();

		Static::saving(function($data)
		{
			$validator = Validator::make($data->toArray(), $data->rules);

			if ($validator->passes())
			{
				return true;
			}
			else
			{
				$data->errors = $validator->errors();
				return false;
			}
		});
	}

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('tmp_policies.id', $variable);
		}
		return $query->where('tmp_policies.id', $variable);
	}

	public function scopeType($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('type', $variable);
		}
		return $query->where('type', $variable);
	}

	public function scopeOnDate($query, $variable)
	{
		if(is_array($variable))
		{
			if(!is_null($variable[1]))
			{
				return $query->where('started_at', '<=', date('Y-m-d H:i:s', strtotime($variable[1])))
							 ->where('started_at', '>=', date('Y-m-d H:i:s', strtotime($variable[0])));
			}
			elseif(!is_null($variable[0]))
			{
				return $query->where('started_at', '>=', date('Y-m-d H:i:s', strtotime($variable[0])));
			}
			else
			{
				return $query->where('started_at', '>=', date('Y-m-d H:i:s'));
			}
		}
		return $query->where('started_at', '<=', date('Y-m-d H:i:s', strtotime($variable)));
	}

	public function scopeNewest($query, $variable)
	{
		return $query->orderBy('started_at', 'DESC')->orderByRaw(DB::raw("FIELD(type, 'passwordreminder', 'assplimit', 'ulsplimit', 'hpsplimit', 'htsplimit', 'hcsplimit', 'firststatussettlement', 'secondstatussettlement', 'firstidle', 'secondidle','thirdidle', 'extendsworkleave', 'extendsmidworkleave', 'firstacleditor', 'secondacleditor', 'asid', 'ulid', 'hcid', 'htid', 'hpid' )"));
	}

}
