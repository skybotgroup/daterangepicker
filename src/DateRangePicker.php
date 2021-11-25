<?php

namespace Skybotgroup\DateRangePicker;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

/**
 * Class DateRangePicker
 * @package Encore\DateRangePicker
 *
 * @see http://www.daterangepicker.com/
 */
class DateRangePicker extends Field
{
    /**
     * @var string
     */
    protected $view = 'daterangepicker::daterangepicker';

    /**
     * @var array
     */
    protected static $css = [
        'vendor/skybotgroup/daterangepicker/daterangepicker.css',
    ];

    /**
     * @var array
     */
    protected static $js = [
        'vendor/skybotgroup/daterangepicker/daterangepicker.js',
    ];

    protected $format = 'YYYY-MM-DD';

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * DateRangePicker constructor.
     * @param $column
     * @param array $arguments
     */
    public function __construct($column, $arguments = [])
    {
        if (is_string($column)) {
            $this->options(['singleDatePicker' => true]);
            return parent::__construct($column, $arguments);
        }

        if (is_array($column)) {
            $this->column = [];
            $this->column['start'] = $column[0];
            $this->column['end'] = $column[1];

            $this->label = $this->formatLabel($arguments);

            $this->id = $this->formatId($this->column);

            $this->multiple = true;
        }
        /* Localisation */
        $this->options([
            'locale' => [
                'applyLabel' => __("Apply"),
                'cancelLabel' => __("Cancel"),
                'customRangeLabel' => __("Custom Range"),
            ]
        ]);
    }

    protected $width = [
        'label' => 2,
        'field' => 10,
    ];

    /**
     * Predefine Date Ranges.
     *
     * @param array $ranges
     * @return $this
     */
    public function ranges($ranges = [])
    {
        return $this->options(compact('ranges'));
    }

    public function defaultRange($range){
        return $this->options([
            'startDate' => $range[0],
            'endDate' => $range[1]
        ]);
    }

    /**
     * Set date format.
     *
     * @param $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        Arr::set($this->options, 'locale.format', $this->format);

        $global = DateRangePickerExtension::config('config', []);

        $this->options($global);

        $this->script = $this->script();

        if ($this->multiple){
            if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
                $this->value['range'] = implode(' - ', $this->value());
                $this->column['range'] = implode('_', $this->column);
            }else{
                $this->value['range'] = implode( $this->value(), ' - ');
                $this->column['range'] = implode($this->column, '_');
            }
        }else{
            $this->value = $this->value();
        }

        $this->variables['multiple'] = $this->multiple;

        return parent::render();
    }

    public function script()
    {
        $options = json_encode($this->options);
        $locale = config('app.locale');
        if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
            $classSelector = implode('_', $this->getElementClass());
        }else{
            $classSelector = implode($this->getElementClass(), '_');
        }
        $script = "
            moment.locale('$locale');
            $('.{$classSelector}').daterangepicker($options);
        ";
        if ($this->multiple) {
            $script .= "
                $('.{$classSelector}').on('apply.daterangepicker', function(ev, picker) {
                    var range = $('.{$classSelector}').val().split(' - ');
                    $('#{$this->id['start']}').val(range[0]);
                    $('#{$this->id['end']}').val(range[1]);
                });
            ";
        }
        return $script;
    }
}