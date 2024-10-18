<?php
// error_reporting(0);
include_once('connection.php');

class Db_Class
{

    // DASHBOARD INFO
    function getTotalOrderPickup()
    {
        $sql = "SELECT COUNT (id) FROM trx_order_pickup";
        return pg_query($sql);
    }

    function getTotalDeliverySheet()
    {
        $sql = "SELECT COUNT (id) FROM trx_order_pickup";
        return pg_query($sql);
    }

    function getTotalOrderSttb()
    {
        $sql = "SELECT COUNT (id) FROM trx_order_sttb_d";
        return pg_query($sql);
    }

    function getTotalArmada()
    {
        $sql = "SELECT COUNT (id) FROM ms_armadas";
        return pg_query($sql);
    }
    // END DASHBOARD INFO


    function getLogin()
    {
        $sql = "select * from public.users where email='" . $this->cleanData($_POST['email']) . "' and password='" . $this->cleanData(md5($_POST['password'])) . "'";
        return pg_query($sql);
    }

    function cleanData($val)
    {
        return pg_escape_string($val);
    }

    function cekLogin($userLogin)
    {
        $userLogin;
        $user = pg_query("SELECT karyawan_id FROM users WHERE id = '" . $this->cleanData($userLogin) . "'");
        // var_dump($user);

        // $hasil = pg_query($user);


        while ($sqlfetch =  pg_fetch_assoc($user)) {
            $idKaryawan = $sqlfetch['karyawan_id'];
        }

        return $idKaryawan;
    }

    // MASTER RAYON
    function getRayon()
    {
        $sql = "SELECT a.id, a.nama_rayon, a.status_record, a.created_at,
        b.id AS id_rayon_d, b.id_rayon, b.id_provinsi, b.id_kota, b.keterangan, b.status_record,
        c.id, c.nama_provinsi,
        d.id, d.nama_kota_kab
        FROM public.ms_rayons_d b 
        LEFT JOIN public.ms_rayons a ON a.id = b.id_rayon
        LEFT JOIN public.ms_provinsis c ON c.id = b.id_provinsi
        LEFT JOIN public.ms_kota_kabs d ON d.id = b.id_kota";
        return pg_query($sql);
    }

    function editRayon($idRayon)
    {
        $sql = "SELECT mrd.id_rayon,mr.nama_rayon,mrd.id_provinsi,mp.nama_provinsi,mrd.id_kota,mk.nama_kota_kab FROM ms_rayons_d mrd 
        LEFT JOIN ms_rayons mr ON mrd.id_rayon = mr.id
        LEFT JOIN ms_provinsis mp ON mrd.id_provinsi = mp.id
        LEFT JOIN ms_kota_kabs mk ON mrd.id_kota = mk.id
        WHERE mrd.id = '" . $idRayon . "'";

        return pg_query($sql);
    }
    // END MASTER RAYON

    // LOADING
    function getLoading($cabang_id)
    {
        $sql = "SELECT a.*, c.nama_routes, b.no_polisi, b.keterangan
        FROM trx_loading a 
        LEFT JOIN trx_delivery_sheet b ON b.no_delivery = a.no_order_delivery
        LEFT JOIN ms_routes c ON c.id = b.route_id
        WHERE a.cabang_id = '$cabang_id' AND a.status_selesai = 'N' 
        ORDER BY a.tanggal_loading DESC LIMIT 50";
        return pg_query($sql);
    }

    function getTaskLoading($cabang_id, $karyawan_id)
    {
        $sql = "SELECT a.*, b.*, d.nama_routes, c.keterangan, c.no_polisi FROM trx_loading a 
        LEFT JOIN trx_loading_d2 b ON b.no_loading = a.no_loading 
        LEFT JOIN trx_delivery_sheet c ON c.no_delivery = a.no_order_delivery 
        LEFT JOIN ms_routes d ON d.id = c.route_id
        WHERE b.id_karyawan = '$karyawan_id' AND a.status_selesai = 'N' ORDER BY a.tanggal_loading DESC LIMIT 50";
        return pg_query($sql);
    }

    function getLoadingD2($cabang_id, $noloading)
    {
        $sql = "SELECT * FROM trx_loading_d2 a LEFT JOIN users b ON b.karyawan_id = a.id_karyawan WHERE a.no_loading = '$noloading'";
       return pg_query($sql);
    }

    function getPetugasScanLoading($cabang_id, $noloading)
    {
       $sql = "SELECT * from trx_loading_d2 where no_loading = '$noloading' and tugas = 'SCAN'";
       return pg_query($sql);
    }

    function getDelivery($cabang_id)
    {
        $sql = "SELECT DISTINCT a.no_delivery, a.route_id, b.nama_routes, a.id_tipe_armada, c.nama_tipe, a.keterangan, a.no_polisi, a.created_at
        FROM trx_delivery_sheet a 
        LEFT JOIN ms_routes b ON b.id = a.route_id
        LEFT JOIN ms_tipe_armadas c ON c.id = a.id_tipe_armada
        WHERE a.cabang_id = '$cabang_id'
        ORDER BY a.created_at DESC";
        return pg_query($sql);
    }

    function getHistoryTaskLoading($cabang_id, $karyawan_id)
    {
        $sql = "SELECT a.*, b.*, d.nama_routes, c.keterangan, c.no_polisi FROM trx_loading a 
        LEFT JOIN trx_loading_d2 b ON b.no_loading = a.no_loading 
        LEFT JOIN trx_delivery_sheet c ON c.no_delivery = a.no_order_delivery 
        LEFT JOIN ms_routes d ON d.id = c.route_id
        WHERE b.id_karyawan = '$karyawan_id' AND a.status_selesai = 'Y' ORDER BY a.tanggal_loading DESC LIMIT 50";
        return pg_query($sql);
    }

    function getHistoryTaskLoadingLoader($cabang_id)
    {
        $sql = "SELECT a.*, d.nama_routes, c.keterangan, c.no_polisi FROM trx_loading a 
        LEFT JOIN trx_delivery_sheet c ON c.no_delivery = a.no_order_delivery 
        LEFT JOIN ms_routes d ON d.id = c.route_id
        WHERE a.cabang_id = '$cabang_id' AND a.status_selesai = 'Y' ORDER BY a.tanggal_loading DESC LIMIT 50";
        return pg_query($sql);
    }

}









