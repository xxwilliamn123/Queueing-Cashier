<x-guest-layout>
    <!--authentication-->
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-5 mx-auto">
                <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
                    <div class="card-body p-5">
                        <img src="{{ asset('assets/images/logo1.png') }}" class="mb-4" width="145" alt="">
                        <h4 class="fw-bold">Get Started Now</h4>
                        <p class="mb-0">Enter your credentials to create your account</p>

                        <x-validation-errors class="mb-4" />

                        <div class="form-body my-4">
                            <form method="POST" action="{{ route('register') }}" class="row g-3">
                                @csrf

                                <div class="col-12">
                                    <label for="inputUsername" class="form-label">Name</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="inputUsername" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="John Doe" 
                                           required autofocus autocomplete="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="inputEmailAddress" class="form-label">Email Address</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="inputEmailAddress" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="example@user.com" 
                                           required autocomplete="username">
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
                                    <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                                    <div class="input-group" id="show_hide_password_confirm">
                                        <input type="password" 
                                               class="form-control border-end-0 @error('password_confirmation') is-invalid @enderror" 
                                               id="inputConfirmPassword" 
                                               name="password_confirmation" 
                                               placeholder="Confirm Password" 
                                               required autocomplete="new-password">
                                        <a href="javascript:;" class="input-group-text bg-transparent">
                                            <i class="bi bi-eye-slash-fill"></i>
                                        </a>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                                   type="checkbox" 
                                                   id="flexSwitchCheckChecked" 
                                                   name="terms" 
                                                   required>
                                            <label class="form-check-label" for="flexSwitchCheckChecked">
                                                {!! __('I read and agree to :terms_of_service and :privacy_policy', [
                                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-primary">'.__('Terms of Service').'</a>',
                                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-primary">'.__('Privacy Policy').'</a>',
                                                ]) !!}
                                            </label>
                                            @error('terms')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-grd-danger">Register</button>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="text-start">
                                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->
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
