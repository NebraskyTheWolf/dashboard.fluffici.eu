<?php

namespace App\Http\Controllers;

use App\Models\LastVersion;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Versioning
 *
 * Controller for updating versioning based on the commit message.
 */
class Versioning extends Controller
{

    const string PREFIX_BREAKING_CHANGES = "BREAKING CHANGES: ";
    const string PREFIX_PATCH = "PATCH: ";

    /**
     * Update the versioning based on the commit message.
     *
     * @param Request $request The HTTP request object.
     *
     * @return JsonResponse The JSON response indicating the result of the versioning update.
     */

    public function index(Request $request)
    {
        $data = json_decode(json_encode($request->all()), true);
        $ref = $data['ref'];
        $before = $data['before'];
        $after = $data['after'];
        $commit = $data['commits'][0];

        if ($ref !== "refs/heads/master") {
            return response()->json([
                'status' => false,
                'message' => 'Cannot update versioning on a branch out of \'refs/heads/master\''
            ]);
        }

        $commitMessage = $commit['message'];
        $version = LastVersion::latest()->first();
        $version->last_commit_id = $before;
        $version->current_commit_id = $after;
        $this->incrementVersion($version, $commitMessage);
        $version->save();

        return response()->json([
            'status' => true,
            'message' => 'Versioning updated!'
        ]);
    }

    private function incrementVersion($version, $message): void
    {
        if (str_starts_with($message, self::PREFIX_BREAKING_CHANGES)) {
            $version->increment('major', 1);
        } elseif (str_starts_with($message, self::PREFIX_PATCH)) {
            $version->increment('patch', 1);
        } else {
            $version->increment('minor', 1);
        }
    }
}

