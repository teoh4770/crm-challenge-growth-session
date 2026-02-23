<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();

            // Storing uploaded files using the original file name can be a significant issue regarding security, especially if you are not validating strongly enough.

            // 'name': media will require a name so that we can pull the client's original name from the upload
            $table->string('name');
            // 'file_name': file name, which will be a generated name
            $table->string('file_name');
            // 'mime_type': the type of the file, either CSV or an image
            $table->string('mime_type');
            // 'path': allow us to reference the file easier
            $table->string('path');
            $table->string('disk')->default('local');
            // 'file_hash': ensure we don't upload the same file more than once
            $table->string('file_hash', 64)->unique();

            // 'collection': allow saving a file to a collection like 'blog posts'
            $table->string('collection')->nullable();
            // 'size': ensure digital assets aren't too large
            $table->unsignedBigInteger('size');

            $table->integer('mediable_id')->nullable();
            $table->string('mediable_type')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
