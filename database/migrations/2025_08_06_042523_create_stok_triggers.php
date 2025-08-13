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
        // Create trigger for stok_in - increases stock quantity and updates harga_beli
        DB::unprepared('
            CREATE TRIGGER update_stok_after_stok_in_insert
            AFTER INSERT ON stok_in
            FOR EACH ROW
            BEGIN
                -- Update stock quantity
                UPDATE stok 
                SET jumlah_stok = jumlah_stok + NEW.jumlah_masuk,
                    status_stok = CASE 
                        WHEN jumlah_stok + NEW.jumlah_masuk = 0 THEN "habis"
                        ELSE "tersedia"
                    END,
                    updated_at = NOW()
                WHERE barang_id = NEW.barang_id;
                
                -- Update harga_beli if harga_satuan is provided
                IF NEW.harga_satuan IS NOT NULL AND NEW.harga_satuan > 0 THEN
                    UPDATE harga 
                    SET harga_beli = NEW.harga_satuan,
                        updated_at = NOW()
                    WHERE barang_id = NEW.barang_id AND status = "aktif";
                END IF;
            END
        ');

        // Create trigger for stok_out - decreases stock quantity and updates harga_jual
        DB::unprepared('
            CREATE TRIGGER update_stok_after_stok_out_insert
            AFTER INSERT ON stok_out
            FOR EACH ROW
            BEGIN
                DECLARE current_stok INT DEFAULT 0;
                DECLARE min_stok INT DEFAULT 0;
                
                -- Get current stock
                SELECT jumlah_stok, stok_minimum INTO current_stok, min_stok
                FROM stok WHERE barang_id = NEW.barang_id;
                
                -- Check if enough stock available
                IF current_stok >= NEW.jumlah_keluar THEN
                    -- Update stock quantity
                    UPDATE stok 
                    SET jumlah_stok = jumlah_stok - NEW.jumlah_keluar,
                        status_stok = CASE 
                            WHEN jumlah_stok - NEW.jumlah_keluar = 0 THEN "habis"
                            ELSE "tersedia"
                        END,
                        updated_at = NOW()
                    WHERE barang_id = NEW.barang_id;
                    
                    -- Update harga_jual if harga_satuan is provided
                    IF NEW.harga_satuan IS NOT NULL AND NEW.harga_satuan > 0 THEN
                        UPDATE harga 
                        SET harga_jual = NEW.harga_satuan,
                            updated_at = NOW()
                        WHERE barang_id = NEW.barang_id AND status = "aktif";
                    END IF;
                ELSE
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "Stok tidak mencukupi untuk pengeluaran barang";
                END IF;
            END
        ');

        // Create trigger for produk - automatically create stock record when product is added
        DB::unprepared('
            CREATE TRIGGER create_stok_after_produk_insert
            AFTER INSERT ON produk
            FOR EACH ROW
            BEGIN
                INSERT INTO stok (
                    barang_id, 
                    jumlah_stok, 
                    stok_minimum, 
                    stok_maksimum, 
                    status_stok, 
                    created_at, 
                    updated_at
                ) VALUES (
                    NEW.barang_id, 
                    0, 
                    5, 
                    100, 
                    "habis", 
                    NOW(), 
                    NOW()
                );
            END
        ');

        DB::unprepared('
            CREATE TRIGGER create_harga_after_produk_insert
            AFTER INSERT ON produk
            FOR EACH ROW
            BEGIN
                INSERT INTO harga (
                    barang_id, 
                    harga_beli, 
                    harga_jual, 
                    status, 
                    created_at, 
                    updated_at
                ) VALUES (
                    NEW.barang_id, 
                    0, 
                    0,
                    "aktif",
                    NOW(), 
                    NOW()
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_stok_after_stok_in_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS update_stok_after_stok_out_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS create_stok_after_produk_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS create_harga_after_produk_insert');
    }
};
