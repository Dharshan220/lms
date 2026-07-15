@extends('layouts.app')

@section('title', 'Dashboard - Nano Spark LMS')

@section('content')
@php
    $user = auth()->user();
    $role = $user->role ?? 'student';

    switch($role) {
        case 'super_admin':
        case 'school_admin':
            redirect()->route('admin.dashboard');
            break;
        case 'teacher':
            redirect()->route('teacher.dashboard');
            break;
        case 'student':
            redirect()->route('student.dashboard');
            break;
        case 'parent':
            redirect()->route('parent.dashboard');
            break;
        default:
            break;
    }
@endphp
@endsection
