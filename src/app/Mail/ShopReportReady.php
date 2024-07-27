<?php

namespace App\Mail;

use App\Models\Shop\Accounting\AccountingDocument;
use App\Models\Shop\Internal\ShopReports;
use App\Models\SocialMedia;
use App\Models\TransactionsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

define('BINARY_UNITSS', array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'));
define('METRIC_UNITSS', array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'));

/**
 * Class ShopReportReady
 *
 * Represents a shop report ready email.
 */
class ShopReportReady extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * An array of report class names.
     *
     * @var array
     */
    protected array $reportClasses = [
        ShopReports::class,
        AccountingDocument::class,
        TransactionsReport::class,
    ];

    /**
     * Constructs a new instance of the class.
     *
     * @return void
     */
    public function __construct() { }

    /**
     * Creates and returns a new Envelope object.
     *
     * @return Envelope The newly created Envelope object.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Monthly Reports',
        );
    }

    /**
     * Retrieves the content for an email.
     *
     * @return Content The content of the email.
     */
    public function content(): Content {
        $files = $this->getFilesDataForReports($this->reportClasses);

        $formattedArray = array();
        foreach ($files as $file) {
            $formattedArray[] = [
                'name' => $file['name'],
                'size' => $file['size']
            ];
        }

        return new Content(
            view: 'emails.admin.shop_report',
            with: [
                'files' => $formattedArray,
                'socials' => SocialMedia::all()
            ]
        );
    }

    /**
     * Retrieves the file data for the given report classes.
     *
     * @param array $reportClasses An array of report classes.
     *
     * @return array The file data for the reports.
     */
    private function getFilesDataForReports(array $reportClasses): array
    {
        $filesData = [];
        foreach ($reportClasses as $reportClass) {
            $filesData[] = $this->getFileData($reportClass::latest()->first());
        }

        return $filesData;
    }

    /**
     * Retrieves the file data for the given model.
     *
     * @param mixed $model The model for which to retrieve the file data.
     * @return array The file data containing the file name and size.
     */
    private function getFileData($model): array
    {
        if (!isset($model->attachment_id)) {
            return $this->getFileDataNotFound();
        }

        $storage = Storage::disk('public');
        if (!$storage->exists($model->attachment_id)) {
            return $this->getFileDataNotFound();
        }

        return [
            'name' => $model->attachment_id,
            'size' => $this->human_readable_bytes($storage->size($model->attachment_id), 2, 'metric')
        ];
    }

    /**
     * Returns an array containing file data when the file is not found.
     *
     * @return array The file data not found, consisting of the following keys:
     *     - 'name': The name of the file, set to 'Not yet available'.
     *     - 'size': The size of the file, set to a human-readable format of 0 bytes using the 'metric' system of measurements.
     */
    private function getFileDataNotFound(): array
    {
        return [
            'name' => 'Not yet available',
            'size' => $this->human_readable_bytes(0, 2, 'metric')
        ];
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $files = $this->getFilesDataForReports($this->reportClasses);
        $formattedArray = array();

        foreach ($files as $file) {
            if (intval($file['size']) <= 0)
                continue;

            $formattedArray[] = [
                'path' => public_path($file['name']),
                'name' => $file['name'],
                'mime' =>  'application/pdf'
            ];
        }

        return [...$formattedArray];
    }

    /**
     * Converts bytes into a human-readable string representation.
     *
     * @param int $bytes The number of bytes.
     * @param int $decimals The number of decimal places to round the result to. Default is 2.
     * @param string $system The unit system to use. Can be either 'binary' or 'metric'. Default is 'binary'.
     * @return string A human-readable string representation of the bytes.
     */

    function human_readable_bytes(int $bytes, int $decimals = 2, string $system = 'binary'): string
    {
        $mod = ($system === 'binary') ? 1024 : 1000;
        $units = array('binary' => BINARY_UNITSS, 'metric' => METRIC_UNITSS);
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f%s", $bytes / pow($mod, $factor), $units[$system][$factor]);
    }
}
