<?php 

class ShipmentQuery{

    public $perPage = 10;
    private $driverID = "";
    private $dbconn;

    public function __construct($dbconn)
    {
        $this->dbconn = $dbconn;
        $this->driverID = "eded8381-d798-4004-bd84-0cc1178b562a";
        // $this->driverID = $this->getDriver();
    }

    private function getDriver(){
        $userQuery = "SELECT id,name FROM users WHERE id = " . $_SESSION['id_user_login'] . " AND role_id = '119';";
        $user = pg_fetch_object(pg_query($this->dbconn, $userQuery));
        
        if ($user) {
            return "";
        }

        return $user['id'];
    }

    /**
     * Get list cara pembayaran
     * 
     * @return array
     */
    public function listCaraPembayaran(){
        $query = "SELECT * FROM ms_cara_bayars";
        return $this->fetchAllRow($query);
    }
    
    /**
     * Get list request pickup with status WAITING LIST 
     * USED STATUS TASK [115 => WAITING LIST, 116 => ON PROGRESS, 117 => DONE]
     * 
     * @param string $driverID
     * @param array $statusTask | array of string
     * @return object
     */
    public function listRequestPickup($statusTask, $page = 1){
        // Due to Postgres strict rules, char ' is used to join arrays
        $statusTaskString = implode("','", $statusTask);
        $offset = ($page - 1) * $this->perPage;

        $query = "SELECT 
                DISTINCT(top.no_order_pickup),
                top.id,
                top.status_task,
                mss.nama_status,
                top.customer_id,
                mc.name AS customer_name,
                mc.phone AS customer_phone,
                tse.no_shipment_entry,
                tt.status_task AS driver_status_task,
	            top.created_at AS pickup_created_at
            FROM trx_order_pickup AS top
            JOIN ms_status AS mss ON mss.id = CAST(top.status_task AS INTEGER)
            LEFT JOIN trx_task AS tt ON top.no_order_pickup = tt.no_ref_process
            LEFT JOIN ms_customers AS mc ON mc.id = CAST(top.customer_id AS INTEGER)
            LEFT JOIN trx_shipment_entry AS tse ON top.no_order_pickup = tse.no_order_pickup
            WHERE tt.tipe_process = 'pickup' 
            AND tt.status_task = '133'
            AND top.status_task IN ('$statusTaskString') 
            AND tt.driver_id = '$this->driverID'
            ORDER BY top.created_at DESC
            LIMIT $this->perPage OFFSET $offset;
        ";
        return $this->fetchAllRow($query);
    }

    /**
     * Get total row for current list request pickup
     * 
     * @param array $statusTask | Refer to listRequestPickup function
     * @param string $driverID
     * @return array
     */
    public function countRequestPickup($statusTask){
        // Due to Postgres strict rules, char ' is used to join arrays
        $statusTaskString = implode("','", $statusTask);

        $query = "SELECT COUNT(*) FROM (
                SELECT 
                    DISTINCT(top.no_order_pickup)
                FROM trx_order_pickup AS top
                JOIN ms_status AS mss ON mss.id = CAST(top.status_task AS INTEGER)
                LEFT JOIN trx_task AS tt ON top.no_order_pickup = tt.no_ref_process
                LEFT JOIN ms_customers AS mc ON mc.id = CAST(top.customer_id AS INTEGER)
                LEFT JOIN trx_shipment_entry AS tse ON top.no_order_pickup = tse.no_order_pickup
                WHERE tt.tipe_process = 'pickup' 
                AND tt.status_task = '133'
                AND top.status_task IN ('$statusTaskString') 
                AND tt.driver_id = '$this->driverID'
            ) AS total_order_pickup;
        ";
        return $this->fetchAllRow($query);
    }

    /**
     * Get detail item for each order pickup
     * 
     * @param string $noOrderPickup
     * @return array
     */
    public function detailRequestPickup($noOrderPickup){
        $query = "SELECT 
                topd.id,
                topd.lokasi_pickup,
                topd.tgl_pickup,
                topd.jam_pickup,
                mjb.nama_barang,
                topd.berat,
                topd.keterangan,
                topd.task_no,
                topd.custsentreceipt_id,
                tt.id AS task_id,
                tt.status_task AS status_driver,
                ms.nama_status AS nama_status_driver
            FROM trx_order_pickup_d1 AS topd
            JOIN ms_jenis_barang AS mjb ON mjb.id = CAST(topd.jenis_barang AS INTEGER)
            JOIN trx_task AS tt ON tt.task_no = topd.task_no
            JOIN ms_status AS ms ON ms.id = CAST(tt.status_task AS INTEGER)
            WHERE topd.no_order_pickup = '$noOrderPickup';";
        return $this->fetchAllRow($query);
    }

    /**
     * Get all shipment entry for pickup order
     * 
     * @param string $noOrderPickup
     * @param string $taskNo
     * @return array
     */
    public function getShipmentPickupOrder($noShipmentEntry){
        $query = "SELECT 
            top.id AS order_pickup_id,
            top.customer_id,
            top.no_order_pickup,
            task.id AS task_id,
            topdetail.id AS order_pickup_detail_id,
            topdetail.task_no,
            topdetail.custsentreceipt_id,
            shipment.no_shipment_entry,
            shipment.id AS shipment_id,
            shipment.sign_cust,
            task.driver_id,
            payment.nama_cara_bayar,
            shipmentDetail.no_sttb,
            shipmentDetail.id AS shipment_detail_id,
            receipt.alamat,
            shipmentDetail.nama_pic_penerima,
            shipmentDetail.tlp_pic_penerima
        FROM trx_shipment_entry AS shipment
        JOIN trx_shipment_entry_d1 AS shipmentDetail ON shipment.no_shipment_entry = shipmentDetail.no_shipment_entry
        JOIN trx_order_pickup AS top ON top.no_order_pickup = shipment.no_order_pickup
        JOIN trx_order_pickup_d1 AS topdetail ON topdetail.no_order_pickup = shipment.no_order_pickup
        JOIN trx_task AS task ON task.task_no = shipment.task_no
        JOIN ms_cara_bayars AS payment ON shipment.cara_bayar_id = payment.id
        JOIN ms_cust_send_receipt AS receipt ON shipmentDetail.alamat_penerima = receipt.id
        WHERE shipment.no_shipment_entry = '$noShipmentEntry'
        AND task.driver_id = '$this->driverID';";

        return $this->fetchAllRow($query);
    }

    /**
     * Get all shipment entry for pickup order
     * 
     * @param string $noOrderPickup
     * @param string $taskNo
     * @return array
     */
    public function getShipmentDetail($shipmentDetail1ID){
        $query = "SELECT 
                detail.no_shipment_entry,
                detail.no_sttb,
                service.jml_koli,
                service.berat_asli,
                service.nama_barang,
                master.nama_service_product,
                master.prefix,
                detail.prioritas,
                detail.tgl_prioritas,
                detail.susulan
            FROM trx_shipment_entry_d1_service AS service
            join trx_shipment_entry_d1 AS detail ON detail.id = service.trx_shipment_entry_d1_id
            JOIN ms_service_products AS master ON service.service_id = master.id    
            WHERE service.trx_shipment_entry_d1_id = " . pg_escape_string($shipmentDetail1ID) . "
            AND service.volume IS NULL
            AND service.volume_desc IS NULL
            ORDER BY service.id DESC
            LIMIT 1;";

        return $this->fetchRow($query);
    }

    /**
     * Get shipment detail
     * 
     * @param string $shipmentD1ID
     * @return array
     */
    public function getShipmentDetailServiceMain($shipmentD1ID){
        $query = "SELECT 
                shipmentService.id,
                trx_shipment_entry_d1_id,
                service_id,
                jml_koli,
                berat_asli,
                nama_barang,
                jumlah_unit,
                jumlah_kg,
                jumlah_kubik,
                shipmentDetail.no_sttb
            FROM trx_shipment_entry_d1_service AS shipmentService
            JOIN trx_shipment_entry_d1 AS shipmentDetail ON shipmentDetail.id = shipmentService.trx_shipment_entry_d1_id
            WHERE trx_shipment_entry_d1_id = " . pg_escape_string($shipmentD1ID) . "
            AND volume IS NULL
            AND volume_desc IS NULL
            ORDER BY id LIMIT 1;";

        return $this->fetchRow($query);
    }

    /**
     * Get shipment detail 1 matrixes
     * 
     * @param string $shipmentD1ID
     * @return object
     */
    public function getShipmentDetailServiceMatrix($shipmentD1ID){
        $query = "SELECT 
                id,
                jml_koli,
                volume_desc,
                berat_volume
            FROM trx_shipment_entry_d1_service
            WHERE trx_shipment_entry_d1_id = " . $shipmentD1ID . "
            AND volume IS NOT NULL
            AND volume_desc IS NOT NULL
            AND berat_volume IS NOT NULL;";

        return $this->fetchAllRow($query);
    }

    /**
     * Get shipment unique features
     * 
     * @param string $shipmentEntryCode
     * @param string $noSTTB
     * @return array
     */
    public function getShipmentFeatures($shipmentEntryCode, $noSTTB){
        $query = "SELECT 
                DISTINCT(features_id),
                feature.nama_features
            FROM trx_shipment_entry_d3 AS shipment
            LEFT JOIN ms_features AS feature ON feature.id = shipment.features_id
            WHERE no_shipment_entry = '$shipmentEntryCode'
            AND shipment.no_sttb = '$noSTTB'
            AND shipment.features_id IS NOT NULL;";

        return $this->fetchAllRow($query);
    }

    /**
     * Get shipment unique documents
     * 
     * @param string $shipmentEntryCode
     * @param string $noSTTB
     * @return array
     */
    public function getShipmentDocuments($shipmentEntryCode, $noSTTB){
        $query = "SELECT 
            detailD2.document_id, 
            detailD2.no_dokumen,
            doc.nama_dokumen
        FROM public.trx_shipment_entry_d2 AS detailD2
        JOIN ms_documents as doc ON detailD2.document_id = doc.id
        WHERE detailD2.no_shipment_entry = '$shipmentEntryCode'
        AND detailD2.no_sttb = '$noSTTB'
        AND detailD2.document_id IS NOT NULL";

        return $this->fetchAllRow($query);
    }

    /**
     * Get shipment extra packing
     * 
     * @param string $shipmentEntryCode
     * @param string $noSTTB
     * @return array
     */
    public function getShipmentExtraPacking($shipmentEntryCode, $noSTTB){
        $query = "SELECT
                detailD3.dimensi_panjang,
                detailD3.dimensi_lebar,
                detailD3.dimensi_tinggi,
                detailD3.biaya,
                detailD3.berat_total,
                harga.nama_packing
            FROM trx_shipment_entry_d3 AS detailD3
            JOIN ms_harga_packing as harga ON detailD3.id_harga_packing = harga.id
            WHERE dimensi_panjang IS NOT NULL
            AND dimensi_lebar IS NOT NULL
            AND dimensi_tinggi IS NOT NULL
            AND detailD3.no_shipment_entry = '$shipmentEntryCode'
            AND detailD3.no_sttb = '$noSTTB'";

        return $this->fetchAllRow($query);
    }

    /**
     * Get shipment unique documents
     * 
     * @param string $customerID
     * @return array
     */
    public function getShipmentDocumentsByCustomer($customerID){
        $query = "SELECT 
                DISTINCT(custDoc.document_id),
                document.nama_dokumen,
                document.no_dokumen,
                document.jenis
            FROM mp_cust_b2b_doc AS custDoc
            JOIN ms_documents AS document ON custDoc.document_id = document.id
            WHERE custDoc.customer_id = $customerID";

        return $this->fetchAllRow($query);
    }

    /**
     * Get list of request pickup documents
     * 
     * @param string $noOrderPickup
     * @return array
     */
    public function getRequestPickupDocument($noOrderPickup){
        $query = "SELECT nama_document,path FROM trx_order_pickup_doc WHERE no_order_pickup = '$noOrderPickup'";
        return $this->fetchAllRow($query);
    }

    /**
     * Get list Shipment Entry Number 
     * 
     * @param string $noOrderPickup
     * @param string $taskNo
     * 
     * @return array
     */
    public function getShipmentEntryNumber($noOrderPickup, $taskNo){
        $query = "SELECT no_shipment_entry FROM trx_shipment_entry WHERE no_order_pickup = '$noOrderPickup' AND task_no = '$taskNo'";
        return $this->fetchAllRow($query);
    }

    /**
     * Get statuses for request pickup detail
     * 
     * @return array
     */
    public function getRequestPickupDetailStatus(){
        $allowedStatus = implode(",",[135,137,136,131,134,133]);
        $query = "SELECT id,nama_status FROM ms_status WHERE id IN ($allowedStatus)";
        return $this->fetchAllRow($query);
    }

    /**
     * Get list customer send receipts for a customer
     */
    public function getListCustomerSendReceipt($customerID){
        $query = "SELECT id, nama_send_receipt, alamat, phone_pic FROM ms_cust_send_receipt WHERE jenis_alamat = 'RECEIPT' AND customer_id = " . $customerID;
        return $this->fetchAllRow($query);
    }

    /**
     * Get customer send receipts
     */
    public function getMasterCustomerSendReceipt($id){
        $query = "SELECT id, nama_send_receipt, alamat, phone_pic FROM ms_cust_send_receipt WHERE id = " . $id;
        return $this->fetchRow($query);
    }

    /**
     * Get list of master data barang
     * !NOTE : Pagination or WHERE clause is not implemented, carefull for potential big data
     * 
     * @return array
     */
    public function getJenisBarang(){
        $query = "SELECT nama_barang FROM ms_jenis_barang";
        return $this->fetchAllRow($query);
    }
    
    /**
     * Fetch all rows from PostGresSQL
     * 
     * @param string $query
     * @return array
     */
    private function fetchAllRow($query){
        return pg_fetch_all(pg_query($this->dbconn,$query));
    }
    
    /**
     * Fetch a row from PostGresSQL
     * 
     * @param string $query
     * @return array
     */
    private function fetchRow($query){
        return pg_fetch_object(pg_query($this->dbconn,$query));
    }
}