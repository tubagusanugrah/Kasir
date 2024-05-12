<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="namaProduk">Nama Produk:</label>
                <input type="text" class="form-control" id="namaProduk" name="namaProduk" required>
            </div>
            <div class="form-group">
                <label for="hargaProduk">Harga Produk:</label>
                <input type="number" class="form-control" id="hargaProduk" name="hargaProduk" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="total">Total :</label>
                <input type="number" class="form-control" id="total" name="total" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambahkan ke Keranjang</button>
        </form>

        <?php
        class Produk {
            private $nama;
            private $harga;
        
            public function __construct($nama, $harga) {
                $this->nama = $nama;
                $this->harga = $harga;
            }
        
            public function getNama() {
                return $this->nama;
            }
        
            public function getHarga() {
                return $this->harga;
            }
        }
        
        class ItemKeranjang {
            private $produk;
            private $total;
        
            public function __construct($produk, $total) {
                $this->produk = $produk;
                $this->total = $total;
            }
        
            public function getProduk() {
                return $this->produk;
            }
        
            public function getTotal() {
                return $this->total;
            }
        
            public function getTotalHarga() {
                return $this->produk->getHarga() * $this->total;
            }
        }
        
        class Keranjang {
            private $item = [];
        
            public function tambahItem($produk, $total) {
                $this->item[] = new ItemKeranjang($produk, $total);
            }
        
            public function getItem() {
                return $this->item;
            }
        
            public function getTotal() {
                $total = 0;
                foreach ($this->item as $item) {
                    $total += $item->getTotalHarga();
                }
                return $total;
            }
        }
        
        class Kasir {
            private $keranjang;
        
            public function __construct($keranjang) {
                $this->keranjang = $keranjang;
            }
        
            public function bayar() {
                $total = $this->keranjang->getTotal();
                echo "<p>Total yang harus dibayar adalah: Rp" . number_format($total, 2) . "</p>";
                $this->cetakStruk();
            }
        
            public function cetakStruk() {
                $item = $this->keranjang->getItem();
                echo "<h2>STRUK PEMBELIAN</h2>";
                foreach ($item as $itemDetail) {
                    echo "<p>" . $itemDetail->getProduk()->getNama() . " - ";
                    echo $itemDetail->getTotal() . " x ";
                    echo "Rp" . number_format($itemDetail->getProduk()->getHarga(), 2) . " = ";
                    echo "Rp" . number_format($itemDetail->getTotalHarga(), 2) . "</p>";
                }
                echo "<p>Total Pembayaran: Rp" . number_format($this->keranjang->getTotal(), 2) . "</p>";
                echo "<p>Terima Kasih!</p>";
            }
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $namaProduk = $_POST['namaProduk'];
            $hargaProduk = floatval($_POST['hargaProduk']);
            $total = intval($_POST['total']);
        
            $produk = new Produk($namaProduk, $hargaProduk);
            $keranjang = new Keranjang();
            $keranjang->tambahItem($produk, $total);
        
            $kasir = new Kasir($keranjang);
            $kasir->bayar();
        }
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>