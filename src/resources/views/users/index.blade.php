@extends("layouts.app")

@section("style")
<link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<style>
	.pagination{
		display: flex !important;
	}
</style>
@endsection

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
						<li class="breadcrumb-item active" aria-current="page">Users Index</li>
					</ol>
				</nav>
			</div>
			{{-- <div class="ms-auto">
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
			</div> --}}
		</div>
		<!--end breadcrumb-->
		<h6 class="mb-0 text-uppercase"><i class="lni lni-users mr-1"></i> ユーザ一覧(管理者限定)</h6>
		<hr />
		<div class="card">
			<div class="card-body">
				@include('partials.success')
				@include('partials.danger')
				@include('partials.warning')

				{{-- スマホ用start --}}
				@if($isMobile)
				<form action="{{ route('users.index') }}" method="GET">
					@csrf
					@method('GET')
					<div class="input-group mb-3">
						<input type="text" name="keyword" class="form-control" placeholder="名前・TEL・メールアドレスで検索" aria-label="search" aria-describedby="search-button">
						<button class="btn btn-outline-secondary" type="submit" id="search-button"><i class="fadeIn animated bx bx-search"></i></button>
					</div>
				</form>
				@isset($keyword)
				<h5>■{{$keyword}}の結果一覧</h5>
				@endisset
				<div id="scroll">
					<ul class="list-group">
						@foreach($users as $customer)
						<li class="list-group-item list-group-item-action">
							<div class="d-flex w-100 justify-content-between">
								<h5 class="mb-1">{{$customer->name}}</h5>
								<small class="text-muted">{{$customer->created_at}}</small>
							</div>
							<p class="mb-1 d-flex align-items-center" style="word-break: break-all;">〒{{$customer->postcode}}&nbsp;{{$customer->prefecture->name}}{{$customer->address}}</p>
							<p class="mb-1 d-flex align-items-center" style="word-break: break-all;"><i class="fadeIn animated bx bx-envelope"></i>&nbsp;{{$customer->email}}</p>
							<p class="mb-1 d-flex align-items-center" style="word-break: break-all;"><i class="fadeIn animated bx bx-phone"></i>&nbsp;{{$customer->tel}}</small>
							<div class="d-flex flex-row mb-1">
								@if($customer->accepted == 1)
								<form action="{{route('users.approval')}}" name="approvalform" method="POST" enctype="multipart/form-data">
									@csrf
									@method('post')
									<input type="hidden" name="id" value="{{$customer->id}}">
									<input type="hidden" name="sendflag" value="0">
									<div class="btn btn-sm btn-primary px-2" name="approval" value="1"><i class="fadeIn animated bx bx-happy-beaming"></i>承認済</div>
									<button class="btn btn-sm btn-outline-danger px-2 " name="approval" value="2"><i class="fadeIn animated bx bx-tired"></i>承認取消</button>
								</form>
								@elseif($customer->accepted == 0)
								<form action="{{route('users.approval')}}" name="approvalform" method="POST" enctype="multipart/form-data">
									@csrf
									@method('post')
									<input type="hidden" name="id" value="{{$customer->id}}">
									<button class="btn btn-sm btn-outline-primary px-2 btn1" name="approval" value="1"><i class="fadeIn animated bx bx-happy-beaming"></i>承認する</button>
									<button class="btn btn-sm btn-outline-danger px-2" name="approval" value="2"><i class="fadeIn animated bx bx-tired"></i>非承認</button>
								</form>
								@else
								<form action="{{route('users.approval')}}" name="approvalform" method="POST" enctype="multipart/form-data">
									@csrf
									@method('post')
									<input type="hidden" name="id" value="{{$customer->id}}">
									<button class="btn btn-sm btn-outline-primary px-2 btn1 " name="approval" value="1"><i class="fadeIn animated bx bx-happy-beaming"></i>承認する</button>
									<div class="btn btn-sm btn-danger px-2" name="approval" value="2"><i class="fadeIn animated bx bx-tired"></i>非承認</div>
								</form>
								@endif
							</div>
							<div class="d-flex flex-row">
								@if($customer->secret_flag == 1)
								<form action="{{route('users.approval')}}" name="modeform" method="POST" enctype="multipart/form-data">
									@csrf
									@method('post')
									<input type="hidden" name="id" value="{{$customer->id}}">
									<button class="btn btn-sm btn-success px-2" name="secret_flag" value="0"><i class="fadeIn animated bx bx-lock"></i>通常に戻す</button>
								</form>
								@elseif($customer->secret_flag == 0)
								<form action="{{route('users.approval')}}" name="modeform" method="POST" enctype="multipart/form-data">
									@csrf
									@method('post')
									<input type="hidden" name="id" value="{{$customer->id}}">
									<button class="btn btn-sm btn-warning px-2" name="secret_flag" value="1"><i class="fadeIn animated bx bx-lock-open"></i>全物件公開する</button>
								</form>
								@endif
							</div>
						</li>
						@endforeach
					</ul>
					{{$users->appends(request()->query())->links()}}
				</div>
				@endif
				{{-- スマホ用end --}}

				@if(!$isMobile)
				<div class="table-responsive">
					<table id="userstable" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>Id</th>
								<th>Name</th>
								<th>Address<br>Email</th>
								<th>Tel</th>
								<th>Status</th>
								<th>Mode</th>
								<th>Created_at</th>
							</tr>
						</thead>
						<tbody>
							@if(count($users) > 0)
							@foreach($users as $customer)
							<tr>
								<td>{{$customer->id}}</td>
								<td>{{$customer->name}}</td>
								<td>〒{{$customer->postcode}}&nbsp;{{$customer->prefecture->name}}{{$customer->address}}<br>{{$customer->email}}</td>
								<td>{{$customer->tel}}</td>
								@if($customer->accepted == 1)
								<td>
									<form action="{{route('users.approval')}}" name="approvalform" method="POST" enctype="multipart/form-data">
										@csrf
										@method('post')
										<input type="hidden" name="id" value="{{$customer->id}}">
										<input type="hidden" name="sendflag" value="0">
										<div class="btn btn-sm btn-primary px-3 pr-1" name="approval" value="1"><i class="fadeIn animated bx bx-happy-beaming"></i>承認済</div>
										<button class="btn btn-sm btn-outline-danger px-3" name="approval" value="2"><i class="fadeIn animated bx bx-tired"></i>承認取消</button>
									</form>
								</td>
								@elseif($customer->accepted == 0)
								<td>
									<form action="{{route('users.approval')}}" name="approvalform" method="POST" enctype="multipart/form-data">
										@csrf
										@method('post')
										<input type="hidden" name="id" value="{{$customer->id}}">
										<button class="btn btn-sm btn-outline-primary px-3 pr-1 btn1" name="approval" value="1"><i class="fadeIn animated bx bx-happy-beaming"></i>承認する</button>
										<button class="btn btn-sm btn-outline-danger px-3" name="approval" value="2"><i class="fadeIn animated bx bx-tired"></i>非承認</button>
									</form>
								</td>
								@else
								<td>
									<form action="{{route('users.approval')}}" name="approvalform" method="POST" enctype="multipart/form-data">
										@csrf
										@method('post')
										<input type="hidden" name="id" value="{{$customer->id}}">
										<button class="btn btn-sm btn-outline-primary px-3 pr-1 btn1" name="approval" value="1"><i class="fadeIn animated bx bx-happy-beaming"></i>承認する</button>
										<div class="btn btn-sm btn-danger px-3" name="approval" value="2"><i class="fadeIn animated bx bx-tired"></i>非承認</div>
									</form>
								</td>
								@endif
								@if($customer->secret_flag == 1)
								<td>
									<form action="{{route('users.approval')}}" name="modeform" method="POST" enctype="multipart/form-data">
										@csrf
										@method('post')
										<input type="hidden" name="id" value="{{$customer->id}}">
										<button class="btn btn-sm btn-success px-3" name="secret_flag" value="0"><i class="fadeIn animated bx bx-lock"></i>通常に戻す</button>
									</form>
								</td>
								@elseif($customer->secret_flag == 0)
								<td>
									<form action="{{route('users.approval')}}" name="modeform" method="POST" enctype="multipart/form-data">
										@csrf
										@method('post')
										<input type="hidden" name="id" value="{{$customer->id}}">
										<button class="btn btn-sm btn-warning px-3 pr-1" name="secret_flag" value="1"><i class="fadeIn animated bx bx-lock-open"></i>全物件公開する</button>
									</form>
								</td>
								@endif
								<td>{{$customer->created_at}}</td>
							</tr>
							@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<th>Id</th>
								<th>Name</th>
								<th>Email</th>
								<th>Tel</th>
								<th>Status</th>
								<th>Mode</th>
								<th>Created_at</th>
							</tr>
						</tfoot>
					</table>
				</div>
				@endif

			</div>
		</div>
	</div>
</div>
<!--end page wrapper -->
@endsection

@section("script")
<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
	'use strict';
	$(document).ready(function() {
		$('#userstable').DataTable({
			displayLength: 25,  
			order: [ [4, 'desc'] ]
		});
	});

	$(function () {
		$(".btn1").on('click', function (e) {
			var result = window.confirm('承認メールを送信しますか。キャンセルを選択した場合は承認のみされます。');
			if( result ) {
				$(this).append('<input type="hidden" name="sendflag" value="1" /> ');
			}
			else {
				$(this).append('<input type="hidden" name="sendflag" value="0" /> ');
			}
		});
	});

</script>

<script>
	$(document).ready(function() {
    $('ul.pagination').hide();
    $('#scroll').jscroll({
        autoTrigger: true,
        loadingHtml: '<div class="text-center"><div class="spinner-border text-primary text-center" role="status"> <span class="visually-hidden">Loading...</span></div></div>',
        padding: 0,
        nextSelector: '.pagination li.active + li a',
        contentSelector: '#scroll',
        callback: function() {
            $('ul.pagination').remove();
        	}
    	});
	});
</script>
@endsection