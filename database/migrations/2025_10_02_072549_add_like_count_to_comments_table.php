<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedInteger('like_count')->default(0)->after('comment');
        });

        // Initialize like_count based on existing comment_likes
        \App\Models\Comment::with('likes')->get()->each(function ($comment) {
            $comment->like_count = $comment->likes()->count();
            $comment->save();
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('like_count');
        });
    }
};
