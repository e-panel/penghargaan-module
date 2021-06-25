@extends('core::page.features')
@section('inner-title', "$title - ")
@section('mPenghargaan', 'opened')

@section('css')
    @include('core::layouts.partials.datatables')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/lity/2.4.0/lity.min.css">
@endsection

@section('js') 
    <script src="https://cdn.enterwind.com/template/epanel/js/lib/datatables-net/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lity/2.4.0/lity.min.js"></script>
    <script>
        $(function() {
            $('#datatable').DataTable({
                order: [[ 3, "desc" ]], 
                processing: true,
                serverSide: true,
                ajax : '{!! request()->fullUrl() !!}?datatable=true',
                columns: [
                    { data: 'pilihan', name: 'pilihan', className: 'table-check' },
                    { data: 'icon', name: 'icon' },
                    { data: 'label', name: 'label' },
                    { data: 'tanggal', name: 'tanggal', className: 'table-date small' },
                    { data: 'aksi', name: 'aksi', className: 'tombol' }
                ],
                "fnDrawCallback": function( oSettings ) {
                    @include('core::layouts.components.callback')
                }
            });
        });
        @include('core::layouts.components.hapus')
    </script>
@endsection

@section('content')

    @if(!$data->count())

        @include('core::layouts.components.kosong', [
            'icon' => 'font-icon font-icon-award',
            'judul' => $title,
            'subjudul' => __('penghargaan::general.empty', ['message' => $title]), 
            'tambah' => route("$prefix.create")
        ])

    @else
        
        {!! Form::open(['method' => 'delete', 'route' => ["$prefix.destroy", 'hapus-all'], 'id' => 'submit-all']) !!}

            @include('core::layouts.components.top', [
                'judul' => $title,
                'subjudul' => __('penghargaan::general.desc'),
                'tambah' => route("$prefix.create"), 
                'hapus' => true
            ])

            <div class="card">
                <div class="card-block table-responsive">
                    <table id="datatable" class="display table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="table-check"></th>
                                <th width="5%"></th>
                                <th>{{ __('penghargaan::general.table.label') }}</th>
                                <th class="text-right">{{ __('penghargaan::general.table.created') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            
        {!! Form::close() !!}
    @endif
@endsection
