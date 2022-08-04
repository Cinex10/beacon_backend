@extends('layouts/contentNavbarLayout')

@section('title', 'Voucher - List')

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
            <div class="col-sm-10 col-md-10">
                <h5 class="card-header">
                    Voucher
                </h5>
            </div>
            <div class="col-2 col-md-2"><button type="button" class="btn btn-primary" style="margin-top: 1em;" data-bs-toggle="modal" data-bs-target="#basicModal"><span class="tf-icons bx bx-plus"></span>Add Voucher</button></div>
            <!-- Modal -->
            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form class="modal-content" action="{{route('createVoucher')}}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Add New Voucher</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" id="description" name="description" class="form-control" placeholder="Enter Description" required>
                                </div>

                            </div>
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <div class="col mb-3">
                                        <label for="nbCustomers" class="form-label">N Customers</label>
                                        <input type="number" id="nbCustomers" name="nbCustomers" class="form-control" placeholder="Enter number of customers" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <!-- <label for="idBeacon" class="form-label">Beacon</label> -->

                                        <div class="mb-6 col-md-12">
                                            <label for="idBeacon" class="form-label">Beacon</label>
                                            <select id="idBeacon" class="select2 form-select" name="idBeacon">

                                                @foreach($beacons as $beacon)


                                                <option value="{{ $beacon->id }}">{{$beacon->id}} - {{$beacon->identifier}}</option>

                                                @endforeach
                                            </select>
                                        </div>



                                    </div>
                                </div>


                            </div>
                            <div class="col mb-3" style="margin-top: 1em;">
                                <label for="terms" class="form-label">Terms</label>
                                <textarea id="terms" name="terms" class="form-control" placeholder="Enter terms"></textarea>
                            </div>

                            <!-- <div class="row" style="margin-top: 1em;">
                                <div class="col mb-3">
                                    <label for="proximityUUID" class="form-label">Proximity UUID</label>
                                    <input type="text" id="proximityUUID" name="proximityUUID" class="form-control" placeholder="xxxx-xxx-xxx-xxxxx-xxx" required>
                                </div>

                            </div> -->

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
                    <th>N Customers</th>
                    <th>Beacon</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($vouchers as $voucher)
                <tr>
                    <td style="max-width: 10em;"><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{$voucher->description}}</strong></td>
                    <td style="max-width: 10em;">{{$voucher->nbCustomers}}</td>
                    <td style="max-width: 10em;">{{$voucher->beacon->identifier}}</td>

                    <td style="max-width: 10em;"><span class="badge bg-label-{{($voucher->status == 'available') ? 'success' : (($voucher->status == 'consumed') ? 'secondary' : (($voucher->status == 'deactive') ? 'danger' :'warning'))}}">{{$voucher->status}}</span></td>
                    <td style="max-width: 10em;">
                        <div class="dropdown" style="position: static;">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach

                <!-- <tr>
                    <td><i class="fab fa-bootstrap fa-lg text-primary me-3"></i> <strong>Bootstrap Project</strong></td>
                    <td>Jerry Milton</td>
                    <td>
                        <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Lilian Fuller">
                                <img src="{{asset('assets/img/avatars/5.png')}}" alt="Avatar" class="rounded-circle">
                            </li>
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                                <img src="{{asset('assets/img/avatars/6.png')}}" alt="Avatar" class="rounded-circle">
                            </li>
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Christina Parker">
                                <img src="{{asset('assets/img/avatars/7.png')}}" alt="Avatar" class="rounded-circle">
                            </li>
                        </ul>
                    </td>
                    <td><span class="badge bg-label-warning me-1">Pending</span></td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
             -->
            </tbody>
        </table>
    </div>
</div>
<!--/ Hoverable Table rows -->
@endsection