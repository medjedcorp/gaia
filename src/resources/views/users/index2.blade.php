@extends("layouts.app")

@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<div class="breadcrumb-title pe-3">Users</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">ユーザー一覧</li>
					</ol>
				</nav>
			</div>
			<div class="ms-auto">
				<div class="btn-group">
					<button type="button" class="btn btn-primary">Settings</button>
					<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
					</button>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item" href="javascript:;">Action</a>
						<a class="dropdown-item" href="javascript:;">Another action</a>
						<a class="dropdown-item" href="javascript:;">Something else here</a>
						<div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
					</div>
				</div>
			</div>
		</div>
		<!--end breadcrumb-->
		<div class="row">
			<div class="col-xl-9 mx-auto">
				<h6 class="mb-0 text-uppercase">ユーザー一覧(管理者限定)</h6>
				<hr />
				<div class="card">
					<div class="card-body">
						<table class="table mb-0 table-hover">
							<thead>
								<tr>
									<th scope="col">Id</th>
									<th scope="col">Name</th>
									<th scope="col">Email</th>
									<th scope="col">Tel</th>
									<th scope="col">Status</th>
								</tr>
							</thead>
							<tbody>
								@if(count($users) > 0)
								@foreach($users as $user)
								<tr>
									<th scope="row">{{$user->id}}</th>
									<td>{{$user->name}}</td>
									<td>{{$user->email}}</td>
									<td>{{$user->tel}}</td>
									@if($user->accepted == 1)
									<td>
										<button type="button" class="btn btn-primary px-3 pr-1"><i class="fadeIn animated bx bx-happy-beaming"></i>承認済</button>
										<button type="button" class="btn btn-outline-danger px-3"><i class="fadeIn animated bx bx-tired"></i>非承認</button>
									</td>
									@else
									<td>
										<button type="button" class="btn btn-outline-primary px-3 pr-1"><i class="fadeIn animated bx bx-happy-beaming"></i>承認済</button>
										<button type="button" class="btn btn-danger px-3"><i class="fadeIn animated bx bx-tired"></i>非承認</button>
									</td>
									@endif
								</tr>
								@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!--end row-->
	</div>
</div>
<!--end page wrapper -->
@endsection