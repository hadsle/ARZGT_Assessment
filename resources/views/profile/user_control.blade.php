@extends('profile.profile-layout')
@section('profile')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>User Management Control</h3>
                </div>

            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    User Datatable
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Role Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                            <tr>
                                <td class="id">{{ ++$key }}</td>
                                <td class="name">{{ $item->name }}</td>>
                                <td class="email">{{ $item->email }}</td>
                                <td class="phone_number">{{ $item->phone_number }}</td>


                                <td class="role_name"><span class="badge bg-success">{{ $item->role }}</span></td>


                                <td class="text-center">
                                    <a href="{{ route('add') }}">
                                        <span class="badge bg-info">Add New</span>
                                    </a>
                                    <a href="{{ url('edit/'.$item->id) }}">
                                        <span class="badge bg-success">View Details</span>
                                    </a>
                                    <a href="{{ url('delete/'.$item->id) }}"
                                        onclick="return confirm('Are you sure to want to delete it?')"><span
                                            class="badge bg-danger">Delete</span></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection