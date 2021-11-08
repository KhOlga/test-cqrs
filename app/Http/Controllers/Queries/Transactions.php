<?php

namespace App\Http\Controllers\Queries;

use App\Http\Controllers\Controller;
use App\Queries\TransactionsList;
use Error;
use Exception;
use Illuminate\Support\Facades\Log;

class Transactions extends Controller
{
	public function __invoke()
	{
		try {
			return TransactionsList::get();

		} catch (Exception $exception) {
			Log::error($exception);
			abort(400, 'Something went wrong. Please try again later');

			//It is possible to dispatch event here or for example store the info about error to database
			// or send notification to the slack etc.

		} catch (Error $error) {
			Log::error($error);
			abort(400, 'Something went wrong. Please try again later');

			//It is possible to dispatch event here or for example store the info about error to database
			// or send notification to the slack etc.
		}
	}
}
