<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    @if (session('success'))
        <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
            <div class="d-flex">
                <div class="toast-body">
                    <strong class="d-block mb-1">Berhasil</strong>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="8000">
            <div class="d-flex">
                <div class="toast-body">
                    <strong class="d-block mb-1">Terjadi Kesalahan</strong>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="toast align-items-start text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="9000">
            <div class="d-flex">
                <div class="toast-body">
                    <strong class="d-block mb-1">Terjadi Kesalahan</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 mt-2" data-bs-dismiss="toast" aria-label="Tutup"></button>
            </div>
        </div>
    @endif
</div>
