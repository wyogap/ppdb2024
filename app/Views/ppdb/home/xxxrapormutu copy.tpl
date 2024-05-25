<!DOCTYPE html>
<html>
    {include file='../header.tpl'}

    <style>
        .skor-merah {
            background-color: red;
            color: white;
            background-clip: content-box;
        }

        .skor-orange {
            background-color: orange;
            color: white;
            background-clip: content-box;
        }

        .skor-hijau {
            background-color: green;
            color: white;
            background-clip: content-box;
        }

        .skor-biru {
            background-color: blue;
            color: white;
            background-clip: content-box;
        }

    </style>

<?php 
	$this->load->helper('url');
?>

	<body class="hold-transition skin-black layout-top-nav">
		<div class="wrapper">
            {include file='../navigation.tpl'}
			<div class="content-wrapper">
				<div class="container">
					<section class="content-header">
						<!-- <div class="row"> -->
						<h1 class="text-white">
							<i class="glyphicon glyphicon-edit"></i> Rapor Mutu Sekolah</small>
						</h1>
					</section>
					<section class="content">
                        <div class="box box-solid">
                            <!-- <div class="box-header with-border">
                                <i class="glyphicon glyphicon-edit text-info"></i>
                                <h3 class="box-title text-info"><b>Kuota Sekolah</b></h3>
                            </div> -->
                            <div class="box-body">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table class="display" id="tnegeri" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <!-- <th class="text-center" data-priority="1"></th> -->
                                                    <th class="text-center" data-priority="1">#</th>
                                                    <th class="text-center">NPSN</th>
                                                    <th class="text-center" data-priority="2">Sekolah</th>
                                                    <th class="text-center" data-priority="3"><span class="text-nowrap">Skor Akhir</span><br>(A+B+C+((D+E)/2)</th>
                                                    <th class="text-center">Kemampuan Literasi</th>
                                                    <th class="text-center text-nowrap">Skor A</th>
                                                    <th class="text-center">Kemampuan Numerasi</th>
                                                    <th class="text-center text-nowrap">Skor B</th>
                                                    <th class="text-center">Karakter</th>
                                                    <th class="text-center text-nowrap">Skor C</th>
                                                    <th class="text-center">Iklim Keamanan</th>
                                                    <th class="text-center text-nowrap">Skor D</th>
                                                    <th class="text-center">Iklim Kebhinekaan</th>
                                                    <th class="text-center text-nowrap">Skor E</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

					</section>
				</div>
			</div>
			{include file='../footer.tpl'}
		</div>
	</body>

    <script type="text/javascript">
    
        $(document).ready(function() {
            $.extend( $.fn.dataTable.defaults, { responsive: true } );

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
                processing: true,
                "language": {
                    "sProcessing":   "Sedang proses...",
                    "sZeroRecords":  "Tidak ditemukan data yang sesuai",
                    "sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 baris",
                    "sInfoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
                    "sInfoPostFix":  "",
                    "sSearch":       "Cari:",
                    "sUrl":          ""
                },
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

</html>
