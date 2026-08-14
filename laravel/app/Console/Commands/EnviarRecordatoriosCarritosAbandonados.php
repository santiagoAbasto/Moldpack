<?php

namespace App\Console\Commands;

use App\Mail\CarritoAbandonadoReminder;
use App\Models\CarritoAbandonado;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarRecordatoriosCarritosAbandonados extends Command
{
    protected $signature = 'moldpack:carritos-abandonados {--hours=24} {--dry-run}';

    protected $description = 'Envia recordatorios de carritos abandonados sin duplicar correos.';

    public function handle()
    {
        $hours = max(1, (int) $this->option('hours'));
        $limit = now()->subHours($hours);

        $carritos = CarritoAbandonado::with('cliente')
            ->whereNull('completed_at')
            ->whereNull('reminder_sent_at')
            ->where('items_count', '>', 0)
            ->where('last_activity_at', '<=', $limit)
            ->orderBy('last_activity_at')
            ->get();

        $this->info('Carritos pendientes encontrados: '.$carritos->count());

        foreach ($carritos as $carrito) {
            $email = $carrito->email ?: optional($carrito->cliente)->email;

            if (!$email) {
                $this->warn('Carrito '.$carrito->id.' sin email. Se omite.');
                continue;
            }

            if ($this->option('dry-run')) {
                $this->line('DRY RUN: enviaria recordatorio a '.$email.' para carrito '.$carrito->id);
                continue;
            }

            try {
                Mail::to($email)->send(new CarritoAbandonadoReminder($carrito));
                $carrito->forceFill(['reminder_sent_at' => now()])->save();
                $this->line('Recordatorio enviado a '.$email.' para carrito '.$carrito->id);
            } catch (\Throwable $exception) {
                Log::error('Error enviando carrito abandonado '.$carrito->id.': '.$exception->getMessage());
                $this->error('Error enviando carrito '.$carrito->id.': '.$exception->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
