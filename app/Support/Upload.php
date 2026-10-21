<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

// Every upload goes through here: one set of rules, and a generated file name so two uploads
// called "photo.jpg" never overwrite each other. Files live under public/ (images/…, documents/…);
// the .htaccess in those folders stops anything uploaded from running as a script.
class Upload
{
    // validation rules (max is in kilobytes, about 11 MB, matching the PHP upload limits).
    public const IMAGE = 'image|mimes:png,jpg,jpeg|max:11000';
    public const DOCUMENT = 'mimes:csv,pdf,doc,docx,xls,xlsx|max:11000';

    // Moves the file into public/$dir and returns the stored name for the database.
    public static function store(UploadedFile $file, string $dir): string
    {
        $name = $file->hashName();
        $file->move(public_path($dir), $name);

        return $name;
    }
}
