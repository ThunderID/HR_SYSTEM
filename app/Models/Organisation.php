<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	name 							: Varchar, 255, Required
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

use Str, Validator, DateTime, Exception;

class Organisation extends BaseModel {
	
	use \App\Models\Traits\HasMany\HasBranchesTrait;
	use \App\Models\Traits\HasMany\HasDocumentsTrait;
	use \App\Models\Traits\HasMany\HasPersonsTrait;
	use \App\Models\Traits\HasMany\HasCalendarsTrait;
	use \App\Models\Traits\HasMany\HasWorkleavesTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 'organisations';

	protected 	$fillable			= 	[
											'name' 						,
										];

	protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'name' 						=> 'required|max:255',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'name' 						=> 'Name', 
											'withattributes' 			=> 'WithAttributes',
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
			return $query->whereIn('id', $variable);
		}

		return $query->where('id', $variable);
	}

	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
	}

	public function scopeAppAuth($query, $variable)
	{
		return $query->with(['authentications' => function($q){$q->whereIn('role', ['application', 'superadmin'])->orderBy('role', 'asc');}, 'authentications.person']);
	}

	public function scopeOnlyAdmin($query, $variable)
	{
		return $query->whereHas('branches.charts.works', function($q){$q->where('status', '<>', 'admin');})
					->with(['branches.charts.works' => function($q){$q->where('status', '<>', 'admin');}]);
	}

	public function scopeWithAttributes($query, $variable)
	{
		if(!is_array($variable))
		{
			$variable 			= [$variable];
		}
		return $query->with($variable);
	}
}
