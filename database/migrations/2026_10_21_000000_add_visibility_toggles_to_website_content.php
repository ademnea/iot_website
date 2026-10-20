<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Lets admins hide a social link or contact detail without deleting what they typed.
// Everything starts visible, so the site looks the same right after this migration.
return new class extends Migration
{
    public const TOGGLES = [
        'twitter_visible', 'facebook_visible', 'instagram_visible', 'youtube_visible', 'linkedin_visible',
        'address_visible', 'email_visible', 'phone_visible',
    ];

    public function up(): void
    {
        Schema::table('website_content', function (Blueprint $table) {
            foreach (self::TOGGLES as $column) {
                $table->boolean($column)->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_content', function (Blueprint $table) {
            $table->dropColumn(self::TOGGLES);
        });
    }
};
