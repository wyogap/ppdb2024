
<div class="col-12">
    <div class="form-input-content text-center error-page">
        <h1 class="error-text fw-bold">404</h1>
        <h4><i class="fa fa-exclamation-triangle text-warning"></i> Halaman yang anda cari tidak ditemukan!</h4>
        <p>Periksa kembali alamat yang anda ketik atau halaman tersebut sudah pindah ke alamat lain.</p>
        <div>
            {if $user_id|default: FALSE}
            <a class="btn btn-primary" href="{$site_url}auth/login">Kembali</a>
            {else} 
            <a class="btn btn-primary" href="{$site_url}">Kembali</a>
            {/if}
        </div>
    </div>
</div>
