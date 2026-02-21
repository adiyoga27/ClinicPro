<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = new \App\Services\SatuSehatService();
$result = $service->getPatientsByOrganization();
if ($result['success']) {
    $entries = $result['data']['entry'] ?? [];
    echo "Found " . count($entries) . " entries\n";
    foreach ($entries as $entry) {
        $r = $entry['resource'] ?? [];
        $ids = $r['identifier'] ?? [];
        $nik = '';
        foreach ($ids as $id) {
            if (isset($id['system']) && str_contains($id['system'], 'nik')) {
                $nik = $id['value'] ?? '';
            }
        }
        echo "Name: " . ($r['name'][0]['text'] ?? 'Unknown') . " NIK: " . ($nik ?: 'NULL') . "\n";
    }
} else {
    echo "Failed: " . json_encode($result);
}
