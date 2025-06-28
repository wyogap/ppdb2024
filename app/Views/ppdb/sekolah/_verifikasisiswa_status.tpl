<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
    <tr id="{$tag}-verifikasi-row">
        <td class="verifikasi-sistem" colspan="1" style="display: none;">
            <b>Data sesuai sistem. Untuk perbaikan data, silahkan hubungi Panitia {$app_short_name} di dinas terkait. </b>
        </td>
        <td class="verifikasi-manual" colspan="1">
            <b>Apakah data {$tag_label} di atas sudah benar? </b>
            <select class="form-control input-default status-verifikasi" 
                    tcg-tag='{$tag}'
                    tcg-field='verifikasi_{$tag}' tcg-field-type='status' tcg-field-submit=1>
                <option value="0">Belum Diverifikasi</option>
                <option value="1">SUDAH Benar</option>
                <option value="2">BELUM Benar</option>
                <option value="3">Perbaikan Data</option>
            </select>
            <button class="btn btn-primary btn-kembalikan" style="display: none;" tcg-tag='{$tag}'>Data Awal</button>
            <button class="btn btn-primary btn-batal" style="display: none;" tcg-tag='{$tag}'>Batalkan</button>
            <button class="btn btn-danger btn-simpan" style="display: none;" tcg-tag='{$tag}'>Konfirmasi</button>
        </td>
    </tr>
    <tr id="{$tag}-catatan-row" class="catatan" tcg-tag='{$tag}'>
        <td colspan="1">
            <span  id="{$tag}-error-msg">
                <textarea class="form-control catatan" placeholder="Catatan verifikasi/perbaikan data" tcg-tag='{$tag}'
                tcg-field='catatan_{$tag}' tcg-field-type='note' tcg-field-submit=1></textarea>
            </span>
        </td>
    </tr>
</table>