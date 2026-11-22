<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\EventBus;
use Livewire\Features\SupportMultipleRootElementDetection\SupportMultipleRootElementDetection;
use ReflectionFunction;
use ReflectionProperty;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->disableLivewireRootElementHeuristic();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Turn off Livewire's "more than one root element" warning.
     *
     * The check re-parses the mount-stage HTML with libxml2, which mis-nests custom
     * ui-* elements and inline SVG emitted by Flux/Blaze components. The theme
     * generator maintains a single Livewire root element, so disabling this
     * debug-only heuristic avoids false-positive warnings in local development.
     */
    protected function disableLivewireRootElementHeuristic(): void
    {
        $scope = SupportMultipleRootElementDetection::class;

        try {
            $bus = $this->app->make(EventBus::class);

            $property = new ReflectionProperty($bus, 'listeners');
            $property->setAccessible(true);

            $listeners = $property->getValue($bus)['mount'] ?? [];

            $filtered = array_values(array_filter(
                $listeners,
                function (callable $listener) use ($scope): bool {
                    if (! $listener instanceof \Closure) {
                        return true;
                    }

                    return (new ReflectionFunction($listener))->getClosureScopeClass()?->getName() !== $scope;
                },
            ));

            $property->setValue($bus, ['mount' => $filtered] + $property->getValue($bus));
        } catch (\Throwable) {
            // The internals moved. The diagnostic is harmless, so carry on without it.
        }
    }
}
