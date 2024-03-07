<?php
session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "stokbarang");


//Menambah barang baru
if (isset($_POST['addnewbarang'])) {
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "insert into stock (namabarang, deskripsi, stock) values('$namabarang', '$deskripsi', '$stock')");
    if ($addtotable) {
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
};

//Menambah barang masuk
if (isset($_POST['barangmasuk'])) {
    $idbarang = $_POST['barangnya'];
    $qty = $_POST['qty'];
    $keterangan = $_POST['keterangan'];

    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang= $idbarang");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "insert into masuk (idbarang, qty, keterangan) values('$idbarang', '$qty', '$keterangan')");

    if ($addtomasuk & $updatestockmasuk) {
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
};


//Menambah barang keluar
if (isset($_POST['barangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];
    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang= $barangnya");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;


    $addtokeluar = mysqli_query($conn, "insert into keluar (idbarang, penerima, qty) values($barangnya, '$penerima', $qty)");
    if ($addtokeluar & $updatestockkeluar) {
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    }
}


//update info barang
if (isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];;
    $update = mysqli_query($conn, "update stock set namabarang ='$namabarang', deskripsi='$deskripsi', stock='$stock' where idbarang = $idb");
    if ($update) {
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}


//menghapus barang dari stock
if (isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if ($hapus) {
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}


//mengubah data barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang= $idm");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk= $idm");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg - $selisih;
        $kuranginstocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang= $idb");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty' keterangan='$keterangan' where idmasuk= $idm ");
        if ($kuranginstocknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg + $selisih;
        $kuranginstocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang=idb");
        $updatenya = mysqli_query($conn, "updatemasuk set qty='$qty' keterangan='$deskripsi' where idmasuk= $idm");
        if ($kuranginstocknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    }
}


//Menghapus barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idm = $_POST['idm'];
    $qty = $_POST['qty'];


    $getdatastock = mysqli_query($conn, "select * from stock where idbarang= $idm");
    $data = mysqli_fetch_array($getdatastock);

    $stock = $data['stock'];

    $selisih = $stock - $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang= $idm");
    $updatedata = mysqli_query($conn, "delete from masuk where idmasuk= $idm");

    if ($update && $hapussdata) {
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }
}


//Mengubah data barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];


    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar=$idk");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];
    $lihatstock = mysqli_query($conn, "select * from stock where idbarang=$qtynya[idbarang]");

    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg - $selisih;
        $kuranginstocknya = mysqli_query($conn, "select * from keluar where idkeluar=$idk");
        $updatenya = mysqli_query($conn, "update keluar set qty=$qty, penerima='$penerima' where idkeluar=$idk");

        if ($kuranginstocknya && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg + $selisih;
        $kuranginstocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang=$qtynya[idbarang]");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar=$idk");
        if ($kuranginstocknya && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    }
}


//Menghapus barang keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $qty = $_POST['qty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang= $idk");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock - $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang= idk");
    $updatedata = mysqli_query($conn, "delete from keluar where idkeluar= $idk");

    if ($update && $hapussdata) {
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
}
