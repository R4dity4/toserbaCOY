<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePostgresStokTriggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(<<<'SQL'
-- Function to update stok and harga after stok_in insert
CREATE OR REPLACE FUNCTION public.update_stok_after_stok_in_insert()
RETURNS trigger
LANGUAGE plpgsql
AS $$
BEGIN
    -- Update stock quantity
    UPDATE stok
    SET jumlah_stok = jumlah_stok + NEW.jumlah_masuk,
        status_stok = CASE
            WHEN jumlah_stok + NEW.jumlah_masuk = 0 THEN 'habis'
            ELSE 'tersedia'
        END,
        updated_at = now()
    WHERE barang_id = NEW.barang_id;

    -- Update harga_beli if harga_satuan is provided
    IF NEW.harga_satuan IS NOT NULL AND NEW.harga_satuan > 0 THEN
        UPDATE harga
        SET harga_beli = NEW.harga_satuan,
            updated_at = now()
        WHERE barang_id = NEW.barang_id AND status = 'aktif';
    END IF;

    RETURN NULL; -- after trigger
END;
$$;

-- Trigger that calls the function after insert on stok_in
CREATE TRIGGER update_stok_after_stok_in_insert
AFTER INSERT ON stok_in
FOR EACH ROW
EXECUTE FUNCTION public.update_stok_after_stok_in_insert();
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_stok_after_stok_in_insert ON stok_in;');
        DB::unprepared('DROP FUNCTION IF EXISTS public.update_stok_after_stok_in_insert();');
    }
}
