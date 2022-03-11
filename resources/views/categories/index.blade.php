@extends('layouts.backend')
@section('content')
    @can('tb_access')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('categories.create') }}">
                    Add Merchandise Category
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Merchandise Categories
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable datatable-category">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Merchandise Category Name
                                </th>
                                @can('tb_access')
                                    <th>
                                        Actions
                                    </th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $key => $category)
                                <tr data-entry-id="{{ $category->id }}">
                                    <td>

                                    </td>
                                    <td>
                                        {{ $category->id ?? '' }}
                                    </td>
                                    <td>
                                        {{ $category->title ?? '' }}
                                    </td>

                                    @can('tb_access')
                                        <td>
                                            <a href="{{ route('categories.edit', [$category->id]) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                            <a href="{{ route('categories.destroyCategory', [$category->id]) }}"
                                                class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                                        </td>
                                    @endcan

                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="4">No Merchandise Categories</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan

@endsection
