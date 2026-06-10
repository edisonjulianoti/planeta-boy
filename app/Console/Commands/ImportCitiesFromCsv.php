<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportCitiesFromCsv extends Command
{
    protected $signature = 'cities:import-from-csv {file : Caminho do arquivo CSV}';
    protected $description = 'Importar cidades do CSV (5572 cidades) em lotes';

    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("Arquivo não encontrado: {$filePath}");
            return Command::FAILURE;
        }

        $this->info("Lendo arquivo: {$filePath}");

        $content = file_get_contents($filePath);
        if ($content === false) {
            $this->error('Erro ao ler o arquivo.');
            return Command::FAILURE;
        }

        // Convert to UTF-8
        $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        $lines = explode("\n", str_replace("\r\n", "\n", $content));
        array_shift($lines); // Remove header

        $this->info('Importando cidades em lotes...');

        // Load existing cities keyed by name|state
        $existing = City::all()->keyBy(fn($c) => mb_strtolower($c->name) . '|' . $c->state);
        $existingSlugs = $existing->pluck('slug')->toArray();

        $batch = [];
        $skipped = 0;
        $total = 0;
        $inserted = 0;
        $updated = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $fields = str_getcsv($line, ';');
            if (count($fields) < 5) continue;

            $ibgeName = trim($fields[3]);
            $state = strtoupper(trim($fields[4]));

            if (empty($ibgeName) || $state === 'EX') {
                $skipped++;
                continue;
            }

            $total++;
            $key = mb_strtolower($ibgeName) . '|' . $state;

            // Always use state in slug to avoid cross-state conflicts
            $slug = Str::slug($ibgeName) . '-' . strtolower($state);

            // Ensure slug is unique (append counter if needed)
            $baseSlug = $slug;
            $counter = 1;
            while (in_array($slug, $existingSlugs)) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
                if ($counter > 10) {
                    $slug = $baseSlug . '-' . uniqid();
                    break;
                }
            }

            if (isset($existing[$key])) {
                // Update existing if slug changed
                $city = $existing[$key];
                if ($city->slug !== $slug) {
                    $city->slug = $slug;
                    $city->save();
                    $updated++;
                }
            } else {
                $now = now();
                $batch[] = [
                    'name' => $ibgeName,
                    'state' => $state,
                    'slug' => $slug,
                    'active' => true,
                    'order' => 0,
                    'featured' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $existingSlugs[] = $slug;

                // Insert in batches of 200
                if (count($batch) >= 200) {
                    $inserted += $this->insertBatch($batch);
                    $batch = [];
                    $this->info("  Progresso: {$total} processadas, {$inserted} inseridas...");
                }
            }
        }

        // Insert remaining
        if (!empty($batch)) {
            $inserted += $this->insertBatch($batch);
        }

        $nowCount = City::count();

        $this->info('');
        $this->info('═══════ Importação concluída! ═══════');
        $this->table(
            ['Status', 'Quantidade'],
            [
                ['Inseridas (novas cidades)', $inserted],
                ['Atualizadas (slug)', $updated],
                ['Puladas (exterior/vazias)', $skipped],
                ['Total no banco agora', $nowCount],
                ['Total processado do CSV', $total],
            ]
        );

        return Command::SUCCESS;
    }

    private function insertBatch(array $batch): int
    {
        try {
            DB::table('cities')->insert($batch);
            return count($batch);
        } catch (\Exception $e) {
            $this->warn('  Erro no lote, tentando inserir individualmente: ' . $e->getMessage());
            $count = 0;
            foreach ($batch as $row) {
                try {
                    DB::table('cities')->insert($row);
                    $count++;
                } catch (\Exception $e2) {
                    $this->warn("    Falha ao inserir {$row['name']}/{$row['state']}: {$e2->getMessage()}");
                }
            }
            return $count;
        }
    }
}
