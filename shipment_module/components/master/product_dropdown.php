<style>
    .nama-barang-dropdown{
        width: 100%;
        position: absolute;
        background-color: white;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    }

    .nama-barang-dropdown button{
        padding: 12px 18px;
        width: 100%;
        text-align: left;
        transition: 0.3s ease-in-out;
        font-size: 14px;
    }

    .nama-barang-dropdown button:hover{
        background-color: #ddd;
    }

</style>

<?php
    if (isset($parentID)) {
?>
    <div class="nama-barang-dropdown" data-parent="<?= $parentID ?>">
        <?php
            // Variable $jenisBarangs obtained from index.php
            if (count($jenisBarangs) > 0) {
                foreach ($jenisBarangs as $barang) {
                    $namaBarang = $barang['nama_barang'];
                    $namaBarangID = implode('_',explode(' ',strtolower($namaBarang)));
                    $namaBarangID = strtolower(str_replace("&","and",$namaBarangID));
                    echo "<button class='btn-dropdown-nama-barang dnone' id='$namaBarangID' data-barang-id='$namaBarangID' data-parent='$parentID'>$namaBarang</button>";
                }
            }else{
                echo "<button disabled=true class='btn-dropdown-nama-barang dnone' style='color:red;'>There's an error occured</button>";
            }
        ?>
    </div>
<?php
    }else{
        echo "<a style='color:red;'>There's an error occured</a>";
    }
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    /**
     * !NOTE : IN JQUERY, SPECIAL CHARACTERS (EG : &,%,*) ARE FORBIDDEN 
     * THEREFORE IN THE IMPLEMENTATION BELOW '&' IS CONVERTED TO 'AND' 
    */ 

    $(document).ready(function() {
        namaBarangs = $('p.nama-barang-item').map((_,el) => $(el).text()).get();

        $('.btn-dropdown-nama-barang').off('mousedown').mousedown(function() {
            dataParentID = $(this).attr('data-parent');          
            if (!($(this).attr('disabled'))) {
                $(`#${dataParentID} .nama-barang-input`).val($(this).text());
            }
        });

        $('input.nama-barang-input').off('focus').focus(function(){
            dataParentID = $(this).attr('data-parent');
            $(`#${dataParentID} .nama-barang-dropdown button`).addClass('dblock');
        });

        $('input.nama-barang-input').off('focusout').focusout(function(){
            dataParentID = $(this).attr('data-parent');
            $(`#${dataParentID} .nama-barang-dropdown button`).removeClass('dblock');
        });

        $('input.nama-barang-input').off('keyup').keyup(function(){
            dataParentID = $(this).attr('data-parent');
            $(`#${dataParentID} .nama-barang-dropdown button`).removeClass('dblock');
            
            currentValue = (this.value).toLowerCase();
            
            acceptedBarangs = namaBarangs.filter((itm) => (currentValue.length === 0) || (new RegExp('.*' + currentValue.toLowerCase() + '.*')).test(itm.toLowerCase()));
            
            for (const barang of acceptedBarangs) {
                barangID = barang.split(' ').join('_').toLowerCase().replace('&','and');
                $(`.nama-barang-dropdown #${barangID}`).addClass('dblock');
            }
        })
    });
</script>