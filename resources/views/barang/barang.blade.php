@extends('template.main')
@section('title', 'Barang')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="text-right">
                                    <a href="/barang/create" class="bg-blue-500 text-white font-bold px-2 py-1 rounded"><i
                                            class="fa-solid fa-plus"></i> Add
                                        Barang</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-striped table-bordered table-hover text-left"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Gambar</th>
                                            <th>Nama</th>
                                            <th>Category</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Tags</th>
                                            <th>Note</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barang as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><img src="/images/{{ $data->image }}" width="100px"></td>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->category }}</td>
                                                <td>{{ $data->stock }}</td>
                                                <td>Rp. {{ number_format($data->price, 0) }}</td>
                                                <td>
                                                    @foreach ($data->tags as $tag)
                                                        <span class="badge badge-info">{{ $tag->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>{{ $data->note }}</td>
                                                <td>
                                                    <form class="d-inline" action="/barang/{{ $data->id_barang }}/edit"
                                                        method="GET">
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm text-xs px-2 py-1">
                                                            <i class="fa-solid fa-pen"></i> Edit
                                                        </button>
                                                    </form>
                                                    <form class="d-inline" action="/barang/{{ $data->id_barang }}"
                                                        method="GET">
                                                        <button type="submit"
                                                            class="btn btn-info btn-sm text-xs px-2 py-1">
                                                            <i class="fa fa-eye"></i> Show
                                                        </button>
                                                    </form>
                                                    <form class="d-inline" action="/barang/{{ $data->id_barang }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm text-xs px-2 py-1" id="btn-delete">
                                                            <i class="fa-solid fa-trash-can"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
        </div>
    </div>

@endsection
