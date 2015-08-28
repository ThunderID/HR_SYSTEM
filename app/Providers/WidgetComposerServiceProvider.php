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
		// APPLICATION
		// -----------------------------------------------------------------------------
		View::composer(['widgets.application.table', 'widgets.application.form'], 'App\Http\ViewComposers\ApplicationComposer');

		// -----------------------------------------------------------------------------
		// MENU
		// -----------------------------------------------------------------------------
		View::composer(['widgets.menu.table', 'widgets.menu.form', 'widgets.groupmenu.table'], 'App\Http\ViewComposers\MenuComposer');

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
		View::composer(['widgets.organisation.calendar.table', 'widgets.organisation.calendar.form', 'widgets.organisation.calendar.simplelist', 'widgets.organisation.calendar.calendar', 'widgets.organisation.calendar.select'], 	'App\Http\ViewComposers\CalendarComposer');

		// -----------------------------------------------------------------------------
		// DOCUMENT
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.document.table', 'widgets.organisation.document.form', 'widgets.organisation.document.select', 'widgets.common.persondocument.form', 'widgets.common.persondocument.form_no_title', 'widgets.common.persondocument.form_multiple', 'widgets.organisation.document.stat.total_document'], 	'App\Http\ViewComposers\DocumentComposer');

		// -----------------------------------------------------------------------------
		// Policy
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.policy.table', 'widgets.organisation.policy.form'], 	'App\Http\ViewComposers\PolicyComposer');

		// -----------------------------------------------------------------------------
		// WORKLEAVE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.workleave.table', 'widgets.organisation.workleave.form', 'widgets.organisation.workleave.select', 'widgets.organisation.person.work.select_workleave', 'widgets.organisation.person.workleave.followworkleave'], 	'App\Http\ViewComposers\WorkleaveComposer');

		// -----------------------------------------------------------------------------
		// PERSON
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.person.table', 'widgets.organisation.person.form', 'widgets.organisation.person.form_multiple', 'widgets.organisation.person.form_no_title', 'widgets.organisation.person.select', 'widgets.organisation.report.attendance.table', 'widgets.organisation.report.wage.table', 'widgets.organisation.person.stat.total_employee', 'widgets.organisation.person.stat.average_loss_rate', 'widgets.organisation.report.attendance.global.table', 'widgets.organisation.report.attendance.person.table', 'widgets.organisation.report.activity.person.log.table', 'widgets.organisation.report.attendance.table_csv', 'widgets.organisation.report.attendance.person.table_csv', 'widgets.organisation.report.attendance.person.log.table_csv', 'widgets.organisation.report.wage.table_csv', 'widgets.organisation.person.workleave.left_quota', 'widgets.organisation.person.upload_csv', 'widgets.organisation.report.activity.table', 'widgets.organisation.report.activity.person.table'], 	'App\Http\ViewComposers\PersonComposer');

		// -----------------------------------------------------------------------------
		// CONTACT
		// -----------------------------------------------------------------------------
		View::composer(['widgets.common.contact.table', 'widgets.common.contact.form', 'widgets.common.contact.form_no_title'], 	'App\Http\ViewComposers\ContactComposer');

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
		View::composer(['widgets.organisation.authentication.table', 'widgets.organisation.authentication.form'], 	'App\Http\ViewComposers\WorkAuthenticationComposer');

		// -----------------------------------------------------------------------------
		// AUTHGROUP
		// -----------------------------------------------------------------------------
		View::composer(['widgets.authgroup.select', 'widgets.authgroup.table', 'widgets.authgroup.form'], 	'App\Http\ViewComposers\AuthGroupComposer');

		// -----------------------------------------------------------------------------
		// FOLLOW
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.branch.chart.follow.table', 'widgets.organisation.branch.chart.follow.form', 'widgets.organisation.calendar.chart.table', 'widgets.organisation.calendar.chart.form'], 	'App\Http\ViewComposers\FollowComposer');

		// -----------------------------------------------------------------------------
		// SCHEDULE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.schedule.table', 'widgets.schedule.form'], 	'App\Http\ViewComposers\ScheduleComposer');

		// -----------------------------------------------------------------------------
		// WORK
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.person.work.table', 'widgets.organisation.person.work.form', 'widgets.organisation.person.work.experience.form', 'widgets.organisation.person.work.select'], 	'App\Http\ViewComposers\WorkComposer');

		// -----------------------------------------------------------------------------
		// PERSON WORKLEAVE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.person.workleave.table', 'widgets.organisation.person.workleave.taken.form', 'widgets.organisation.person.workleave.given.form', 'widgets.organisation.person.workleave.select', 'widgets.organisation.person.workleave.quota_workleave_year'], 	'App\Http\ViewComposers\PersonWorkleaveComposer');

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

		// -----------------------------------------------------------------------------
		// PROCESS LOG
		// -----------------------------------------------------------------------------
		View::composer(['widgets.organisation.report.attendance.form'], 	'App\Http\ViewComposers\ProcessLogComposer');

		// -----------------------------------------------------------------------------
		// RECORD LOG
		// -----------------------------------------------------------------------------
		View::composer(['widgets.recordlog.table'], 	'App\Http\ViewComposers\RecordLogComposer');
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