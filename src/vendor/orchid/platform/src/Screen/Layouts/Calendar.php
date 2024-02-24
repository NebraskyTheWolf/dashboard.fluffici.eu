<?php

namespace Orchid\Screen\Layouts;

use Orchid\Screen\Layout;
use Orchid\Screen\Repository;

abstract class Calendar extends Layout
{

    public const string TYPE_WEEK = 'dayGridWeek';
    public const string TYPE_YEAR = 'dayGridYear';

    protected $template = 'layouts.calendar';

    protected string $description;

    protected string $title = 'My Calendar';

    protected string $slug = 'calendar-0';

    protected string $initialView = self::TYPE_YEAR;

    protected bool $export = false;
    protected bool $editable = false;

    protected string $color = 'red';

    protected bool $googleCalendar = false;
    protected string $googleCalendarSecret;
    protected string $googleCalendarURL;

    protected string $locale = 'cs';

    /**
     * Set the title of the chart.
     *
     * @param string|null $title The title of the chart. If null is passed, it will unset the title.
     *
     * @return $this Returns the current instance of the class.
     */
    public function title(?string $title = null): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Sets the export flag to the given value.
     *
     * @param bool $export The value to set the export flag to.
     * @return static Returns a reference to the current instance.
     */
    public function export(bool $export): static
    {
        $this->export = $export;

        return $this;
    }


    /**
     * Sets the description of the object.
     *
     * @param string $description The description to set.
     *
     * @return static Returns an instance of the current object with the description set.
     */
    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the type property.
     *
     * @param string $type The new value for the type property.
     * @return static Returns an instance of the current class.
     */
    public function type(string $type): static
    {
        $this->initialView = $type;

        return $this;
    }

    /**
     * Sets the slug to the given value.
     *
     * @param string $slug The value to set the slug to.
     * @return static Returns a reference to the current instance.
     */
    public function slug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Sets the locale to the given value.
     *
     * @param string $locale The locale to set.
     * @return static Returns a reference to the current instance.
     */
    public function locale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Sets the value of the Google Calendar flag.
     *
     * @param bool $isGoogle The value to set for the Google Calendar flag, possible values are "true" or "false".
     * @return static Returns a reference to the current instance.
     */
    public function isGoogleCalendar(bool $isGoogle): static
    {
        $this->googleCalendar = $isGoogle;

        return $this;
    }

    /**
     * Sets the Google Calendar URL to the given value.
     *
     * @param string $url The URL to set as the Google Calendar URL.
     * @return static Returns a reference to the current instance.
     */
    public function setGoogleCalendarURL(string $url): static
    {
        $this->googleCalendarURL = $url;

        return $this;
    }

    /**
     * Sets the Google Calendar secret key to the given value.
     *
     * @param string $secret The secret key to set for the Google Calendar.
     * @return static Returns a reference to the current instance.
     */
    public function setGoogleCalendarSecret(string $secret): static
    {
        $this->googleCalendarSecret = $secret;

        return $this;
    }

    /**
     * Sets the editable flag to the given value.
     *
     * @param bool $editable The value to set the editable flag to.
     * @return static Returns a reference to the current instance.
     */
    public function editable(bool $editable): static
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Sets the color to the given value.
     *
     * @param string $color The value to set the color to.
     * @return static Returns a reference to the current instance.
     */
    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function build(Repository $repository)
    {
        $this->query = $repository;

        if (!$this->isSee()) {
            return;
        }

        return view($this->template, [
            'title'                 => __($this->title),
            'description'           => __($this->description),
            'slug'                  => $this->slug,
            'initialView'           => $this->initialView,
            'export'                => $this->export,
            'color'                 => $this->color,
            'editable'              => $this->editable,
            'locale'                => $this->locale,
            'googleCalendar'        => $this->googleCalendar,
            'googleCalendarURL'     => $this->googleCalendarURL,
            'googleCalendarSecret'  => $this->googleCalendarSecret
        ]);
    }
}
