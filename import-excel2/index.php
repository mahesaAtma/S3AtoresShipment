<!DOCTYPE html>
<html>
<head>
	<title>Mari Belajar Coding</title>
	<?php
	//include 'koneksi.php';
	?>
</head>
<body>

	<table>
		<!--form upload file-->
		<form method="post" enctype="multipart/form-data" >
			<tr>
				<td>Pilih File</td>
				<td><input name="filemhsw" type="file" required="required"></td>
			</tr>
			<tr>
				<td></td>
				<td><input name="upload" type="submit" value="Import"></td>
			</tr>
		</form>
	</table>
	<?php
	error_reporting(E_ERROR | E_PARSE);
	if (isset($_POST['upload'])) {

		require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
		require('spreadsheet-reader-master/SpreadsheetReader.php');

		//upload data excel kedalam folder uploads
		$target_dir = "uploads/".basename($_FILES['filemhsw']['name']);
		
		move_uploaded_file($_FILES['filemhsw']['tmp_name'],$target_dir);

		$Reader = new SpreadsheetReader($target_dir);

		/*
		foreach ($Reader as $Key => $Row)
		{
			// import data excel mulai baris ke-2 (karena ada header pada baris 1)
			if ($Key < 1) continue;			
			$query=mysql_query("INSERT INTO mahasiswa(nim,nama,alamat,jurusan) VALUES ('".$Row[0]."', '".$Row[1]."','".$Row[2]."','".$Row[3]."')");
		}
		if ($query) {
				echo "Import data berhasil";
			}else{
				echo mysql_error();
			}
		*/
		
		foreach ($Reader as $Key => $Col)
		{
			// import data excel mulai baris ke-2 (karena ada header pada baris 1)
			if ($Key==1){
					$tahun=$Col[7];
			
			}
			
			if ($Key==2){
					$bulan_awal=$Col[7];
			
			}
			if ($Key==3){
					$bulan_akhir=$Col[7];
			
			}
				
			
		}
		
		
		
		foreach ($Reader as $Key => $Col)
		{
			// import data excel mulai baris ke-2 (karena ada header pada baris 1)
			if ($Key==6){
				
				//mencari posisi kolom yang ada nilai 1 atau 0
				for ($k=4; $k<=40; $k++){
					if($Col[$k]=='0' or $Col[$k]=='1'){
							if($Col[$k]!='0'){
							$kolom_count[] = $k;
							}
					}

			
			
				}
			
			}
		}
		
		
		foreach ($Reader as $Key => $Col)
		{
			// import data excel mulai baris ke-2 (karena ada header pada baris 1)
			if ($Key==8){
				
				foreach ($kolom_count as $value) {
				 $tgl_array_count[$value] = $Col[$value];
				}
			
			}
				
			
		}
		
		foreach ($Reader as $Key => $Col)
		{
			// import data excel mulai baris ke-9
			if ($Key>=9){
				
				$jml_masuk=0;
				
				
				foreach ($kolom_count as $value) {
					
					 if(strlen($tgl_array_count[$value])<2){
						  $tgl='0'.$tgl_array_count[$value];
					  }else{
						   $tgl=$tgl_array_count[$value];
					  }
					  
					
					if($Col[$value]=="" or strtoupper($Col[$value])=="PU" or strtoupper($Col[$value])=="LK" or strtoupper($Col[$value])=="S1" or strtoupper($Col[$value])=="S2"){
						$ket=strtoupper($Col[$value]);
						if($ket=" ") {$ket="HADIR";}
						$jml_masuk++;
					}else{
						$ket=strtoupper($Col[$value]);
					}
				  if($tgl_array_count[$value]>=21 and $tgl_array_count[$value]<=31 ){
					 
					  echo $Col[1].'['.$Col[2].']'.' TGL : '.$tahun.'-'.$bulan_awal.'-'.$tgl.' :'. $ket."<br>";
				  }else{
					   echo $Col[1].'['.$Col[2].']'.' TGL : '.$tahun.'-'.$bulan_akhir.'-'.$tgl.' :'. $ket."<br>";
				  }
				  
				}
				
				echo 'Jumlah Masuk : '.$jml_masuk."<br>";
				
			
			}
				
			
		}
		
		
		

		
		
	}
	?>
	
	<!--
	
	 <center><h1>Data Produk</h1><center>
    <center><a href="tambah_produk.php">+ &nbsp; Tambah Produk</a><center>
    <br/>
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Produk</th>
          <th>Dekripsi</th>
          <th>Harga Beli</th>
          <th>Harga Jual</th>
          <th>Gambar</th>
          <th>Action</th>
        </tr>
    </thead>
    <tbody>
      <?php
      // jalankan query untuk menampilkan semua data diurutkan berdasarkan nim
      $query = "SELECT * FROM produk ORDER BY id ASC";
      $result = mysqli_query($koneksi, $query);
      //mengecek apakah ada error ketika menjalankan query
      if(!$result){
        die ("Query Error: ".mysqli_errno($koneksi).
           " - ".mysqli_error($koneksi));
      }

      //buat perulangan untuk element tabel dari data mahasiswa
      $no = 1; //variabel untuk membuat nomor urut
      // hasil query akan disimpan dalam variabel $data dalam bentuk array
      // kemudian dicetak dengan perulangan while
      while($row = mysqli_fetch_assoc($result))
      {
      ?>
       <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $row['nama_produk']; ?></td>
          <td><?php echo substr($row['deskripsi'], 0, 20); ?>...</td>
          <td>Rp <?php echo number_format($row['harga_beli'],0,',','.'); ?></td>
          <td>Rp <?php echo $row['harga_jual']; ?></td>
          <td style="text-align: center;"><img src="gambar/<?php echo $row['gambar_produk']; ?>" style="width: 120px;"></td>
          <td>
              <a href="edit_produk.php?id=<?php echo $row['id']; ?>">Edit</a> |
              <a href="proses_hapus.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Anda yakin akan menghapus data ini?')">Hapus</a>
          </td>
      </tr>
         
      <?php
        $no++; //untuk nomor urut terus bertambah 1
      }
      ?>
    </tbody>
    </table>
	
	
	
	
	
	 $query = "INSERT INTO produk (nama_produk, deskripsi, harga_beli, harga_jual, gambar_produk) VALUES ('$nama_produk', '$deskripsi', '$harga_beli', '$harga_jual', '$nama_gambar_baru')";
	  $result = mysqli_query($koneksi, $query);
	  // periska query apakah ada error
	  if(!$result){
		  die ("Query gagal dijalankan: ".mysqli_errno($koneksi).
			   " - ".mysqli_error($koneksi));
	  } else {
		//tampil alert dan akan redirect ke halaman index.php
		//silahkan ganti index.php sesuai halaman yang akan dituju
		echo "<script>alert('Data berhasil ditambah.');window.location='index.php';</script>";
	  }
				  
				  
				  
				  
				  
	<h2>Data Mahasiswa</h2>
	<table border="1">
		<tr>
			<th>No</th>
			<th>NIM</th>
			<th>Nama</th>
			<th>Alamat</th>			
			<th>Jurusan</th>
		</tr>
		<?php 		
		$no=1;
		$data = mysql_query("select * from mahasiswa");
		while($d = mysql_fetch_array($data)){
			?>
			<tr>
				<td><?=$no++; ?></td>
				<td><?=$d['nim']; ?></td>
				<td><?=$d['nama']; ?></td>
				<td><?=$d['alamat']; ?></td>				
				<td><?=$d['jurusan']; ?></td>
			</tr>
			<?php 
		}
		?>
	</table>
	-->
</body>
</html>