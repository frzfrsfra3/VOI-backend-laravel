<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 2025_11_18_000040_create_contact_messages_table.php
return new class extends Migration {
    public function up() {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'reviewed'])->default('new');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('contact_messages'); }
};

