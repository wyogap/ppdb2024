<div class="container">
    <div class="title-call-action another text-white text-center">
        Waktu Pelaksanaan PPDB
    </div>
    <div class="row text-center">
        <table class="table">
            <tbody>
     <?php foreach($tahapan_pelaksanaan->getResult() as $row):
        if ($row->tahapan_id==0 || $row->tahapan_id==99) {
            continue;
        }
    ?>
        
               <tr class="h4" style="padding-top: 10px; padding-bottom: 10px">
                    <td><?php echo $row->tahapan; ?></td>
                    <?php if ($row->tanggal_mulai == $row->tanggal_selesai) { ?>
                    <td><?php echo strftime("%d %B %Y %H:%M", strtotime($row->tanggal_mulai)); ?></td>
                    <?php } else { ?>
                    <td><?php echo strftime("%d %B %Y %H:%M", strtotime($row->tanggal_mulai)) . " s.d. " . strftime("%d %B %Y %H:%M", strtotime($row->tanggal_selesai)); ?></td>
                    <?php } ?>
                </tr>
    <?php endforeach;?>
                <tr><td></td><td></td></tr>
            </tbody>
        </table>
    </div>
</div>