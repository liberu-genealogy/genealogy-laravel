<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The notifications table was created in 2017 with an auto-incrementing integer
 * primary key, but Laravel's database notification channel writes a UUID string.
 * Every insert has been rejected ever since, so no in-app notification has ever
 * been stored.
 *
 * Note what that means operationally: every notification class here implements
 * ShouldQueue, and Laravel dispatches one job per channel. The database write
 * therefore failed *inside its own queue job*, which was retried and ultimately
 * failed. Nothing was silently discarded — since 2017 each in-app notification
 * has become a failed job. Installs that keep a failed_jobs backlog may have a
 * large one, and it should be reviewed and purged separately from this change.
 * The mail channel is a separate job and was never affected.
 *
 * The original migration cannot simply be corrected: it has already run on live
 * installs, and editing it changes nothing that exists.
 *
 * Because no row could ever be written, the table is necessarily empty, and
 * recreating it is both safe and simpler than altering a primary key's type
 * across MariaDB and SQLite. That assumption is asserted rather than trusted —
 * if rows are somehow present, this migration stops instead of destroying them.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('notifications')) {
            $this->createTable();

            return;
        }

        if ($this->alreadyUsesStringKey()) {
            return;
        }

        $existing = DB::table('notifications')->count();

        if ($existing > 0) {
            throw new RuntimeException(
                "The notifications table holds {$existing} row(s), which should be impossible: "
                .'its integer primary key rejects the UUIDs Laravel writes. Inspect them before '
                .'rerunning this migration — it recreates the table and they would be lost.'
            );
        }

        Schema::drop('notifications');
        $this->createTable();
    }

    /**
     * Restores the original integer key. Unlike up(), this direction destroys
     * rows that genuinely can exist — once this migration has run, real
     * notifications accumulate, and no integer-keyed table can hold them. So
     * the guard matters more here than it does going forward: refuse rather
     * than silently discard a user's notification history during a rollback
     * triggered by some unrelated migration.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            $existing = DB::table('notifications')->count();

            if ($existing > 0) {
                throw new RuntimeException(
                    "Refusing to roll back: the notifications table holds {$existing} row(s), "
                    .'and the integer primary key this restores cannot store them. Export or '
                    .'delete them first if you genuinely intend to lose them.'
                );
            }

            Schema::drop('notifications');
        }

        Schema::create('notifications', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    private function createTable(): void
    {
        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Guards against rerunning on an install that has already been corrected,
     * and against a fresh install where a future consolidated schema already
     * declares the column correctly.
     */
    private function alreadyUsesStringKey(): bool
    {
        return ! in_array(
            Schema::getColumnType('notifications', 'id'),
            ['integer', 'bigint', 'int', 'int8'],
            true,
        );
    }
};
