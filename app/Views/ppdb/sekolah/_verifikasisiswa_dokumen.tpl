<tr id="row-dokumen-{$docid}" {if $visible_tag|default: FALSE}tcg-visible-tag='{$visible_tag}'{/if}>
    <td style="width:45%;"><b>{$label}</b></td>
    <td>:</td>
    <td style="width: 50%;">
        {if !($flag_upload_dokumen)}
        Dicocokkan di sekolah tujuan
        {else}
            <img id="dokumen-{$docid}" class="img-view-thumbnail" tcg-doc-id="{$docid}" 
                    src="{$dok.web_path}" 
                    img-path="{$dok.thumbnail_path}" 
                    img-id="{$dok.dokumen_id}" 
                    img-title="{$label}"
                    style="display:none; "/>  
            <span>
            <input type="file" class="upload-file" tcg-doc-id="{$docid}" id="unggah-profil-{$docid}" hidden/>
            <label for="unggah-profil-{$docid}" class="btn btn-primary" tcg-tag='{$tag}' tcg-input-type='label'>Unggah</label>
            </span>
            <div id="msg-dokumen-{$docid}" class="box-red" style="margin-top: 5px; padding-left: 5px; padding-right: 5px; display: none;"></div>
        {/if}
    </td>
</tr>