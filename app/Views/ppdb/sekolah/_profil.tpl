<script type="text/javascript">
    user_id = "{$profil.user_id}";

    $(document).ready(function() {

    });

    function simpan_profil() {
        let json = {};

        json['user_id'] = user_id;
        json['nama'] = $("#nama").val().trim();
        json['email'] = $("#email").val().trim();
        json['handphone'] = $("#handphone").val().trim();

        $.ajax({
            type: 'POST',
            url: "{$site_url}ppdb/sekolah/profil/simpan",
            dataType: 'json',
            data: json,
            async: true,
            cache: false,
            //if we use formData, set processData = false. if we use json, set processData = true!
            //contentType: true,
            //processData: true,      
            timeout: 60000,
            success: function(json) {
                if (json.error !== undefined && json.error != "" && json.error != null) {
                    toastr.error("Tidak berhasil menyimpan perubahan profil. " + json.error);
                    return;
                }
                toastr.success("Perubahan profil berhasil disimpan.");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("Tidak berhasil menyimpan perubahan profil. " + textStatus);
                return;
            }
        });

    }
</script>