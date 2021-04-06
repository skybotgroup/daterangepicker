<?php

namespace Skybotgroup\DateRangePicker;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\ServiceProvider;

class DateRangePickerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(DateRangePickerExtension $extension)
    {
        if (! DateRangePickerExtension::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'daterangepicker');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/skybotgroup/daterangepicker')],
                'daterangepicker'
            );
        }

        Admin::booting(function () {
            Form::extend('daterangepicker', DateRangePicker::class);

            if ($alias = DateRangePickerExtension::config('alias')) {
                Form::alias('daterangepicker', $alias);
            }
        });
    }
}