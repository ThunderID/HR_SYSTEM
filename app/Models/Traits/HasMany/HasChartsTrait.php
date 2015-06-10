<?php namespace App\Models\Traits\HasMany;

use DateTime;

trait HasChartsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasChartsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/

	public function Charts()
	{
		return $this->hasMany('App\Models\Chart');
	}

	public function Departments()
	{
		return $this->hasMany('App\Models\Chart');
	}

	public function scopeDisplayDepartments($query, $variable)
	{
		return $query->with(['departments' => function($q)use($variable){$q->where('tag', 'like' ,$variable.'%')->groupBy('tag');}]);
	}

	public function scopeStructure($query, $variable)
	{
		return $query->with(['charts' => function($q)use($variable){$q->where('path', 'like' ,$variable.'%')->orderBy('path');}]);
	}

	public function scopeStructureByDepartment($query, $variable)
	{
		return $query->with(['charts' => function($q)use($variable){$q->where('tag', 'like' ,$variable.'%')->orderBy('path');}]);
	}

	public function scopeCountResign($query, $variable)
	{
		if(strtotime($variable))
		{
			$days = new DateTime($variable);

			return $query->with(['charts.works' => function($q)use($days){$q->selectRaw('count(*) as count')->where('works.end', '>=', $days->format('Y-m-d'));}]);
		}

		return $query->with(['charts.works' => function($q){$q->selectRaw('count(*) as count')->whereNotNull('works.end');}]);
	}

	public function scopeCurrentLeader($query, $variable)
	{
		return $query->with(['charts.works' => function($q)use($variable){$q->whereNotNull('works.end')->take($variable);}, 'charts.works.person']);
		return  $query->join('charts', 'branches.id', '=', 'charts.branch_id')
					 ->join('works', 'charts.id', '=', 'works.chart_id')
					 ->join('persons', 'works.person_id', '=', 'persons.id')
					 ->where('works.end', null)
					 ->where('charts.chart_id', 0)
					 ->groupBy('branches.id')
					 ->take($variable);
	}

	public function scopeCountWorkerByStatus($query, $variable)
	{
		return  $query->select('branches.*')
					 ->join('charts', 'branches.id', '=', 'charts.branch_id')
					 ->join('works', 'charts.id', '=', 'works.chart_id')
					 ->where('works.end', null)
					 ->where('works.status', 'like', '%'.$variable.'%');
	}

	public function scopeCountWorkerByBranchByStatus($query, $variable)
	{
		return  $query->selectRaw('count(status) as count, status, hr_branches.name as branch')
					 ->join('charts', 'branches.id', '=', 'charts.branch_id')
					 ->join('works', 'charts.id', '=', 'works.chart_id')
					 ->where('works.end', null)
					 ->where('works.status', 'like', '%'.$variable.'%')
					 ->groupBy('works.status')
					 ->groupBy('branches.id');
	}

	public function scopeCountWorkerByChartByStatus($query, $variable)
	{
		return $query->with(['charts.works' => function($q){$q->selectRaw('count(*) as count , status')->whereNotNull('works.end')->groupBy('works.status');}]);
	}
}
