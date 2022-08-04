@extends('layouts/contentNavbarLayout')

@section('title', 'Annonces - List')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')


<!-- Hoverable Table rows -->



<div class="card">
    <div class="container">
        <div class="row row-cols-2">
            <div class="col-sm-3 col-md-3">


                <h5 class="card-header">
                    Annonces
                </h5>

                <!-- @error('image')
                <div class="alert alert-danger mt-1 mb-1">{{Session::get('success')}}</div>
                @enderror -->


            </div>
            <div class="col-sm-7 col-md-7" ,>

                @if(Session::has('success'))
                <div style="margin-top: 1em;">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{Session::get('success')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>

                </div>
                @endif
                <!-- @error('image')
                <div class="alert alert-danger mt-1 mb-1">{{Session::get('success')}}</div>
                @enderror -->


            </div>
            <div class="col-2 col-md-2"><button type="button" class="btn btn-primary" style="margin-top: 1em;" data-bs-toggle="modal" data-bs-target="#basicModal"><span class="tf-icons bx bx-plus"></span>Add Annonce</button></div>
            <!-- Modal -->
            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form class="modal-content" action="{{route('createAnnonce')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Add New Annonce</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" id="description" name="description" class="form-control" placeholder="Enter description" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-0">
                                    <label for="picture" class="form-label">Picture</label>
                                    <input type="file" id="image" name="image" class="form-control" required>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="formSubmit">Create</button>
                        </div>

                    </form>
                </div>
            </div>



        </div>
    </div>

    <div class="table-responsive text-nowrap">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Picture</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <style>
                .zoom {

                    /* Animation */
                    transition: transform .2s;
                    width: 1em;


                }

                .zoom:hover {
                    transform: scale(5);
                    /* overflow: visible; */
                    /* position: absolute; */
                    overflow: visible;
                    height: auto;

                    height: auto;
                    /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
                }
            </style>
            <tbody class="table-border-bottom-0">
                @foreach ($annonces as $annonce)
                <tr>
                    <td style="max-width: 10em;"><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$annonce->description}}</strong></td>

                    <td style="max-width: 1em;">
                        <div class="zoom"><img style="height: 3em;width: 3em;border-radius: 10px;" src="http://127.0.0.1:8000/upload/pictures/{{$annonce->url}}">
                    </td>
                    <!-- <td style=" max-width: 7em;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">{{$annonce->address}}</td> -->
                    <td style="max-width: 10em;"><span class="badge bg-label-{{($annonce->status == 'active') ? 'primary' : 'danger'}}">{{$annonce->status}}</span></td>
                    <td style="max-width: 10em;">
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                        </div>
                    </td>

                    <!-- image modal -->
                    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <iframe height="350" src="http://127.0.0.1:8000/upload/pictures/1659175367.png"></iframe>
                            </div>
                        </div>
                    </div>

                </tr>
                @endforeach


            </tbody>
        </table>
    </div>
</div>
<!--/ Hoverable Table rows -->
@endsection