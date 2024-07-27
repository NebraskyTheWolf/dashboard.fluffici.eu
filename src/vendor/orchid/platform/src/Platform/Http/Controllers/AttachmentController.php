<?php

declare(strict_types=1);

namespace Orchid\Platform\Http\Controllers;

use App\Models\AuditLogs;
use App\Models\PlatformAttachments;
use app\Models\Security\Account\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Orchid\Attachment\Models\Attachment;
use Orchid\Platform\Dashboard;

/**
 * Class AttachmentController.
 */
class AttachmentController extends Controller
{
    /**
     * @var Attachment
     */
    protected $attachment;


    /**
     * AttachmentController constructor.
     */
    public function __construct()
    {
        $this->checkPermission('platform.systems.attachment');
        $this->attachment = Dashboard::modelClass(Attachment::class);
    }

    public function uploaded(Request $request): JsonResponse {

        if ($request->has('user_id')
            && $request->has('action_id')
            && $request->has('tag')
            && $request->has('id')) {

            if ($request->input('tag') == "avatars") {
                $user = User::where('id', $request->input('user_id'))->firstOrFail();

                if ($user->avatar == 1) {
                    AuditLogs::create([
                        'name' => User::where('id', $request->input('user_id'))->firstOrFail()->name,
                        'slug' => 'file_changed',
                        'type' => substr($request->input('id'), 0, 16) . ' -> ' . substr($user->avatar_id, 0, 16)
                    ]);
                } else {
                    $user->avatar = 1;
                }

                $user->avatar_id = $request->input('id');
                $user->save();
            }

            PlatformAttachments::create([
                'user_id' => $request->input('user_id'),
                'action_id' => $request->input('action_id') ?: "none",
                'bucket' => $request->input('tag'),
                'attachment_id' => $request->input('id')
            ]);

            AuditLogs::create([
                'name' => User::where('id', $request->input('user_id'))->firstOrFail()->name,
                'slug' => 'file_upload',
                'type' => substr($request->input('id'), 0, 16) . ' : ' . $request->input('tag')
            ]);

            return response()->json([
                'objectId' => $request->input('id')
            ]);
        } else {
            return response()->json([
                'status' => 'failed'
            ]);
        }
    }
}
