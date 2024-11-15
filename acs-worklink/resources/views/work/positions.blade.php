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
            <a href="#" onclick="openModal('addPositionModal')" class="btn mb-1 btn-info fs-20">เพิ่มตำแหน่ง</a>
        </ol>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title"><h3>ตำแหน่ง <span class="label label-info data-count"></span></h3> </div>
                        <div class="row" id="positionCards">
                       

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Add Position Modal -->
<div id="addPositionModal" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">เพิ่มตำแหน่ง</h3>
                <button type="button" class="close" onclick="clearForm('addPositionModal')"><span>&times;</span>
                </button>
            </div>
            <form id="addPositionForm" method="POST" action="javascript:void(0)">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="positionDescTH" class="form-label">ชื่อตำแหน่งภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="positionDescTH" name="position_desc_th" autocomplete="off" value="{{ old('position_desc_th') }}">
                            <div id="positionDescTHError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="positionDescEN" class="form-label">ชื่อตำแหน่งภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="positionDescEN" name="position_desc_en" autocomplete="off" value="{{ old('position_desc_en') }}">
                            <div id="positionDescENError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="positionStatusSelect" class="form-label">สถานะการรับสมัคร <span class="text-danger">*</span></label>
                            <select class="form-control" id="positionStatus" name="position_status">
                                <option value="">กรุณาเลือกสถานะการรับสมัคร</option>
                                <option value="ปกติ">ปกติ</option>
                                <option value="รับสมัครด่วน">รับสมัครด่วน</option>
                            </select>
                         
                            <div id="positionStatusError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="departmentSelect" class="form-label">แผนก <span class="text-danger">*</span></label>
                            <select class="form-control" id="positionDepartment" name="position_department_id">
                                <option value="">กรุณาเลือกแผนก</option>

                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->department_desc_th }}</option>
                                @endforeach
                            </select>
                         
                            <div id="positionDepartmentError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jobqualificationSelect" class="form-label">คุณสมบัติงาน <span class="text-danger">*</span></label>
                            <select class="form-control" id="positionJobQualification" name="position_job_qualification_id">
                                <option value="">กรุณาเลือกคุณสมบัติงาน</option>

                                @foreach($jobQualifications as $jobQualification)
                                    <option value="{{ $jobQualification->job_qualification_id }}">{{ $jobQualification->company_th }}</option>
                                @endforeach
                            </select>
                         
                            <div id="positionJobQualificationError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workModeSelect" class="form-label">รูปแบบงาน <span class="text-danger">*</span></label>
                            <select class="form-control" id="positionMode" name="position_mode_id">
                                <option value="">กรุณาเลือกรูปแบบงาน</option>

                                @foreach($workModes as $workMode)
                                    <option value="{{ $workMode->mode_id }}">{{ $workMode->mode_desc_th }}</option>
                                @endforeach
                            </select>
                         
                            <div id="positionModeError"></div>
                        </div>
                      
                        <div class="col-md-12 mb-3">
                            <label for="responsibilitiesTH" class="form-label">หน้าที่ความรับผิดชอบภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="addResponsibilitiesTH" name="responsibilities_th">{{ old('responsibilities_th') }}</textarea>
                            <div id="responsibilitiesTHError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="responsibilitiesEN" class="form-label">หน้าที่ความรับผิดชอบภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="addResponsibilitiesEN" name="responsibilities_en">{{ old('responsibilities_en') }}</textarea>
                            <div id="responsibilitiesENError"></div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="knowledgeSkillsTH" class="form-label">ความรู้ความสามารถภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="addKnowledgeSkillsTH" name="knowledge_skills_th">{{ old('knowledge_skills_th') }}</textarea>
                            <div id="knowledgeSkillsTHError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="knowledgeSkillsEN" class="form-label">ความรู้ความสามารถภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="addKnowledgeSkillsEN" name="knowledge_skills_en">{{ old('knowledge_skills_en') }}</textarea>
                            <div id="knowledgeSkillsENError"></div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="requireFeatureTH" class="form-label">คุณสมบัติที่ต้องการภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="addRequireFeatureTH" name="require_feature_th">{{ old('require_feature_th') }}</textarea>
                            <div id="requireFeatureTHError"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="requireFeatureEN" class="form-label">คุณสมบัติที่ต้องการภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="addRequireFeatureEN" name="require_feature_en">{{ old('require_feature_en') }}</textarea>
                            <div id="requireFeatureENError"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="salary" class="form-label">เงินเดือน <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="salary" name="salary" autocomplete="off" value="{{ old('salary') }}">
                            <div id="salaryError"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vacancies" class="form-label">จำนวนตำแหน่งงานว่าง <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="vacancies" name="vacancies" autocomplete="off" value="{{ old('vacancies') }}">
                            <div id="vacanciesError"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('addPositionModal')">ปิด</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Edit Position Modal -->
<div id="editPositionModal" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">แก้ไขตำแหน่ง</h3>
                <button type="button" class="close" onclick="clearForm('editPositionModal')"><span>&times;</span>
                </button>
            </div>
            <form id="editPositionForm" method="POST" action="javascript:void(0)">
                @csrf
                <input type="hidden" id="positionId" name="position_id">
                <div class="modal-body">
                <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="positionDescTH" class="form-label">ชื่อตำแหน่งภาษาไทย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="positionDescTH" name="position_desc_th" autocomplete="off" value="{{ old('position_desc_th') }}">
                            <div id="positionDescTHError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="positionDescEN" class="form-label">ชื่อตำแหน่งภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="positionDescEN" name="position_desc_en" autocomplete="off" value="{{ old('position_desc_en') }}">
                            <div id="positionDescENError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="positionStatusSelect" class="form-label">สถานะการรับสมัคร <span class="text-danger">*</span></label>
                            <select class="form-control" id="positionStatus" name="position_status">
                                <option value="">กรุณาเลือกสถานะการรับสมัคร</option>
                                <option value="ปกติ">ปกติ</option>
                                <option value="รับสมัครด่วน">รับสมัครด่วน</option>
                            </select>
                         
                            <div id="positionStatusError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="departmentSelect" class="form-label">แผนก <span class="text-danger">*</span></label>
                            <select class="form-control" id="positionDepartment" name="position_department_id">
                                <option value="">กรุณาเลือกแผนก</option>

                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->department_desc_th }}</option>
                                @endforeach
                            </select>
                         
                            <div id="positionDepartmentError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jobqualificationSelect" class="form-label">คุณสมบัติงาน <span class="text-danger">*</span></label>
                            <select class="form-control" id="positionJobQualification" name="position_job_qualification_id">
                                <option value="">กรุณาเลือกคุณสมบัติงาน</option>

                                @foreach($jobQualifications as $jobQualification)
                                    <option value="{{ $jobQualification->job_qualification_id }}">{{ $jobQualification->company_th }}</option>
                                @endforeach
                            </select>
                         
                            <div id="positionJobQualificationError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="workModeSelect" class="form-label">รูปแบบงาน <span class="text-danger">*</span></label>
                            <select class="form-control" id="positionMode" name="position_mode_id">
                                <option value="">กรุณาเลือกรูปแบบงาน</option>

                                @foreach($workModes as $workMode)
                                    <option value="{{ $workMode->mode_id }}">{{ $workMode->mode_desc_th }}</option>
                                @endforeach
                            </select>
                         
                            <div id="positionModeError2"></div>
                        </div>
                      
                        <div class="col-md-12 mb-3">
                            <label for="responsibilitiesTH" class="form-label">หน้าที่ความรับผิดชอบภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="editResponsibilitiesTH" name="responsibilities_th">{{ old('responsibilities_th') }}</textarea>
                            <div id="responsibilitiesTHError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="responsibilitiesEN" class="form-label">หน้าที่ความรับผิดชอบภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="editResponsibilitiesEN" name="responsibilities_en">{{ old('responsibilities_en') }}</textarea>
                            <div id="responsibilitiesENError2"></div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="knowledgeSkillsTH" class="form-label">ความรู้ความสามารถภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="editKnowledgeSkillsTH" name="knowledge_skills_th">{{ old('knowledge_skills_th') }}</textarea>
                            <div id="knowledgeSkillsTHError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="knowledgeSkillsEN" class="form-label">ความรู้ความสามารถภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="editKnowledgeSkillsEN" name="knowledge_skills_en">{{ old('knowledge_skills_en') }}</textarea>
                            <div id="knowledgeSkillsENError2"></div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="requireFeatureTH" class="form-label">คุณสมบัติที่ต้องการภาษาไทย <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="editRequireFeatureTH" name="require_feature_th">{{ old('require_feature_th') }}</textarea>
                            <div id="requireFeatureTHError2"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="requireFeatureEN" class="form-label">คุณสมบัติที่ต้องการภาษาอังกฤษ <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" id="editRequireFeatureEN" name="require_feature_en">{{ old('require_feature_en') }}</textarea>
                            <div id="requireFeatureENError2"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="salary" class="form-label">เงินเดือน <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="salary" name="salary" autocomplete="off" value="{{ old('salary') }}">
                            <div id="salaryError2"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vacancies" class="form-label">จำนวนตำแหน่งงานว่าง <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="vacancies" name="vacancies" autocomplete="off" value="{{ old('vacancies') }}">
                            <div id="vacanciesError2"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="clearForm('editPositionModal')">ปิด</button>
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
        tinymce.init({
            selector: '#addResponsibilitiesTH',  
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
            selector: '#addResponsibilitiesEN', 
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
            selector: '#addKnowledgeSkillsTH',  
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
            selector: '#addKnowledgeSkillsEN', 
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
            selector: '#addRequireFeatureTH',  
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
            selector: '#addRequireFeatureEN', 
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
            selector: '#editResponsibilitiesTH',  
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
            selector: '#editResponsibilitiesEN', 
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
            selector: '#editKnowledgeSkillsTH',  
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
            selector: '#editKnowledgeSkillsEN', 
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
            selector: '#editRequireFeatureTH',  
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
            selector: '#editRequireFeatureEN', 
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
            if (key === 'position_desc_th') {
            $('#positionDescTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'position_desc_en') {
            $('#positionDescENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'position_status') {
            $('#positionStatusError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'position_department_id') {
            $('#positionDepartmentError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'position_job_qualification_id') {
            $('#positionJobQualificationError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'position_mode_id') {
            $('#positionModeError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'responsibilities_th') {
            $('#responsibilitiesTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'responsibilities_en') {
            $('#responsibilitiesENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'knowledge_skills_th') {
            $('#knowledgeSkillsTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'knowledge_skills_en') {
            $('#knowledgeSkillsENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'require_feature_th') {
            $('#requireFeatureTHError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'require_feature_en') {
            $('#requireFeatureENError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'salary') {
            $('#salaryError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
            else if (key === 'vacancies') {
            $('#vacanciesError' + (action === 'edit' ? '2' : '')).html('<div class="text-error">' + value[0] + '</div>');
            } 
        });
    }

    function updateCount(count) {
        $('.data-count').text(count);
    }

    function updateContent(positions) {
        let content = '';
        $.each(positions, function(index, position) {
            content += `
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h5>${position.position_desc_th} (${position.position_desc_en})</h5>
                        </div>
                        <p><strong>แผนก: </strong>${position.department_desc_th} (${position.department_desc_en})</p>
                        <p><strong>คุณสมบัติงาน: </strong> ${position.company_th} (${position.company_en})</p>
                        <p><strong>รูปแบบงาน: </strong> ${position.mode_desc_th} (${position.mode_desc_en})</p>
                        <p><strong>สถานะการรับสมัคร: </strong>${position.position_status}</p>
                        <p>
                            <strong>หน้าที่ความรับผิดชอบ:</strong> <br>
                            ${position.responsibilities_th} <br>
                            ${position.responsibilities_en} <br>
                        </p>
                           <p>
                            <strong>ความรู้ความสามารถ:</strong> <br>
                            ${position.knowledge_skills_th} <br>
                            ${position.knowledge_skills_en} <br>
                        </p>
                        <p>
                            <strong>คุณสมบัติที่ต้องการ:</strong> <br>
                            ${position.require_feature_th} <br>
                            ${position.require_feature_en} <br>
                        </p>
                        <p>
                            <strong>เงินเดือน: </strong> ${position.salary}
                        </p>
                        <p>
                            <strong>จำนวนตำแหน่งงานว่าง: </strong> ${position.vacancies}
                        </p>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-warning btn-sm edit-btn editData" data-id="${position.position_id}">แก้ไข</button>
                            <button class="btn btn-danger btn-sm delete-btn deleteData" data-id="${position.position_id}">ลบ</button>
                        </div>
                    </div>
                </div>
            </div>`;
        });

        $('#positionCards').html(content);
        updateCount(positions.length);
    }


    function fetchPositions() {
        $.ajax({
        type: "GET",
        url: "{{ route('getPositions') }}",
        success: function(data) {
            updateContent(data.positions);
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

        fetchPositions();

        $('#addPositionForm').submit(function(e) {
            e.preventDefault();
            clearErrors();

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ route('insertPosition') }}",
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
                    
                    fetchPositions();
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

                clearForm('addPositionModal'); 
                },
                error: function(resp) {
                if (resp.status === 422) {
                    var errors = resp.responseJSON.errors;
                    handleValidationErrors(errors , 'add');
                }
                }
            })
        });

        $('#positionCards').on('click', '.editData', function () {
            var positionId = $(this).data('id');

            $.ajax({
                url: "{{ route('getPositionById', ['id' => ':id']) }}".replace(':id', positionId),
                type: "GET",
                success: function(data) {
                    $('#editPositionModal #positionId').val(data.position.position_id);
                    $('#editPositionModal #positionDescTH').val(data.position.position_desc_th);
                    $('#editPositionModal #positionDescEN').val(data.position.position_desc_en);
                    $('#editPositionModal #positionStatus').val(data.position.position_status);
                    $('#editPositionModal #positionDepartment').val(data.position.position_department_id);
                    $('#editPositionModal #positionJobQualification').val(data.position.position_job_qualification_id);
                    $('#editPositionModal #positionMode').val(data.position.position_mode_id);
                    $('#editPositionModal #salary').val(data.position.salary);
                    $('#editPositionModal #vacancies').val(data.position.vacancies);


                    tinymce.get('editResponsibilitiesTH').setContent(data.position.responsibilities_th);
                    tinymce.get('editResponsibilitiesEN').setContent(data.position.responsibilities_en);
                    tinymce.get('editKnowledgeSkillsTH').setContent(data.position.knowledge_skills_th);
                    tinymce.get('editKnowledgeSkillsEN').setContent(data.position.knowledge_skills_en);
                    tinymce.get('editRequireFeatureTH').setContent(data.position.require_feature_th);
                    tinymce.get('editRequireFeatureEN').setContent(data.position.require_feature_en);

                    var responsibilitiesTHVal = tinymce.get('editResponsibilitiesTH').getContent();
                    var responsibilitiesENVal = tinymce.get('editResponsibilitiesEN').getContent();
                    var knowledgeSkillsTHVal = tinymce.get('editKnowledgeSkillsTH').getContent();
                    var knowledgeSkillsENVal = tinymce.get('editKnowledgeSkillsEN').getContent();
                    var requireFeatureTHVal = tinymce.get('editRequireFeatureTH').getContent();
                    var requireFeatureENVal = tinymce.get('editRequireFeatureEN').getContent();

                    $('#editPositionModal #editResponsibilitiesTHDisplay').val(responsibilitiesTHVal);
                    $('#editPositionModal #editResponsibilitiesENDisplay').val(responsibilitiesENVal);
                    $('#editPositionModal #editKnowledgeSkillsTHDisplay').val(knowledgeSkillsTHVal);
                    $('#editPositionModal #editKnowledgeSkillsENDisplay').val(knowledgeSkillsENVal);
                    $('#editPositionModal #editRequireFeatureTHDisplay').val(requireFeatureTHVal);
                    $('#editPositionModal #editRequireFeatureENDisplay').val(requireFeatureENVal);


                    openModal('editPositionModal'); 
                },
                error: function(error) {
                    console.error('AJAX error:', error);
                }
            });
        });
        

        $('#editPositionForm').submit(function (e) {
            e.preventDefault();
            clearErrors();

            var formData = new FormData(this);
            var positionId = formData.get('position_id');

            $.ajax({
                type: "POST",
                url: "{{ route('updatePosition', ['id' => ':id']) }}".replace(':id', positionId),
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

                    fetchPositions();
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

                    closeModal('editPositionModal');
                },
                error: function(resp) {
                    if (resp.status == 422) {
                        var errors = resp.responseJSON.errors;
                        handleValidationErrors(errors, 'edit');
                    }
                }
            });
        });

        $('#positionCards').on('click', '.deleteData', function () {
            var positionId = $(this).data('id');
            
            toastr.warning("ต้องการลบตำแหน่งนี้ใช่หรือไม่?<br /><br /><button type='button' class='btn clear toastCloseBtn' id='confirmDelete'>ใช่</button>", null, {
            closeButton: true,
            positionClass: 'toast-top-right',
            timeOut: 0,
            onShown: function (toast) {
                $('#confirmDelete').click(function () {
                    toastr.clear(toast);

                    $.ajax({
                        url: "{{ route('deletePosition', ['id' => ':id']) }}".replace(':id', positionId),
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

                                fetchPositions();
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