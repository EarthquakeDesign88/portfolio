@extends('partials.master')

@section('content')
<style>
    .work-place-en {
        margin-left: 6rem;
    }
    
</style>


<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <a href="#" onclick="openModal('addJobQualificationModal')" class="btn mb-1 btn-info fs-20">เพิ่มคุณสมบัติงาน</a>
        </ol>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title"><h3>คุณสมบัติงาน <span class="label label-info data-count"></span></h3> </div>
                        <div class="row" id="jobQualificationCards">
                       

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Add Job Qualification Modal -->
<div id="addJobQualificationModal" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">เพิ่มคุณสมบัติ</h3>
                <button type="button" class="close" onclick="clearForm('addJobQualificationModal')"><span>&times;</span>
                </button>
            </div>
            <form id="addJobQualificationForm" method="POST" action="javascript:void(0)">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="companyTH" class="form-label">ชื่อบริษัทภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="companyTH" name="company_th" autocomplete="off" value="{{ old('company_th') }}">
                            <div id="companyTHError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="companyEN" class="form-label">ชื่อบริษัทภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="companyEN" name="company_en" autocomplete="off" value="{{ old('company_en') }}">
                            <div id="companyENError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="workPlaceTH" class="form-label">ที่อยู่บริษัทภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="workPlaceTH" name="work_place_th" rows="2"></textarea>
                            <div id="workPlaceTHError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="workPlaceEN" class="form-label">ที่อยู่บริษัทภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="workPlaceEN" name="work_place_en" rows="2"></textarea>
                            <div id="workPlaceENError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workingDayTH" class="form-label">วันทำงานภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="workingDayTH" name="working_day_th" autocomplete="off" value="{{ old('working_day_th') }}">
                            <div id="workingDayTHError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workingDayEN" class="form-label">วันทำงานภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="workingDayEN" name="working_day_en" autocomplete="off" value="{{ old('working_day_en') }}">
                            <div id="workingDayENError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dayOffTH" class="form-label">วันหยุดภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="dayOffTH" name="day_off_th" autocomplete="off" value="{{ old('day_off_th') }}">
                            <div id="dayOffTHError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dayOffEN" class="form-label">วันหยุดภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="dayOffEN" name="day_off_en" autocomplete="off" value="{{ old('day_off_en') }}">
                            <div id="dayOffENError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workTimeStart" class="form-label">เวลาเริ่มงาน <span class="text-danger">*</span></label>
                            <input class="form-control" id="workTimeStart" name="working_time_start" value="{{ old('working_time_start') }}">
                            <div id="workingTimeStartError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workTimeEnd" class="form-label">เวลาสิ้นสุดการทำงาน <span class="text-danger">*</span></label>
                            <input class="form-control" id="workTimeEnd" name="working_time_end" autocomplete="off" value="{{ old('working_time_end') }}">
                            <div id="workingTimeEndError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="benefitsTH" class="form-label">สวัสดิการภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="addBenefitsTH" name="benefits_th">{{ old('benefits_th') }}</textarea>
                            <div id="benefitsTHError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="benefitsEN" class="form-label">สวัสดิการภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="addBenefitsEN" name="benefits_en">{{ old('benefits_en') }}</textarea>
                            <div id="benefitsENError"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('addJobQualificationModal')">ปิด</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Edit Job Qualification Modal -->
<div id="editJobQualificationModal" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">แก้ไขคุณสมบัติ</h3>
                <button type="button" class="close" onclick="clearForm('editJobQualificationModal')"><span>&times;</span>
                </button>
            </div>
            <form id="editJobQualificationForm" method="POST" action="javascript:void(0)">
                @csrf
                <input type="hidden" id="jobQualificationId" name="job_qualification_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="companyTH" class="form-label">ชื่อบริษัทภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="companyTH" name="company_th" autocomplete="off" value="{{ old('company_th') }}">
                            <div id="companyTHError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="companyEN" class="form-label">ชื่อบริษัทภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="companyEN" name="company_en" autocomplete="off" value="{{ old('company_en') }}">
                            <div id="companyENError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="workPlaceTH" class="form-label">ที่อยู่บริษัทภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="workPlaceTH" name="work_place_th" rows="2"></textarea>
                            <div id="workPlaceTHError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="workPlaceEN" class="form-label">ที่อยู่บริษัทภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="workPlaceEN" name="work_place_en" rows="2"></textarea>
                            <div id="workPlaceENError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workingDayTH" class="form-label">วันทำงานภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="workingDayTH" name="working_day_th" autocomplete="off" value="{{ old('working_day_th') }}">
                            <div id="workingDayTHError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workingDayEN" class="form-label">วันทำงานภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="workingDayEN" name="working_day_en" autocomplete="off" value="{{ old('working_day_en') }}">
                            <div id="workingDayENError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dayOffTH" class="form-label">วันหยุดภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="dayOffTH" name="day_off_th" autocomplete="off" value="{{ old('day_off_th') }}">
                            <div id="dayOffTHError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dayOffEN" class="form-label">วันหยุดภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="dayOffEN" name="day_off_en" autocomplete="off" value="{{ old('day_off_en') }}">
                            <div id="dayOffENError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workTimeStart" class="form-label">เวลาเริ่มงาน <span class="text-danger">*</span></label>
                            <input class="form-control" id="workTimeStart2" name="working_time_start" value="{{ old('working_time_start') }}">
                            <div id="workingTimeStartError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workTimeEnd" class="form-label">เวลาสิ้นสุดการทำงาน <span class="text-danger">*</span></label>
                            <input class="form-control" id="workTimeEnd2" name="working_time_end" autocomplete="off" value="{{ old('working_time_end') }}">
                            <div id="workingTimeEndError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="benefitsTH" class="form-label">สวัสดิการภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="editBenefitsTH" name="benefits_th"></textarea>
                            <div id="benefitsTHError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="benefitsEN" class="form-label">สวัสดิการภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="editBenefitsEN" name="benefits_en"></textarea>
                            <div id="benefitsENError2"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('editJobQualificationModal')">ปิด</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="{{ asset('assets/plugins/jquery/dist/jquery.min.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/0a1z90cfkh75ei9a7kjb4o30qsunq08mzhsc1navpn7qovr7/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>


<script type="text/javascript">
    $(document).ready(function(){
        $('#workTimeStart').bootstrapMaterialDatePicker({
            date: false,
            shortTime: false,
            format: 'HH:mm',
            switchOnClick: true,
        });

        $('#workTimeEnd').bootstrapMaterialDatePicker({
            date: false,
            shortTime: false,
            format: 'HH:mm',
            switchOnClick: true
        });

        $('#workTimeStart2').bootstrapMaterialDatePicker({
            date: false,
            shortTime: false,
            format: 'HH:mm',
            switchOnClick: true,
        });

        $('#workTimeEnd2').bootstrapMaterialDatePicker({
            date: false,
            shortTime: false,
            format: 'HH:mm',
            switchOnClick: true
        });

        tinymce.init({
            selector: '#addBenefitsTH',  
            height: 300,
            plugins: [
                'advlist', 'autolink', 'link', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'fullscreen', 'insertdatetime', 
                'table', 'emoticons'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' + 
            'bullist numlist outdent indent | link | print preview fullscreen | ' +
            'forecolor backcolor emoticons',
            menu: {
                favs: { title: 'menu', items: 'visualaid | searchreplace | emoticons' }
            },
            menubar: 'favs file edit view insert format tools table',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });

        tinymce.init({
            selector: '#addBenefitsEN', 
            height: 300,
            plugins: [
                'advlist', 'autolink', 'link', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'fullscreen', 'insertdatetime', 
                'table', 'emoticons'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' + 
            'bullist numlist outdent indent | link | print preview fullscreen | ' +
            'forecolor backcolor emoticons',
            menu: {
                favs: { title: 'menu', items: 'visualaid | searchreplace | emoticons' }
            },
            menubar: 'favs file edit view insert format tools table',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });

        tinymce.init({
            selector: '#editBenefitsTH',  
            height: 300,
            plugins: [
                'advlist', 'autolink', 'link', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'fullscreen', 'insertdatetime', 
                'table', 'emoticons'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' + 
            'bullist numlist outdent indent | link | print preview fullscreen | ' +
            'forecolor backcolor emoticons',
            menu: {
                favs: { title: 'menu', items: 'visualaid | searchreplace | emoticons' }
            },
            menubar: 'favs file edit view insert format tools table',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });

        tinymce.init({
            selector: '#editBenefitsEN', 
            height: 300,
            plugins: [
                'advlist', 'autolink', 'link', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'fullscreen', 'insertdatetime', 
                'table', 'emoticons'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' + 
            'bullist numlist outdent indent | link | print preview fullscreen | ' +
            'forecolor backcolor emoticons',
            menu: {
                favs: { title: 'menu', items: 'visualaid | searchreplace | emoticons' }
            },
            menubar: 'favs file edit view insert format tools table',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
        
    });


    function openModal(modalId) {
        $('#' + modalId).modal('show');
    }

    function closeModal(modalId) {
        $('#' + modalId).modal('hide');
    }

    function clearErrors() {
        $('.text-error').html('');
    }

    function clearForm(modalId) {
        $('#' + modalId + ' form').trigger("reset");
        clearErrors();
        closeModal(modalId)
    }

    function handleValidationErrors(errors, action) {
        $.each(errors, function(key, value) {
            if (key === 'company_th') {
            $('#companyTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'company_en') {
            $('#companyENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'work_place_th') {
            $('#workPlaceTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'work_place_en') {
            $('#workPlaceENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'working_day_th') {
            $('#workingDayTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'working_day_en') {
            $('#workingDayENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'day_off_th') {
            $('#dayOffTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'day_off_en') {
            $('#dayOffENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'working_time_start') {
            $('#workingTimeStartError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'working_time_end') {
            $('#workingTimeEndError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'benefits_th') {
            $('#benefitsTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'benefits_en') {
            $('#benefitsENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
        });
    }

    function updateCount(count) {
        $('.data-count').text(count);
    }

    function updateContent(jobQualifications) {
        let content = '';
        $.each(jobQualifications, function(index, jobQualification) {
            content += `
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h5>${jobQualification.company_th}</h5>
                            <h5>${jobQualification.company_en}</h5>
                        </div>
                        <p><strong>สถานที่ทำงาน: </strong>${jobQualification.work_place_th}</p>
                        <p class="work-place-en">${jobQualification.work_place_en}</p>
                        <p><strong>วันทำงาน: </strong> ${jobQualification.working_day_th} (${jobQualification.working_day_en})</p>
                        <p><strong>วันหยุด: </strong> ${jobQualification.day_off_th} (${jobQualification.day_off_en})</p>
                        <p><strong>เวลางาน: </strong> ${jobQualification.working_time}</p>
                        <p>
                            <strong>สวัสดิการ:</strong> <br>
                            ${jobQualification.benefits_th} <br>
                            ${jobQualification.benefits_en} <br>
                        </p>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-warning btn-sm edit-btn editData" data-id="${jobQualification.job_qualification_id}">แก้ไข</button>
                            <button class="btn btn-danger btn-sm delete-btn deleteData" data-id="${jobQualification.job_qualification_id}">ลบ</button>
                        </div>
                    </div>
                </div>
            </div>`;
        });

        $('#jobQualificationCards').html(content);
        updateCount(jobQualifications.length);
    }


    function fetchJobQualifications() {
        $.ajax({
        type: "GET",
        url: "{{ route('getJobQualifications') }}",
        success: function(data) {
            updateContent(data.jobQualifications);
        },
        error: function(error) {
            console.error('AJAX error:', error);
        }
        });
    }

    $("document").ready(function() {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        fetchJobQualifications();

        $('#addJobQualificationForm').submit(function(e) {
            e.preventDefault();
            clearErrors();

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('insertJobQualification') }}",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(resp) {
                var message = resp.message;
                
                if (resp.status === "success") {
                    Command: toastr["success"](message, null, {
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp",
                    "timeOut": 3000,
                    "extendedTimeOut": 1000,
                    "positionClass": "toast-top-right",
                    "progressBar": true,
                    "toastClass": "custom-toast"
                    });
                    
                    fetchJobQualifications();
                } 
                else {
                    Command: toastr["error"](message, null, {
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp",
                    "timeOut": 3000,
                    "extendedTimeOut": 1000,
                    "positionClass": "toast-top-right",
                    "progressBar": true,
                    "toastClass": "custom-toast"
                    });
                }

                clearForm('addJobQualificationModal'); 
                },
                error: function(resp) {
                if (resp.status === 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors , 'add');
                }
                }
            })
        });

        $('#jobQualificationCards').on('click', '.editData', function () {
            var jobQualificationId = $(this).data('id');

            $.ajax({
                url: "{{ route('getJobQualificationById', ['id' => ':id']) }}".replace(':id', jobQualificationId),
                type: "GET",
                success: function(data) {
                    console.log(data); 
                    $('#editJobQualificationModal #jobQualificationId').val(data.jobQualification.job_qualification_id);
                    $('#editJobQualificationModal #companyTH').val(data.jobQualification.company_th);
                    $('#editJobQualificationModal #companyEN').val(data.jobQualification.company_en);
                    $('#editJobQualificationModal #workPlaceTH').val(data.jobQualification.work_place_th);
                    $('#editJobQualificationModal #workPlaceEN').val(data.jobQualification.work_place_en);
                    $('#editJobQualificationModal #workingDayTH').val(data.jobQualification.working_day_th);
                    $('#editJobQualificationModal #workingDayEN').val(data.jobQualification.working_day_en);
                    $('#editJobQualificationModal #dayOffTH').val(data.jobQualification.day_off_th);
                    $('#editJobQualificationModal #dayOffEN').val(data.jobQualification.day_off_en);
                 
    
                    tinymce.get('editBenefitsTH').setContent(data.jobQualification.benefits_th);
                    tinymce.get('editBenefitsEN').setContent(data.jobQualification.benefits_en);

                    var benefitsTHVal = tinymce.get('editBenefitsTH').getContent();
                    var benefitsENVal = tinymce.get('editBenefitsEN').getContent();

                    $('#editJobQualificationModal #editBenefitsTH').val(benefitsTHVal);
                    $('#editJobQualificationModal #editBenefitsEN').val(benefitsENVal);


                    var workingTime = data.jobQualification.working_time; 
                    if (workingTime) {
                        var times = workingTime.split('-'); 
                        $('#editJobQualificationModal #workTimeStart2').val(times[0].trim()); 
                        $('#editJobQualificationModal #workTimeEnd2').val(times[1].trim());
                    } else {
                        $('#editJobQualificationModal #workTimeStart2').val(''); 
                        $('#editJobQualificationModal #workTimeEnd2').val('');
                    }


                    openModal('editJobQualificationModal'); 
                },
                error: function(error) {
                    console.error('AJAX error:', error);
                }
            });
        });

        $('#editJobQualificationForm').submit(function (e) {
            e.preventDefault();
            clearErrors();

            var formData = new FormData(this);
            var jobQualificationId = formData.get('job_qualification_id');

            $.ajax({
                type: "POST",
                url: "{{ route('updateJobQualification', ['id' => ':id']) }}".replace(':id', jobQualificationId),
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function (resp) {
                    var message = resp.message;

                    if (resp.status == "success") {
                        Command: toastr["success"](message, null, {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            "timeOut": 3000,
                            "extendedTimeOut": 1000,
                            "positionClass": "toast-top-right",
                            "progressBar": true,
                            "toastClass": "custom-toast"
                        });

                            fetchJobQualifications();
                        } 
                    else {
                    Command: toastr["error"](message, null, {
                        "showMethod": "slideDown",
                        "hideMethod": "slideUp",
                        "timeOut": 3000,
                        "extendedTimeOut": 1000,
                        "positionClass": "toast-top-right",
                        "progressBar": true,
                        "toastClass": "custom-toast"
                    });
                    }

                    closeModal('editJobQualificationModal');
                },
                error: function(resp) {
                    if (resp.status == 422) {
                        var errors = resp.responseJSON.errors;
                        handleValidationErrors(errors, 'edit');
                    }
                }
            });
        });

        $('#jobQualificationCards').on('click', '.deleteData', function () {
            var jobQualificationId = $(this).data('id');
            
            toastr.warning("ต้องการลบคุณสมบัตินี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
            closeButton: true,
            positionClass: 'toast-top-right',
            timeOut: 0,
            onShown: function (toast) {
                $('#confirmDelete').click(function () {
                    toastr.clear(toast);

                    $.ajax({
                        url: "{{ route('deleteJobQualification', ['id' => ':id']) }}".replace(':id', jobQualificationId),
                        type: "DELETE",
                        success: function (resp) {
                            var message = resp.message;

                            if (resp.status == 'success') {
                                Command: toastr["success"](message, null, {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                "timeOut": 3000,
                                "extendedTimeOut": 1000,
                                "positionClass": "toast-top-right",
                                "progressBar": true,
                                "toastClass": "custom-toast"
                                });

                                fetchJobQualifications();
                            } 
                            else {
                                Command: toastr["error"](message, null, {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                "timeOut": 3000,
                                "extendedTimeOut": 1000,
                                "positionClass": "toast-top-right",
                                "progressBar": true,
                                "toastClass": "custom-toast"
                                });
                            }
                        },
                        error: function (error) {
                            Command: toastr["error"]("พบข้อผิดพลาด โปรดลองใหม่อีกครั้ง", null, {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            "timeOut": 3000,
                            "extendedTimeOut": 1000,
                            "positionClass": "toast-top-right",
                            "progressBar": true,
                            "toastClass": "custom-toast"
                            });
                        }

                    });

                });
            },
            toastClass: 'custom-toast'
        });
        });

    });


</script>

@endsection