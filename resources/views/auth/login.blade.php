<x-guest-layout>
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center">
        <div class="container-fluid my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
                        <div class="card-body p-5">
                            <img src="{{ asset('assets/images/logo1.png') }}" class="mb-4" width="145" alt="">
                            <h4 class="fw-bold">Get Started Now</h4>
                            <p class="mb-0">Enter your credentials to login your account</p>

                            <x-validation-errors class="mb-4" />

                            @session('status')
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    {{ $value }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endsession

                            <div class="form-body my-5">
                                <form method="POST" action="{{ route('login') }}" class="row g-3">
                                    @csrf

                                    <div class="col-12">
                                        <label for="inputEmailAddress" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="inputEmailAddress" name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="jhon@example.com" 
                                               required autofocus autocomplete="username">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="inputChoosePassword" class="form-label">Password</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" 
                                                   class="form-control border-end-0 @error('password') is-invalid @enderror" 
                                                   id="inputChoosePassword" 
                                                   name="password" 
                                                   placeholder="Enter Password" 
                                                   required autocomplete="current-password">
                                            <a href="javascript:;" class="input-group-text bg-transparent">
                                                <i class="bi bi-eye-slash-fill"></i>
                                            </a>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="remember">
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 text-end">
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}">Forgot Password ?</a>
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-grd-primary">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>
    <!--authentication-->

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined') {
                $("#show_hide_password a").on('click', function (event) {
                    event.preventDefault();
                    var input = $('#show_hide_password input');
                    var icon = $('#show_hide_password i');
                    if (input.attr("type") == "text") {
                        input.attr('type', 'password');
                        icon.addClass("bi-eye-slash-fill");
                        icon.removeClass("bi-eye-fill");
                    } else if (input.attr("type") == "password") {
                        input.attr('type', 'text');
                        icon.removeClass("bi-eye-slash-fill");
                        icon.addClass("bi-eye-fill");
                    }
                });
            }
        });
    </script>
    @endpush
</x-guest-layout>
