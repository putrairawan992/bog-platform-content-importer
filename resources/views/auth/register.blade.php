@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('register') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="register-name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="register-name"
                               value="{{ old('name') }}">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="register-email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="register-email"
                               value="{{ old('email') }}">
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="register-password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="register-password">
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="register-password-confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               id="register-password-confirmation">
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-primary" type="submit">Register</button>
                        <a href="{{ route('login') }}">Already have an account?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
