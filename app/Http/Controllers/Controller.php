<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // The record an edit page opens, from ?id= in its link; 404 when there's no such record.
    // (The edit links used to carry every field in the query string, which broke whenever a
    // value contained & or #.)
    protected function findForEdit(string $table, Request $request): object
    {
        $request->validate(['id' => 'required|integer']);
        $record = DB::table($table)->where('id', $request->query('id'))->first();
        abort_if(! $record, 404);

        return $record;
    }

    // date inputs need Y-m-d; the database stores a datetime.
    protected function dateInput(?string $value): string
    {
        return $value ? substr($value, 0, 10) : '';
    }
}
