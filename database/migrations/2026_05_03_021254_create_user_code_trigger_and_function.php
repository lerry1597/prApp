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
        // 1. Buat Sequence jika belum ada
        DB::unprepared("CREATE SEQUENCE IF NOT EXISTS user_code_seq START 1;");

        // 2. Buat Function untuk Generate Code
        DB::unprepared("
            CREATE OR REPLACE FUNCTION generate_user_code()
            RETURNS CHAR(12) AS $$
            DECLARE
                seq BIGINT;
                prefix INT;
            BEGIN
                -- ambil angka unik dari sequence
                seq := nextval('user_code_seq');

                -- buat random 3 digit (100–999)
                prefix := floor(random() * 900 + 100);

                -- gabungkan: YYMMDD + random + sequence
                RETURN to_char(current_date, 'YYMMDD') ||
                       LPAD(prefix::text, 3, '0') ||
                       LPAD(seq::text, 3, '0');
            END;
            $$ LANGUAGE plpgsql;
        ");

        // 3. Buat Function Trigger
        DB::unprepared("
            CREATE OR REPLACE FUNCTION set_user_code()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Hanya generate jika user_code kosong
                IF NEW.user_code IS NULL THEN
                    NEW.user_code := generate_user_code();
                END IF;
                RETURN NEW;
            END;
            $$ language plpgsql;
        ");

        // 4. Buat Trigger pada tabel users
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_set_user_code ON users;
            CREATE TRIGGER trg_set_user_code
            BEFORE INSERT ON users
            FOR EACH ROW
            EXECUTE FUNCTION set_user_code();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_set_user_code ON users;");
        DB::unprepared("DROP FUNCTION IF EXISTS set_user_code();");
        DB::unprepared("DROP FUNCTION IF EXISTS generate_user_code();");
        DB::unprepared("DROP SEQUENCE IF EXISTS user_code_seq;");
    }
};
