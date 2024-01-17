<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Orchid\Platform\Events\UploadedFileEvent;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Toast;
use Orchid\Attachment\Models\Attachment as OrchidAttachment;
use Illuminate\Support\Facades\Http;
use App\Models\PlatformAttachments;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class UploadListener 
{
    use InteractsWithQueue;
    
    private $res;

    /**
     * Handle the event.
     *
     * @param  UploadedFileEvent  $event
     * @return void
     */
    public function handle(UploadedFileEvent $event) {
        try {

            $client = new Client();
            $this->res = $client->request('POST', env('DASHBOARD_HOSTNAME', "") . '/autumn/attachments', [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-Client-Token' => ''. env('AUTUMN_SECRET', "0"),
                ],
                'multipart' => [
                    [
                        'name'     => $event->attachment->name,
                        'filename' => $event->attachment->original_name,
                        'contents' => \GuzzleHttp\Psr7\Utils::streamFor($event->attachment->path, 'r'),
                    ]
                ],
            ]);
    
            $response = @json_decode($this->res->getBody()->getContents(), true);

            if ($response->id === NULL) {
                Toast::error('Cannot upload ' . $event->attachment->name . ' because of a unknown error.')->delay(2000);
                return;
            }
    
            PlatformAttachments::create([
                'user_id' => $event->attachment->user_id,
                'action_id' => '',
                'bucket' => $event->bucket,
                'attachment_id' =>  $response->id
            ]);

            Toast::success('File ' . $event->attachment->name . ' uploaded with the ID ' . $response->id)->delay(2000);

        } catch (\Exception $e) {
            $response = $this->res->getResponse();
            $body = @json_encode($response->getBody()->getContents(), true);

            if ($body->type == 'Malware') {
                Toast::error('Upload failed, ' . $event->attachment->name . ' because a malware was found.')->delay(2000);
            } else if ($body->type == 'FileTypeNotAllowed') {
                Toast::error('Upload failed, ' . $event->attachment->name . ' because the type is not accepted.')->delay(2000);
            } else if ($body->type == 'UnknownTag') {
                Toast::error('Upload failed, ' . $event->attachment->name . ' because tag is not found.')->delay(2000);
            } else if ($body->type == 'S3Error') {
                Toast::error('Upload failed, ' . $event->attachment->name . ' because S3 server is offline.')->delay(2000);
            } else if ($body->type == 'LabelMe') {
                Toast::warning('Upload failed, ' . $event->attachment->name . ' please label the file.')->delay(2000);
            }  else {
                Toast::error('Upload failed, ' . $event->attachment->name . $body->type)->delay(2000);
            } 
        }
    }
}
