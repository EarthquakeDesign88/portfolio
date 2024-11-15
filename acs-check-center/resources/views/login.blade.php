@extends('layouts/blankLayout')

@section('title', config('variables.appName'))


@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">

      <!-- Login -->
      <div class="card p-2">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros',["height"=>40,"withbg"=>'fill: #fff;'])</span>
            <span class="app-brand-text demo text-heading fw-semibold">{{config('variables.appName')}}</span>
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-2">
          <form class="mb-3" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="user_name" name="user_name" placeholder="ชื่อบัญชีผู้ใช้" autofocus>
              <label for="user_name">ชื่อบัญชีผู้ใช้</label>
            </div>
            <div class="mb-3">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <label for="password">รหัสผ่าน</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">เข้าสู่ระบบ</button>
            </div>
            @error('user_name')
            <div class="alert alert-danger mt-3">{{ $message }}</div>
            @enderror
            @error('password')
            <div class="alert alert-danger mt-3">{{ $message }}</div>
            @enderror
            @if(session('error'))
            <div class="alert alert-danger mt-3">
              {{ session('error') }}
            </div>
            @endif
          </form>
        </div>
      </div>
      <!-- /Login -->
      <!-- <img src="{{asset('assets/img/illustrations/tree-3.png')}}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block"> -->
      <img src="https://images.unsplash.com/photo-1635704764831-082c47202c6c?q=80&w=2073&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="authentication-image d-none d-lg-block" alt="triangle-bg">
      <!-- <img src="{{asset('assets/img/illustrations/tree.png')}}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block"> -->
    </div>
  </div>
</div>
@endsection