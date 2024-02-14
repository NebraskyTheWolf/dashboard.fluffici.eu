<?php

namespace App\Http\Controllers;

use App\Models\LastVersion;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

// Assuming you have a Version model

class Versioning extends Controller
{
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
        $commits = $data['commits'][0];

        if ($ref !== "refs/heads/master") {
            return response()->setStatusCode(403)->json([
                'status' => false,
                'message' => 'Cannot update versioning on a branch out of \'refs/heads/master\''
            ]);
        }

        $commitMessage = $commits['message'];
        $version = LastVersion::latest()->first();
        $version->last_commit_id = $before;
        $version->current_commit_id = $after;

        if (str_starts_with($commitMessage, "BREAKING CHANGES: ")) {
            $version->increment('major', 1);
        } else if (str_starts_with($commitMessage, "PATCH: ")) {
            $version->increment('patch', 1);
        } else {
            $version->increment('minor', 1);
        }

        $version->save();

        return response()->json([
            'status' => true,
            'message' => 'Versioning updated!'
        ]);
    }
}
