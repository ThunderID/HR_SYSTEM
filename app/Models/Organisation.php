<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	name 							: Varchar, 255, Required
 * 	code 							: Varchar, 255, Required, Unique
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//this package
 	1 Relationship hasMany 
	{
		Branches
	}

 * 	//ther package
 	2 Relationships hasMany 
	{
		Documents
		Persons
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class Organisation extends BaseModel {
	
	use SoftDeletes;
	use \App\Models\Traits\HasMany\HasBranchesTrait;
	use \App\Models\Traits\HasMany\HasDocumentsTrait;
	use \App\Models\Traits\HasMany\HasPersonsTrait;
	use \App\Models\Traits\HasMany\HasCalendarsTrait;
	use \App\Models\Traits\HasMany\HasWorkleavesTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'organisations';

	protected 	$fillable			= 	[
											'name' 						,
											'code' 						,
										];

	// protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'name' 						=> 'required|max:255',
											'code' 						=> 'required|max:255',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'code' 						=> 'Code', 
											'name' 						=> 'Name', 
											'withattributes' 			=> 'WithAttributes',
										];
	
	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'code' 						=> 'Must be string', 
											'withattributes' 			=> 'Must be array of relationship',
										];

	public $sortable 				= 	['id', 'name', 'created_at'];

	protected $appends				= 	['has_branches', 'has_documents', 'has_workleaves', 'has_calendars'];

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

	/* ---------------------------------------------------------------------------- ERRORS ----------------------------------------------------------------------------*/
	/**
	 * return errors
	 *
	 * @return MessageBag
	 * @author 
	 **/
	function getError()
	{
		return $this->errors;
	}

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/

	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/
	public function getHasBranchesAttribute($value)
	{
		if(isset($this->getRelations()['branches']) && count($this->getRelations()['branches']))
		{
			return true;
		}
		return false;
	}

	public function getHasDocumentsAttribute($value)
	{
		if(isset($this->getRelations()['documents']) && count($this->getRelations()['documents']))
		{
			return true;
		}
		return false;
	}

	public function getHasWorkleavesAttribute($value)
	{
		if(isset($this->getRelations()['workleaves']) && count($this->getRelations()['workleaves']))
		{
			return true;
		}
		return false;
	}

	public function getHasCalendarsAttribute($value)
	{
		if(isset($this->getRelations()['calendars']) && count($this->getRelations()['calendars']))
		{
			return true;
		}
		return false;
	}

	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('organisations.id', $variable);
		}
		return $query->where('organisations.id', $variable);
	}
	
	public function scopeCode($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('organisations.code', $variable);
		}
		return $query->where('organisations.code', $variable);
	}

	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
	}

	public function scopeOnlyAdmin($query, $variable)
	{
		return $query->whereHas('branches.charts.works', function($q){$q->where('status', '<>', 'admin');})
					->with(['branches.charts.works' => function($q){$q->where('status', '<>', 'admin');}]);
	}

}
