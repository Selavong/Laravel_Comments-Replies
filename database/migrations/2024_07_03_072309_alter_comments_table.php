<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create a new table with the desired schema
        Schema::create('new_comments', function (Blueprint $table) {
            $table->id();
            $table->string("content", 4000);
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("movie_id");
            $table->unsignedBigInteger("parent_id")->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        });

        // Copy data from the old table to the new table
        DB::statement('INSERT INTO new_comments (id, content, user_id, movie_id, parent_id, created_at, updated_at) SELECT id, content, user_id, movie_id, NULL, created_at, updated_at FROM comments');

        // Drop the old table
        Schema::drop('comments');

        // Rename the new table to the old table name
        Schema::rename('new_comments', 'comments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Create the old table schema
        Schema::create('old_comments', function (Blueprint $table) {
            $table->id();
            $table->string("content", 4000);
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("movie_id");
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });

        // Copy data from the new table to the old table
        DB::statement('INSERT INTO old_comments (id, content, user_id, movie_id, created_at, updated_at) SELECT id, content, user_id, movie_id, created_at, updated_at FROM comments');

        // Drop the new table
        Schema::drop('comments');

        // Rename the old table back to the original name
        Schema::rename('old_comments', 'comments');
    }
};
