@isset ($warning)
<div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2">
  <div class="d-flex align-items-center">
    <div class="font-35 text-dark"><i class="bx bx-info-circle"></i>
    </div>
    <div class="ms-3">
      <h6 class="mb-0 text-dark">Warning Alerts</h6>
      <div class="text-dark">{{ $warning }}</div>
    </div>
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endisset