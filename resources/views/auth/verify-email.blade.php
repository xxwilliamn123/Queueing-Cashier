<x-guest-layout>
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center">
        <div class="container my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
                        <div class="card-body p-5">
                            <img src="{{ asset('assets/images/logo1.png') }}" class="mb-4" width="145" alt="">
                            <h4 class="fw-bold">Verify Your Email</h4>
                            <p class="mb-0">{{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>

                            @if (session('status') == 'verification-link-sent')
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="form-body mt-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <form method="POST" action="{{ route('verification.send') }}">
                                            @csrf
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-grd-primary">{{ __('Resend Verification Email') }}</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('profile.show') }}" class="text-primary">
                                                {{ __('Edit Profile') }}
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link text-danger p-0">
                                                    {{ __('Log Out') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>
    <!--authentication-->
</x-guest-layout>
