@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --}}
@endsection
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            @if (Gate::allows('admin_access'))
                <a class="btn btn-success" href="{{ route('import.agency') }}">
                    Get Agency Import Sample
                </a>
            @endif
            @if (Gate::allows('admin_access')||Gate::allows('tb_access'))
            <a class="btn btn-primary" href="{{ route('import.teamleader') }}">
                Get Teamleaders Import Sample
            </a>
            @endif
            @if (Gate::allows('admin_access')||Gate::allows('tb_access')||Gate::allows('team_leader_access'))
                <a class="btn btn-warning" href="{{ route('import.bas') }}">
                    Get BrandAmbassadors Import Sample
                </a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-12">
          @if (session('error'))
            <div class="alert alert-danger" style="color: #fff; padding: 1em;background: red;">
                {{ session('error') }}
            </div>
          @endif
          @if (session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
          @endif
        </div>
      </div>
    <div class="row">
        <div class="col-12 mb-4">
          <div class="card">
            <div class="card-header ">
              <h3 class="card-title">Upload Data</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">

              <form role="form" class="import" method="POST" action="{{route('import.submit')}}"  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <h4 for="exampleInputFile">Choose User Role</h4>
                              <div class="input-group">
                                <select name="role_id" id="" class="form-control"
                                    style="border: 1px solid; border-radius:10px;" required>
                                    <option selected disabled>--- Select Roles ---</option>
                                    @forelse ($roles as $id => $county)
                                        <option value="{{ $id }}">{{ strtoupper($county) }}</option>
                                    @empty
                                        <option disabled> No Roles Added Yet</option>
                                    @endforelse
                                </select>

                                @error('county_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <h4 for="exampleInputFile">File input</h4>
                            <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file" required="">
                                <label class="custom-file-label" for="file">Choose file</label>
                            </div>
                            {{-- <div class="input-group-append">
                                <span class="input-group-text" id="">Upload</span>
                            </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" style="width:100%">Import Data</button>
                </div>
              </form><br>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex ">
                <h4 class="card-title">Imported User Data</h4>
                <p class="text-right">{{$entryCount}} Uploaded Today</p>

              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="table1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                  </tr>
                  </thead>
                  <tbody>

                        @forelse($users as $key=>$user)
                          <tr>
                            <td>{{++$key}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->phone}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{strtoupper($user->roles->title)??'No Assigned Roles'}}</td>
                            <td>{{$user->created_at}}</td>
                          </tr>
                          @empty
                          <tr>

                            <td colspan="6" class="text-center">No Users Imported Today</td>
                          </tr>
                        @endforelse
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"
        integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#UserTable').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Merchandise_list',
                        exportOptions: {
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, ':visible']
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'merchandise_list',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
@endsection
