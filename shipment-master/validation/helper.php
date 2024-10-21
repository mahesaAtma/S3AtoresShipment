<?php

/**
 * Helper for validating input
 */
class Helper{
    private $dbconn = null;

    public function __construct($dbconn)
    {
        $this->dbconn = $dbconn;
    }

    /**
     * Validate Number STTB for each STTB input types
     * 
     * @param Array data
     * @return Array
     */
    public function validateNoSTTB($data){
        $noSTTBQuery = "SELECT id FROM trx_order_sttb_d WHERE no_sttb_fisik = '" . $data['no_sttb'] . "';";
        $noSTTB = pg_fetch_object(pg_query($this->dbconn, $noSTTBQuery));
        
        $shipmentSTTBQuery = "SELECT id FROM trx_shipment_entry_d1 WHERE no_sttb = '" . $data['no_sttb'] . "';";
        $shipmentSTTB = pg_fetch_object(pg_query($this->dbconn, $shipmentSTTBQuery));
        
        // 'input' is equal to scan sttb barcode
        if ($data['sttb_input_type'] == 'e-STTB') {
            if ($noSTTB) {
                return ['success' => false, 'message' => "Nomor E-STTB " . $data['no_sttb'] . " sudah ada dalam database, mohon buat E-STTB baru!"];
            }
        }else if ($data['sttb_input_type'] == 'input'){
            if (!$noSTTB) {
                return ['success' => false, 'message' => "Nomor STTB " . $data['no_sttb'] . " tidak dapat ditemukan!"];
            }
        
            if ($shipmentSTTB) {
                return ['success' => false, 'message' => "Nomor STTB " . $data['no_sttb'] . " telah digunakan untuk shipment!"];
            }
        }else if ($data['sttb_input_type'] == 'FISIK'){
            if (!$noSTTB) {
                return ['success' => false, 'message' => "Nomor STTB " . $data['no_sttb'] . " tidak dapat ditemukan!"];
            }

            if ($shipmentSTTB) {
                return ['success' => false, 'message' => "Nomor STTB " . $data['no_sttb'] . " sudah dipakai!"];
            }
        }else{
            return ['success' => false, 'message' => "Tipe input sttb tidak valid!"];
        }
        
        return ['success' => true];
    }

    /**
     * Validate and insert to table trx_status_sttb
     * 
     * @param Array $data
     * @param String $noShipment
     * @param String $nowDatetime
     * @param Object $orderPickup
     * @param Object $user
     * @return Array $queryInsert
     */
    public function insertStatusSTTB($data, $noShipment, $nowDatetime, $orderPickup, $user){
        $serviceData = isset($data['service_data']) ? $data['service_data'] : [];
    
        $jmlKoli = isset($serviceData['jumlah_koli']) ? $serviceData['jumlah_koli'] : 'NULL'; 
        $jmlKg = isset($serviceData['jumlah_kg']) ? $serviceData['jumlah_kg'] : 'NULL'; 
    
        $queryInsert = ["INSERT INTO trx_status_sttb (
            no_ref_process,
            nama_process,
            no_sttb,
            status_sttb,
            cabang_id,
            unit_id,
            status_record,
            created_by,
            updated_by,
            created_at,
            updated_at,
            keterangan,
            koli,
            kg,
            volume,
            penerima,
            foto
        ) VALUES (
            '" . $noShipment . "',
            'SHIPMENT_ENTRY',
            '" . $data['no_sttb'] . "',
            100,
            '" . $orderPickup->cabang_id . "',
            '" . $orderPickup->unit_id . "',
            1,
            '" . $user->name . "',
            NULL,
            '" . $nowDatetime . "',
            '" . $nowDatetime . "',
            NULL,
            " . $jmlKoli . ",
            " . $jmlKg . ",
            NULL,
            NULL,
            NULL
        );"];
    
        return ['success' => true, 'queries' => $queryInsert];
    }

    /**
     * Validate and insert to table trx_shipment_entry_d1
     * 
     * @param Array $data
     * @param String $nowDate
     * @param String $nowDatetime
     * @param String $noShipment
     * @param Object $orderPickup
     * @param Object $customer
     * @param Object $user
     * @return Array $queryInsert
     */
    function insertTrxShipmentEntryD1($data, $nowDate, $nowDatetime, $noShipment, $orderPickup, $customer, $user){
        $serviceData = isset($data['service_data']) ? $data['service_data'] : [];
    
        if (empty($data['address_receipt_id'])) {
            return ['success' => false, 'message' => "Data alamat penerima tidak boleh kosong!"];
        }
        
        $sendReceiptQuery = "SELECT id, alamat, nama_pic, phone_pic FROM ms_cust_send_receipt WHERE id = " . pg_escape_string($data['address_receipt_id']) . ";";
        $sendReceipt = pg_fetch_object(pg_query($this->dbconn, $sendReceiptQuery));
        if (!$sendReceipt) {
            return ['success' => false, 'message' => "Alamat penerima tidak dapat ditemukan!"];
        }
    
        $jmlPrintBarcode = isset($serviceData['jumlah_koli']) ? $serviceData['jumlah_koli'] : 0;
        $tglPrioritas = !isset($data['tgl_prioritas_pengiriman']) || $data['tgl_prioritas_pengiriman'] == '' ? 'NULL' : "'" . $data['tgl_prioritas_pengiriman'] . "'";
    
        $queryInsert = ["INSERT INTO trx_shipment_entry_d1 (
            no_shipment_entry,
            no_sttb,
            jenis_sttb,
            no_sub_sttb,
            alamat_pengirim,
            nama_pic_pengirim,
            tlp_pic_pengirim,
            alamat_penerima,
            nama_pic_penerima,
            tlp_pic_penerima,
            price,
            jml_print_barcode,
            status_unloading,
            catatan_customer,
            no_ba,
            tgl_entry,
            unit_id,
            cabang_id,
            status_record,
            created_by,
            updated_by,
            created_at,
            updated_at,
            prioritas,
            tgl_prioritas,
            no_resi,
            is_counter,
            susulan
        ) VALUES (
            '" . $noShipment . "',
            '" . $data['no_sttb'] . "',
            '" . $data['sttb_input_type'] . "',
            NULL,
            '" . $customer->id . "',
            '" . $customer->name_pic . "',
            '" . $customer->phone_pic . "',
            '" . $sendReceipt->id . "',
            '" . $sendReceipt->nama_pic . "',
            '" . $sendReceipt->phone_pic . "',
            0,
            " . $jmlPrintBarcode . ",
            'N',
            NULL,
            NULL,
            '" . $nowDate . "',
            '" . $orderPickup->unit_id . "',
            '" . $orderPickup->cabang_id . "',
            1,
            '" . $user->name . "',
            NULL,
            '" . $nowDatetime . "',
            '" . $nowDatetime . "',
            '" . $data['prioritas_pengiriman'] . "',
            " . $tglPrioritas . ",
            NULL,
            NULL,
            '" . $data['barang_susulan'] . "'
        ) RETURNING id;"];
    
        return ['success' => true, 'queries' => $queryInsert];
    }

    /**
     * Validate and insert to table trx_shipment_entry_d1_service
     * 
     * @param Array $data
     * @param String $nowDatetime
     * @param Object $orderPickup
     * @param Integer $shipmentEntryD1ID
     * @param Object $user
     * @return Array $queryInsert
     */
    public function insertTrxShipmentEntryD1Service($data, $nowDatetime, $orderPickup, $shipmentEntryD1ID, $user){
        $serviceData = isset($data['service_data']) ? $data['service_data'] : [];
        
        $queryInsert = [];
    
        if (empty($serviceData)) {
            return ['success' => false, 'message' => "Mohon mengisi data service terlebih dahulu"];
        }
        if (!isset($serviceData['nama_barang'])) {
            return ['success' => false, 'message' => "Mohon mengisi data nama barang terlebih dahulu"];
        }
        if (!isset($serviceData['jumlah_koli'])) {
            return ['success' => false, 'message' => "Mohon mengisi data jumlah koli terlebih dahulu"];
        }
        if (!isset($serviceData['berat_asli'])) {
            return ['success' => false, 'message' => "Mohon mengisi data berat asli terlebih dahulu"];
        }
    
    
        $serviceQuery = "SELECT id FROM ms_service_products WHERE nama_service_product = '" . strtoupper($serviceData['type']) . "';";
        $service = pg_fetch_object(pg_query($this->dbconn, $serviceQuery));
        if (!$service) {
            return ['success' => false, 'message' => "Tipe service " . $serviceData['type'] . " tidak dapat ditemukan!"];
        }
    
        // if (isset($serviceData['nama_barang'])) {
        //     $jenisBarangQuery = "SELECT id FROM ms_jenis_barang WHERE nama_barang = '" . strtoupper($serviceData['nama_barang']) . "';";
        //     $jenisBarang = pg_fetch_object(pg_query($dbconn, $jenisBarangQuery));
        //     if (!$jenisBarang) {
        //         return ['success' => false, 'message' => "Nama barang " . $serviceData['nama_barang'] . " tidak dapat ditemukan!"];
        //     }
        // }
    
        $columns = implode(',', [
            'trx_shipment_entry_d1_id',
            'service_id',
            'jml_koli',
            'berat_asli',
            'volume',
            'pallet',
            'tgl_entry',
            'unit_id',
            'cabang_id',
            'status_record',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'status',
            'volume_desc',
            'berat_volume',
            'tipe_armada',
            'jumlah_unit',
            'nama_barang',
            'tipe_kontainer',
            'jumlah_kubik',
            'jumlah_kg',
            'berat_total',
            'total_berat_volume'
        ]);
    
        $volMatrixArray = isset($serviceData['vol_matrix']) ? $serviceData['vol_matrix'] : [];
    
        $jumlahKoliMain = (int) $serviceData['jumlah_koli'];
        $jumlahKoliVolMatrix = 0;
    
        if (in_array($serviceData['type'], ['reguler', 'express', 'primex', 'ltl'])) {
            if (count($volMatrixArray) > 0) {
                foreach ($volMatrixArray as $volMatrix) {
                    $jumlahKoliVolMatrix += (int) $volMatrix['jumlah_koli'];
    
                    $volume = ((int) $volMatrix['dimension']['p']) * ((int) $volMatrix['dimension']['l']) * ((int) $volMatrix['dimension']['t']);
                    $volumeDesc = $volMatrix['dimension']['p'] . "x" . $volMatrix['dimension']['l'] . "x" . $volMatrix['dimension']['t'] . " (cm)";
        
                    $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
                        " . $columns . "
                    ) VALUES (
                        '" . $shipmentEntryD1ID . "',
                        " . $service->id . ",
                        " . $volMatrix['jumlah_koli'] . ",
                        NULL,
                        " . $volume . ",
                        NULL,
                        NULL,
                        '" . $orderPickup->unit_id . "',
                        '" . $orderPickup->cabang_id . "',
                        1,
                        '" . $user->name . "',
                        NULL,
                        '" . $nowDatetime . "',
                        '" . $nowDatetime . "',
                        NULL,
                        '" . $volumeDesc . "',
                        '" . $volMatrix['berat_asli'] . "',
                        NULL,
                        NULL,
                        NULL,
                        NULL,
                        NULL,
                        NULL,
                        NULL,
                        NULL
                    );";
                }
    
                if ($jumlahKoliVolMatrix > $jumlahKoliMain) {
                    return ['success' => false, 'message' => "Jumlah koli pada vol matrix tidak boleh lebih besar dari jumlah koli utama!"];
                }
            }else{
                $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
                    " . $columns . "
                ) VALUES (
                    '" . $shipmentEntryD1ID . "',
                    " . $service->id . ",
                    " . $serviceData['jumlah_koli'] . ",
                    " . $serviceData['berat_asli'] . ",
                    NULL,
                    NULL,
                    NULL,
                    '" . $orderPickup->unit_id . "',
                    '" . $orderPickup->cabang_id . "',
                    1,
                    '" . $user->name . "',
                    NULL,
                    '" . $nowDatetime . "',
                    '" . $nowDatetime . "',
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    '" . $serviceData['nama_barang'] . "',
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL
                );";
            }
        }else if (in_array($serviceData['type'], ['fcl', 'ftl'])) {
            $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
                " . $columns . "
            ) VALUES (
                '" . $shipmentEntryD1ID . "',
                " . $service->id . ",
                " . $serviceData['jumlah_koli'] . ",
                NULL,
                NULL,
                NULL,
                NULL,
                '" . $orderPickup->unit_id . "',
                '" . $orderPickup->cabang_id . "',
                1,
                '" . $user->name . "',
                NULL,
                '" . $nowDatetime . "',
                '" . $nowDatetime . "',
                NULL,
                NULL,
                NULL,
                NULL,
                " . $serviceData['jumlah_unit'] . ",
                NULL,
                NULL,
                " . $serviceData['jumlah_kubik'] . ",
                " . $serviceData['jumlah_kg'] . ",
                NULL,
                NULL
            );";
        }else if (in_array($serviceData['type'], ['lcl'])) {
            $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
                " . $columns . "
            ) VALUES (
                '" . $shipmentEntryD1ID . "',
                " . $service->id . ",
                'NULL',
                'NULL',
                'NULL',
                'NULL',
                'NULL',
                '" . $orderPickup->unit_id . "',
                '" . $orderPickup->cabang_id . "',
                1,
                '" . $user->name . "',
                'NULL',
                '" . $nowDatetime . "',
                '" . $nowDatetime . "',
                'NULL',
                'NULL',
                'NULL',
                'NULL',
                'NULL',
                '" . $serviceData['nama_barang'] . "',
                'NULL',
                'NULL',
                'NULL',
                'NULL',
                'NULL'
            );";
        }  
    
        $totalJmlKoli = (int) $serviceData['jumlah_koli'] - $jumlahKoliVolMatrix;
        
        $queryInsert[] = "INSERT INTO trx_shipment_entry_d1_service (
            " . $columns . "
        ) VALUES (
            '" . $shipmentEntryD1ID . "',
            " . $service->id . ",
            " . $totalJmlKoli . ",
            " . $serviceData['berat_asli'] . ",
            NULL,
            NULL,
            NULL,
            '" . $orderPickup->unit_id . "',
            '" . $orderPickup->cabang_id . "',
            1,
            '" . $user->name . "',
            NULL,
            '" . $nowDatetime . "',
            '" . $nowDatetime . "',
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            '" . $serviceData['nama_barang'] . "',
            NULL,
            NULL,
            NULL,
            NULL,
            NULL
        );";
    
        return ['success' => true, 'queries' => $queryInsert];
    }

    /**
     * Validate and insert to table trx_shipment_entry_d2
     * 
     * @param Array $data
     * @param String $nowDatetime
     * @param String $noShipment
     * @param Object $orderPickup
     * @param Object $user
     * @return Array $queryInsert
     */
    public function insertTrxShipmentEntryD2($data, $nowDatetime, $noShipment, $orderPickup, $user){
        $documentData = isset($data['document_data']) ? $data['document_data'] : [];
        $queryInsert = [];
    
        if (count($documentData) <= 0) {
            return ['success' => true, 'queries' => $queryInsert];
        }
    
        $documentIds = array_map(function($doc){
            return $doc['id'];
        }, $documentData);
    
        $selectQuery = "SELECT id,no_dokumen FROM ms_documents WHERE id IN (" . implode(',', $documentIds) . ")";
        $documents = pg_fetch_all(pg_query($this->dbconn,$selectQuery));
        if (!$documents) {
            return ['success' => false, 'message' => "Pilihan dokumen tidak dapat ditemukan!"];
        }
    
        foreach ($documents as $document) {
            $queryInsert[] = "INSERT INTO trx_shipment_entry_d2 (
                no_shipment_entry,
                no_sttb,
                document_id,
                no_dokumen,
                status_available,
                cabang_id,
                unit_id,
                status_record,
                created_by,
                updated_by,
                created_at,
                updated_at
            ) VALUES (
                '" . $noShipment . "',
                '" . $data['no_sttb'] . "',
                " . $document['id'] . ",
                '" . $document['no_dokumen'] . "',
                'true',
                '" . $orderPickup->cabang_id . "',
                '" . $orderPickup->unit_id . "',
                1,
                '" . $user->name . "',
                NULL,
                '" . $nowDatetime . "',
                '" . $nowDatetime . "'
            );";
        }
    
        return ['success' => true, 'queries' => $queryInsert];
    }

    /**
     * Validate and insert to table trx_shipment_entry_d3
     * 
     * @param Array $data
     * @param String $nowDatetime
     * @param String $noShipment
     * @param Object $orderPickup
     * @param Object $user
     * @return Array $queryInsert
     */
    public function insertTrxShipmentEntryD3($data, $nowDatetime, $noShipment, $orderPickup, $user){
        $requestData = isset($data['request_data']) ? $data['request_data'] : [];
        
        $queryInsert = [];

        if (empty($requestData)) {
            return ['success' => true, 'queries' => $queryInsert];
        }

        foreach ($requestData as $key => $value) {
            if ($value == 'false') {
                continue;
            }

            $featureText = strtoupper(implode(' ', explode('_',$key)));

            $featureQuery = "SELECT id FROM ms_features WHERE nama_features = '" . $featureText . "';";
            $feature = pg_fetch_object(pg_query($this->dbconn, $featureQuery));
            if (!$feature) {
                return ['success' => false, 'message' => "Feature atau request " . $featureText . " tidak dapat ditemukan!"];
            }

            $queryInsert[] = "INSERT INTO trx_shipment_entry_d3 (
                no_shipment_entry,
                no_sttb,
                features_id,
                jumlah_features,
                jml_koli,
                dimensi_panjang,
                dimensi_lebar,
                dimensi_tinggi,
                biaya,
                berat_total,
                status_ambil,
                unit_id,
                cabang_id,
                status_record,
                created_by,
                updated_by,
                created_at,
                updated_at,
                id_harga_packing
            ) VALUES (
                '" . $noShipment . "',
                '" . $data['no_sttb'] . "',
                " . $feature->id . ",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                '" . $orderPickup->unit_id . "',
                '" . $orderPickup->cabang_id . "',
                1,
                '" . $user->name . "',
                NULL,
                '" . $nowDatetime . "',
                '" . $nowDatetime . "',
                NULL
            );";
        }

        return ['success' => true, 'queries' => $queryInsert];
    }

    /**
     * Validate and insert to table trx_shipment_entry_d3
     * 
     * @param Array $data
     * @param String $nowDatetime
     * @param String $noShipment
     * @param Object $orderPickup
     * @param Object $user
     * @return Array $queryInsert
     */
    public function insertTrxShipmentEntryD3Packing($data, $nowDatetime, $noShipment, $orderPickup, $user){
        $packingData = isset($data['packing_data']) ? $data['packing_data'] : [];

        $queryInsert = [];

        if (empty($packingData)) {
            return ['success' => true, 'queries' => $queryInsert];
        }

        if (empty($packingData['dimensi'])) {
            return ['success' => false, 'message' => "Mohon mengisi dimensi extra packing terlebih dahulu!"];
        }

        $namaPacking = strtoupper("packing " . $packingData['jenis_packing']);
        $packingQuery = "SELECT id FROM ms_harga_packing WHERE nama_packing = '" . pg_escape_string($namaPacking) . "';";
        $packing = pg_fetch_object(pg_query($this->dbconn, $packingQuery));
        if (!$packing) {
            return ['success' => false, 'message' => "Data " . $namaPacking . " tidak dapat ditemukan!"];
        }

        foreach ($packingData['dimensi'] as $item) {
            $queryInsert[] = "INSERT INTO trx_shipment_entry_d3 (
                no_shipment_entry,
                no_sttb,
                features_id,
                jumlah_features,
                jml_koli,
                dimensi_panjang,
                dimensi_lebar,
                dimensi_tinggi,
                biaya,
                berat_total,
                status_ambil,
                unit_id,
                cabang_id,
                status_record,
                created_by,
                updated_by,
                created_at,
                updated_at,
                id_harga_packing
            ) VALUES (
                '" . $noShipment . "',
                '" . $data['no_sttb'] . "',
                NULL,
                NULL,
                " . $item['jumlah_koli'] . ",
                " . $item['panjang'] . ",
                " . $item['lebar'] . ",
                " . $item['tinggi'] . ",
                " . $item['harga'] . ",
                " . $item['berat'] . ",
                NULL,
                '" . $orderPickup->unit_id . "',
                '" . $orderPickup->cabang_id . "',
                1,
                '" . $user->name . "',
                NULL,
                '" . $nowDatetime . "',
                '" . $nowDatetime . "',
                $packing->id
            );";
        }

        return ['success' => true, 'queries' => $queryInsert];
    }
}