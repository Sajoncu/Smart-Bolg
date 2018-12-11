@extends('layouts.backend.app')
@section('title', 'Add Tags')

@push('css')
    <link href="{{asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@endpush

@section('content')
        <div class="container-fluid">

            <!-- Vertical Layout | With Floating Label -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Edit Tag
                            </h2>
                        </div>
                        <div class="body">
                            <form action="{{route('admin.tag.update', $tag->id)}}" method="post">
                                @csrf
								@method('PUT')
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" id="name" class="form-control" value="{{ $tag->name }}" name="name">
                                        <label class="form-label">Tag Name</label>
                                    </div>
                                </div>

                                <br>
                                <a href="{{route('admin.tag.index')}}" class="btn btn-danger m-t-15 waves-effect">BACK</a>
                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">UPDATE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Vertical Layout | With Floating Label -->

        </div>
@endsection

@push('js')

@endpush