<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		\App\Models\Application::observe(new \App\Models\Observers\ApplicationObserver);
		\App\Models\Menu::observe(new \App\Models\Observers\MenuObserver);
		\App\Models\Authentication::observe(new \App\Models\Observers\AuthenticationObserver);
		\App\Models\Api::observe(new \App\Models\Observers\ApiObserver);

		\App\Models\Contact::observe(new \App\Models\Observers\ContactObserver);

		\App\Models\Document::observe(new \App\Models\Observers\DocumentObserver);
		\App\Models\DocumentDetail::observe(new \App\Models\Observers\DocumentDetailObserver);
		\App\Models\Template::observe(new \App\Models\Observers\TemplateObserver);
		\App\Models\PersonDocument::observe(new \App\Models\Observers\PersonDocumentObserver);

		\App\Models\Finger::observe(new \App\Models\Observers\FingerObserver);
		\App\Models\FingerPrint::observe(new \App\Models\Observers\FingerPrintObserver);

		\App\Models\Log::observe(new \App\Models\Observers\LogObserver);
		\App\Models\Log::observe(new \App\Models\Observers\ProcessingLogObserver);
		\App\Models\ProcessLog::observe(new \App\Models\Observers\ProcessLogObserver);
		\App\Models\ErrorLog::observe(new \App\Models\Observers\ErrorLogObserver);


		\App\Models\Organisation::observe(new \App\Models\Observers\OrganisationObserver);
		\App\Models\Branch::observe(new \App\Models\Observers\BranchObserver);
		\App\Models\Chart::observe(new \App\Models\Observers\ChartObserver);

		\App\Models\Person::observe(new \App\Models\Observers\PersonObserver);

		\App\Models\Calendar::observe(new \App\Models\Observers\CalendarObserver);
		\App\Models\Schedule::observe(new \App\Models\Observers\ScheduleObserver);
		\App\Models\PersonSchedule::observe(new \App\Models\Observers\PersonScheduleObserver);
		\App\Models\Follow::observe(new \App\Models\Observers\FollowObserver);

		\App\Models\PersonWidget::observe(new \App\Models\Observers\PersonWidgetObserver);

		\App\Models\Workleave::observe(new \App\Models\Observers\WorkleaveObserver);
		\App\Models\PersonWorkleave::observe(new \App\Models\Observers\PersonWorkleaveObserver);

		\App\Models\Work::observe(new \App\Models\Observers\WorkObserver);
		
		\App\Models\SettingIdle::observe(new \App\Models\Observers\SettingIdleObserver);
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		\App\Models\Application::observe(new \App\Models\Observers\ApplicationObserver);
		\App\Models\Menu::observe(new \App\Models\Observers\MenuObserver);
		\App\Models\Authentication::observe(new \App\Models\Observers\AuthenticationObserver);
		\App\Models\Api::observe(new \App\Models\Observers\ApiObserver);

		\App\Models\Contact::observe(new \App\Models\Observers\ContactObserver);

		\App\Models\Document::observe(new \App\Models\Observers\DocumentObserver);
		\App\Models\DocumentDetail::observe(new \App\Models\Observers\DocumentDetailObserver);
		\App\Models\Template::observe(new \App\Models\Observers\TemplateObserver);
		\App\Models\PersonDocument::observe(new \App\Models\Observers\PersonDocumentObserver);

		\App\Models\Finger::observe(new \App\Models\Observers\FingerObserver);
		\App\Models\FingerPrint::observe(new \App\Models\Observers\FingerPrintObserver);

		\App\Models\Log::observe(new \App\Models\Observers\LogObserver);
		\App\Models\Log::observe(new \App\Models\Observers\ProcessingLogObserver);
		\App\Models\ProcessLog::observe(new \App\Models\Observers\ProcessLogObserver);
		\App\Models\ErrorLog::observe(new \App\Models\Observers\ErrorLogObserver);

		\App\Models\Organisation::observe(new \App\Models\Observers\OrganisationObserver);
		\App\Models\Branch::observe(new \App\Models\Observers\BranchObserver);
		\App\Models\Chart::observe(new \App\Models\Observers\ChartObserver);

		\App\Models\Person::observe(new \App\Models\Observers\PersonObserver);

		\App\Models\Calendar::observe(new \App\Models\Observers\CalendarObserver);
		\App\Models\Schedule::observe(new \App\Models\Observers\ScheduleObserver);
		\App\Models\PersonSchedule::observe(new \App\Models\Observers\PersonScheduleObserver);
		\App\Models\Follow::observe(new \App\Models\Observers\FollowObserver);

		\App\Models\PersonWidget::observe(new \App\Models\Observers\PersonWidgetObserver);

		\App\Models\Workleave::observe(new \App\Models\Observers\WorkleaveObserver);
		\App\Models\PersonWorkleave::observe(new \App\Models\Observers\PersonWorkleaveObserver);

		\App\Models\Work::observe(new \App\Models\Observers\WorkObserver);

		\App\Models\SettingIdle::observe(new \App\Models\Observers\SettingIdleObserver);

		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}

}
