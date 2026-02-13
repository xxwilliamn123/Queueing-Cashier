<x-guest-layout>
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center">
        <div class="container my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
                        <div class="card-body p-5">
                            <img src="{{ asset('assets/images/logo1.png') }}" class="mb-4" width="145" alt="">
                            <h4 class="fw-bold">Two Factor Authentication</h4>

                            <x-validation-errors class="mb-4" />

                            <div x-data="{ recovery: false }">
                                <p class="mb-0" x-show="! recovery">
                                    {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
                                </p>

                                <p class="mb-0" x-cloak x-show="recovery">
                                    {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                                </p>

                                <div class="form-body mt-4">
                                    <form method="POST" action="{{ route('two-factor.login') }}" class="row g-4">
                                        @csrf

                                        <div class="col-12" x-show="! recovery">
                                            <label for="code" class="form-label">Code</label>
                                            <input type="text" 
                                                   class="form-control @error('code') is-invalid @enderror" 
                                                   id="code" 
                                                   name="code" 
                                                   inputmode="numeric" 
                                                   x-ref="code" 
                                                   autocomplete="one-time-code" 
                                                   autofocus>
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12" x-cloak x-show="recovery">
                                            <label for="recovery_code" class="form-label">Recovery Code</label>
                                            <input type="text" 
                                                   class="form-control @error('recovery_code') is-invalid @enderror" 
                                                   id="recovery_code" 
                                                   name="recovery_code" 
                                                   x-ref="recovery_code" 
                                                   autocomplete="one-time-code">
                                            @error('recovery_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <button type="button" 
                                                        class="btn btn-link text-primary p-0"
                                                        x-show="! recovery"
                                                        x-on:click="
                                                            recovery = true;
                                                            $nextTick(() => { $refs.recovery_code.focus() })
                                                        ">
                                                    {{ __('Use a recovery code') }}
                                                </button>

                                                <button type="button" 
                                                        class="btn btn-link text-primary p-0"
                                                        x-cloak
                                                        x-show="recovery"
                                                        x-on:click="
                                                            recovery = false;
                                                            $nextTick(() => { $refs.code.focus() })
                                                        ">
                                                    {{ __('Use an authentication code') }}
                                                </button>

                                                <button type="submit" class="btn btn-grd-primary">
                                                    {{ __('Log in') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
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
