<?php

namespace App\Commands;

use App\Models\Payload;
use ArtisanSdk\CQRS\Command;

class SavePayload extends Command
{
	protected $model;

	/**
	 * @param $model
	 */
	public function __construct(Payload $model)
	{
		$this->model = $model;
	}

	public function run()
	{
		$payload = $this->payload;

		$payload->id = $this->argument('id', ['uuid']);
		$payload->from_email = $this->argument('from_email', ['email']);
		$payload->to_email = $this->argument('to_email', ['email']);
		$payload->currency = $this->argument('currency');
		$payload->amount = $this->argument('amount', ['integer', 'min:0']);
		$payload->created_at = $this->argument('created_at');

		$payload->save();

		return $payload;
	}
}
