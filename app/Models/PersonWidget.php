<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign key from Person
 * 	title 	 						: Varchar, 255, Required
 * 	type 	 						: Required in panel or table or stat
 * 	order 	 						: Required, tiny int
 * 	query 	 	 					: Varchar, 255, Required in json format
 * 	field 	 	 					: Varchar, 255, in json format
 * 	function 	 	 				: Varchar, 255, Required in total_documents or total_branches or total_employees or index_branches or index_employees or index_documents
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship belongsTo
	{
		Person
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class PersonWidget extends BaseModel {

	use \App\Models\Traits\BelongsTo\HasPersonTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 'person_widgets';
	protected 	$fillable			= [
										'title' 						,
										'type' 							,
										'row' 							,
										'col' 							,
										'query' 						,
										'field' 						,
										'function' 						,
									];
	protected	$dates 				= ['created_at', 'updated_at', 'deleted_at'];
	protected 	$rules				= [
										'title' 						=> 'required|max:255',
										'type' 							=> 'required|in:table,panel,stat',
										'row' 							=> 'required|numeric',
										'col' 							=> 'required|numeric',
										'query' 						=> 'required|max:255',
										'field' 						=> 'max:255',
										'function' 						=> 'required|in:total_documents,total_branches,total_employees,index_branches,index_employees,index_documents|max:255',
									];
	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personid' 					=> 'PersonID', 
											'title' 					=> 'Title', 
											'type' 						=> 'Type', 
											'withattributes' 			=> 'WithAttributes',
										];
	public $sortable 				= ['title', 'row', 'created_at'];

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
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeTitle($query, $variable)
	{
		return $query->where('title', 'like' ,'%'.$variable.'%');
	}

	public function scopeType($query, $variable)
	{
		return $query->where('type', $variable);
	}
}
