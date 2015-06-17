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
		View::composer(['widgets.organisation.select', 'widgets.common.nav_sidebar', 'widgets.common.form_name'], 	'App\Http\ViewComposers\OrganisationComposer');

		// -----------------------------------------------------------------------------
		// BRANCH
		// -----------------------------------------------------------------------------
		View::composer(['widgets.branch.table', 'widgets.branch.form'], 	'App\Http\ViewComposers\BranchComposer');

		// -----------------------------------------------------------------------------
		// CALENDAR
		// -----------------------------------------------------------------------------
		View::composer(['widgets.calendar.table', 'widgets.calendar.form'], 	'App\Http\ViewComposers\CalendarComposer');

		// -----------------------------------------------------------------------------
		// WORKLEAVE
		// -----------------------------------------------------------------------------
		View::composer(['widgets.workleave.table', 'widgets.workleave.form'], 	'App\Http\ViewComposers\WorkleaveComposer');

		// -----------------------------------------------------------------------------
		// DOCUMENT
		// -----------------------------------------------------------------------------
		View::composer(['widgets.document.table', 'widgets.document.form'], 	'App\Http\ViewComposers\DocumentComposer');

		// -----------------------------------------------------------------------------
		// PERSON
		// -----------------------------------------------------------------------------
		View::composer(['widgets.person.table', 'widgets.person.form'], 	'App\Http\ViewComposers\PersonComposer');


		// -----------------------------------------------------------------------------
		// CONTACT
		// -----------------------------------------------------------------------------
		View::composer(['widgets.contact.table', 'widgets.contact.form'], 	'App\Http\ViewComposers\ContactComposer');

		// -----------------------------------------------------------------------------
		// CHART
		// -----------------------------------------------------------------------------
		View::composer(['widgets.chart.list', 'widgets.chart.form'], 	'App\Http\ViewComposers\ChartComposer');

		// -----------------------------------------------------------------------------
		// API
		// -----------------------------------------------------------------------------
		View::composer(['widgets.api.table', 'widgets.api.form'], 	'App\Http\ViewComposers\ApiComposer');

		// -----------------------------------------------------------------------------
		// FINGERPRINT
		// -----------------------------------------------------------------------------
		View::composer(['widgets.fingerprint.table', 'widgets.fingerprint.form'], 	'App\Http\ViewComposers\FingerPrintComposer');
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