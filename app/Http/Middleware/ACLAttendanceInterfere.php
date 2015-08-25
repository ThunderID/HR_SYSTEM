<?php namespace App\Http\Middleware;

use App\Models\Policy;
use Closure;
use Illuminate\Support\Facades\Redirect;

class ACLAttendanceInterfere {

    public function handle($request, Closure $next)
    {
        if ($request->session()->has('duedate') && $request->session()->get('user')['menuid'] > 1) 
        {
        	switch ($request->session()->get('user')['menuid']) 
            {
        		case '2':
        			$type 			= 'firstacleditor';
        			$duedate2 		= '- 1 month';
        			$proberror 		= 'Maksimal range adalah satu bulan (28 - 31 hari).';
        			break;
        		case '3':
        			$type 			= 'secondacleditor';
        			$proberror 		= 'Maksimal range adalah 5 hari.';
        			$duedate2 		= '- 5 days';
        			break;
        	}

        	if($request->input('org_id'))
        	{
        		$org_id 			= $request->input('org_id');
        	}
        	else
        	{
        		$org_id 			= $request->session()->get('organisationid');
        	}

        	$limit 					= Policy::type($type)->ondate(date('Y-m-d'))->organisationid($org_id)->orderby('created_at', 'asc')->first();
        	if($limit)
        	{
        		$limitdate 			= date('Y-m-d', strtotime($limit->value));
        	}
        	else
        	{
        		$limitdate 			= date('Y-m-d', strtotime($duedate2));
        	}

        	$currentdate 			= date('Y-m-d', strtotime($request->session()->get('duedate')));

        	if($currentdate < $limitdate)
        	{
                return Redirect::route('hr.organisations.show', [$org_id, 'org_id' => $org_id])->with('alert_warning', $proberror);
        	}
        }

        if ($request->session()->has('settlementattendance') && $request->session()->get('user')['menuid'] > 1) 
        {
            $request->session()->forget('settlementattendance');
            
            return Redirect::route('hr.organisations.show', [$org_id, 'org_id' => $org_id])->with('alert_warning', 'Tidak dapat mengubah status yang sudah settle');
        }

        return $next($request); 
    }
}