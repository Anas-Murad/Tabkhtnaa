<?php

namespace Database\Seeders;

use App\Models\Translate;
use Illuminate\Database\Seeder;

class TranslateSeeder extends Seeder
{
  /**
   * Seed app UI translations (ar / en / fr).
   */
  public function run(): void
  {
    $rows = require database_path('data/app_translations.php');

    foreach ($rows as $row) {
      Translate::updateOrCreate(
        ['key' => $row['key'], 'model' => $row['model'] ?? 'app'],
        [
          'ar' => $row['ar'] ?? null,
          'en' => $row['en'] ?? null,
          'fr' => $row['fr'] ?? null,
          'tr' => $row['tr'] ?? null,
        ]
      );
    }
  }
}
