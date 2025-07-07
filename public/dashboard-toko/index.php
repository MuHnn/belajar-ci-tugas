<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <?php 
    function curl(){ 
        $curl = curl_init(); 
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost:8080/api", // sesuaikan URL jika beda
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_CUSTOMREQUEST => "GET", 
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: random123678abcghi",
            ),
        ));
            
        $output = curl_exec($curl); 	
        curl_close($curl);      
        
        $data = json_decode($output);       
        return $data;
    } 
    ?>
    
    <div class="p-3 pb-md-4 mx-auto text-center">
        <h1 class="display-4 fw-normal text-body-emphasis">Dashboard - TOKO</h1>
        <p class="fs-5 text-body-secondary"><?= date("l, d-m-Y") ?> <span id="jam"></span>:<span id="menit"></span>:<span id="detik"></span></p>
    </div> 

    <hr>

    <div class="table-responsive card m-5 p-5">
        <table class="table text-center table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Alamat</th>
                    <th>Total Harga</th>
                    <th>Ongkir</th>
                    <th>Status</th>
                    <th>Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $send1 = curl();

                    if(!empty($send1)){
                        $hasil1 = $send1->results;
                        $i = 1; 

                        if(!empty($hasil1)){
                            foreach($hasil1 as $item1){ 
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $item1->username; ?></td>
                    <td><?= $item1->alamat; ?></td>
                    <td>
                        Rp <?= number_format($item1->total_harga); ?>
                        <div class="text-muted" style="font-size: 0.9em;">
                            (<?= $item1->jumlah_item ?? 0; ?> item)
                        </div>
                    </td>
                    <td>Rp <?= number_format($item1->ongkir); ?></td>
                    <td><?= $item1->status; ?></td>
                    <td><?= $item1->created_at; ?></td>
                </tr>
                <?php
                            } 
                        }
                    }
                ?> 
            </tbody>
        </table>
    </div> 

    <script>
        window.setTimeout("waktu()", 1000);
        function waktu() {
            var waktu = new Date();
            setTimeout("waktu()", 1000);
            document.getElementById("jam").innerHTML = waktu.getHours().toString().padStart(2, '0');
            document.getElementById("menit").innerHTML = waktu.getMinutes().toString().padStart(2, '0');
            document.getElementById("detik").innerHTML = waktu.getSeconds().toString().padStart(2, '0');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
