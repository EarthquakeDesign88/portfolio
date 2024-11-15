@extends('partials.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="email-left-box"><a href="email-compose.html" class="btn btn-primary btn-block">Compose</a>
                    <div class="mail-list mt-4"><a href="{{ route('inbox') }}" class="list-group-item border-0 text-primary p-r-0"><i class="fa fa-inbox font-18 align-middle mr-2"></i> <b>Inbox</b> <span class="badge badge-primary badge-sm float-right m-t-5">{{$mailCount}}</span> </a>
                        <!-- <a href="#" class="list-group-item border-0 p-r-0"><i class="fa fa-paper-plane font-18 align-middle mr-2"></i>Sent</a> <a href="#" class="list-group-item border-0 p-r-0"><i class="fa fa-star-o font-18 align-middle mr-2"></i>Important <span class="badge badge-danger badge-sm float-right m-t-5">47</span> </a>
                        <a href="#" class="list-group-item border-0 p-r-0"><i class="mdi mdi-file-document-box font-18 align-middle mr-2"></i>Draft</a><a href="#" class="list-group-item border-0 p-r-0"><i class="fa fa-trash font-18 align-middle mr-2"></i>Trash</a> -->
                    </div>
                    <!-- <h5 class="mt-5 m-b-10">Categories</h5>
                    <div class="list-group mail-list"><a href="#" class="list-group-item border-0"><span class="fa fa-briefcase f-s-14 mr-2"></span>Work</a> <a href="#" class="list-group-item border-0"><span class="fa fa-sellsy f-s-14 mr-2"></span>Private</a> <a href="#"
                            class="list-group-item border-0"><span class="fa fa-ticket f-s-14 mr-2"></span>Support</a> <a href="#" class="list-group-item border-0"><span class="fa fa-tags f-s-14 mr-2"></span>Social</a>
                    </div> -->
                </div>
                <div class="email-right-box">
                    <!-- <div class="toolbar" role="toolbar">
                        <div class="btn-group m-b-20">
                            <button type="button" class="btn btn-light"><i class="fa fa-archive"></i>
                            </button>
                            <button type="button" class="btn btn-light"><i class="fa fa-exclamation-circle"></i>
                            </button>
                            <button type="button" class="btn btn-light"><i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="btn-group m-b-20">
                            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"><i class="fa fa-folder"></i> <b class="caret m-l-5"></b>
                            </button>
                            <div class="dropdown-menu"><a class="dropdown-item" href="javascript: void(0);">Social</a> <a class="dropdown-item" href="javascript: void(0);">Promotions</a> <a class="dropdown-item" href="javascript: void(0);">Updates</a>
                                <a class="dropdown-item" href="javascript: void(0);">Forums</a>
                            </div>
                        </div>
                        <div class="btn-group m-b-20">
                            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tag"></i> <b class="caret m-l-5"></b>
                            </button>
                            <div class="dropdown-menu"><a class="dropdown-item" href="javascript: void(0);">Updates</a> <a class="dropdown-item" href="javascript: void(0);">Promotions</a>
                                <a class="dropdown-item" href="javascript: void(0);">Forums</a>
                            </div>
                        </div>
                        <div class="btn-group m-b-20">
                            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">More <span class="caret m-l-5"></span>
                            </button>
                            <div class="dropdown-menu"><a class="dropdown-item" href="javascript: void(0);">Mark as Unread</a> <a class="dropdown-item" href="javascript: void(0);">Add to Tasks</a> <a class="dropdown-item"
                                    href="javascript: void(0);">Add Star</a> <a class="dropdown-item" href="javascript: void(0);">Mute</a>
                            </div>
                        </div>
                    </div> -->
                    <div class="read-content">
                        <div class="media pt-5">
                            <p style="background-color: brown; width: 50px; height: 50px; margin-right: 10px; border-radius: 50%; display: flex; justify-content: center; align-items: center;color: #fff; font-size: 20px">{{$mail->first_name[0]}}</p>
                            <div class="media-body">
                                <h5 class="m-b-3">{{$mail->first_name}} {{$mail->last_name}}</h5>
                                <p class="m-b-2">{{$formattedDate}}</p>
                            </div>

                        </div>
                        <hr>
                        @php
                            $date = new DateTime($mail->created_at);
                            $formattedTime = $date->format('h:i A');
                        @endphp
                        <div class="media mb-4 mt-1">
                            <div class="media-body"><span class="float-right">{{$formattedTime}}</span>
                                <h4 class="m-0 text-primary">แบบฟอร์มสมัครงาน</h4>
                                <!-- <small class="text-muted">To:Me,invernessmckenzie@example.com</small> -->
                            </div>
                        </div>
                        <!-- <h5 class="m-b-15">Hi,{{$mail->first_name}}</h5> -->
                        <p><strong>Name : </strong> {{$mail->first_name}} {{$mail->last_name}}</p>
                        <p><strong>Email :</strong> {{$mail->email}}</p>
                        <p><strong>Mobile :</strong> {{$mail->mobile}}</p>
                        <p><strong>Salary :</strong> {{$mail->salary}}</p>
                        <!-- </strong> A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture</p> -->
                        <!-- <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of
                            Grammar.
                        </p>
                        <p>Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet.
                            Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero,
                            sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,</p>
                        <h5 class="m-b-5 p-t-15">Kind Regards</h5> -->
                        <!-- <p>Mr Smith</p> -->
                        <hr>
                        @php
                            $i = 0;
                            if(!empty($mail->cv)) $i++;
                            if(!empty($mail->portfolio)) $i++;
                        @endphp
                        <h6 class="p-t-15"><i class="fa fa-download mb-2"></i> Attachments <span>({{$i}})</span></h6>
                        <div class="row m-b-30">
                            @if(!empty($mail->cv))
                            <div class="col-auto"><a href="{{Storage::url('uploads/cvs/' . $mail->cv)}}" class="text-muted" download>{{$mail->cv}}</a>
                            </div>
                            @endif
                            @if(!empty($mail->portfolio))
                            <div class="col-auto"><a href="{{Storage::url('uploads/portfolios/' . $mail->portfolio)}}" class="text-muted" download>{{$mail->portfolio}}</a>
                            </div>
                            @endif  
                            <!-- <div class="col-auto"><a href="#" class="text-muted">My-Resume.pdf</a>
                            </div> -->
                        </div>
                        <hr>
                        <!-- <div class="form-group p-t-15">
                            <textarea class="w-100 p-20 l-border-1" name="" id="" cols="30" rows="5" placeholder="It's really an amazing.I want to know more about it..!"></textarea>
                        </div> -->
                    </div>
                    <!-- <div class="text-right">
                        <button class="btn btn-primaryw-md m-b-30" type="button">Reply</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
</div>


@endsection