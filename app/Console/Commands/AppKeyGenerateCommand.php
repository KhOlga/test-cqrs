<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Encryption\Encrypter;

class AppKeyGenerateCommand extends Command {
    use ConfirmableTrait;

    protected $signature = 'app:key-generate';

    protected $description = 'Set the application key, if it doesn\'t exist';

    public function handle(): void {
        if (env('APP_KEY')) {
            return;
        }

        $key = $this->generateRandomKey();

        if (! $this->setKeyInEnvironmentFile($key)) {
            return;
        }

        $this->laravel['config']['app.key'] = $key;

        $this->info('Application key set successfully.');
    }

    protected function generateRandomKey(): string {
        return 'base64:'.base64_encode(
            Encrypter::generateKey($this->laravel['config']['app.cipher'])
        );
    }

    protected function setKeyInEnvironmentFile($key): bool {
        $currentKey = $this->laravel['config']['app.key'];

        if (strlen($currentKey) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($key);

        return true;
    }

    protected function writeNewEnvironmentFileWith($key): void {
        /** @var mixed $laravel */
        $laravel = $this->laravel;
        file_put_contents($laravel->environmentFilePath(), preg_replace(
            $this->keyReplacementPattern(),
            'APP_KEY='.$key,
            file_get_contents($laravel->environmentFilePath())
        ));
    }

    protected function keyReplacementPattern(): string {
        /** @var mixed $laravel */
        $laravel = $this->laravel;
        $escaped = preg_quote('='.$laravel['config']['app.key'], '/');

        return "/^APP_KEY{$escaped}/m";
    }
}
