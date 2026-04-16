<div>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Manage</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card rounded-4">
        <div class="card-body">
            <h5 class="mb-4">Display Monitor Settings</h5>

            <form wire:submit.prevent="save">
                <!-- App Theme -->
                <div class="mb-4">
                    <label for="appTheme" class="form-label fw-bold">Application Theme</label>
                    <select
                        id="appTheme"
                        class="form-select @error('appTheme') is-invalid @enderror"
                        wire:model="appTheme">
                        @foreach (\App\Livewire\Admin\Settings::ALLOWED_THEMES as $themeValue)
                            <option value="{{ $themeValue }}">{{ match ($themeValue) {
                                'blue-theme' => __('Blue'),
                                'light' => __('Light'),
                                'dark' => __('Dark'),
                                'semi-dark' => __('Semi dark'),
                                'bodered-theme' => __('Bordered'),
                                default => $themeValue,
                            } }}</option>
                        @endforeach
                    </select>
                    @error('appTheme')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-secondary">This theme applies to all users globally.</small>
                </div>

                <hr class="my-4">

                <!-- Marquee Text -->
                <div class="mb-4">
                    <label for="displayMarqueeText" class="form-label fw-bold">Marquee Text</label>
                    <textarea 
                        class="form-control @error('displayMarqueeText') is-invalid @enderror" 
                        id="displayMarqueeText" 
                        wire:model="displayMarqueeText" 
                        rows="3"
                        placeholder="Enter marquee text to display at the bottom of the monitor..."></textarea>
                    @error('displayMarqueeText') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                    <small class="text-secondary">This text will scroll at the bottom of the display monitor.</small>
                </div>

                <hr class="my-4">

                <!-- Video Settings -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Display Video</label>
                    <p class="text-secondary small mb-3">Choose to use either a video URL (YouTube) or upload a video file.</p>

                    <!-- Video URL Option -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="videoOption" 
                                id="videoUrlOption" 
                                wire:model="useVideoFile" 
                                value="0"
                                wire:click="$set('useVideoFile', false)">
                            <label class="form-check-label" for="videoUrlOption">
                                Use Video URL (YouTube)
                            </label>
                        </div>
                        @if(!$useVideoFile)
                        <div class="mt-2 ms-4">
                            <input 
                                type="url" 
                                class="form-control @error('displayVideoUrl') is-invalid @enderror" 
                                id="displayVideoUrl" 
                                wire:model="displayVideoUrl" 
                                placeholder="https://www.youtube.com/watch?v=VIDEO_ID or https://youtu.be/VIDEO_ID">
                            @error('displayVideoUrl') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                            <small class="text-secondary">Accepted YouTube links: watch (`youtube.com/watch?v=`), short (`youtu.be/`), and embed (`youtube.com/embed/`).</small>
                        </div>
                        @endif
                    </div>

                    <!-- Video File Upload Option -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="videoOption" 
                                id="videoFileOption" 
                                wire:model="useVideoFile" 
                                value="1"
                                wire:click="$set('useVideoFile', true)">
                            <label class="form-check-label" for="videoFileOption">
                                Upload Video File
                            </label>
                        </div>
                        @if($useVideoFile)
                        <div class="mt-2 ps-4" wire:loading.remove wire:target="videoFile">
                            @if($currentVideoPath || $videoFile)
                            <div class="mt-2">
                                <div class="alert alert-info d-flex align-items-center justify-content-between mb-0">
                                    <span class="text-truncate pe-3">
                                        {{ $currentVideoPath ? basename($currentVideoPath) : $videoFile->getClientOriginalName() }}
                                    </span>
                                    @if($currentVideoPath)
                                    <button 
                                        type="button" 
                                        class="btn btn-danger px-3 raised d-flex align-items-center gap-2"
                                        wire:click="deleteVideoFile"
                                        wire:confirm="Are you sure you want to delete this video file?">
                                        <i class="material-icons-outlined">delete</i>
                                        <span>Remove file</span>
                                    </button>
                                    @else
                                    <button
                                        type="button"
                                        class="btn btn-danger px-3 raised d-flex align-items-center gap-2"
                                        wire:click="$set('videoFile', null)">
                                        <i class="material-icons-outlined">delete</i>
                                        <span>Remove file</span>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div
                                class="position-relative border border-2 border-secondary-subtle rounded-3 p-4 text-center bg-light overflow-hidden">
                                <input 
                                    type="file" 
                                    class="position-absolute top-0 start-0 w-100 h-100 opacity-0 @error('videoFile') is-invalid @enderror"
                                    style="cursor: pointer;"
                                    id="videoFile" 
                                    wire:model="videoFile" 
                                    wire:loading.attr="disabled"
                                    wire:target="videoFile"
                                    accept="video/mp4,video/webm,video/ogg">
                                <i class="material-icons-outlined mb-2" style="font-size: 2rem;">cloud_upload</i>
                                <p class="mb-1 fw-semibold">Drag and drop your video file here</p>
                                <p class="text-secondary small mb-0">or click to browse</p>
                            </div>
                            @error('videoFile') 
                                <div class="text-danger small mt-2">{{ $message }}</div> 
                            @enderror
                            <small class="text-secondary">Supported formats: MP4, WebM, OGG (Max: 3GB)</small>
                            @endif
                        </div>

                        @if(!$currentVideoPath && !$videoFile)
                        <div class="mt-2 ps-4" wire:loading.block wire:target="videoFile">
                            <div class="border border-2 border-secondary-subtle rounded-3 p-4 bg-light">
                                <div class="placeholder-glow mb-3">
                                    <span class="placeholder w-100"></span>
                                    <span class="placeholder w-75"></span>
                                </div>
                                <div class="placeholder-glow mb-2">
                                    <span class="placeholder w-100" style="height: 2.75rem;"></span>
                                </div>
                                <div class="d-flex align-items-center gap-2 text-secondary small">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span>Uploading temporary file...</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>

                <!-- Save Button -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4 raised d-flex gap-2" wire:loading.attr="disabled">
                        <i class="material-icons-outlined" wire:loading.remove wire:target="save">save</i>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="save"></span>
                        <span wire:loading.remove wire:target="save">Save Settings</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Configure Toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    document.addEventListener('livewire:init', () => {
        // Handle Toastr notifications
        Livewire.on('toastr', (event) => {
            const type = event[0].type || 'info';
            const message = event[0].message || '';
            
            switch(type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning(message);
                    break;
                case 'info':
                default:
                    toastr.info(message);
                    break;
            }
        });
    });
</script>
