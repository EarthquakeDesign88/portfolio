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
                    <div role="toolbar" class="toolbar">
                        <div class="btn-group">
                            <div class="bg-dark" style="padding: 5px 20px; font-size: 20px; border-radius: 5px">Mail Inbox</div>
                            <!-- <button aria-expanded="false" data-toggle="dropdown" class="btn btn-dark dropdown-toggle" type="button">More <span class="caret m-l-5"></span>
                            </button>
                            <div class="dropdown-menu"><span class="dropdown-header">More Option :</span> <a href="javascript: void(0);" class="dropdown-item">Mark as Unread</a> <a href="javascript: void(0);" class="dropdown-item">Add to Tasks</a> <a href="javascript: void(0);"
                                    class="dropdown-item">Add Star</a> <a href="javascript: void(0);" class="dropdown-item">Mute</a>
                            </div> -->
                        </div>
                    </div>
                    <div class="email-list m-t-15">
                        @foreach($mails as $mail)
                        @php
                        $date = new DateTime($mail->created_at);
                        $formattedTime = $date->format('h:i A');

                        $text = '';
                        if($mail->mail_status == 1){
                        $text = 'unread';
                        }
                        @endphp
                        <div class="message {{$text}}">
                            <a href="{{ route('read', $mail->form_id) }}">
                                <div class="col-mail col-mail-1">
                                    <div class="email-checkbox">
                                        <input type="checkbox" id="chk3">
                                        <label class="toggle" for="chk3"></label>
                                    </div><span class="star-toggle ti-star"></span>
                                </div>
                                <div class="col-mail col-mail-2">
                                    <div class="subject">สมัครงาน {{$mail->first_name}} {{$mail->last_name}}</div>
                                    <div class="date">{{$formattedTime}}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                        <!-- <div class="message unread">
                            <a href="email-read.html">
                                <div class="col-mail col-mail-1">
                                    <div class="email-checkbox">
                                        <input type="checkbox" id="chk15">
                                        <label class="toggle" for="chk15"></label>
                                    </div><span class="star-toggle ti-star"></span>
                                </div>
                                <div class="col-mail col-mail-2">
                                    <div class="subject">Almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</div>
                                    <div class="date">11:49 am</div>
                                </div>
                            </a>
                        </div> -->
                    </div>
                    <div class="row pt-4">
                        {{ $mails->links('vendor.pagination.bootstrap-4') }}
                    </div>
                    <!-- panel -->
                    <!-- <div class="row">
                        <div class="col-7">
                            <div class="text-left">1 - 10 of {{$mailCount}}</div>
                        </div>
                        <div class="col-5">
                            <div class="btn-group float-right">
                                <a href="{{ route('inbox') }}"><button class="btn btn-gradient" type="button"><i class="fa fa-angle-left"></i>
                                    </button></a>
                                <a><button class="btn btn-dark" type="button"><i class="fa fa-angle-right"></i>
                                    </button></a>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection