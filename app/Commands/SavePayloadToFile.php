<?php

namespace App\Commands;

use ArtisanSdk\CQRS\Command;
use Illuminate\Support\Facades\Log;

class SavePayloadToFile extends Command
{
	public function run()
	{
		$payload = [];

		$payload['id'] = $this->argument('id');
		$payload['from_email'] = $this->argument('from_email');
		$payload['to_email'] = $this->argument('to_email');
		$payload['currency'] = $this->argument('currency');
		$payload['amount'] = $this->argument('amount');
		$payload['created_at'] = $this->argument('created_at');

		$json = json_encode($payload);
		$file = storage_path('app/txn-list.json');

		file_put_contents($file, $json, FILE_APPEND | LOCK_EX);
		file_put_contents($file, "\n", FILE_APPEND | LOCK_EX);

		Log::info('Created payload ID: ' . $payload['id']);

		return $payload;
	}
}
