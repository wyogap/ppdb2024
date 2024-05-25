<script type="text/javascript">
    
    $(document).ready(function() {
        $.extend( $.fn.dataTable.defaults, { 
            responsive: true,
			"language": {
				"processing":   "Sedang proses...",
				"lengthMenu":   "Tampilan _MENU_ baris",
				"zeroRecords":  "Tidak ditemukan data yang sesuai",
				"info":         "Tampilan _START_ - _END_ dari _TOTAL_ baris",
				"infoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
				"infoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
				"infoPostFix":  "",
				"loadingRecords": "Loading...",
				"emptyTable":   "Tidak ditemukan data yang sesuai",
				"search":       "Cari:",
				"url":          "",
				"paginate": {
                    "first":    "Awal",
                    "previous": "Balik",
                    "next":     "Lanjut",
                    "last":     "Akhir"
				},
				aria: {
                    sortAscending:  ": klik untuk mengurutkan dari bawah ke atas",
                    sortDescending: ": klik untuk mengurutkan dari atas ke bawah"
				}
			},	
        } );

        $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
            $.fn.dataTable.tables( { visible: true, api: true } ).columns.adjust().responsive.recalc();
        } );

        $('#tnegeri').dataTable({
            responsive: {
                details: {
                    renderer: function ( api, rowIdx, columns ) {
                        var data = $.map( columns, function ( col, i ) {
                            if (!col.hidden) { return ''; }

                            let data=api.row(rowIdx).data();
                            let className = '';
                            let skor=0;
                            if (col.columnIndex==4) {
                                skor = data['skor_kemampuan_literasi']
                            }
                            else if (col.columnIndex==6) {
                                skor = data['skor_kemampuan_numerasi']
                            }
                            else if (col.columnIndex==8) {
                                skor = data['skor_karakter']
                            }
                            else if (col.columnIndex==10) {
                                skor = data['skor_iklim_keamanan']
                            }
                            else if (col.columnIndex==12) {
                                skor = data['skor_iklim_kebhinekaan']
                            }

                            if (skor==1) {
                                className='skor-merah';
                            } else if (skor==2) {
                                className='skor-orange';
                            } else if (skor==3) {
                                className='skor-hijau';
                            } else if (skor==4) {
                                className='skor-biru';
                            }

                            let row =
                                '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                    '<td>'+col.title+':'+'</td> '+
                                    '<td class="' +className+ '">&nbsp;'+col.data+'&nbsp;</td>'+
                                '</tr>';
                            
                            return row;
                        } ).join('');
    
                        return data ?
                            $('<table/>').append( data ) :
                            false;
                    }
                }
            },
            "pageLength": 25,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            "paging": true,
            "pagingType": "numbers",
            "dom": 'Bfrtpil',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: 'Ekspor',
                    className: 'btn-sm btn-primary',
                    exportOptions: {
                        orthogonal: "export",
                        modifier: {
                            //selected: true
                        },
                    },
                },
            ],
            processing: true,
            ajax: "{site_url()}home/rapormutu?json=1",
            columns: [
                { data: "nilai_sekolah_id", className: 'dt-body-right' },
                { data: "npsn", className: 'editable dt-body-center' },
                { data: "nama", className: 'editable dt-body-left', width: "40%" },
                { data: "skor_akhir", className: 'editable dt-body-center', width: "20%" },
                { data: "kemampuan_literasi", className: 'editable dt-body-center', 
                    width: "20%",
                },
                { data: "skor_kemampuan_literasi", className: 'editable dt-body-center', 
                    width: "20%", 
                },
                { data: "kemampuan_numerasi", className: 'editable dt-body-center', 
                    width: "20%",
                },
                { data: "skor_kemampuan_numerasi", className: 'editable dt-body-center', 
                    width: "20%" 
                },
                { data: "karakter", className: 'editable dt-body-center', 
                    width: "20%",
                },
                { data: "skor_karakter", className: 'editable dt-body-center', 
                    width: "20%" 
                },
                { data: "iklim_keamanan", className: 'editable dt-body-center', 
                    width: "20%",
                },
                { data: "skor_iklim_keamanan", className: 'editable dt-body-center', 
                    width: "20%" 
                },
                { data: "iklim_kebhinekaan", className: 'editable dt-body-center', 
                    width: "20%",
                },
                { data: "skor_iklim_kebhinekaan", className: 'editable dt-body-center', 
                    width: "20%" 
                },
            ],
            order: [ 0, 'asc' ],
            'rowCallback': function(row, data, index){
                skor = parseInt(data['skor_kemampuan_literasi']);
                if (skor==1) {
                    $(row).find('td:eq(4)').addClass('skor-merah');
                } else if (skor==2) {
                    $(row).find('td:eq(4)').addClass('skor-orange');
                } else if (skor==3) {
                    $(row).find('td:eq(4)').addClass('skor-hijau');
                } else if (skor==4) {
                    $(row).find('td:eq(4)').addClass('skor-biru');
                }
                skor = parseInt(data['skor_kemampuan_numerasi']);
                if (skor==1) {
                    $(row).find('td:eq(6)').addClass('skor-merah');
                } else if (skor==2) {
                    $(row).find('td:eq(6)').addClass('skor-orange');
                } else if (skor==3) {
                    $(row).find('td:eq(6)').addClass('skor-hijau');
                } else if (skor==4) {
                    $(row).find('td:eq(6)').addClass('skor-biru');
                }
                skor = parseInt(data['skor_karakter']);
                if (skor==1) {
                    $(row).find('td:eq(8)').addClass('skor-merah');
                } else if (skor==2) {
                    $(row).find('td:eq(8)').addClass('skor-orange');
                } else if (skor==3) {
                    $(row).find('td:eq(8)').addClass('skor-hijau');
                } else if (skor==4) {
                    $(row).find('td:eq(8)').addClass('skor-biru');
                }
                skor = parseInt(data['skor_iklim_keamanan']);
                if (skor==1) {
                    $(row).find('td:eq(10)').addClass('skor-merah');
                } else if (skor==2) {
                    $(row).find('td:eq(10)').addClass('skor-orange');
                } else if (skor==3) {
                    $(row).find('td:eq(10)').addClass('skor-hijau');
                } else if (skor==4) {
                    $(row).find('td:eq(10)').addClass('skor-biru');
                }
                skor = parseInt(data['skor_iklim_kebhinekaan']);
                if (skor==1) {
                    $(row).find('td:eq(12)').addClass('skor-merah');
                } else if (skor==2) {
                    $(row).find('td:eq(12)').addClass('skor-orange');
                } else if (skor==3) {
                    $(row).find('td:eq(12)').addClass('skor-hijau');
                } else if (skor==4) {
                    $(row).find('td:eq(12)').addClass('skor-biru');
                }
            }
        });

    });

</script>