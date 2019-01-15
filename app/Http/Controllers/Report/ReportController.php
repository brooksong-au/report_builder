<?php
declare(strict_types=1);
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TxTextControl\ReportingCloud\ReportingCloud;
use TxTextControl\ReportingCloud\Stdlib\ConsoleUtils;

define('REPORTING_CLOUD_API_KEY', 'fAtWaj6fJUuSY2FD0UhA1U7P4aysAyDOR6FQM8KS0');

include_once base_path().DIRECTORY_SEPARATOR.'vendor/textcontrol/txtextcontrol-reportingcloud/demo'.'/bootstrap.php';

class ReportController extends Controller
{
    const REPORTING_CLOUD_DEMO_MEDIA_PATH = 'vendor/textcontrol/txtextcontrol-reportingcloud/resource';
    public function runAll()
    {

        $getFileInfos = function (): array {

            $ret = [];

            $di = new \DirectoryIterator(base_path().DIRECTORY_SEPARATOR.'vendor/textcontrol/txtextcontrol-reportingcloud/demo');
            foreach ($di as $fileInfo) {
                if (__FILE__ === $fileInfo->getPathname()) {
                    continue;
                }
                if ('bootstrap.php' === $fileInfo->getFilename()) {
                    continue;
                }
                if ('php' !== $fileInfo->getExtension()) {
                    continue;
                }
                $ret[] = clone $fileInfo;
            }

            return $ret;
        };


        $command = 'clear';
        passthru($command);

        $fileInfos = $getFileInfos();
        $count     = count($fileInfos);
        $counter   = 0;

        foreach ($fileInfos as $fileInfo) {

            $counter++;

            echo sprintf('%d/%d) Executing %s...', $counter, $count, $fileInfo->getFilename());
            echo PHP_EOL;
            echo PHP_EOL;

            $command = sprintf('%s %s', PHP_BINARY, $fileInfo->getPathname());
            passthru($command);

            echo PHP_EOL;
            echo '...DONE.';
            echo PHP_EOL;
            echo PHP_EOL;
            echo PHP_EOL;
        }
    }

    public function apiKeys()
    {
        $reportingCloud = new ReportingCloud([
            'api_key' => ConsoleUtils::apiKey(),
        ]);

        $apiKeys = $reportingCloud->getApiKeys();

        if (!empty($apiKeys)) {
            foreach ($apiKeys as $apiKey) {
                if ($apiKey['key'] == ConsoleUtils::apiKey()) {
                    echo sprintf("Keeping API key %s...\n", $apiKey['key']);
                    continue;
                }
                echo sprintf("Deleting API key %s...\n", $apiKey['key']);
                $reportingCloud->deleteApiKey($apiKey['key']);
                unset($apiKey);
            }
        }

        $newApiKey = $reportingCloud->createApiKey();

        unset($reportingCloud);

        $reportingCloud = new ReportingCloud([
            'api_key' => $newApiKey,
        ]);

        dump($reportingCloud->getAccountSettings());
        dump($reportingCloud->getTemplateList());

    }

    public function download(Request $request)
    {
        $destinationFilename = storage_path() . '/report/test_template_merged.pdf';

        $reportingCloud = new ReportingCloud([
            'api_key' => ConsoleUtils::apiKey(),
            'test' => false,
        ]);


        $mergeData = $request->input();

        $data = $reportingCloud->mergeDocument($mergeData, 'PDF', 'sample_invoice.tx');
        file_put_contents($destinationFilename, $data[0]);

        return response()->download($destinationFilename)->deleteFileAfterSend();
    }
}
