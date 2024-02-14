<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastVersion extends Model
{
    use HasFactory;

    public $table = 'last_version';

    /**
     * Returns the current version.
     *
     * @return string The current version represented as a string in the format "major.minor.patch".
     */
    public function getCurrentVersion(): string
    {
        return $this->major . '.' . $this->minor . '.' . $this->patch;
    }

    /**
     * Retrieves the current commit ID from the version control system.
     *
     * This method returns the commit ID of the current version in the version control system.
     *
     * @return string The commit ID, or null if it is not available.
     */
    public function getCurrentCommitId(): string
    {
        return $this->current_commit_id;
    }

    /**
     * Retrieves the short version of the current commit ID from the version control system.
     *
     * This method returns the short version of the commit ID of the current version in the version control system.
     * The short version consists of the first 8 characters of the commit ID.
     *
     * @return string The short commit ID, or null if it is not available.
     */
    public function getShortCommitId(): string
    {
        return substr($this->current_commit_id, 0, 8);
    }
}
