
<div class="col-12">
    <div class="form-input-content text-center error-page">
        <h1 class="error-text  fw-bold">403</h1>
        <h4><i class="fa fa-times-circle text-danger"></i> Terlarang!</h4>
        <p>Anda tidak mempunyai hak akses kepada alamat ini.</p>
        <div>
            {if $user_id|default: FALSE}
            <a class="btn btn-primary" href="{$site_url}auth/login">Kembali</a>
            {else} 
            <a class="btn btn-primary" href="{$site_url}">Kembali</a>
            {/if}
        </div>
    </div>
</div>
