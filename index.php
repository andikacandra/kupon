<?php 
    // Aplikasi Kupon, start project 3 sep 2021 (02:45 s.d 04:00) sudah termasuk testing dan validasi hasil secara manual melalui excel
    // note : sourcode ini dibuat berdasarkan soal, belum termasuk jika ada pengkodisian khusus atau berada di luar soal.

    $setKupon = 10000;

    $tableHadiah[0] = ['count'  => 50, 'nilai' => 100000];
    $tableHadiah[1] = ['count'  => 100, 'nilai' => 50000];
    $tableHadiah[2] = ['count'  => 250, 'nilai' => 20000];
    $tableHadiah[3] = ['count'  => 500, 'nilai' => 10000];
    $tableHadiah[4] = ['count'  => 1000, 'nilai' => 5000];

    $perBox = 1000;
    $countBox = $setKupon/ $perBox;
    
    // generate kupon
    $kupon = [];
    for ($i=0; $i < $countBox; $i++) { 
        for ($j=$i*$perBox; $j < (($i+1)*$perBox < $setKupon ? ($i+1)*$perBox : $setKupon); $j++) { 
            $kupon[] = [
                'no' => str_pad($j+1, strlen($setKupon), "0", STR_PAD_LEFT),
                'box' => $i+1,
            ];
        }
    }

    // setting hadiah
    $tempIndex = [];
    for ($i=0; $i < $countBox; $i++) {
        foreach($tableHadiah as $h){
            for ($j=0; $j < $h['count']/$countBox; $j++) { 
                $index = unixIndex($tempIndex, $i*$perBox, ((($i+1)*$perBox)-1));
                $tempIndex[] = $index;

                $kupon[$index]['hadiah'] = $h['nilai'];
            }
        }
    }

    function unixIndex($tempIndex, $start, $finish){
        $randIndex = rand($start, $finish);
                
        if(in_array($randIndex, $tempIndex)){
            return unixIndex($tempIndex, $start, $finish);
        }else{
            return $randIndex;
        }
    }

    // untuk generate ke excel, nantinya akan dilakukan pengecekan dari excel
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=kupon.xls");
?>


<table border="1" style="width: 100%;">
    <tr>
        <?php for ($i=0; $i < $countBox; $i++) { ?>
            <th colspan="2" style="white-space: nowrap;">Box-<?php echo "".($i+1); ?></th>
        <?php } ?>

    </tr>

    <tr>
        <?php for ($i=0; $i < $countBox; $i++) { ?>
            <th style="white-space: nowrap;">No Kupon</th>
            <th style="white-space: nowrap;">Hadiah</th>
        <?php } ?>
    </tr>
    
    
    <?php 
        for ($i= 0; $i < $perBox; $i++) { 
            echo "<tr>";
                for ($j=0; $j < $countBox; $j++) {
                    echo "<td>'".$kupon[$i+($j*$perBox)]['no']."</td> "; // saya tambah petik agar pada excel 00001 tidak berubah menjadi 1, namun pada data sebenarnya tetap 00001
                    echo "<td>".($kupon[$i+($j*$perBox)]['hadiah'] ? $kupon[$i+($j*$perBox)]['hadiah'] : "Anda belum beruntung")."</td>";   
                }
            echo "</tr>";
        }
    ?>
</table>