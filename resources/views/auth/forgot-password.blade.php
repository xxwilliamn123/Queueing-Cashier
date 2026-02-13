<x-guest-layout>
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center">
        <div class="container my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
                        <div class="card-body p-5">
                            <img src="{{ asset('assets/images/logo1.png') }}" class="mb-4" width="145" alt="">
                            <h4 class="fw-bold">Forgot Password?</h4>
                            <p class="mb-0">Enter your registered email ID to reset the password</p>

                            <x-validation-errors class="mb-4" />

                            @session('status')
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    {{ $value }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endsession

                            <div class="form-body mt-4">
                                <form method="POST" action="{{ route('password.email') }}" class="row g-4">
                                    @csrf

                                    <div class="col-12">
                                        <label class="form-label">Email id</label>
                                        <input type="email" 
                                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="example@user.com" 
                                               required autofocus autocomplete="username">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-grd-primary">Send</button>
                                            <a href="{{ route('login') }}" class="btn btn-light">Back to Login</a>
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
</x-guest-layout>
