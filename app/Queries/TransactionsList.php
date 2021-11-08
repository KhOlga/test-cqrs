<?php

namespace App\Queries;

class TransactionsList
{
	public static function get()
	{
		$string = file_get_contents(storage_path('app/txn-list.json'));

		foreach (explode("\n", $string) as $item) {
			if ($item) {
				$array[] = json_decode($item, true, 512, JSON_OBJECT_AS_ARRAY);
			}
		}

		return response()->json(['txns' => $array]);
	}
}
