<script type="text/javascript" defer> 

{foreach $subtables as $subtbl}
var fkey_value_{$subtbl.crud.table_id} = '';
var fkey_label_{$subtbl.crud.table_id} = '';
var fkey_value_{$subtbl.crud.table_id}_2 = '';
var fkey_label_{$subtbl.crud.table_id}_2 = '';
var fdata_{$subtbl.crud.table_id} = null;
var fkey_column_{$subtbl.crud.table_id} = '';
{/foreach}

$(document).ready(function() {

    {if empty($detail)}
        //use user-select event instead of select/deselect to avoid being triggerred because of API
        dt_{$tbl.table_id}.on('select.dt deselect.dt', function() {
            let data = dt_{$tbl.table_id}.rows({
                selected: true
            }).data();

            if (data.length == 0 || data.length > 1) {
                //on deselect all, clear subtables
                {foreach $subtables as $subtbl}
                dt_{$subtbl.crud.table_id}.clear().draw();
                fkey_value_{$subtbl.crud.table_id} = '';
                fkey_label_{$subtbl.crud.table_id} = "";
                fkey_value_{$subtbl.crud.table_id}_2 = '';
                fkey_label_{$subtbl.crud.table_id}_2 = "";
                fdata_{$subtbl.crud.table_id} = null;
                fkey_column_{$subtbl.crud.table_id} = "";
                //disable edit        
                $("#{$subtbl.crud.table_id}_wrapper .dt-action-buttons .dt-buttons").hide();
                {/foreach}
            } else {
                //on select, reload subtables
                {foreach $subtables as $subtbl}
                //master value
                fkey_value_{$subtbl.crud.table_id} = data[0]['{$subtbl.table_key_column}'];
                fkey_label_{$subtbl.crud.table_id} = data[0]['{$subtbl.table_label_column}'];
                {if $subtbl.table_key_column2}
                fkey_value_{$subtbl.crud.table_id}_2 = data[0]['{$subtbl.table_key_column2}'];
                {else}
                fkey_value_{$subtbl.crud.table_id}_2 = "";
                {/if}
                fdata_{$subtbl.crud.table_id} = data[0];
                fkey_column_{$subtbl.crud.table_id} = "{$subtbl.subtable_fkey_column}";
                dt_{$subtbl.crud.table_id}.ajax.url("{$subtbl.crud.ajax}?key=" +fkey_value_{$subtbl.crud.table_id}{if $subtbl.table_key_column2}+"&key2="+fkey_value_{$subtbl.crud.table_id}_2{/if});
                {if $subtbl.crud.editor}
                editor_{$subtbl.crud.table_id}.s.ajax = "{$subtbl.crud.ajax}?key=" +fkey_value_{$subtbl.crud.table_id}{if $subtbl.table_key_column2}+"&key2="+fkey_value_{$subtbl.crud.table_id}_2{/if};
                {/if}
                //reload retain paging
                dt_{$subtbl.crud.table_id}.ajax.reload(null, false);
                //enable edit (if necessary)
                $("#{$subtbl.crud.table_id}_wrapper .dt-action-buttons .dt-buttons").show();
                {/foreach}
            }

        });
    {/if}
});

</script>

{foreach $subtables as $subtbl}
    {include file="./_js-crud-table.tpl" tbl=$subtbl.crud fsubtable='1' fkey=$subtbl.subtable_fkey_column fkey2=$subtbl.subtable_fkey_column2 flabel=$subtbl.label}
{/foreach}

