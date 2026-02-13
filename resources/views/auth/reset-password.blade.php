<x-guest-layout>
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center">
        <div class="container my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
                        <div class="card-body p-5">
                            <img src="{{ asset('assets/images/logo1.png') }}" class="mb-4" width="145" alt="">
                            <h4 class="fw-bold">Generate New Password</h4>
                            <p class="mb-0">We received your reset password request. Please enter your new password!</p>

                            <x-validation-errors class="mb-4" />

                            <div class="form-body mt-4">
                                <form method="POST" action="{{ route('password.update') }}" class="row g-4">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    <div class="col-12">
                                        <label class="form-label" for="NewPassword">New Password</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" 
                                                   class="form-control border-end-0 @error('password') is-invalid @enderror" 
                                                   id="NewPassword" 
                                                   name="password" 
                                                   placeholder="Enter new password" 
                                                   required autocomplete="new-password">
                                            <a href="javascript:;" class="input-group-text bg-transparent">
                                                <i class="bi bi-eye-slash-fill"></i>
                                            </a>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label" for="ConfirmPassword">Confirm Password</label>
                                        <div class="input-group" id="show_hide_password_confirm">
                                            <input type="password" 
                                                   class="form-control border-end-0 @error('password_confirmation') is-invalid @enderror" 
                                                   id="ConfirmPassword" 
                                                   name="password_confirmation" 
                                                   placeholder="Confirm password" 
                                                   required autocomplete="new-password">
                                            <a href="javascript:;" class="input-group-text bg-transparent">
                                                <i class="bi bi-eye-slash-fill"></i>
                                            </a>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-grd-info">Change Password</button>
                                            <a href="{{ route('login') }}" class="btn btn-grd-royal">Back to Login</a>
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

                $("#show_hide_password_confirm a").on('click', function (event) {
                    event.preventDefault();
                    var input = $('#show_hide_password_confirm input');
                    var icon = $('#show_hide_password_confirm i');
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
