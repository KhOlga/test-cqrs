<?php

namespace App\Http\Controllers\Commands;

use App\Commands\SavePayloadToFile;
use App\Http\Controllers\Controller;
use ArtisanSdk\CQRS\Builder;
use ArtisanSdk\CQRS\Concerns\CQRS;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendMoney extends Controller
{
	use CQRS;

	public function __invoke(Request $request)
	{
		try {
			(new Builder(new SavePayloadToFile()))
				->id(Str::uuid())
				->from_email($request->input('from_email'))
				->to_email($request->input('to_email'))
				->currency($request->input('currency'))
				->amount($request->input('amount'))
				->created_at(now())
				->run();

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
