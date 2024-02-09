<?php

declare(strict_types=1);

namespace App\Orchid\Presenters;

use Illuminate\Support\Str;
use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class UserPresenter extends Presenter implements Personable, Searchable
{
    /**
     * Returns the label for this presenter, which is used in the UI to identify it.
     */
    public function label(): string
    {
        return 'Users';
    }

    /**
     * Returns the title for this presenter, which is displayed in the UI as the main heading.
     */
    public function title(): string {
        return $this->entity->name;
    }

    /**
     * Returns the subtitle for this presenter, which provides additional context about the user.
     */
    public function subTitle(): string {
        $roles = $this->entity->roles->pluck('name')->implode(' / ');

        return (string) Str::of($roles)
            ->limit(20)
            ->whenEmpty(fn () => 'Guest');
    }

    /**
     * Returns the URL for this presenter, which is used to link to the user's edit page.
     */
    public function url(): string
    {
        return route('platform.systems.users.edit', $this->entity);
    }

    public function status(): ?string
    {
        return '';
    }

    /**
     * Returns the URL for the user's Gravatar image, or a default image if one is not found.
     */
    public function image(): ?string {
        if ($this->entity->avatar == 1) {
            return 'https://autumn.fluffici.eu/avatars/' . $this->entity->avatar_id . '?width=256&height=256';
        }
        return 'https://ui-avatars.com/api/?name=' . $this->title() . '&background=0D8ABC&color=fff';
    }

    /**
     * Returns the number of models to return for a compact search result.
     * This method is used by the search functionality to display a list of matching results.
     */
    public function perSearchShow(): int{
        return 4;
    }

    /**
     * Returns a Laravel Scout builder object that can be used to search for matching users.
     * This method is used by the search functionality to retrieve a list of matching results.
     */
    public function searchQuery(?string $query = null): Builder
    {
        return $this->entity->search($query);
    }

    public function getId(): int {
        return $this->entity->id;
    }
}
