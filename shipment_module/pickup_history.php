<style>
    .history-pickup-sttb-item{
        line-height: 10px;
        font-weight: 400;
    }

    .history-pickup-sttb-item p{
        cursor: pointer;
    }
</style>

<div class="main-content">
    <div class="section__content section__content--p0 overflow-y-hidden">
        <div style="max-height:70vh; overflow-y:scroll;" class="container-fluid">
            <?php 
                $statusDone = ['117'];
                
                $totalPagination = $shipmentObj->countRequestPickup($statusDone)[0]['count'];
                $linkParent = "index.php?page=history_barang";
                include('./shipment_module/components/pagination.php');

                $historyPickupLists = $shipmentObj->listRequestPickup($statusDone, $currentPage);
                foreach ($historyPickupLists as $parentKey => $item) {
                    $historyCollapse = 'historyCollapseProduct' . $parentKey;
                    $historyCollapseChild = 'historyCollapseChildProduct' . $parentKey;
            ?>
                <div class="card">
                    <div class="card-header onHover bg-blue-tosca" id="<?= $historyCollapse ?>" style="padding: auto 20px;">
                        <div class="dflex align-item-c justify-content-sb" type="button" data-toggle="collapse" data-target="#<?= $historyCollapseChild ?>" aria-expanded="true" aria-controls="<?= $historyCollapseChild ?>">
                            <h6> <?php echo $item['no_order_pickup']; ?></h6>
                            <?php
                                echo "<h6 class='shipment-list-status shipment-done-status'> " . $item['nama_status'] . "</h6>";
                            ?>
                        </div>
                    </div>

                    <div id="<?= $historyCollapseChild ?>" class="collapse" aria-labelledby="<?= $historyCollapse ?>" data-parent="#accordionExample">
                        <?php 
                            $detailHistoryPickups = $shipmentObj->detailRequestPickup($item['no_order_pickup']);
                            foreach ($detailHistoryPickups as $childKey => $detailItem) {
                        ?>
                            <div class="card-body">
                                <div class="dflex align-item-c justify-content-sb">
                                    <h6><span class="opacity-50">Alamat <?php echo $childKey + 1 ?> </span> <span class="express-tag">Express</span></h6>
                                    <p><span class="opacity-50">Tgl Pickup :</span> <?= $detailItem['tgl_pickup'] ?>, <?= $detailItem['jam_pickup'] ?></p>
                                </div>
                        
                                <div style="line-height: 11px;">
                                    <p><?= $detailItem['lokasi_pickup'] ?></p>
                                    <p style="font-size: 14px;"><?= $item['customer_name'] ?> (<?= is_null($item['customer_phone']) ? '-' : $item['customer_phone'] ?>)</p>
                                </div>
                                <p class="opacity-50">List STTB</p>
                                <ol class="history-pickup-sttb-item ml-4">
                                    <?php 
                                        $sttbHistoryNumbers = $shipmentObj->getShipmentEntryNumber($item['no_order_pickup'], $detailItem['task_no']);
                                        foreach ($sttbHistoryNumbers as $sttbHistoryItem) {
                                            echo "<li>" . $sttbHistoryItem['no_shipment_entry'] ."</li>";
                                        }
                                    ?>
                                </ol>
                                <?php 

                                ?>
                            </div>

                            <?= ($childKey != count($detailHistoryPickups) - 1) ? "<hr class='line-boundaries'/>" : "";?>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            <?php      
                }
            ?>
        </div>
    </div>
</div>
