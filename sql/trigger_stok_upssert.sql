-- =====================================================
-- Blok D: Fungsi + Trigger dengan UPSERT (membuat stok jika belum ada)
-- Gunakan file ini: sql/trigger_stok_upssert.sql
-- Paste/Run di Supabase SQL Editor
-- =====================================================

CREATE OR REPLACE FUNCTION public.update_stok_after_stok_in_insert()
RETURNS trigger
LANGUAGE plpgsql
AS $$
DECLARE
    v_current integer;
BEGIN
    -- Coba UPDATE; jika tidak ada row yang diupdate, lakukan INSERT
    UPDATE stok
    SET jumlah_stok = COALESCE(jumlah_stok, 0) + NEW.jumlah_masuk,
        status_stok = CASE
            WHEN COALESCE(jumlah_stok, 0) + NEW.jumlah_masuk = 0 THEN 'habis'
            ELSE 'tersedia'
        END,
        updated_at = now()
    WHERE barang_id = NEW.barang_id;

    IF NOT FOUND THEN
        INSERT INTO stok (barang_id, jumlah_stok, status_stok, created_at, updated_at)
        VALUES (NEW.barang_id, COALESCE(NEW.jumlah_masuk,0), CASE WHEN COALESCE(NEW.jumlah_masuk,0)=0 THEN 'habis' ELSE 'tersedia' END, now(), now());
    END IF;

    -- Update harga bila diperlukan
    IF NEW.harga_satuan IS NOT NULL AND NEW.harga_satuan > 0 THEN
        UPDATE harga
        SET harga_beli = NEW.harga_satuan,
            updated_at = now()
        WHERE barang_id = NEW.barang_id
          AND status = 'aktif';
    END IF;

    RETURN NULL;
END;
$$;

CREATE TRIGGER update_stok_after_stok_in_insert
AFTER INSERT ON stok_in
FOR EACH ROW
EXECUTE FUNCTION public.update_stok_after_stok_in_insert();

-- =====================================================
-- (Opsional) Rollback:
-- DROP TRIGGER IF EXISTS update_stok_after_stok_in_insert ON stok_in;
-- DROP FUNCTION IF EXISTS public.update_stok_after_stok_in_insert();
-- =====================================================
