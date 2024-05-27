<table class="table table-striped" style="margin-bottom: 0px !important; width: 100%">
    <tr id="{$tag}-konfirmasi-row">
        <td colspan="1">
            <b>Apakah data {$tag_label} di atas sudah benar? </b>
            <select class="form-control input-default status-verifikasi" 
                tcg-tag='{$tag}'
                tcg-field='verifikasi_{$tag}' tcg-field-type='status' tcg-field-submit=1>
                <option value="0">Belum Diverifikasi</option>
            <option value="1">SUDAH Benar</option>
            <option value="2">BELUM Benar</option>
            </select>
            <button class="btn btn-secondary btn-perbaikan" tcg-tag='{$tag}'>Perbaiki Data</button>
            <button class="btn btn-danger btn-simpan" style="display: none;" tcg-tag='{$tag}'>Simpan Data</button>
            <button class="btn btn-primary btn-batal" style="display: none;" tcg-tag='{$tag}'>Batalkan</button>
        </td>
    </tr>
    <tr id="{$tag}-catatan-row" class="catatan" tcg-tag='{$tag}'>
        <td colspan="1">
            <span  id="{$tag}-error-msg">
                <textarea class="form-control catatan" placeholder="Catatan verifikasi" tcg-tag='{$tag}'
                tcg-field='catatan_{$tag}' tcg-field-type='note' tcg-field-submit=1></textarea>
            </span>
        </td>
    </tr>
</table>