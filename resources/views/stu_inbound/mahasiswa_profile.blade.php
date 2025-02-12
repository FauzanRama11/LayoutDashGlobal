@extends("layouts.master")

@section('content') 
    
@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/photoswipe.css')}}">
@endpush
@php										
function getFileUrl($fileUrl) {
												
	if (empty($fileUrl)) return null;

		$filePath = ltrim(str_replace('repo/', '', $fileUrl), '/');
		$segments = explode('/', $filePath);
		$fileName = array_pop($segments);
		$folder = implode('/', $segments);

	return !empty($folder)
		? route('view.dokumen', ['folder' => $folder, 'fileName' => $fileName]) 
		: route('view.dokumen', ['folder' => $fileName]);	
}

@endphp



	<div class="container-fluid">
	    <div class="user-profile">
	        <div class="row">
	            <!-- user profile header start-->
	            <div class="col-sm-12">
	                <div class="card profile-header">
	                    <img class="img-fluid bg-img-cover" src="{{asset('assets/images/user-profile/bg-profile.jpg')}}" alt="" />
	                    <div class="profile-img-wrrap"><img class="img-fluid bg-img-cover" src="{{asset('assets/images/user-profile/bg-profile.jpg')}}" alt="" /></div>
	                    <div class="userpro-box">
	                        <div class="img-wrraper">
	                            <div class="avatar"><img class="img-fluid" alt="" src="{{$data->photo_base64}}" /></div>
	                            <!-- <a class="icon-wrapper" href="#"><i class="icofont icofont-pencil-alt-5"></i></a> -->
	                        </div>
	                        <div class="user-designation">
	                            <div class="title">
	                                <a target="_blank" href="">
	                                    <h4>{{Auth::user()->name}}</h4>
	                                    <p><a href="mailto:{{Auth::user()->email}}">{{Auth::user()->email}}</p>
	                                </a>
	                            </div>
								<div class="follow">
	                                <ul class="follow-list">
	                                    <li>
										<span>Username</span>
	                                        <div class="follow">{{Auth::user()->username}}</div>
	                                        
	                                    </li>
	                                    <li>
										<span>Change Password</span>
										<div class="follow">
											@php
												// Enkripsi username sebelum digunakan di dalam route
												$encryptedUsername = Crypt::encryptString(Auth::user()->username);
											@endphp

											<form action="{{ route('change.pass', ['username' => $encryptedUsername]) }}" method="GET" style="display:inline;">
												<button class="btn btn-success btn-xs" type="submit" data-toggle="tooltip" data-placement="top" title="Edit">
													<i class="fa fa-edit"></i>
												</button>
											</form>
										</div>
									</li>

	                           
	                                </ul>
	                            </div>
	                           
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- user profile header end-->
	            <div class="col-xl-3 col-lg-12 col-md-5 xl-35">
	                <div class="default-according style-1 faq-accordion job-accordion">
	                    <div class="row">
	                        <div class="col-xl-12">
	                            <div class="card">
	                                <div class="card-header">
	                                    <h5 class="p-0">
	                                        <button class="btn btn-link ps-0" data-bs-toggle="collapse" data-bs-target="#collapseicon2" aria-expanded="true" aria-controls="collapseicon2">About Me</button>
	                                    </h5>
	                                </div>
	                                <div class="collapse show" id="collapseicon2" aria-labelledby="collapseicon2" data-parent="#accordion">
	                                    <div class="card-body post-about">
	                                        <ul>
	                                            <li>
	                                                <div class="icon"><i data-feather="phone"></i></div>
	                                                <div>
                                             
	                                                    <h5>{{$data->telp}}</h5>
                                                        <p>Telephone Number</p>
	                                                </div>
	                                            </li>
	                                            <li>
	                                                <div class="icon"><i data-feather="user"></i></div>
	                                                <div>
                                                        
                                                        <h5>{{$data->jenis_kelamin}}</h5>
                                                        <p>Gender</p>
	                                                </div>
	                                            </li>
	                                            <li>
	                                                <div class="icon"><i data-feather="calendar"></i></div>
	                                                <div>
                                                        
                                                        <h5>{{$data->tgl_lahir}}</h5>
                                                        <p>Date of Birth</p>
	                                                </div>
	                                            </li>
	                                            <li>
	                                                <div class="icon"><i data-feather="briefcase"></i></div>
	                                                <div>
                                                        
                                                        <h5>{{$data->university_name}}</h5>
                                                        <p>Host University</p>
	                                                </div>
	                                            </li>
	                                            <li>
	                                                <div class="icon"><i data-feather="map-pin"></i></div>
	                                                <div>
                                                        
                                                        <h5>{{$data->country}}</h5>
                                                        <p>Nationality</p>
	                                                </div>
	                                            </li>
	                                        </ul>
	                                        <!-- <div class="social-network theme-form">
	                                            <span class="f-w-600">Social Networks</span>
	                                            <button class="btn social-btn btn-fb mb-2 text-center"><i class="fa fa-facebook m-r-5"></i>Facebook</button>
	                                            <button class="btn social-btn btn-twitter mb-2 text-center"><i class="fa fa-twitter m-r-5"></i>Twitter</button>
	                                            <button class="btn social-btn btn-google text-center"><i class="fa fa-dribbble m-r-5"></i>Dribbble</button>
	                                        </div> -->
	                                    </div>
	                                </div>
                                    </div>
	                            </div>
	                        </div>
	                    </div>
	                    <!-- profile post end -->
	                </div>
                    <div class="col-xl-9 col-lg-12 col-md-7 xl-65">
	                <div class="row">
	                    <!-- profile post start-->
	                    <div class="col-sm-12">
	                        <div class="card">
	                            <div class="profile-post">
	                                <div class="post-header">
	                                    <div class="media">
	                                      
	                                        <div class="media-body align-self-center">
	                                            <a href="#"> <h5 class="user-name">Your File</h5></a>
	                                            <h6>Submitted File</h6>
	                                        </div>
	                                    </div>
	                                    <div class="post-setting"><i class="fa fa-ellipsis-h"></i></div>
	                                </div>
	                                <div class="post-body">
										@if($data->passport_url)
                                        	<h7>Passport</h7>
											<div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="previewdiv" style="display: {{ $data->passport_url ? 'block' : 'none' }};">
											@if($data->passport_base64)
                                                    <img id="preview" src="{{ $data->passport_base64 }}" alt="Preview" class="img-fluid" style="max-width: 100%; height: 300px; object-fit: cover;">
                                                @else
                                                    <p id="noLogoMessage">File tidak tersedia.</p>
                                                @endif
                                			</div>
										@endif
										@if($data->student_id_url)
											<h7>Student ID</h7>
											<div class="col-sm-12 border border-3 p-3 d-flex justify-content-center align-items-center" id="previewdiv" style="display: {{ $data->student_id_url ? 'block' : 'none' }};">
												@if($data->id_base64)
                                                    <img id="preview" src="{{ $data->id_base64 }}" alt="Preview" class="img-fluid" style="max-width: 100%; height: 300px; object-fit: cover;">
                                                @else
                                                    <p id="noLogoMessage">File tidak tersedia.</p>
                                                @endif
										@endif
                                		</div>
                                       
	                                </div>
	                            </div>
	                        </div>
	                 </div>
                    </div>
	            </div>
	            <!-- user profile fifth-style end-->
	            <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
	                <div class="pswp__bg"></div>
	                <div class="pswp__scroll-wrap">
	                    <div class="pswp__container">
	                        <div class="pswp__item"></div>
	                        <div class="pswp__item"></div>
	                        <div class="pswp__item"></div>
	                    </div>
	                    <div class="pswp__ui pswp__ui--hidden">
	                        <div class="pswp__top-bar">
	                            <div class="pswp__counter"></div>
	                            <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
	                            <button class="pswp__button pswp__button--share" title="Share"></button>
	                            <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
	                            <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
	                            <div class="pswp__preloader">
	                                <div class="pswp__preloader__icn">
	                                    <div class="pswp__preloader__cut">
	                                        <div class="pswp__preloader__donut"></div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
	                            <div class="pswp__share-tooltip"></div>
	                        </div>
	                        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
	                        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
	                        <div class="pswp__caption">
	                            <div class="pswp__caption__center"></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	
	@push('scripts')
	<script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
    <script src="{{asset('assets/js/photoswipe/photoswipe.min.js')}}"></script>
    <script src="{{asset('assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
    <script src="{{asset('assets/js/photoswipe/photoswipe.js')}}"></script>
	@endpush


	<script>
    let isFileValid = true; 
  
    document.addEventListener("DOMContentLoaded", function () {
        const fileInputs = document.querySelectorAll("input[type='file']");
        console.log('File validation initialized.');
  
        fileInputs.forEach(input => {
            input.addEventListener("change", handleFileChange);
        });
    });
  
    function handleFileChange(event) {
        validateFileSize(event.target); 
        previewImage(event.target); 
    }
  
    function validateFileSize(input) {
        const file = input.files[0]; 
        if (file) {
            const maxSize = 2 * 1024 * 1024; 
            if (file.size > maxSize) {
                Swal.fire({
                    title: 'File too large!',
                    text: 'The file size exceeds 2 MB. Please upload a smaller file.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                input.value = ""; 
                isFileValid = false; 
            } else {
                isFileValid = true; 
            }
        }
    }
  
    function previewImage(input) {
        const previewDiv = document.getElementById('previewdiv'); 
        const previewImg = document.getElementById('preview'); 
  
        if (input.files && input.files[0]) {
            const file = input.files[0]; 
            const reader = new FileReader(); 
  
            if (['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                reader.onload = function (e) {
                    previewImg.src = e.target.result; 
                    previewImg.style.display = 'block'; 
                    previewDiv.style.display = 'flex'; 
                };
                reader.readAsDataURL(file); 
            } else {
                Swal.fire({
                    title: 'Invalid File Type!',
                    text: 'Only JPG, JPEG, or PNG files are allowed.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                input.value = ''; 
                previewDiv.style.display = 'none'; 
            }
        } else {
            previewDiv.style.display = 'none';
            previewImg.style.display = 'none';
        }
    }
  </script>

@endsection