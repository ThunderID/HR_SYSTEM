<?php namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class WidgetComposerServiceProvider extends ServiceProvider {

	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->admin_widget();
		$this->web_widget();
	}

	/**
	 * Register
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	private function admin_widget()
	{
		// -----------------------------------------------------------------------------
		// COMMON
		// -----------------------------------------------------------------------------
		// View::composer(['admin.widgets.common.logo'], 'App\Http\ViewComposers\Admin\Common\LogoComposer');
		// View::composer(['admin.widgets.common.nav.menu'], 'App\Http\ViewComposers\Admin\Common\MenuComposer');

		// -----------------------------------------------------------------------------
		// LOGIN
		// -----------------------------------------------------------------------------
		View::composer(['widgets.form.form_login'], 'App\Http\ViewComposers\Common\LoginFormComposer');

		// -----------------------------------------------------------------------------
		// ORGANISATION
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.select', 'widgets.common.sidebar', 'widgets.organisation.form'], 	'App\Http\ViewComposers\OrganisationComposer');

		// -----------------------------------------------------------------------------
		// BRANCH
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.branch.table', 'widgets.organisation.branch.form', 'widgets.organisation.branch.stat.total_branch'], 	'App\Http\ViewComposers\BranchComposer');

		// -----------------------------------------------------------------------------
		// CALENDAR
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.calendar.table', 'widgets.organisation.calendar.form', 'widgets.organisation.calendar.calendar', 'widgets.organisation.calendar.select'], 	'App\Http\ViewComposers\CalendarComposer');

		// -----------------------------------------------------------------------------
		// WORKLEAVE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.workleave.table', 'widgets.organisation.workleave.form', 'widgets.organisation.workleave.select'], 	'App\Http\ViewComposers\WorkleaveComposer');

		// -----------------------------------------------------------------------------
		// DOCUMENT
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.document.table', 'widgets.organisation.document.form', 'widgets.organisation.document.select', 'widgets.common.persondocument.form', 'widgets.organisation.document.stat.total_document'], 	'App\Http\ViewComposers\DocumentComposer');

		// -----------------------------------------------------------------------------
		// IDLE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.idle.table', 'widgets.idle.form'], 	'App\Http\ViewComposers\IdleComposer');

		// -----------------------------------------------------------------------------
		// PERSON
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.person.table', 'widgets.organisation.person.form', 'widgets.organisation.person.select', 'widgets.report.attendance.table', 'widgets.report.wage.table', 'widgets.organisation.person.stat.total_employee', 'widgets.organisation.person.stat.average_loss_rate'], 	'App\Http\ViewComposers\PersonComposer');

		// -----------------------------------------------------------------------------
		// CONTACT
		// -----------------------------------------------------------------------------
		View::composer(['widgets.common.contact.table', 'widgets.common.contact.form'], 	'App\Http\ViewComposers\ContactComposer');

		// -----------------------------------------------------------------------------
		// CHART
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.branch.chart.list', 'widgets.organisation.branch.chart.form', 'widgets.organisation.branch.chart.stat', 'widgets.organisation.branch.chart.select'], 	'App\Http\ViewComposers\ChartComposer');

		// -----------------------------------------------------------------------------
		// API
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.branch.api.table', 'widgets.organisation.branch.api.form'], 	'App\Http\ViewComposers\ApiComposer');

		// -----------------------------------------------------------------------------
		// FINGERPRINT
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.branch.fingerprint.table', 'widgets.fingerprint.form', 'widgets.organisation.branch.fingerprint.block'], 	'App\Http\ViewComposers\FingerPrintComposer');

		// -----------------------------------------------------------------------------
		// AUTHENTICATION
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.branch.chart.authentication.table', 'widgets.authentication.form'], 	'App\Http\ViewComposers\ApplicationComposer');

		// -----------------------------------------------------------------------------
		// FOLLOW
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.branch.chart.follow.table', 'widgets.organisation.branch.chart.follow.form', 'widgets.calendar.chart.table', 'widgets.calendar.chart.form'], 	'App\Http\ViewComposers\FollowComposer');

		// -----------------------------------------------------------------------------
		// SCHEDULE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.schedule.table', 'widgets.schedule.form'], 	'App\Http\ViewComposers\ScheduleComposer');

		// -----------------------------------------------------------------------------
		// WORK
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.person.work.table', 'widgets.organisation.person.work.form', 'widgets.organisation.person.work.experience.form'], 	'App\Http\ViewComposers\WorkComposer');

		// -----------------------------------------------------------------------------
		// PERSON WORKLEAVE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.person.workleave.table', 'widgets.organisation.person.workleave.form', 'widgets.organisation.workleave.chart.form'], 	'App\Http\ViewComposers\PersonWorkleaveComposer');

		// -----------------------------------------------------------------------------
		// PERSON DOCUMENT
		// -----------------------------------------------------------------------------
		View::composer(['widgets.common.persondocument.table'], 	'App\Http\ViewComposers\PersonDocumentComposer');

		// -----------------------------------------------------------------------------
		// RELATIVE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.person.relative.table', 'widgets.organisation.person.relative.form', 'widgets.organisation.person.relative.employee.form'], 	'App\Http\ViewComposers\RelativeComposer');

		// -----------------------------------------------------------------------------
		// TEMPLATE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.document.template.table', 'widgets.organisation.document.template.form'], 	'App\Http\ViewComposers\TemplateComposer');
	}

	private function web_widget()
	{
		// -----------------------------------------------------------------------------
		// 
		// -----------------------------------------------------------------------------
		// View::composer('web.widgets.headline_carousel', 'App\Http\ViewComposers\Web\HeadlineComposer');
		// View::composer('web.widgets.tour_list', 'App\Http\ViewComposers\Web\TourListComposer');
		// View::composer('web.widgets.latest_blog', 'App\Http\ViewComposers\Web\BlogListComposer');
		// View::composer('web.widgets.trending_destination', 'App\Http\ViewComposers\Web\TrendingDestinationComposer');
		// View::composer('web.widgets.supported_by', 'App\Http\ViewComposers\Web\VendorListComposer');
		// View::composer('web.widgets.search_tour', 'App\Http\ViewComposers\Web\TourFilterComposer');
	}

}