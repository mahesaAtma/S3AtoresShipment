<?php

$dbconn = pg_connect("host=151.106.113.141 port=8750 dbname=db_s3_ailox_test user=postgres password=LjrJaya321!@%");
if ($dbconn) {
} else {
    echo "Koneksi Gagal !";
}